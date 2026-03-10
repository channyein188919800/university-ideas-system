<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vote;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ReportController extends Controller
{
    public function statistics()
    {
        $totalIdeas = Idea::count();
        $totalComments = Comment::count();
        $totalVotes = Vote::count();

        $departmentStats = Department::withCount(['ideas', 'users'])
            ->get()
            ->map(function ($dept) use ($totalIdeas) {
                $contributors = $dept->ideas()->select('user_id')->distinct()->pluck('user_id')->count();
                return [
                    'id' => $dept->id,
                    'name' => $dept->name,
                    'ideas_count' => $dept->ideas_count,
                    'contributors_count' => $contributors,
                    'percentage' => $totalIdeas > 0 ? round(($dept->ideas_count / $totalIdeas) * 100, 2) : 0,
                ];
            });

        $finalClosureDate = Setting::getFinalClosureDate();
        $canExport = $finalClosureDate && now()->gte($finalClosureDate);

        return view('qa-manager.reports.statistics', compact(
            'departmentStats',
            'totalIdeas',
            'totalComments',
            'totalVotes',
            'finalClosureDate',
            'canExport'
        ));
    }

    public function exceptionReports(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $departmentId = $request->integer('department_id');
        $search = trim((string) $request->input('search', ''));

        $ideasWithoutCommentsQuery = Idea::query()
            ->with(['user', 'department'])
            ->whereDoesntHave('comments', function ($query) {
                $query->where('hidden', false);
            });

        $anonymousIdeasQuery = Idea::query()
            ->anonymous()
            ->with(['user', 'department']);

        $anonymousCommentsQuery = Comment::query()
            ->anonymous()
            ->with(['user', 'idea.department']);

        if ($departmentId) {
            $ideasWithoutCommentsQuery->where('department_id', $departmentId);
            $anonymousIdeasQuery->where('department_id', $departmentId);
            $anonymousCommentsQuery->whereHas('idea', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            });
        }

        if ($search !== '') {
            $ideasWithoutCommentsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });

            $anonymousIdeasQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });

            $anonymousCommentsQuery->where(function ($query) use ($search) {
                $query->where('content', 'like', "%{$search}%")
                    ->orWhereHas('idea', function ($ideaQuery) use ($search) {
                        $ideaQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $ideasWithoutComments = $ideasWithoutCommentsQuery
            ->latest()
            ->paginate(10, ['*'], 'ideas_page')
            ->withQueryString();

        $anonymousIdeas = $anonymousIdeasQuery
            ->latest()
            ->paginate(10, ['*'], 'anonymous_ideas_page')
            ->withQueryString();

        $anonymousComments = $anonymousCommentsQuery
            ->latest()
            ->paginate(10, ['*'], 'anonymous_comments_page')
            ->withQueryString();

        return view('qa-manager.reports.exceptions', compact(
            'ideasWithoutComments',
            'anonymousIdeas',
            'anonymousComments',
            'departments',
            'departmentId',
            'search'
        ));
    }

    public function downloadCsv(Request $request)
    {
        $finalClosureDate = Setting::getFinalClosureDate();

        if (!$finalClosureDate || now()->lt($finalClosureDate)) {
            return redirect()->back()->with('error', 'Data can only be downloaded after the final closure date.');
        }

        $type = $request->input('type', 'ideas');
        $allowedTypes = ['ideas', 'comments', 'votes', 'users'];
        if (!in_array($type, $allowedTypes, true)) {
            return redirect()->back()->with('error', 'Invalid export type requested.');
        }

        AuditLogger::log(
            'EXPORT_DATA',
            "QA Manager exported {$type} data as CSV.",
            null,
            'success',
            ['type' => $type]
        );

        $filename = $type . '_export_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'ideas') {
                fputcsv($file, [
                    'ID', 'Title', 'Description', 'Author', 'Author Email', 'Department',
                    'Categories', 'Status', 'Views', 'Thumbs Up', 'Thumbs Down', 'Comments Count',
                    'Is Anonymous', 'Is Hidden', 'Created At', 'Documents Count'
                ]);

                Idea::with(['user', 'department', 'categories', 'documents'])->chunk(300, function ($ideas) use ($file) {
                    foreach ($ideas as $idea) {
                        fputcsv($file, [
                            $idea->id,
                            $idea->title,
                            $idea->description,
                            $idea->user?->name,
                            $idea->user?->email,
                            $idea->department?->name,
                            $idea->categories->pluck('name')->implode(', '),
                            $idea->status,
                            $idea->views_count,
                            $idea->thumbs_up_count,
                            $idea->thumbs_down_count,
                            $idea->comments_count,
                            $idea->is_anonymous ? 'Yes' : 'No',
                            $idea->hidden ? 'Yes' : 'No',
                            optional($idea->created_at)->format('Y-m-d H:i:s'),
                            $idea->documents->count(),
                        ]);
                    }
                });
            }

            if ($type === 'comments') {
                fputcsv($file, [
                    'ID', 'Idea ID', 'Idea Title', 'Author', 'Author Email', 'Content',
                    'Is Anonymous', 'Is Hidden', 'Created At'
                ]);

                Comment::with(['user', 'idea'])->chunk(500, function ($comments) use ($file) {
                    foreach ($comments as $comment) {
                        fputcsv($file, [
                            $comment->id,
                            $comment->idea_id,
                            $comment->idea?->title,
                            $comment->user?->name,
                            $comment->user?->email,
                            $comment->content,
                            $comment->is_anonymous ? 'Yes' : 'No',
                            $comment->hidden ? 'Yes' : 'No',
                            optional($comment->created_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            }

            if ($type === 'votes') {
                fputcsv($file, [
                    'ID', 'Idea ID', 'Idea Title', 'User', 'User Email', 'Vote Type', 'Created At'
                ]);

                Vote::with(['user', 'idea'])->chunk(500, function ($votes) use ($file) {
                    foreach ($votes as $vote) {
                        fputcsv($file, [
                            $vote->id,
                            $vote->idea_id,
                            $vote->idea?->title,
                            $vote->user?->name,
                            $vote->user?->email,
                            $vote->vote_type,
                            optional($vote->created_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            }

            if ($type === 'users') {
                fputcsv($file, [
                    'ID', 'Name', 'Email', 'Role', 'Status', 'Department', 'Terms Accepted', 'Created At'
                ]);

                User::with('department')->chunk(500, function ($users) use ($file) {
                    foreach ($users as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->name,
                            $user->email,
                            $user->role,
                            $user->status,
                            $user->department?->name,
                            $user->terms_accepted ? 'Yes' : 'No',
                            optional($user->created_at)->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function downloadDocuments()
    {
        $finalClosureDate = Setting::getFinalClosureDate();
        
        if (!$finalClosureDate || now()->lt($finalClosureDate)) {
            return redirect()->back()->with('error', 'Documents can only be downloaded after the final closure date.');
        }

        $exportDirectory = 'exports';
        Storage::disk('local')->makeDirectory($exportDirectory);

        $zipFileName = 'documents_export_' . now()->format('Y-m-d_His') . '.zip';
        $zipRelativePath = $exportDirectory . '/' . $zipFileName;
        $zipPath = Storage::disk('local')->path($zipRelativePath);

        AuditLogger::log(
            'EXPORT_DATA',
            'QA Manager exported uploaded idea documents as ZIP.',
            null,
            'success',
            ['type' => 'documents_zip']
        );

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $ideas = Idea::with('documents')->has('documents')->get();

            foreach ($ideas as $idea) {
                $ideaFolder = 'idea_' . $idea->id . '_' . Str::slug($idea->title);

                foreach ($idea->documents as $document) {
                    $filePath = $document->file_path;

                    if (Storage::disk('public')->exists($filePath)) {
                        $absolutePath = Storage::disk('public')->path($filePath);
                        $zip->addFile($absolutePath, $ideaFolder . '/' . $document->original_name);
                    }
                }
            }

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend();
        }

        return redirect()->back()->with('error', 'Failed to create ZIP file.');
    }
}

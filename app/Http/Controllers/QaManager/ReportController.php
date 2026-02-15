<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Idea;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ReportController extends Controller
{
    public function statistics()
    {
        $totalIdeas = Idea::count();
        $totalComments = Comment::count();
        
        $departmentStats = Department::withCount(['ideas', 'users'])
            ->get()
            ->map(function ($dept) use ($totalIdeas) {
                $contributors = $dept->ideas()->select('user_id')->distinct()->pluck('user_id')->count();
                return [
                    'name' => $dept->name,
                    'ideas_count' => $dept->ideas_count,
                    'contributors_count' => $contributors,
                    'percentage' => $totalIdeas > 0 ? round(($dept->ideas_count / $totalIdeas) * 100, 2) : 0,
                ];
            });
        
        return view('qa-manager.reports.statistics', compact('departmentStats', 'totalIdeas'));
    }

    public function exceptionReports()
    {
        $ideasWithoutComments = Idea::withoutComments()->with(['user', 'department'])->get();
        $anonymousIdeas = Idea::anonymous()->with(['user', 'department'])->get();
        $anonymousComments = Comment::anonymous()->with(['user', 'idea'])->get();
        
        return view('qa-manager.reports.exceptions', compact('ideasWithoutComments', 'anonymousIdeas', 'anonymousComments'));
    }

    public function downloadCsv()
    {
        $finalClosureDate = Setting::getFinalClosureDate();
        
        if (!$finalClosureDate || now()->lt($finalClosureDate)) {
            return redirect()->back()->with('error', 'Data can only be downloaded after the final closure date.');
        }
        
        $ideas = Idea::with(['user', 'department', 'categories', 'comments', 'documents'])->get();
        
        $filename = 'ideas_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function () use ($ideas) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Title', 'Description', 'Author', 'Author Email', 'Department',
                'Categories', 'Status', 'Views', 'Thumbs Up', 'Thumbs Down',
                'Comments Count', 'Is Anonymous', 'Created At', 'Documents Count'
            ]);
            
            foreach ($ideas as $idea) {
                fputcsv($file, [
                    $idea->id,
                    $idea->title,
                    $idea->description,
                    $idea->user->name,
                    $idea->user->email,
                    $idea->department->name,
                    $idea->categories->pluck('name')->implode(', '),
                    $idea->status,
                    $idea->views_count,
                    $idea->thumbs_up_count,
                    $idea->thumbs_down_count,
                    $idea->comments_count,
                    $idea->is_anonymous ? 'Yes' : 'No',
                    $idea->created_at->format('Y-m-d H:i:s'),
                    $idea->documents->count(),
                ]);
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
        
        $zipFileName = 'documents_export_' . date('Y-m-d_His') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $ideas = Idea::with('documents')->has('documents')->get();
            
            foreach ($ideas as $idea) {
                $ideaFolder = 'idea_' . $idea->id . '_' . Str::slug($idea->title);
                
                foreach ($idea->documents as $document) {
                    $filePath = storage_path('app/public/' . $document->file_path);
                    
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, $ideaFolder . '/' . $document->original_name);
                    }
                }
            }
            
            $zip->close();
            
            return response()->download($zipPath)->deleteFileAfterSend();
        }
        
        return redirect()->back()->with('error', 'Failed to create ZIP file.');
    }
}

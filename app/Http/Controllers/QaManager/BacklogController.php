<?php

namespace App\Http\Controllers\QaManager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Idea;
use Illuminate\Http\Request;

class BacklogController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');
        $search = trim((string) $request->input('search', ''));
        $categoryId = $request->integer('category_id');

        $query = Idea::query()->with(['user', 'department', 'categories']);

        $query->when($tab === 'pending', fn ($q) => $q->where('status', 'pending'));
        $query->when($tab === 'fixing', fn ($q) => $q->where('status', 'under_review'));
        $query->when($tab === 'resolved', fn ($q) => $q->where('status', 'approved')->where('is_closed', false));
        $query->when($tab === 'closed', function ($q) {
            $q->where(function ($inner) {
                $inner->where('status', 'rejected')->orWhere('is_closed', true);
            });
        });

        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($categoryId) {
            $query->whereHas('categories', function ($categoryQuery) use ($categoryId) {
                $categoryQuery->where('categories.id', $categoryId);
            });
        }

        $ideas = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::active()->ordered()->get();

        $statusCounts = [
            'pending' => Idea::where('status', 'pending')->count(),
            'fixing' => Idea::where('status', 'under_review')->count(),
            'resolved' => Idea::where('status', 'approved')->where('is_closed', false)->count(),
            'closed' => Idea::where(function ($q) {
                $q->where('status', 'rejected')->orWhere('is_closed', true);
            })->count(),
        ];

        return view('qa-manager.backlog.university-backlog', compact(
            'ideas',
            'categories',
            'statusCounts',
            'tab',
            'search',
            'categoryId'
        ));
    }
}

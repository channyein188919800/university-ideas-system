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
        $allowedTabs = ['pending', 'approved', 'rejected'];
        $tab = $request->input('tab', 'pending');
        if (!in_array($tab, $allowedTabs, true)) {
            $tab = 'pending';
        }
        $search = trim((string) $request->input('search', ''));
        $categoryId = $request->integer('category_id');

        $query = Idea::query()->with(['user', 'department', 'categories']);

        $query->when($tab === 'pending', fn ($q) => $q->where('status', 'pending'));
        $query->when($tab === 'approved', fn ($q) => $q->where('status', 'approved'));
        $query->when($tab === 'rejected', fn ($q) => $q->where('status', 'rejected'));

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
            'approved' => Idea::where('status', 'approved')->count(),
            'rejected' => Idea::where('status', 'rejected')->count(),
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

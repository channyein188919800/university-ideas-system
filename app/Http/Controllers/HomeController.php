<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $popularIdeas = Idea::published()->visible()->popular()->take(5)->get();
        $mostViewedIdeas = Idea::published()->visible()->mostViewed()->take(5)->get();
        $latestIdeas = Idea::published()->visible()->latest()->take(5)->get();
        $latestComments = Comment::with('idea')
            ->whereHas('idea', function ($query) {
                $query->where('hidden', false);
            })
            ->where('hidden', false)
            ->latest()
            ->take(5)
            ->get();
        $categories = Category::active()->ordered()->get();
        
        $ideaClosureDate = Setting::getIdeaClosureDate();
        $finalClosureDate = Setting::getFinalClosureDate();
        
        return view('home', compact(
            'popularIdeas',
            'mostViewedIdeas',
            'latestIdeas',
            'latestComments',
            'categories',
            'ideaClosureDate',
            'finalClosureDate'
        ));
    }
}

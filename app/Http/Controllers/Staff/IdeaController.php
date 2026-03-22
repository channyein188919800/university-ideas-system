<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use Illuminate\Support\Facades\Auth;

class IdeaController extends Controller
{
    public function show(Idea $idea)
    {
        $user = Auth::user();

        if (!$user || !$user->isStaff()) {
            abort(403, 'Unauthorized access.');
        }

        if ($idea->user_id !== $user->id) {
            abort(403, 'You can only view your own ideas.');
        }

        if ($idea->status === 'approved') {
            return redirect()->route('ideas.show', $idea);
        }

        return view('staff.idea-details', compact('idea'));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsController extends Controller
{
    public function show()
    {
        return view('auth.terms');
    }

    public function accept(Request $request)
    {
        $request->validate([
            'terms_accepted' => 'required|accepted',
        ]);

        $user = Auth::user();
        $user->terms_accepted = true;
        $user->terms_accepted_at = now();
        $user->save();

        switch ($user->role) {
            case 'admin':
                $redirect = route('admin.dashboard');
                break;
            case 'qa_manager':
                $redirect = route('qa-manager.dashboard');
                break;
            case 'qa_coordinator':
                $redirect = route('qa-coordinator.dashboard');
                break;
            default:
                $redirect = route('staff.dashboard');
        }

        return redirect($redirect)->with('success', 'Terms and Conditions accepted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Report;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, Idea $idea)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:120',
            'details' => 'nullable|string|max:1000',
        ]);

        $report = Report::create([
            'idea_id' => $idea->id,
            'reporter_id' => Auth::id(),
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => 'open',
        ]);

        AuditLogger::log(
            'REPORT_IDEA',
            "Reported Idea #{$idea->id} ({$idea->title}).",
            $idea,
            'warning',
            ['reason' => $report->reason]
        );

        return back()->with('success', 'Thanks for the report. Our team will review it.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'academic_year' => Setting::getValue('academic_year', date('Y')),
            'idea_closure_date' => Setting::getValue('idea_closure_date'),
            'final_closure_date' => Setting::getValue('final_closure_date'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'idea_closure_date' => 'required|date',
            'final_closure_date' => 'required|date|after_or_equal:idea_closure_date',
        ]);

        Setting::setValue('academic_year', $validated['academic_year'], 'string', 'closure_dates', 'Current academic year');
        Setting::setValue('idea_closure_date', $validated['idea_closure_date'], 'datetime', 'closure_dates', 'Date when idea submission closes');
        Setting::setValue('final_closure_date', $validated['final_closure_date'], 'datetime', 'closure_dates', 'Final date when commenting closes');

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully!');
    }
}

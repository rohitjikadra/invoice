<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::getCached();

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_mobile' => ['nullable', 'string', 'max:30'],
            'gst_no' => ['nullable', 'string', 'max:50'],
            'pan_no' => ['nullable', 'string', 'max:50'],
            'state_name' => ['nullable', 'string', 'max:100'],
            'state_code' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_no' => ['nullable', 'string', 'max:100'],
            'bank_ifsc' => ['nullable', 'string', 'max:50'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'agent_name' => ['nullable', 'string', 'max:255'],
            'terms' => ['nullable', 'string'],
        ]);

        $settings = Setting::getCached();
        $settings->update($data);

        Setting::clearCache();

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully.');
    }
}

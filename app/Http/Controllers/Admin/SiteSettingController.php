<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settingsGroups = SiteSetting::orderBy('id')->get()->groupBy('group');

        return view('admin.site-settings.index', compact('settingsGroups'));
    }

    public function update(Request $request)
    {
        // Handle file uploads (logo, favicon)
        foreach ($request->allFiles() as $inputName => $file) {
            if (!str_starts_with($inputName, 'settings_file_')) {
                continue;
            }

            $settingKey = str_replace('settings_file_', '', $inputName);
            $setting = SiteSetting::where('key', $settingKey)->first();

            if (!$setting) {
                continue;
            }

            $request->validate([
                $inputName => 'image|mimes:png,jpg,jpeg,webp,ico,svg|max:2048',
            ]);

            // Delete old file
            if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
            }

            $path = $file->store('settings', 'public');
            $setting->update(['value' => $path]);
        }

        // Handle text settings
        $data = $request->input('settings', []);

        foreach ($data as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        SiteSetting::clearCache();

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}

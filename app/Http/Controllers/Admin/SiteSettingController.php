<?php

namespace App\Http\Controllers\Admin;

use App\Application\Setting\UseCases\ListSettings;
use App\Application\Setting\UseCases\UpdateSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(
        private ListSettings $listSettings,
        private UpdateSettings $updateSettings,
    ) {}

    public function index()
    {
        $settingsGroups = $this->listSettings->execute();

        return view('admin.site-settings.index', compact('settingsGroups'));
    }

    public function update(Request $request)
    {
        $fileSettings = [];
        $textSettings = $request->input('settings', []);

        // Handle file uploads
        foreach ($request->allFiles() as $inputName => $file) {
            if (!str_starts_with($inputName, 'settings_file_')) {
                continue;
            }

            $request->validate([
                $inputName => 'image|mimes:png,jpg,jpeg,webp,ico,svg|max:2048',
            ]);

            $settingKey = str_replace('settings_file_', '', $inputName);
            $fileSettings[$settingKey] = $file;
        }

        $this->updateSettings->execute($textSettings, $fileSettings);

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}

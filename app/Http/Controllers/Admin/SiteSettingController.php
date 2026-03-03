<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settingsGroups = SiteSetting::orderBy('id')->get()->groupBy('group');

        return view('admin.site-settings.index', compact('settingsGroups'));
    }

    public function update(Request $request)
    {
        $data = $request->input('settings', []);

        foreach ($data as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        SiteSetting::clearCache();

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}

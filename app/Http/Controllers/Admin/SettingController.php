<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $formStatus = Setting::get('form_status', 'open');
        return response()->json(['form_status' => $formStatus]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'form_status' => 'required|in:open,closed',
        ]);

        Setting::set('form_status', $request->form_status);

        return response()->json([
            'success'     => true,
            'form_status' => $request->form_status,
        ]);
    }
}

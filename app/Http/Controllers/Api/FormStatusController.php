<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class FormStatusController extends Controller
{
    public function index()
    {
        return response()->json([
            'form_status' => Setting::get('form_status', 'open'),
            'app_url'     => config('app.url'),
        ]);
    }
}

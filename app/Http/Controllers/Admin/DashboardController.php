<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;

class DashboardController extends Controller
{
    public function index()
    {
        $total        = Contribution::count();
        $posters      = Contribution::where('contribution_type', 'Poster')->count();
        $orales       = Contribution::where('contribution_type', 'Communication orale')->count();
        $paid         = Contribution::where('payment_status', 'Oui')->count();
        $unpaid       = Contribution::where('payment_status', 'Pas encore')->count();
        $latest       = Contribution::latest()->take(5)->get();

        return response()->json([
            'stats' => [
                'total'   => $total,
                'posters' => $posters,
                'orales'  => $orales,
                'paid'    => $paid,
                'unpaid'  => $unpaid,
            ],
            'latest' => $latest,
        ]);
    }
}

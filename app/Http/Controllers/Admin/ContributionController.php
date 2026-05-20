<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Contribution::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', "%{$s}%")
                  ->orWhere('prenom', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('contribution_type') && $request->contribution_type !== 'Tous') {
            $query->where('contribution_type', $request->contribution_type);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'Tous') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('session') && $request->session !== 'Tous') {
            $query->where('session', $request->session);
        }

        $contributions = $query->latest()->paginate($request->integer('per_page', 15));

        return response()->json($contributions);
    }

    public function show(Contribution $contribution)
    {
        return response()->json($contribution);
    }

    public function destroy(Contribution $contribution)
    {
        // Delete associated files
        foreach (['poster_file', 'payment_receipt'] as $field) {
            if ($contribution->$field) {
                \Storage::disk('public')->delete($contribution->$field);
            }
        }
        $contribution->delete();
        return response()->json(['success' => true]);
    }

    public function export(Request $request)
    {
        $query = Contribution::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', "%{$s}%")
                  ->orWhere('prenom', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }
        if ($request->filled('contribution_type') && $request->contribution_type !== 'Tous') {
            $query->where('contribution_type', $request->contribution_type);
        }
        if ($request->filled('payment_status') && $request->payment_status !== 'Tous') {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('session') && $request->session !== 'Tous') {
            $query->where('session', $request->session);
        }

        $contributions = $query->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="contributions_' . now()->format('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () use ($contributions) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Nom', 'Prénom', 'Email', 'Session',
                'Type de contribution', 'Statut de paiement',
                'Fichier Poster', 'Reçu de paiement', 'Date de soumission',
            ]);
            foreach ($contributions as $c) {
                fputcsv($handle, [
                    $c->nom,
                    $c->prenom,
                    $c->email,
                    $c->session,
                    $c->contribution_type,
                    $c->payment_status ?? '',
                    $c->poster_file ? url('admin/files/download/' . $c->id . '/poster') : '',
                    $c->payment_receipt ? url('admin/files/download/' . $c->id . '/receipt') : '',
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

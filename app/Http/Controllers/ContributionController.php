<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContributionRequest;
use App\Models\Contribution;
use Illuminate\Http\JsonResponse;

class ContributionController extends Controller
{
    public function store(StoreContributionRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('poster_file')) {
            $data['poster_file'] = $request->file('poster_file')
                ->store('contributions', 'public');
        }

        if ($request->hasFile('payment_receipt')) {
            $data['payment_receipt'] = $request->file('payment_receipt')
                ->store('contributions', 'public');
        }

        Contribution::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Votre contribution a été soumise avec succès.',
        ], 201);
    }
}

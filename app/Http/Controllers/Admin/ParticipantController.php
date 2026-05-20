<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ParticipantMail;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    /** List all participants */
    public function index()
    {
        return response()->json(Participant::orderByDesc('created_at')->get());
    }

    /** Add a participant (with duplicate check) */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'       => 'required|email',
            'name'        => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'notes'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Duplicate check
        if (Participant::where('email', $request->email)->exists()) {
            return response()->json([
                'duplicate' => true,
                'message'   => 'Cet email existe déjà dans la base de données.',
            ], 409);
        }

        $participant = Participant::create($validator->validated());

        return response()->json($participant, 201);
    }

    /** Delete a participant */
    public function destroy(Participant $participant)
    {
        $participant->delete();
        return response()->json(['success' => true]);
    }

    /** Send a custom email to selected participants */
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'     => 'required|array',
            'ids.*'   => 'integer|exists:participants,id',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participants = Participant::whereIn('id', $request->ids)->get();

        $sent = 0;
        foreach ($participants as $participant) {
            try {
                Mail::to($participant->email)->send(
                    new ParticipantMail($request->subject, $request->body, $participant)
                );
                $sent++;
            } catch (\Exception $e) {
                // continue sending to others
            }
        }

        return response()->json(['sent' => $sent, 'total' => $participants->count()]);
    }
}

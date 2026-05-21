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

    /** Upload a presentation file (ppt/pptx) and return stored path */
    public function uploadPresentation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Accept any file type; limit to 50MB
            'file' => 'required|file|max:51200', // max 50MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Store slider files in `public/sliders` so they are organized for the site slider
        $path = $request->file('file')->store('sliders', 'public');

        return response()->json(['path' => $path]);
    }

    /** Upload a program file (image/pdf/zip) and return stored path */
    public function uploadProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:51200', // max 50MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('file')->store('programs', 'public');

        return response()->json(['path' => $path]);
    }

    /** Send a custom email to selected participants */
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'     => 'required|array|max:10',
            'ids.*'   => 'integer|exists:participants,id',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
            'presentation_path' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $participants = Participant::whereIn('id', $request->ids)->get();

        $sent   = 0;
        $failed = [];
        $body   = str_replace('{{APP_URL}}', config('app.url'), $request->body);
        foreach ($participants as $participant) {
            try {
                Mail::to($participant->email)->send(
                    new ParticipantMail($request->subject, $body, $participant, $request->presentation_path ?? null)
                );
                $sent++;
            } catch (\Exception $e) {
                $failed[] = $participant->email;
            }
        }

        return response()->json(['sent' => $sent, 'failed' => $failed]);
    }
            /** Send a program file to selected participants (file already uploaded or upload with request) */
            public function sendProgram(Request $request)
            {
                $validator = Validator::make($request->all(), [
                    'ids' => 'required|array|max:50',
                    'ids.*' => 'integer|exists:participants,id',
                    'subject' => 'required|string|max:255',
                    'body' => 'required|string',
                    'program_path' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                // If a file was sent directly in the request (multipart), store it
                if ($request->hasFile('file')) {
                    $stored = $request->file('file')->store('programs', 'public');
                    $programPath = $stored;
                } else {
                    $programPath = $request->program_path ?? null;
                }

                $participants = Participant::whereIn('id', $request->ids)->get();
                $sent = 0;
                $failed = [];
                $body = str_replace('{{APP_URL}}', config('app.url'), $request->body);

                foreach ($participants as $participant) {
                    try {
                        Mail::to($participant->email)->send(
                            new ParticipantMail($request->subject, $body, $participant, $programPath, 'Télécharger le programme →')
                        );
                        $sent++;
                    } catch (\Exception $e) {
                        $failed[] = $participant->email;
                    }
                }

                return response()->json(['sent' => $sent, 'failed' => $failed]);
            }
}

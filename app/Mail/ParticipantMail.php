<?php

namespace App\Mail;

use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ParticipantMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $mailBody,
        public Participant $participant,
        public ?string $presentationPath = null,
        public ?string $fileButtonText = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.participant');
    }

    /**
     * Build the message and pass presentation path to the view.
     * We do NOT attach the file — instead the email will contain a download link.
     */
    public function build()
    {
        $presentationUrl = null;
        if (!empty($this->presentationPath)) {
            // Get the storage url (may be relative or absolute depending on disk)
            $storageUrl = Storage::url($this->presentationPath);
            // If storageUrl is already absolute (has scheme), use it.
            $scheme = parse_url($storageUrl, PHP_URL_SCHEME);
            if ($scheme) {
                $presentationUrl = $storageUrl;
            } else {
                // Ensure we build the absolute URL using APP_URL to avoid local 127.0.0.1 links in production
                $appUrl = rtrim(config('app.url') ?: URL::to('/'), '/');
                $presentationUrl = $appUrl . '/' . ltrim($storageUrl, '/');
            }
        }

        return $this->subject($this->mailSubject)
                    ->view('emails.participant')
                    ->with([
                        'mailBody' => $this->mailBody,
                        'participant' => $this->participant,
                        'presentation_url' => $presentationUrl,
                        'file_button_text' => $this->fileButtonText,
                    ]);
    }
}

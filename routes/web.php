<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContributionController as AdminContributionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Api\FormStatusController;
use App\Http\Controllers\ContributionController;
use Illuminate\Support\Facades\Route;

// ─── Public API ──────────────────────────────────────────────────────────────
Route::post('/contributions', [ContributionController::class, 'store']);
Route::get('/api/form-status', [FormStatusController::class, 'index']);

// ─── Admin Auth ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::post('/login',  [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
    Route::get('/me',      [AuthController::class, 'me'])->middleware('auth')->name('me');

    // Protected admin API (JSON only — non-AJAX requests get the SPA)
    Route::middleware(['auth', 'admin.json'])->group(function () {
        Route::get('/dashboard',              [DashboardController::class, 'index']);
        Route::get('/contributions',          [AdminContributionController::class, 'index']);
        Route::get('/contributions/export',   [AdminContributionController::class, 'export']);
        Route::get('/contributions/{contribution}',    [AdminContributionController::class, 'show']);
        Route::delete('/contributions/{contribution}', [AdminContributionController::class, 'destroy']);
        Route::get('/settings',              [SettingController::class, 'index']);
        Route::match(['put','post'], '/settings', [SettingController::class, 'update']);
        // Participants
        Route::get('/participants',                  [ParticipantController::class, 'index']);
        Route::post('/participants',                 [ParticipantController::class, 'store']);
        Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy']);
        Route::post('/participants/send-email',      [ParticipantController::class, 'sendEmail']);
        Route::post('/participants/upload-program',   [ParticipantController::class, 'uploadProgram']);
        Route::post('/participants/send-program',     [ParticipantController::class, 'sendProgram']);
        Route::post('/participants/upload-presentation', [ParticipantController::class, 'uploadPresentation']);
    });

    // File download — auth only, no JSON check (streams binary file)
    Route::middleware('auth')->get('/files/download/{contribution}/{type}', [FileController::class, 'download']);
});

// ─── Email preview (dev only) ─────────────────────────────────────────────
Route::get('/preview-email', function () {
    $participant = new \App\Models\Participant([
        'name'  => 'Reda Himmi',
        'email' => 'redahimmi31@gmail.com',
    ]);
    $subject = 'Confirmation de votre participation — CIRIV 2026';
    $body    = "Bonjour,\n\nDans le cadre de votre participation au Congrès International de la Recherche, de l'Innovation et de la Valorisation, qui se tiendra en ligne le 22 mai 2026 à partir de 8h30, nous vous prions de bien vouloir confirmer votre présence en remplissant le formulaire disponible via le lien ci-dessous :\n\n" . config('app.url') . "\n\nDès validation de votre inscription, vous recevrez automatiquement le lien de connexion Zoom pour suivre l'événement.\n\nCordialement,\n\nEquipe d'organisation CIRIV.";

    return new \App\Mail\ParticipantMail($subject, $body, $participant);
});

Route::get('/send-test-email', function () {
    $participant = new \App\Models\Participant([
        'name'  => 'Reda Himmi',
        'email' => 'redahimmi31@gmail.com',
    ]);
    $subject = 'Confirmation de votre participation — CIRIV 2026';
    $body    = "Bonjour,\n\nDans le cadre de votre participation au Congrès International de la Recherche, de l'Innovation et de la Valorisation, qui se tiendra en ligne le 22 mai 2026 à partir de 8h30, nous vous prions de bien vouloir confirmer votre présence en remplissant le formulaire disponible via le lien ci-dessous :\n\n" . config('app.url') . "\n\nDès validation de votre inscription, vous recevrez automatiquement le lien de connexion Zoom pour suivre l'événement.\n\nCordialement,\n\nEquipe d'organisation CIRIV.";

    \Illuminate\Support\Facades\Mail::to('redahimmi31@gmail.com')
        ->send(new \App\Mail\ParticipantMail($subject, $body, $participant));

    return response('<h2 style="font-family:sans-serif;color:#006B4F;padding:40px">✅ Email envoyé à redahimmi31@gmail.com</h2>');
});

// ─── Vue SPA catch-all ───────────────────────────────────────────────────────
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

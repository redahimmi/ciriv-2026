<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $mailSubject }}</title>
  <style>
    body { margin:0; padding:0; background:#f0f4f3; font-family:'Segoe UI', Arial, sans-serif; }
    .wrapper { max-width:600px; margin:32px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.10); }
    .top-bar { height:6px; background:linear-gradient(90deg, #C62828 0%, #C62828 30%, #006B4F 30%, #006B4F 100%); }
    .header { background:linear-gradient(160deg,#00472f 0%,#006B4F 50%,#007a5a 100%); padding:44px 40px 36px; text-align:center; position:relative; }
    .header::after { content:''; display:block; position:absolute; bottom:0; left:50%; transform:translateX(-50%); width:80px; height:3px; background:#C62828; border-radius:2px; }
    .header img { height:80px; width:auto; object-fit:contain; display:block; margin:0 auto 18px; filter:drop-shadow(0 3px 10px rgba(0,0,0,0.25)); }
    .header h1 { color:#ffffff; font-size:20px; margin:0 0 8px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; text-shadow:0 1px 4px rgba(0,0,0,0.2); }
    .header-sub { font-size:11px; color:rgba(255,255,255,0.55); letter-spacing:0.12em; text-transform:uppercase; margin:0 0 16px; }
    .body { padding:36px 40px 28px; }
    .greeting { font-size:16px; color:#1a2332; font-weight:700; margin-bottom:20px; }
    .content { font-size:15px; color:#374151; line-height:1.8; margin:0 0 16px; }
    .btn-wrap { text-align:center; margin:32px 0; }
    .btn { display:inline-block; padding:14px 36px; border-radius:50px; font-size:15px; font-weight:700; text-decoration:none; color:#ffffff; background:linear-gradient(135deg,#006B4F,#0D9E72); box-shadow:0 4px 18px rgba(0,107,79,0.35); }
    .divider { border:none; border-top:1px solid #e5e7eb; margin:28px 0; }
    .note { font-size:13px; color:#6b7280; line-height:1.6; }
    .footer { background:#f1f5f3; border-top:3px solid #C62828; padding:20px 40px; text-align:center; }
    .footer p { font-size:12px; color:#9ca3af; margin:4px 0; }
    .footer strong { color:#006B4F; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="top-bar"></div>
    <div class="header">
      <img src="{{ $message->embed(public_path('assets/logowhiteciriv.png')) }}" alt="CIRIV" />
      <h1>CIRIV 2026 — Congrès International</h1>
      <div class="header-sub">International Congress on Research, Innovation and Valorization</div>
    </div>
    <div class="body">
      @if($participant->name)
      <div class="greeting">Cher(e) {{ $participant->name }},</div>
      @endif

      @php
        $lines = explode("\n", $mailBody);
        $output = '';
        $buffer = [];
        foreach ($lines as $line) {
          $trimmed = trim($line);
          if (preg_match('/^https?:\/\/\S+$/', $trimmed)) {
            if ($buffer) {
              $output .= '<p class="content">' . nl2br(e(implode("\n", $buffer))) . '</p>';
              $buffer = [];
            }
            $output .= '<div class="btn-wrap"><a href="' . e($trimmed) . '" class="btn">Confirmer ma participation &rarr;</a></div>';
          } else {
            $buffer[] = $line;
          }
        }
        if ($buffer) {
          $output .= '<p class="content">' . nl2br(e(implode("\n", $buffer))) . '</p>';
        }
      @endphp
      {!! $output !!}

      <hr class="divider"/>
      <p class="note">Ce message vous a été envoyé en tant que participant(e) inscrit(e) au congrès CIRIV 2026.</p>
    </div>
    <div class="footer">
      <p><strong>CIRIV 2026</strong> — Congrès International sur la Recherche, l'Innovation et la Valorisation</p>
      <p>© {{ date('Y') }} CIRIV. Tous droits réservés.</p>
    </div>
  </div>
</body>
</html>

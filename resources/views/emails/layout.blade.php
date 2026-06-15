<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'BushXplorer' }}</title>
<style>
  body { margin:0; padding:0; background:#f5f1eb; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; color:#1c1917; }
  .wrap { max-width:560px; margin:32px auto; }
  .header { background:#166534; padding:24px 32px; border-radius:16px 16px 0 0; }
  .header-logo { color:#fff; font-size:18px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; }
  .header-tagline { color:#86efac; font-size:11px; margin-top:2px; letter-spacing:0.15em; text-transform:uppercase; }
  .body { background:#fff; padding:32px; }
  .footer { background:#f9f7f3; padding:20px 32px; border-radius:0 0 16px 16px; border-top:1px solid #e7e3db; }
  .footer p { margin:0; font-size:11px; color:#a8a29e; }
  .btn { display:inline-block; padding:12px 28px; border-radius:10px; font-weight:700; font-size:14px; text-decoration:none; }
  .btn-green { background:#166534; color:#fff; }
  .btn-amber { background:#c9a84c; color:#fff; }
  h2 { margin:0 0 16px; font-size:22px; font-weight:700; color:#1c1917; font-family:Georgia,serif; line-height:1.3; }
  p { margin:0 0 16px; font-size:14px; line-height:1.6; color:#44403c; }
  .meta-box { background:#f9f7f3; border-radius:10px; padding:16px 20px; margin:20px 0; }
  .meta-row { display:flex; justify-content:space-between; padding:4px 0; font-size:13px; }
  .meta-label { color:#78716c; }
  .meta-value { font-weight:600; color:#1c1917; text-align:right; }
  .divider { border:none; border-top:1px solid #e7e3db; margin:20px 0; }
  .highlight { color:#166534; font-weight:700; }
</style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div class="header-logo">🌿 BushXplorer</div>
        <div class="header-tagline">Adventure awaits</div>
    </div>
    <div class="body">
        @yield('content')
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} BushXplorer · You're receiving this because you have a booking with us.</p>
        <p style="margin-top:4px;">Questions? Reply to this email or contact us at <a href="mailto:hello@bushxplorer.co.za" style="color:#166534;">hello@bushxplorer.co.za</a></p>
    </div>
</div>
</body>
</html>

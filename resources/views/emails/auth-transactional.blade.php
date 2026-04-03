<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="font-family: 'Segoe UI', Arial, Helvetica, sans-serif; line-height: 1.6; color: #0f172a; background: #eef2ff; margin: 0; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);">
        <div style="padding: 28px 28px 12px; text-align: center; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            @if(!empty($logoUrl))
                <img src="{{ $logoUrl }}" alt="{{ $systemName }}" style="max-height: 56px; max-width: 180px; margin-bottom: 12px;">
            @else
                <div style="display:inline-block;width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,0.18);color:#fff;font-size:24px;font-weight:700;line-height:56px;margin-bottom:12px;">
                    S
                </div>
            @endif
            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.02em;">{{ $headline }}</h1>
        </div>
        <div style="padding: 28px;">
            {!! $introHtml !!}
            @if(!empty($ctaLabel) && !empty($ctaUrl))
                <p style="margin: 24px 0; text-align: center;">
                    <a href="{{ $ctaUrl }}" style="display:inline-block;padding:14px 28px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#ffffff;text-decoration:none;border-radius:12px;font-weight:700;box-shadow:0 10px 24px rgba(99,102,241,0.35);">
                        {{ $ctaLabel }}
                    </a>
                </p>
            @endif
            @if(!empty($bodyHtml))
                <div style="margin-top: 16px;">{!! $bodyHtml !!}</div>
            @endif
            @if(!empty($expiresNotice))
                <p style="margin: 20px 0 0; padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; color: #64748b; font-size: 13px;">
                    {{ $expiresNotice }}
                </p>
            @endif
        </div>
        <div style="padding: 18px 28px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; font-size: 13px;">
            {!! $footerHtml !!}
        </div>
    </div>
</body>
</html>

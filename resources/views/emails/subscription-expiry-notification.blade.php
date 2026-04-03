<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine ?? 'Thông báo' }}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #1f2937; background: #f9fafb; margin: 0; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
        <div style="padding: 24px 24px 0;">
            {!! $headerHtml !!}
        </div>
        <div style="padding: 16px 24px;">
            {!! $bodyHtml !!}
        </div>
        <div style="padding: 0 24px 24px; border-top: 1px solid #e5e7eb; margin-top: 8px; padding-top: 16px;">
            {!! $footerHtml !!}
        </div>
    </div>
</body>
</html>

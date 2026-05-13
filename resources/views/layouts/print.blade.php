<!DOCTYPE html>
<html lang="{{ $planLanguage ?? 'en' }}">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Treatment Plan' }}</title>
    <style>
        @page { margin: 20mm 15mm; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #1a1a1a; }
        .page-break { page-break-after: always; }
        h1 { font-size: 22pt; font-weight: 600; }
        h2 { font-size: 16pt; font-weight: 600; margin-top: 1.5em; }
        h3 { font-size: 13pt; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin: 1em 0; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #e5e5e5; }
        th { font-weight: 600; background: #f9fafb; }
        .text-right { text-align: right; }
        .text-muted { color: #6b7280; }
        .text-sm { font-size: 9pt; }
        .watermark { text-align: center; font-size: 8pt; color: #9ca3af; margin-top: 2em; }
    </style>
    @stack('styles')
</head>
<body>
    {{ $slot }}
</body>
</html>

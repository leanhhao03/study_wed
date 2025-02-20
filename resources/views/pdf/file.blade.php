<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans';
            line-height: 1.6;
        }
        h1 {
            color: #333;
        }
        p {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1 style="font-family: DejaVu Sans;">{{ $title }}</h1>
    <div style="font-family: DejaVu Sans;">{!! $content !!}</div>
</body>
</html>

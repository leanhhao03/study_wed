<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ghi chú</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div id="note"></div>
    @vite('resources/js/app.js')
</body>
</html>

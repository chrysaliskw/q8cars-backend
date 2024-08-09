@props(['title' => '',])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{ $title }} | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="https://dev.kuwait-cars.aufaitux.com/_next/static/media/kuwait-logo-dark.ddd20637.svg">
    @include('partials.site-css')
</head>
<body>
<div class="wrapper-page">
    <div class="wrapper-page">
        {{ $slot }}
    </div>
</div>
</body>
</html>

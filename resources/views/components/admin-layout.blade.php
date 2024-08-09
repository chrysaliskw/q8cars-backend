@props(['title' => '',])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <title>{{ $title }} | {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="https://dev.kuwait-cars.aufaitux.com/_next/static/media/kuwait-logo-dark.ddd20637.svg">
    @include('partials.site-css')
</head>
<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    @include('admin.partials.topbar')
    @include('admin.partials.sidebar')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid" id="app">
                
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="pull-left page-title">{{ $title }}</h4>
                        <ol class="breadcrumb pull-right">
                            {{ $breadcrumb ?? '' }}
                        </ol>
                    </div>
                </div>
            
                @if (session('success'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        Success! {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        Warning! {{ session('error') }}
                    </div>
                @endif
                
                {{ $slot }}
            
            </div>
            {{ $model ?? '' }}
        </div>
        <footer class="footer text-right">
            <?= date('Y') ?> © {{ env('APP_NAME') }}.
        </footer>
    </div>
    @include('partials.site-scripts')
    <!-- <script src="{{ mix('js/app.js') }}"></script> -->
    {{ $scripts ?? '' }}
    
</div>
<!-- END wrapper -->
</body>
</html>

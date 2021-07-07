<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{!! csrf_token() !!}"/>
    <title>Kz Constructora</title>
    <!-- Global stylesheets -->
    <link rel="stylesheet" href="{{asset('css/compiled.css')}}">
    <link href="{{asset('assets/image/logokz.png')}}" rel="icon">
    <link href="{{asset('global_assets/css/icons/fontawesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/fontstyle.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <!-- Core JS files -->

</head>
<body>
<div class="navbar navbar-expand-md navbar-dark bg-primary-400">
    <div class="navbar-brand">
        <a href="#" class="d-inline-block text-white">
            <img src="{{asset('assets/image/logo.png')}}" alt="">
        </a>
    </div>
</div>
<div class="page-content">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center align-items-center">
            @yield('content')
        </div>
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; {{now()->year}} - <a href="javascript:void(0)" target="_blank">#</a>
					</span>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('css/compiled.js')}}"></script>
@stack('js')
</body>
</html>

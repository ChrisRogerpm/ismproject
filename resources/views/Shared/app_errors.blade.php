<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{!! csrf_token() !!}"/>
    <title>{{env('APP_NAME')}}</title>

    <link rel="stylesheet" href="{{asset('css/compiled.css')}}">
    <link href="{{asset('assets/image/iconn.ico')}}" rel="icon">
    <link href="{{asset('global_assets/css/icons/fontawesome/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css">
    <link href="{{asset('global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">

</head>

<body>

<div class="navbar navbar-expand-md navbar-dark bg-primary-400">
    <div class="navbar-brand">
        <a href="#" class="d-inline-block">
            <img src="{{asset('assets/image/logo.png')}}" alt="">
        </a>
    </div>
</div>
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">

            <!-- Container -->
            <div class="flex-fill">

                <!-- Error title -->
                <div class="text-center mb-3">
                    @yield('container-error')
                </div>
                <div class="row">
                    <div class="col-xl-4 offset-xl-4 col-md-8 offset-md-2">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ URL::previous() }}" class="btn bg-primary-400 btn-block btn-lg"><i
                                        class="icon-arrow-left22 mr-2"></i> Volver</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; {{now()->year}} - <a href="http://assoflex.com/" target="_blank">ASSOFLEX</a>
					</span>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('css/compiled.js')}}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
    <title>PORTAL DE TRAMITE PRÁCTICAS PRE PROFESIONALES Y TESIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

    @stack('css')

    <link href="{{asset('assets/css/pages/login/login-1.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/skins/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/skins/aside/dark.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/media/logos/logo_fau_ico.png')}}" rel="icon">
    <link rel="stylesheet" href="{{asset('fonts/fontstyle.css')}}">
</head>
<!-- end::Head -->

<!-- begin::Body -->

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
                <!--begin::Content-->
                <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
                    @yield('content')
                </div>
                <!--end::Content-->
            </div>
        </div>
    </div>
    <!-- end:: Page -->


    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff"
                    , "dark": "#282a3c"
                    , "light": "#ffffff"
                    , "primary": "#5867dd"
                    , "success": "#34bfa3"
                    , "info": "#36a3f7"
                    , "warning": "#ffb822"
                    , "danger": "#fd3995"
                }
                , "base": {
                    "label": [
                        "#c5cbe3"
                        , "#a1a8c3"
                        , "#3d4465"
                        , "#3e4466"
                    ]
                    , "shape": [
                        "#f0f3ff"
                        , "#d9dffa"
                        , "#afb4d4"
                        , "#646c9a"
                    ]
                }
            }
        };

    </script>
    <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/scripts.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/global/validation/validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/global/loadingoverlay/loadingoverlay.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/components/extended/sweetalert2.js')}}"></script>
    <script src="{{asset('assets/plugins/global/axios/axios.min.js')}}"></script>
    <script src="{{asset('assets/viewJs/Metodos/General-Auth.js')}}"></script>
    @stack('js')

</body>

</html>

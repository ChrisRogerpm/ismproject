<!DOCTYPE html>
<html lang="es">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
    <title>Industria San Miguel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="{{asset('fonts/fonts-google.css')}}">
    <!--end::Fonts -->
    <link rel="stylesheet" href="{{asset('assets/css/pages/login/login-6.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/global/plugins.bundle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.bundle.css')}}">
    <!--end::Global Theme Styles -->
    <link rel="stylesheet" href="{{asset('assets/css/skins/header/base/light.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/skins/header/menu/light.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/skins/brand/dark.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/skins/aside/dark.css')}}">
    <link href="{{asset('assets/media/logos/iconn.ico')}}" rel="icon">
    <link rel="stylesheet" href="{{asset('fonts/fontstyle.css')}}">
    @stack('css')
</head>

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
                <div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
                    @yield('content')
                </div>
                <div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background-image: url({{asset('assets/media/bg/bg-4.jpg')}});">
                    <div class="kt-login__section">
                        <div class="kt-login__block">
                            <h3 class="kt-login__title text-center">INDUSTRIAS SAN MIGUEL</h3>
                            <div class="kt-login__desc text-center">
                                EMPRESA DE JORGE AÑAÑOS JERÍ
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="valueMessage" value="{{session()->get('message')}}">
            </div>
        </div>
    </div>
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
    <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
    <script src="{{asset('assets/plugins/global/validation/validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/global/loadingoverlay/loadingoverlay.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/components/extended/sweetalert2.js')}}"></script>
    <script src="{{asset('assets/plugins/global/axios/axios.min.js')}}"></script>
    <script src="{{asset('assets/viewJs/Metodos/General-Auth.js')}}"></script>
    @if(session()->has('message'))
    <script>
        let mensaje = $("#valueMessage").val();
        ShowAlert({
            type: 'warning'
            , message: mensaje
        });

    </script>
    @endif
    @stack('js')
</body>
</html>

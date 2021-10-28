@extends('Shared.app_auth')
@section('content')
<div class="kt-login__wrapper">
    <div class="kt-login__container">
        <div class="kt-login__body">
            <div class="kt-login__logo">
                <a href="javascript:void(0)">
                    <img src="{{asset('assets/media/logos/logo_color.png')}}" alt="">
                </a>
            </div>
            <div class="kt-login__signin">
                <div class="kt-login__head">
                    <h3 class="kt-login__title">INGRESE A SU CUENTA</h3>
                </div>
                <div class="kt-login__form">
                    <form class="kt-form" id="frmNuevo" autocomplete="off">
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="INGRESE SU USUARIO" name="email">
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-last" type="password" placeholder="INGRESE SU CONTRASEÑA" name="password">
                        </div>
                        <div class="kt-login__actions">
                            <button type="button" id="btnSesion" class="btn btn-brand btn-pill btn-elevate">INICIAR SESIÓN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Autenticacion/Login.js')}}"></script>
@endpush

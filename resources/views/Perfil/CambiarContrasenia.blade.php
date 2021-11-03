@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">CAMBIAR CONTRASEÑA </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> ACCIONES
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item btnGuardar" href="javascript:void(0)"><i class="fa fa-plus-square"></i>
                        GUARDAR</a>
                    <a class="dropdown-item btnRecargar" href="javascript:void(0)"><i class="fa fa-redo"></i>
                        RECARGAR</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form id="frmNuevo" autocomplete="off">
                    <div class="row">
                        <input type="hidden" name="idUsuario" id="idUsuario">
                        <div class="col-lg-4">
                            <label for=""><b>CONTRASEÑA ANTERIOR</b></label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password">
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for=""><b>NUEVA CONTRASEÑA</b></label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="NuevaContrasenia" id="NuevaContrasenia">
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span></div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for=""><b>REPITA CONTRASEÑA NUEVA</b></label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="VerificarContrasenia" id="VerificarContrasenia">
                                <div class="input-group-append"><span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{asset('assets/viewJs/Autenticacion/CambiarContrasenia.js')}}"></script>
@endpush

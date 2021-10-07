@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">REGISTRAR CENTRO OPERATIVO </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> ACCIONES
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item btnGuardar" href="#"><i class="fa fa-save"></i> GUARDAR</a>
                    <a class="dropdown-item btnVolver" href="#"><i class="fa fa-arrow-left"></i> VOLVER</a>
                    <a class="dropdown-item btnRecargar" href="#"><i class="fa fa-redo"></i> RECARGAR</a>
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
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        DATOS DE CENTRO OPERATIVO
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form id="frmNuevo" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for=""><b>NOMBRE</b></label>
                                <input type="text" class="form-control" name="nombreCeo" id="nombreCeo">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CODIGO</b></label>
                                <input type="text" class="form-control" name="codigoCeo" id="codigoCeo">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>LUGAR</b></label>
                                <input type="text" class="form-control" name="lugar" id="lugar">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for=""><b>EMPRESA</b></label>
                                <input type="text" class="form-control" name="empresa" id="empresa">
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
<script src="{{asset('assets/viewJs/CentroOperativo/CentroOperativoRegistrar.js')}}"></script>
@endpush

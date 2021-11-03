@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">REGISTRAR ROL </h3>
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
                        DATOS DE ROL
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form id="frmNuevo" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label><b>NOMBRE DE ROL</b></label>
                                <input type="text" class="form-control" name="nombreRol" id="nombreRol">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>MODULO DE INICIO</b></label>
                                <select name="paginaInicio" id="CbpaginaInicio" class="form-control" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        PERMISOS
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="table">
                            <thead>
                                <tr>
                                    <th>MODULO</th>
                                    <th class="text-center" style="width:10%">OPCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="text-center">CARGANDO MODULOS ...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{asset('assets/viewJs/Rol/RolRegistrar.js')}}"></script>
@endpush

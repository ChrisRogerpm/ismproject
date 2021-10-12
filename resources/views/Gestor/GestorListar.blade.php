@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">GESTORES </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> ACCIONES
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item btnNuevo" href="javascript:void(0)"><i class="fa fa-plus-square"></i>NUEVO GESTOR</a>
                    <a class="dropdown-item btnImportarExcel" href="javascript:void(0)"><i class="fa fa-file-excel"></i>IMPORTAR EXCEL</a>
                    <a class="dropdown-item" id="GenerarExcel" href="javascript:void(0)"><i class="fa fa-file-excel"></i>EXPORTAR EXCEL COMPACTO</a>
                    <a class="dropdown-item" id="GenerarExcelDetalle" href="javascript:void(0)"><i class="fa fa-file-excel"></i>EXPORTAR EXCEL DETALLADO</a>
                    <a class="dropdown-item btnBuscar" href="javascript:void(0)"><i class="fa fa-search"></i>BUSCAR</a>
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
                <form id="frmNuevo">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>CENTRO OPERATIVO</b></label>
                                <select name="idCeo" id="CbidCeo" class="form-control" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row pb-3" id="btnContenedorEliminar" style="display:none">
                    <div class="col-xl-1 col-md-2 ml-auto">
                        <button type="button" class="btn btn-danger btn-block" id="btnElimnar"><i class="fa fa-trash mr-1"></i> ELIMINAR</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="table"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Gestor.modal.modalImportarGestor')
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Gestor/GestorListar.js')}}"></script>
@endpush

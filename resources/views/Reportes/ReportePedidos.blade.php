@extends('Shared.app')

@section('header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><span class="font-weight-semibold"> CONVERSIÓN DE ARCHIVO DE PEDIDO</span></h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-link btn-float text-default btnRecargar"><i class="icon-reload-alt text-slate-700"></i> <span>RECARGAR</span></button>
                <button type="button" class="btn btn-link btn-float text-default" id="btnCargarArchivo"><i class="icon-file-excel text-slate-700"></i> <span>CARGAR ARCHIVOS</span></button>
            </div>
        </div>
    </div>
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="#" class="breadcrumb-item"> CONVERSIÓN DE ARCHIVO DE PEDIDOS</a>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        {{-- <div class="header-elements d-none">
            <div class="breadcrumb justify-content-center">
                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-gear mr-2"></i>
                        Acción
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" id="GenerarExcel" class="dropdown-item"><i class="icon-file-excel"></i>Generar Excel Comision</a>
                        <a href="#" id="GenerarExcelDetalle" class="dropdown-item"><i class="icon-file-excel"></i>Generar Excel Comision Detalle</a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="frmReporte">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for=""><b><i class="icon-file-excel mr-1"></i>SUBIR ARCHIVO DATOS PLANTILLA (*)</b></label>
                                <input type="file" class="form-input-styled" name="archivoPlantilla" accept=".xlsx, .xls, .xlsm" data-fouc>
                                <span class="form-text text-muted">Formatos aceptados: .xlsx, .xls, .xlsm</span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for=""><b><i class="icon-file-excel mr-1"></i>SUBIR ARCHIVO BONIFICACIONES (*)</b></label>
                                <input type="file" class="form-input-styled" name="archivoBonificaciones" accept=".xlsx, .xls, .xlsm" data-fouc>
                                <span class="form-text text-muted">Formatos aceptados: .xlsx, .xls, .xlsm</span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for=""><b><i class="icon-file-excel mr-1"></i>SUBIR ARCHIVO PEDIDOS (*)</b></label>
                                <input type="file" class="form-input-styled" name="archivoPedido" accept=".xlsx, .xls, .xlsm" data-fouc>
                                <span class="form-text text-muted">Formatos aceptados: .xlsx, .xls, .xlsm</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script src="{{asset('assets/js/Reporte/ReportePedidos.js')}}"></script>
@endpush

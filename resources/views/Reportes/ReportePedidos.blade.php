@extends ('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">CONVERSIÓN DE ARCHIVO DE PEDIDO</h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> ACCIONES
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" id="btnCargarArchivos" href="#"><i class="fa fa-plus"></i> CARGAR ARCHIVOS</a>
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
            <div class="kt-portlet__body">
                <form id="frmReporte">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for=""><strong>SUBIR ARCHIVO DATOS PLANTILLA (*)</strong></label>
                                <input type="file" class="form-control" name="archivoPlantilla" accept=".xlsx, .xls, .xlsm">
                                <span class="form-text text-muted">Formatos aceptados: .xlsx, .xls, .xlsm</span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for=""><strong>SUBIR ARCHIVO BONIFICACIONES (*)</strong></label>
                                <input type="file" class="form-control" name="archivoBonificaciones" accept=".xlsx, .xls, .xlsm">
                                <span class="form-text text-muted">Formatos aceptados: .xlsx, .xls, .xlsm</span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for=""><strong>SUBIR ARCHIVO PEDIDOS (*)</strong></label>
                                <input type="file" class="form-control" name="archivoPedido" accept=".xlsx, .xls, .xlsm">
                                <span class="form-text text-muted">Formatos aceptados: .xlsx, .xls, .xlsm</span>
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
<script src="{{asset('assets/viewJs/Reporte/ReportePedidos.js')}}"></script>
@endpush

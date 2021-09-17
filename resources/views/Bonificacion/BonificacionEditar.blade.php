@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">EDITAR BONIFICACION </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus-square"></i> ACCIONES
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
                        DATOS DE BONIFICACION
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form id="frmNuevo" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CENTRO OPERATIVO</b></label>
                                <input type="text" class="form-control" value="{{$nombreCeo}}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>FECHA INICIO</b></label>
                                <div class="input-group">
                                    <input type="text" class="form-control Fecha" name="fechaInicio" id="fechaInicio">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>FECHA FIN</b></label>
                                <div class="input-group">
                                    <input type="text" class="form-control Fecha" name="fechaFin" id="fechaFin">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>DÍAS A BONIFICAR</b></label>
                                <input type="text" class="form-control text-center" name="diasBonificar" id="diasBonificar" readonly>
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
                        PRODUCTOS
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a href="javascript:void(0)" id="btnModalProducto" class="btn btn-primary">
                            <i class="fa fa-plus-square mr-1"></i>ABRIR PRODUCTOS
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablaProductoRegistrado">
                            <thead>
                                <tr>
                                    <th class="text-center">LINEA</th>
                                    <th class="text-center">MARCA</th>
                                    <th class="text-center">FORMATO</th>
                                    <th class="text-center">COD</th>
                                    <th class="text-center">CAJA X</th>
                                    <th class="text-center">CONDICIÓN AT</th>
                                    <th class="text-center">SKU</th>
                                    <th class="text-center">BONIF (BOTELLAS)</th>
                                    <th class="text-center">MARCA/FORMATO BONIFICAR</th>
                                    <th class="text-center">SABOR A BONIFICAR</th>
                                    <th class="text-center" style="width:10%">OPCIONES</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="11" class="text-center">CARGANDO PRODUCTOS...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Bonificacion.modal.modalProducto')
</div>
@endsection
@push('js')
<script>
    idCeo = @json($idCeo);
    nombreCeo = @json($nombreCeo);
    Bonificacion = @json($Bonificacion);

</script>
<script src="{{asset('assets/viewJs/Bonificacion/BonificacionEditar.js')}}"></script>
@endpush

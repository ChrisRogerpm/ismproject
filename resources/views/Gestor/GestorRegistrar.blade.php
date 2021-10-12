@extends('Shared.app')

@push('css')
<style>
    .input-group {
        flex-wrap: nowrap;
    }

</style>
@endpush

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">REGISTRAR GESTOR </h3>
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
                        DATOS DE GESTOR
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form id="frmNuevo" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>CENTRO OPERATIVO</b></label>
                                <input type="text" class="form-control" value="{{$nombreCeo}}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label><b>NOMBRE</b></label>
                                <input type="text" class="form-control" name="nombre" id="nombre">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>CODIGO</b></label>
                                <input type="text" class="form-control" name="codigoGestor" id="codigoGestor">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>TELEFONO</b></label>
                                <input type="text" class="form-control" name="telefono" id="telefono">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>DNI</b></label>
                                <input type="text" class="form-control" name="nroDocumento" id="nroDocumento">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label><b>MESA</b></label>
                                <div class="input-group">
                                    <select name="idMesa" id="CbidMesa" class="form-control" style="width:100%"></select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary btn-icon" data-skin="dark" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="Recuerde, al cambiar de mesa, las rutas y los supervisores registrados se cambiaran">
                                            <i class="fa fa-info-circle"></i>
                                        </button>
                                    </div>
                                </div>

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
                    <h3 class="kt-portlet__head-title" id="txtTituloProductos">
                        PRODUCTOS
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a href="javascript:void(0)" id="btnModalProducto" class="btn btn-primary">
                            <i class="fa fa-plus-square mr-1"></i>ABRIR PRODUCTOS
                        </a>
                        <a href="javascript:void(0)" id="btnEliminarProductos" class="btn btn-danger">
                            <i class="fa fa-trash mr-1"></i>ELIMINAR PRODUCTOS
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="tablaProductoRegistrado">
                                <thead>
                                    <tr>
                                        <th class="text-center">CODALT</th>
                                        <th>PRODUCTO</th>
                                        <th>MARCA</th>
                                        <th>FORMATO</th>
                                        <th>SABOR</th>
                                        <th class="text-center" style="width:10%">OPCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" id="txtTituloRutas">
                        RUTAS
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a href="javascript:void(0)" id="btnModalRutas" class="btn btn-primary">
                            <i class="fa fa-plus-square mr-1"></i>ABRIR RUTAS
                        </a>
                        <a href="javascript:void(0)" id="btnEliminarRutas" class="btn btn-danger">
                            <i class="fa fa-trash mr-1"></i>ELIMINAR RUTAS
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="tablaRutaRegistrado">
                            <thead>
                                <tr>
                                    <th>RUTA</th>
                                    <th class="text-center" style="width:10%">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">NO SE HA REGISTRADO RUTAS</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" id="txtTituloSupervisores">
                        SUPERVISORES
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <a href="javascript:void(0)" id="btnModalSupervisors" class="btn btn-primary">
                            <i class="fa fa-plus-square mr-1"></i>ABRIR SUPERVISORES
                        </a>
                        <a href="javascript:void(0)" id="btnEliminarSupervisors" class="btn btn-danger">
                            <i class="fa fa-trash mr-1"></i>ELIMINAR SUPERVISORES
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="tablaSupervisorRegistrado">
                            <thead>
                                <tr>
                                    <th>SUPERVISOR</th>
                                    <th class="text-center" style="width:10%">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">NO SE HA REGISTRADO SUPERVISORES</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Gestor.modal.modalProducto')
    @include('Gestor.modal.modalRuta')
    @include('Gestor.modal.modalSupervisor')
</div>
@endsection

@push('js')
<script>
    idCeo = @json($idCeo);

</script>
<script src="{{asset('assets/viewJs/Gestor/GestorRegistrar.js')}}"></script>
@endpush

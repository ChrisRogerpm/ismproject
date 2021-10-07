@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">EDITAR PRODUCTO </h3>
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
                        DATOS DE PRODUCTO
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <form id="frmNuevo" autocomplete="off">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CENTRO OPERATIVO</b></label>
                                <input type="text" class="form-control" id="nombreCeo" value="{{$nombreCeo}}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>LINEA</b></label>
                                <select name="idLinea" id="CbidLinea" class="form-control" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CODALT</b></label>
                                <input type="text" class="form-control" id="sku" name="sku">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>NOMBRE DE PRODUCTO</b></label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>MARCA</b></label>
                                <input type="text" class="form-control" id="marca" name="marca">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>FORMATO</b></label>
                                <input type="text" class="form-control" id="formato" name="formato">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>SABOR</b></label>
                                <input type="text" class="form-control" id="sabor" name="sabor">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>UNIDAD X CAJA</b></label>
                                <input type="text" class="form-control" id="unidadxCaja" name="unidadxCaja">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>UNIDAD X PAQUETE</b></label>
                                <input type="text" class="form-control" id="unidadxPaquete" name="unidadxPaquete">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CAJA X PAQUETE</b></label>
                                <input type="text" class="form-control" id="cajaxpaquete" name="cajaxpaquete">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CODIGO PADRE</b></label>
                                <input type="text" class="form-control" id="codigoPadre" name="codigoPadre">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CODIGO HIJO</b></label>
                                <input type="text" class="form-control" id="codigoHijo" name="codigoHijo">
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
<script>
    idCeo = @json($idCeo);
    Producto = @json($Producto);

</script>
<script src="{{asset('assets/viewJs/Producto/ProductoEditar.js')}}"></script>
@endpush

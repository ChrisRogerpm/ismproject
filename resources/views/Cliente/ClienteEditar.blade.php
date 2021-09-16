@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">EDITAR CLIENTE </h3>
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
                        DATOS DE CLIENTE
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
                                <label for=""><b>NRO DE DOCUMENTO</b></label>
                                <input type="text" class="form-control" name="nroDocumento" id="nroDocumento">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>RUTA</b></label>
                                <input type="text" class="form-control" name="ruta" id="ruta">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>MODULO</b></label>
                                <input type="text" class="form-control" name="modulo" id="modulo">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CODIGO DE CLIENTE</b></label>
                                <input type="text" class="form-control" name="codigoCliente" id="codigoCliente">
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label for=""><b>NOMBRE / RAZÓN SOCIAL</b></label>
                                <input type="text" class="form-control" name="nombreRazonSocial" id="nombreRazonSocial">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for=""><b>DIRECCIÓN</b></label>
                                <input type="text" class="form-control" name="direccion" id="direccion">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>REFERENCIA</b></label>
                                <input type="text" class="form-control" name="referencia" id="referencia">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>NEGOCIO</b></label>
                                <input type="text" class="form-control" name="negocio" id="negocio">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CANAL</b></label>
                                <input type="text" class="form-control" name="canal" id="canal">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>SUBCANAL</b></label>
                                <input type="text" class="form-control" name="subCanal" id="subCanal">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>GIRO NEGOCIO</b></label>
                                <input type="text" class="form-control" name="giroNegocio" id="giroNegocio">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>DISTRITO</b></label>
                                <input type="text" class="form-control" name="distrito" id="distrito">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>DIA DE VISITA</b></label>
                                <input type="text" class="form-control" name="diaVisita" id="diaVisita">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>DIA DE REPARTO</b></label>
                                <input type="text" class="form-control" name="diaReparto" id="diaReparto">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>TELEFONO</b></label>
                                <input type="text" class="form-control" name="telefono" id="telefono">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CODIGO ANTIGUO</b></label>
                                <input type="text" class="form-control" name="codigoAntiguo" id="codigoAntiguo">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>USUARIO</b></label>
                                <input type="text" class="form-control" name="usuario" id="usuario">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CONTRASENIA</b></label>
                                <input type="text" class="form-control" name="contrasenia" id="contrasenia">
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
    Cliente = @json($Cliente);

</script>
<script src="{{asset('assets/viewJs/Cliente/ClienteEditar.js')}}"></script>
@endpush

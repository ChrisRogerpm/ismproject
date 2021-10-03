@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">COMISION GESTORES </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
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
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for=""><b>CENTRO OPERATIVO</b></label>
                                <select name="idCeo" id="CbidCeo" class="form-control" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for=""><b>FECHA INICIO</b></label>
                                <div class="input-group">
                                    <input type="text" class="form-control Fecha" name="fechaInicio" id="fechaInicio">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for=""><b>FECHA FIN</b></label>
                                <div class="input-group">
                                    <input type="text" class="form-control Fecha" name="fechaFin" id="fechaFin">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="table">
                            <tfoot>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-center">TOTAL</td>
                                    <td id="txtTotalCantidadSumado">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Reportes.modal.modalPedidoDetalleGestor')
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Reporte/ReporteComisiones.js')}}"></script>
@endpush

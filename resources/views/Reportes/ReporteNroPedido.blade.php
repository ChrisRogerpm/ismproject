@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">PEDIDOS MÁS VENDIDOS </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        {{-- <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> ACCIONES
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" id="GenerarExcel" href="javascript:void(0)"><i class="fa fa-file-excel"></i>EXPORTAR EXCEL</a>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-xl">
                    <div class="col-lg-6">
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">TOTAL DE PEDIDOS VENDIDOS</h3>
                                    <span class="kt-widget1__desc"><b>MONTO TOTAL</b></span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand" id="MontoTotalNroPedidosVendidos">0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">PEDIDO MÁS VENDIDO</h3>
                                    <span class="kt-widget1__desc"><b id="txtNombreNroPedidoMasVendido" class="text-uppercase">Pedido no encontrado</b></span>
                                </div>
                                <span class="kt-widget1__number kt-font-primary" id="txtCantidadTotalNroPedidoMasVendido">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form id="frmNuevo">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CENTRO OPERATIVO</b></label>
                                <select name="idCeo" id="CbidCeo" class="form-control" style="width: 100%;"></select>
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
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="table">
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td class="text-right">TOTAL</td>
                                    <td id="txtTotalCantidadSumado">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Reporte/ReporteNroPedidoListar.js')}}"></script>
@endpush

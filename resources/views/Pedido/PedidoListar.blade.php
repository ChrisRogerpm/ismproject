@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">PEDIDOS </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
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
                                    <h3 class="kt-widget1__title">TOTAL DE GESTORES ACTIVOS</h3>
                                    <span class="kt-widget1__desc"><b>MONTO TOTAL</b></span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand" id="MontoTotalGestoresActivos">0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">BONIFICACION ACTIVA</h3>
                                    <span class="kt-widget1__desc"><b>FECHA VIGENCIA</b></span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand" id="FechaVigenciaBonificacion">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--mobile">
            {{-- <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" id="txtTituloProductos"></h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-actions">
                        <button type="button" class="btn btn-success" id="btnActualizarGestores">
                            <i class="fa fa-cog mr-1"></i>
                            ACTUALIZAR GESTORES
                        </button>
                    </div>
                </div>
            </div> --}}
            <div class="kt-portlet__body">
                <form id="frmNuevo">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for=""><b>CENTRO OPERATIVO</b></label>
                                <select name="idCeo" id="CbidCeo" class="form-control" style="width: 100%;"></select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_tabs_1_1" id="tabListado">
                                    <i class="fa fa-file-alt"></i> LISTADO DE PEDIDOS
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2">
                                    <i class="fa fa-cog"></i> CONVERTIR ARCHIVO PEDIDO
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-striped table-sm table-bordered table-hover table-checkable" id="table"></table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                                <form id="frmImportarPedido">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for=""><strong>SUBIR ARCHIVO PEDIDOS (*)</strong></label>
                                                <div></div>
                                                <div class="custom-file">
                                                    <input type="file" name="archivoPedido" id="archivoPedido" class="custom-file-input" accept=".xlsx">
                                                    <label class="custom-file-label" for="customFile">Elegir archivo...</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 ml-auto">
                                            <button type="button" class="btn btn-primary btn-block" id="btnImportarPedido"><i class="fa fa-plus-square"></i>IMPORTAR PEDIDO</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Pedido.modal.modalPedidoDetalle')
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Pedido/PedidoListar.js')}}"></script>
@endpush

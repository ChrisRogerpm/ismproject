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
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#kt_tabs_1_1">
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
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="table"></table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                                2 It has survived not only five centuries, but also the leap into
                                electronic typesetting, remaining essentially unchanged. It was
                                popularised in the 1960s with the release of Letraset sheets containing
                                Lorem Ipsum passages, and more recently with desktop publishing software
                                like Aldus PageMaker including versions of Lorem Ipsum.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Pedido/PedidoListar.js')}}"></script>
@endpush

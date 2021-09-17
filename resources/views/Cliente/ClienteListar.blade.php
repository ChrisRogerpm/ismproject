@extends('Shared.app')

@section('header')
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">CLIENTES </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-cog"></i> ACCIONES
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item btnNuevo" href="javascript:void(0)"><i class="fa fa-plus-square"></i>NUEVO CLIENTE</a>
                    <a class="dropdown-item btnModalImportarCliente" href="javascript:void(0)"><i class="fa fa-file-excel"></i>IMPORTAR EXCEL</a>
                    <a class="dropdown-item btnRecargar" href="javascript:void(0)"><i class="fa fa-redo"></i>RECARGAR</a>
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
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="table"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Cliente.modal.modalImportarCliente')
</div>
@endsection
@push('js')
<script src="{{asset('assets/viewJs/Cliente/ClienteListar.js')}}"></script>
@endpush
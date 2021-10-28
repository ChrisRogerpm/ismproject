let PedidoListar = (function () {
    const fncAcciones = function () {
        $(document).on("change", "#CbidCeo", function () {
            fncListarPedidos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
            fncMostrarDataGestoresBonificaciones({
                idCeo: $("#CbidCeo").val(),
            });
        });
        $(document).on("change", "#archivoPedido", function (e) {
            const files = e.target.files;
            if (files[0] === undefined) {
                $(`.custom-file-label`).text("Elegir archivo...");
            } else {
                $(`.custom-file-label`).text(files[0].name);
            }
        });
        $(document).on("click", "#btnImportarPedido", function () {
            $("#frmImportarPedido").submit();
            if (_objetoForm_frmImportarPedido.valid()) {
                let dataForm = new FormData($("#frmImportarPedido")[0]);
                dataForm.append("idCeo", parseInt($("#CbidCeo").val()));
                EnviarDataPost({
                    url: "PedidoImportarDataJson",
                    data: dataForm,
                    callBackSuccess: function (response) {
                        $("#tabListado").click();
                        $(`.custom-file-label`).text("Elegir archivo...");
                        LimpiarFormulario({
                            formulario: "#frmImportarPedido",
                            nameVariable: "frmImportarPedido",
                        });
                        let urlExcel = `${basePath}Excels/${response}`;
                        window.open(urlExcel, "_blank");
                        fncListarPedidos();
                    },
                });
            }
        });
        $(document).on("click", ".btnVerDetalle", function () {
            let nroPedido = $(this).data("id");
            let idCeo = $("#CbidCeo").val();
            fncListarPedidoDetalle({
                data: {
                    nroPedido: nroPedido,
                    idCeo: idCeo,
                },
                callBackSuccess: function () {
                    $("#ModalPedidoDetalle").modal({
                        keyboard: false,
                        backdrop: "static",
                    });
                },
            });
        });
    };
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "CentroOperativoListarActivosJson",
            idSelect: "#CbidCeo",
            dataId: "idCeo",
            dataValor: "nombreCeo",
            withoutplaceholder: true,
            callBackSuccess: function () {
                fncListarPedidos({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
                fncMostrarDataGestoresBonificaciones({
                    idCeo: $("#CbidCeo").val(),
                });
            },
        });
    };
    const fncMostrarDataGestoresBonificaciones = (obj) => {
        let objeto = {
            idCeo: 0,
        };
        let options = $.extend({}, objeto, obj);
        CargarDataGET({
            url: "ReporteGestoresBonificacionCentroOperativoJson",
            dataForm: {
                idCeo: options.idCeo,
            },
            callBackSuccess: function (response) {
                $("#MontoTotalGestoresActivos").text(response.GestoresVigentes);
                $("#FechaVigenciaBonificacion").text(
                    response.BonificacionVigente
                );
            },
        });
    };
    const fncListarPedidoDetalle = function (obj) {
        let objeto = {
            data: {},
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "PedidoDetalleListarJson",
            table: "#tablePedidoDetalle",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nroPedido", title: "NRO DE PEDIDO" },
                { data: "fechaVenta", title: "FECHA DE VENTA" },
                { data: "fechaMovimiento", title: "FECHA DE MOVIMIENTO" },
                { data: "sku", title: "SKU" },
                { data: "cantidad", title: "CANTIDAD" },
                { data: "precio", title: "PRECIO" },
                { data: "precioDescuento", title: "PRECIO DESCUENTO" },
                { data: "descuento", title: "DESCUENTO" },
                { data: "tdocto", title: "TDOCTO" },
            ],
            tabledrawCallback: function () {},
            callBackSuccess: function () {
                options.callBackSuccess();
            },
        });
    };
    const fncListarPedidos = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "PedidoListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nroPedido", title: "NRO DE PEDIDO" },
                { data: "codigoCliente", title: "CODIGO DE CLIENTE" },
                { data: "direccion", title: "DIRECCIÓN" },
                { data: "nombreRazonSocial", title: "NOMBRE" },
                { data: "tipoDocumento", title: "TIPO DE DOCUMENTO" },
                { data: "nroDocumento", title: "NRO DE DOCUMENTO" },
                { data: "ruta", title: "RUTA" },
                { data: "modulo", title: "MODULO" },
                { data: "nombreGestor", title: "GESTOR" },
                {
                    data: null,
                    title: "OPCIONES",
                    render: function (value) {
                        let ver = `<a class="dropdown-item btnVerDetalle" data-id="${value.nroPedido}" href="javascript:void(0)"><i class="fa fa-eye"></i> VER DETALLE</a>`;
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${ver}
                                    </div>
                                </span>`;
                    },
                    className: "text-center",
                },
            ],
        });
    };
    const fncValidarFormularioImportarPedido = () => {
        ValidarFormulario({
            contenedor: "#frmImportarPedido",
            nameVariable: "frmImportarPedido",
            rules: {
                archivoPedido: { required: true },
            },
            messages: {
                archivoPedido: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncValidarFormularioImportarPedido();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    PedidoListar.init();
});

ListadePedidos = [];
let PedidoListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnBuscar", function () {
            fncListarPedidos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarPedidos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnImportarExcel", function () {
            LimpiarFormulario({
                formulario: "frmImportarPedido",
                nameVariable: "frmImportarPedido",
            });
            $(`.custom-file-label`).text("Elegir archivo...");
            $("#ModalImportarPedido").modal({
                keyboard: false,
                backdrop: "static",
            });
        });
        $(document).on("change", "#PedidoExcel", function (e) {
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
                    callBackSuccess: function () {
                        $("#ModalImportarPedido").modal("hide");
                        fncListarPedidos();
                    },
                });
            }
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
            ],
            callBackSuccess: function (response) {
                ListadePedidos = response.data;
            },
        });
    };
    const fncValidarFormularioImportarPedido = () => {
        ValidarFormulario({
            contenedor: "#frmImportarPedido",
            nameVariable: "frmImportarPedido",
            rules: {
                PedidoExcel: { required: true },
            },
            messages: {
                PedidoExcel: { required: "El campo es requerido" },
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

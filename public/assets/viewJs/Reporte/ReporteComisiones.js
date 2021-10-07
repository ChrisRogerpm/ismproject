let ReporteComisionesListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnBuscar", function () {
            fncListarReporteComisiones({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarReporteComisiones({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".Fecha", function () {
            fncListarReporteComisiones({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnVer", function () {
            let idGestor = $(this).data("id");
            let dataForm = $("#frmNuevo").serializeFormJSON();
            $("#txtTituloModalItem").text(
                `PRODUCTOS VENDIDOS ENTRE ${dataForm.fechaInicio} / ${dataForm.fechaFin}`
            );
            dataForm = Object.assign(dataForm, {
                idGestor: idGestor,
            });
            fncListarPedidoDetalleGestor({
                data: dataForm,
                callBackSuccess: function () {
                    $("#ModalPedidoDetalleGestor").modal({
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
                fncListarReporteComisiones({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
        $(".Fecha")
            .datepicker({ language: "es", orientation: "bottom left" })
            .datepicker("setDate", new Date());
    };
    const fncListarPedidoDetalleGestor = function (obj) {
        let objeto = {
            data: {},
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "PedidoDetalleListarProductosGestorJson",
            table: "#tablePedidoDetalleGestor",
            ajaxDataSend: options.data,
            tableOrdering: false,
            tableColumns: [
                {
                    data: "sku",
                    title: "SKU",
                    className: "text-center",
                    width: "10%",
                },
                {
                    data: "codigoPadre",
                    title: "CODIGO PADRE",
                    className: "text-center",
                    width: "10%",
                },
                { data: "nombreProducto", title: "PRODUCTO" },
                {
                    data: "cantidad",
                    title: "CANTIDAD",
                    className: "text-center",
                    width: "10%",
                },
                {
                    data: "montoComision",
                    title: "COMISIÓN",
                    className: "text-center",
                },
            ],
            tablefooterCallback: function () {
                var api = this.api();
                var intVal = function (i) {
                    return typeof i === "string"
                        ? i.replace(/[\$,]/g, "") * 1
                        : typeof i === "number"
                        ? i
                        : 0;
                };
                let TotalCantidadSumado = api
                    .column(4, { search: "applied" })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $("#txtTotalDetalleGestor").text(
                    TotalCantidadSumado.toFixed(2)
                );
            },
            callBackSuccess: function () {
                options.callBackSuccess();
            },
        });
    };
    const fncListarReporteComisiones = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ReporteComisionesGestoresJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableOrdering: false,
            tableColumns: [
                {
                    data: "codigoGestor",
                    title: "CODIGO",
                    className: "text-center",
                    width: "10%",
                },
                { data: "nombre", title: "NOMBRE" },
                {
                    data: "telefono",
                    title: "TELEFONO",
                    className: "text-center",
                    width: "10%",
                },
                {
                    data: "nroDocumento",
                    title: "DNI",
                    className: "text-center",
                    width: "10%",
                },
                {
                    data: "montoComision",
                    title: "COMISIÓN",
                    className: "text-center",
                    width: "10%",
                },
                {
                    data: null,
                    title: "OPCIONES",
                    render: function (value) {
                        let ver = `<a class="dropdown-item btnVer" data-id="${value.codigoGestor}" href="javascript:void(0)"><i class="fa fa-file-alt"></i> VER DETALLE</a>`;
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${ver}
                                    </div>
                                </span>`;
                    },
                    className: "text-center",
                    width: "8%",
                },
            ],
            tablefooterCallback: function () {
                var api = this.api();
                var intVal = function (i) {
                    return typeof i === "string"
                        ? i.replace(/[\$,]/g, "") * 1
                        : typeof i === "number"
                        ? i
                        : 0;
                };
                let TotalCantidadSumado = api
                    .column(4, { search: "applied" })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $("#txtTotalCantidadSumado").text(
                    TotalCantidadSumado.toFixed(2)
                );
            },
        });
    };
    return {
        init: function () {
            fncInicializarData();
            fncAcciones();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ReporteComisionesListar.init();
});

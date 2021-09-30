let ReportePedidoListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnBuscar", function () {
            fncListarReportePedidos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarReportePedidos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".Fecha", function () {
            fncListarReportePedidos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on('click', "#GenerarExcel", function () {
            let { fechaInicio, fechaFin } = $("#frmNuevo").serializeFormJSON();
            let idCeo = $("#CbidCeo").val();
            let url = `${basePathApi}PedidoMasVendidoDownload?idCeo=${idCeo}&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
            window.open(url, "_blank");
        })
    };
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "CentroOperativoListarActivosJson",
            idSelect: "#CbidCeo",
            dataId: "idCeo",
            dataValor: "nombreCeo",
            withoutplaceholder: true,
            callBackSuccess: function () {
                fncListarReportePedidos({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
        $(".Fecha").datepicker({
            language: "es",
            orientation: "bottom left",
        }).datepicker("setDate", new Date());
    };
    const fncListarReportePedidos = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ReporteNroPedidoListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableOrdering: false,
            tableColumns: [
                { data: "nroPedido", title: "NRO DE PEDIDO", className: "text-center", width: "10%" },
                { data: "productosInvolucrados", title: "PRODUCTOS (MARCAS)" },
                { data: "TotalPedido", title: "TOTAL DE PEDIDO", className: "text-center", width: "10%" },

            ],
            callBackSuccess: function (response) {
                let data = response.data[0];
                if (data != undefined) {
                    $("#txtNombreNroPedidoMasVendido").text(data.nroPedido);
                    $("#txtCantidadTotalNroPedidoMasVendido").text(data.TotalPedido);
                } else {
                    $("#txtNombreNroPedidoMasVendido").text('Pedido no encontrado');
                    $("#txtCantidadTotalNroPedidoMasVendido").text('0.00');
                }
            },
            tablefooterCallback: function (row, tbldata, start, end, display) {
                var api = this.api(), tbldata;
                var intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                let TotalCantidadSumado = api
                    .column(2, { search: 'applied' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $("#txtTotalCantidadSumado").text(TotalCantidadSumado.toFixed(2));
                $("#MontoTotalNroPedidosVendidos").text(TotalCantidadSumado.toFixed(2));
            }
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
    ReportePedidoListar.init();
});

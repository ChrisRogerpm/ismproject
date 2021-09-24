let ReporteProductoListar = (function () {
    const fncAcciones = function () {

        $(document).on("click", ".btnBuscar", function () {
            fncListarReporteProductos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarReporteProductos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".Fecha", function () {
            fncListarReporteProductos({
                data: $("#frmNuevo").serializeFormJSON(),
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
                fncListarReporteProductos({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
        $(".Fecha")
            .datepicker({
                language: "es",
                orientation: "bottom left",
            })
            .datepicker("setDate", new Date());
    };
    const fncListarReporteProductos = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ReporteProductoListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableOrdering: false,
            // tablepageLength: 25,
            tableColumns: [
                { data: "skuProducto", title: "SKU", className: "text-center" },
                { data: "nombreProducto", title: "PRODUCTO" },
                { data: "precio", title: "PRECIO", className: "text-center" },
                { data: "cantidad", title: "CANTIDAD", className: "text-center" },
                { data: "total", title: "TOTAL", className: "text-center" },
            ],
            callBackSuccess: function (response) {
                let data = response.data[0];
                if (data != undefined) {
                    $("#txtNombreProductoMasVendido").text(data.nombreProducto);
                    $("#txtCantidadTotalProductoMasVendido").text(data.cantidad);
                } else {
                    $("#txtNombreProductoMasVendido").text('Producto no encontrado');
                    $("#txtCantidadTotalProductoMasVendido").text('0.00');
                }
                // txtNombreProductoMasVendido
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
                    .column(3, { search: 'applied' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                let TotalCostoSumado = api
                    .column(4, { search: 'applied' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $("#MontoTotalProductosVendidos").text(TotalCantidadSumado.toFixed(2));
                $("#txtTotalCantidadSumado").text(TotalCantidadSumado.toFixed(2));
                $("#txtTotalCostoSumado").text(TotalCostoSumado.toFixed(2));
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
    ReporteProductoListar.init();
});

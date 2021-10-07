ListadeRutas = [];
let RutaListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnBuscar", function () {
            fncListarRutas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarRutas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".btnBuscar", function () {
            fncListarRutas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnActualizarRutas", function () {
            let idCeo = $("#CbidCeo").val();
            Swal.fire({
                title: `¿DESEA ACTUALIZAR LAS RUTAS?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#fc8004",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, ACTUALIZAR",
                cancelButtonText: "CANCELAR",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "RutaActualizarJson",
                        data: {
                            idCeo: idCeo,
                        },
                        callBackSuccess: function () {
                            setTimeout(() => {
                                fncListarRutas();
                            }, 1100);
                        },
                    });
                }
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
                fncListarRutas({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarRutas = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "RutaListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "descripcion", title: "RUTA" },
            ],
            tabledrawCallback: function () {
                $(".btnEditar").tooltip();
            },
            callBackSuccess: function (response) {
                ListadeRutas = response.data;
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    RutaListar.init();
});

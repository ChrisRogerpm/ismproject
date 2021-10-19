ListadeBonificacions = [];
let BonificacionListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarBonificacion/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idBonificacion = $(this).data("id");
            RedirigirUrl("EditarBonificacion/" + idBonificacion);
        });
        $(document).on("click", ".btnBuscar", function () {
            fncListarBonificacions({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarBonificacions({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".btnBuscar", function () {
            fncListarBonificacions({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnActivar", function () {
            let idBonificacion = $(this).data("id");
            let objBonificacion = ListadeBonificacions.find((ele) => {
                ele.idBonificacion == idBonificacion;
            });
            Swal.fire({
                title: `ESTA SEGURO DE ACTIVAR ESTA BONIFICACION?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, ACTIVAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "BonificacionActivarJson",
                        data: {
                            idBonificacion: idBonificacion,
                        },
                        callBackSuccess: function () {
                            fncListarBonificacions();
                        },
                    });
                }
            });
        });
        $(document).on("click", "#GenerarExcel", function () {
            let objBonificacionActiva = ListadeBonificacions.find(
                (ele) => parseInt(ele.estado) == 1
            );
            if (objBonificacionActiva != null) {
                let idCeo = $("#CbidCeo").val();
                let url = `${basePathApi}BonificacionesDownload?idCeo=${idCeo}`;
                window.open(url, "_blank");
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA ENCONTRADO BONIFICACIÓN ACTIVA",
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
                fncListarBonificacions({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarBonificacions = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "BonificacionListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableOrdering: false,
            tableColumns: [
                { data: "nombreBonificacion", title: "NOMBRE" },
                {
                    data: "fechaInicio",
                    title: "FECHA DE INICIO",
                    className: "text-center",
                    width: "13%",
                },
                {
                    data: "fechaFin",
                    title: "FECHA FIN",
                    className: "text-center",
                    width: "13%",
                },
                {
                    data: "diasBonificar",
                    title: "DÍAS A BONIFICAR",
                    className: "text-center",
                    width: "13%",
                },
                {
                    data: "estadoNombre",
                    title: "ESTADO",
                    class: "text-center",
                    width: "13%",
                },
                {
                    data: null,
                    title: "OPCIONES",
                    width: "10%",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idBonificacion}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        let activar = `<a class="dropdown-item btnActivar" data-id="${value.idBonificacion}" href="javascript:void(0)"><i class="fa fa-hand-point-up"></i> ACTIVAR BONIFICACION</a>`;
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${editar}
                                    ${activar}
                                    </div>
                                </span>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".btnEditar").tooltip();
            },
            tablerowCallback: function (row, data, index) {
                if (data.estadoNombre == "ACTIVO") {
                    $(row)
                        .find("td:eq(4)")
                        .css({ color: "white", "background-color": "#E53935" });
                } else {
                    $(row)
                        .find("td:eq(4)")
                        .css({ color: "white", "background-color": "#7CB342" });
                }
            },
            callBackSuccess: function (response) {
                ListadeBonificacions = response.data;
                let verificarBonificacionActiva = ListadeBonificacions.find(
                    (ele) => parseInt(ele.estado) == 1
                );
                if (verificarBonificacionActiva == null) {
                    ShowAlert({
                        type: "warning",
                        message:
                            "NO SE HA ENCONTRADO NINGUNA BONIFICACION ACTIVO",
                    });
                }
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
    BonificacionListar.init();
});

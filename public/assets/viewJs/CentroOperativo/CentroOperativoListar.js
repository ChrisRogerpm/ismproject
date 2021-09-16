ListaCentroOperativo = [];
let CentroOperativoListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            RedirigirUrl("RegistrarCentroOperativo");
        });
        $(document).on("click", ".btnEditar", function () {
            let idCeo = $(this).data("id");
            RedirigirUrl("EditarCentroOperativo/" + idCeo);
        });
        $(document).on("click", ".btnBloquear", function () {
            let idCeo = $(this).data("id");
            let objCentroOperativo = ListaCentroOperativo.find(
                (ele) => ele.idCeo == idCeo
            );
            Swal.fire({
                title: `ESTA SEGURO DE BLOQUEAR ESTE CENTRO OPERATIVO : ${objCentroOperativo.nombreCeo}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, BLOQUEAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "CentroOperativoBloquearJson",
                        data: {
                            idCeo: idCeo,
                        },
                        callBackSuccess: function () {
                            fncListarCentroOperativos();
                        },
                    });
                }
            });
        });
        $(document).on("click", ".btnRestablecer", function () {
            let idCeo = $(this).data("id");
            let objCentroOperativo = ListaCentroOperativo.find(
                (ele) => ele.idCeo == idCeo
            );
            Swal.fire({
                title: `ESTA SEGURO DE RESTABLECER ESTE CENTRO OPERATIVO : ${objCentroOperativo.nombreCeo}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, RESTABLECER!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "CentroOperativoRestablecerJson",
                        data: {
                            idCeo: idCeo,
                        },
                        callBackSuccess: function () {
                            fncListarCentroOperativos();
                        },
                    });
                }
            });
        });
    };
    const fncListarCentroOperativos = function () {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "CentroOperativoListarJson",
            table: "#table",
            tableColumns: [
                { data: "nombreCeo", title: "NOMBRE" },
                { data: "codigoCeo", title: "CODIGO" },
                { data: "empresa", title: "EMPRESA" },
                { data: "lugar", title: "LUGAR" },
                {
                    data: null,
                    title: "ESTADO",
                    className: "text-center",
                    render: function (value) {
                        return value.estado == 1 ? "ACTIVO" : "INACTIVO";
                    },
                },
                {
                    data: null,
                    title: "OPCIONES",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idCeo}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        let bloquear = `<a class="dropdown-item btnBloquear" data-id="${value.idCeo}" href="javascript:void(0)"><i class="fa fa-lock"></i> BLOQUEAR</a>`;
                        let restablecer = `<a class="dropdown-item btnRestablecer" data-id="${value.idCeo}" href="javascript:void(0)"><i class="fa fa-lock-open"></i> RESTABLECER</a>`;
                        if (value.estado == 1) {
                            restablecer = ``;
                        } else {
                            bloquear = ``;
                            editar = ``;
                        }
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${editar}
                                    ${bloquear}
                                    ${restablecer}
                                    </div>
                                </span>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".btnEditar").tooltip();
            },
            callBackSuccess: function (response) {
                ListaCentroOperativo = response.data;
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncListarCentroOperativos();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    CentroOperativoListar.init();
});

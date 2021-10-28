ListadeUsuarios = [];
let UsuarioListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            RedirigirUrl(`RegistrarUsuario`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idUsuario = $(this).data("id");
            RedirigirUrl("EditarUsuario/" + idUsuario);
        });
        $(document).on("click", ".btnBloquear", function () {
            let idUsuario = $(this).data("id");
            let objUsuario = ListadeUsuarios.find(
                (ele) => ele.idUsuario == idUsuario
            );
            Swal.fire({
                title: `ESTA SEGURO DE BLOQUEAR ESTE USUARIO : ${objUsuario.nombreApellido}?`,
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
                        url: "UsuarioBloquearJson",
                        data: {
                            idUsuario: idUsuario,
                        },
                        callBackSuccess: function () {
                            fncListarUsuarios();
                        },
                    });
                }
            });
        });
        $(document).on("click", ".btnRestablecer", function () {
            let idUsuario = $(this).data("id");
            let objUsuario = ListadeUsuarios.find(
                (ele) => ele.idUsuario == idUsuario
            );
            Swal.fire({
                title: `ESTA SEGURO DE RESTABLECER ESTE USUARIO : ${objUsuario.nombreApellido}?`,
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
                        url: "UsuarioRestablecerJson",
                        data: {
                            idUsuario: idUsuario,
                        },
                        callBackSuccess: function () {
                            fncListarUsuarios();
                        },
                    });
                }
            });
        });
    };
    const fncListarUsuarios = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "UsuarioListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nombreCeo", title: "CENTRO OPERATIVO", width: "10%" },
                { data: "nombreRol", title: "ROL", width: "10%" },
                { data: "nombreApellido", title: "NOMBRE" },
                { data: "nroDocumento", title: "NRO DOCUMENTO", width: "10%" },
                {
                    data: "email",
                    title: "EMAIL",
                    width: "10%",
                    class: "text-center",
                },
                {
                    data: "estado",
                    title: "ESTADO",
                    width: "10%",
                    class: "text-center",
                },
                {
                    data: null,
                    title: "OPCIONES",
                    width: "10%",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idUsuario}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        let bloquear = `<a class="dropdown-item btnBloquear" data-id="${value.idUsuario}" href="javascript:void(0)"><i class="fa fa-lock"></i> BLOQUEAR</a>`;
                        let restablecer = `<a class="dropdown-item btnRestablecer" data-id="${value.idUsuario}" href="javascript:void(0)"><i class="fa fa-lock-open"></i> RESTABLECER</a>`;
                        if (value.idestado == 1) {
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
                ListadeUsuarios = response.data;
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncListarUsuarios();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    UsuarioListar.init();
});

ListadeRols = [];
ListaRolesEliminar = [];
let RolListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            RedirigirUrl(`RegistrarRol`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idRol = $(this).data("id");
            RedirigirUrl("EditarRol/" + idRol);
        });
        $(document).on("click", "#btnEliminarRoles", function () {
            Swal.fire({
                title: `ESTA SEGURO DE ELIMINAR LOS ROLES  SELECCIONADOS?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, ELIMINAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "RolEliminarJson",
                        data: {
                            ListaRolesEliminar: ListaRolesEliminar,
                        },
                        callBackSuccess: function () {
                            fncListarRols();
                        },
                    });
                }
            });
        });
        $(document).on("ifChecked", "#table input:checkbox", function () {
            let idRol = $(this).val();
            ListaRolesEliminar.push(parseInt(idRol));
        });
        $(document).on("ifUnchecked", "#table input:checkbox", function () {
            let idRol = $(this).val();
            ListaRolesEliminar = ListaRolesEliminar.filter(
                (item) => parseInt(item) != parseInt(idRol)
            );
        });
    };
    const fncListarRols = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "RolListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                {
                    data: null,
                    title: "",
                    width: "3%",
                    class: "text-center",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input type="checkbox" id="CheckTicket" value="${value.idRol}" data-checkbox="icheckbox_square-blue">
                        </div>`;
                    },
                },
                { data: "nombreRol", title: "ROL" },
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
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idRol}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${editar}
                                    </div>
                                </span>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".icheck-inline").iCheck({
                    checkboxClass: "icheckbox_square-blue",
                    radioClass: "iradio_square-red",
                    increaseArea: "25%",
                });
            },
            callBackSuccess: function (response) {
                ListadeRols = response.data;
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncListarRols();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    RolListar.init();
});

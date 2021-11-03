ListadePermisosTabla = [];
ListadePermisos = [];
let RolEditar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Rol`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                if (ListadePermisosTabla.length > 0) {
                    dataForm = Object.assign(dataForm, {
                        idRol: Rol.idRol,
                        ListadePermisosTabla: ListadePermisosTabla,
                    });
                    EnviarDataPost({
                        url: "RolEditarJson",
                        data: dataForm,
                        callBackSuccess: function () {
                            setTimeout(function () {
                                RefrescarVentana();
                            }, 1100);
                        },
                    });
                } else {
                    ShowAlert({
                        type: "warning",
                        message: "NO SE HA SELECCIONADO NINGUN MODULO",
                    });
                }
            }
        });
        $(document).on("ifChecked", "#table input:checkbox", function () {
            let modulo = $(this).val();
            ListadePermisosTabla.push(modulo);
            fncCargarModuloInicio();
        });
        $(document).on("ifUnchecked", "#table input:checkbox", function () {
            let modulo = $(this).val();
            ListadePermisosTabla = ListadePermisosTabla.filter(
                (item) => item != modulo
            );
            fncCargarModuloInicio();
        });
    };
    const fncInicializarData = () => {
        $("#nombreRol").val(Rol.nombreRol);
    };
    const fncCargarModuloInicio = () => {
        let contenedor = $("#CbpaginaInicio");
        contenedor.html("");
        contenedor.append(`<option value="">-- Seleccione --</option>`);
        if (ListadePermisosTabla.length > 0) {
            ListadePermisosTabla.map((ele) => {
                contenedor.append(
                    `<option
                        value="${ele}"
                        ${ele == Rol.paginaInicio ? `selected` : ``}
                    >
                    ${ele}
                    </option>`
                );
            });
        } else {
            contenedor
                .html("")
                .append(`<option value="">-- Seleccione --</option>`);
        }
        contenedor.select2();
    };
    const fncListarPermisos = function () {
        let listapermisos = ListaRolPermiso.map((ele) => ele.permisoModulo);
        ListadePermisosTabla = listapermisos;
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "PermisoListarJson",
            table: "#table",
            tableOrdering: false,
            tablepageLength: 25,
            tableColumns: [
                {
                    data: null,
                    title: "OPCIONES",
                    width: "3%",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input
                            type="checkbox"
                            id="CheckTicket"
                            value="${value.modulo}"
                            ${
                                listapermisos.find(
                                    (ele) => ele === value.modulo
                                ) != undefined
                                    ? "checked"
                                    : ""
                            }
                            data-checkbox="icheckbox_square-blue"
                            >
                        </div>
                        `;
                    },
                    class: "text-center",
                },
                { data: "modulo", title: "MODULO", width: "90%" },
            ],
            tabledrawCallback: function () {
                $(".icheck-inline").iCheck({
                    checkboxClass: "icheckbox_square-blue",
                    radioClass: "iradio_square-red",
                    increaseArea: "25%",
                });
            },
            callBackSuccess: function (response) {
                ListadePermisos = response.data;
                fncCargarModuloInicio();
            },
        });
    };
    const fncValidarFormularioEditar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                nombreRol: { required: true },
                paginaInicio: { required: true },
            },

            messages: {
                nombreRol: { required: "El campo es requerido" },
                paginaInicio: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncListarPermisos();
            fncValidarFormularioEditar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    RolEditar.init();
});

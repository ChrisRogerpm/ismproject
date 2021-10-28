ListadePermisosTabla = [];
ListadePermisos = [];
let RolRegistrar = (function () {
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
                        ListadePermisosTabla: ListadePermisosTabla,
                    });
                    EnviarDataPost({
                        url: "RolRegistrarJson",
                        data: dataForm,
                        callBackSuccess: function () {
                            setTimeout(function () {
                                RedirigirUrl(`Rol`);
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
    const fncCargarModuloInicio = () => {
        let contenedor = $("#CbpaginaInicio");
        contenedor.html("");
        contenedor.append(`<option value="">-- Seleccione --</option>`);
        if (ListadePermisosTabla.length > 0) {
            ListadePermisosTabla.map((ele) => {
                contenedor.append(`<option value="${ele}">${ele}</option>`);
            });
        } else {
            contenedor
                .html("")
                .append(`<option value="">-- Seleccione --</option>`);
        }
        contenedor.select2();
    };
    const fncListarPermisos = function () {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "PermisoListarJson",
            table: "#table",
            tablepageLength: 25,
            tableOrdering: false,
            tableColumns: [
                {
                    data: null,
                    title: "OPCIONES",
                    width: "3%",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input type="checkbox" id="CheckTicket" value="${value.modulo}" data-checkbox="icheckbox_square-blue">
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
    const fncValidarFormularioRegistrar = () => {
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
            fncListarPermisos();
            fncValidarFormularioRegistrar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    RolRegistrar.init();
});

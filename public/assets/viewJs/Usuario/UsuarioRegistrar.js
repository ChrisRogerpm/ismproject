let UsuarioRegistrar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Usuario`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                EnviarDataPost({
                    url: "UsuarioRegistrarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`Usuario`);
                        }, 1100);
                    },
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
        });
        CargarDataSelect({
            url: "RolListarJson",
            idSelect: "#CbidRol",
            dataId: "idRol",
            dataValor: "nombreRol",
        });
    };
    const fncValidarFormularioRegistrar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                idCeo: { required: true },
                idRol: { required: true },
                email: { required: true },
                nombre: { required: true },
                apellido: { required: true },
            },

            messages: {
                descripcion: { required: "El campo es requerido" },
                idCeo: { required: "El campo es requerido" },
                idRol: { required: "El campo es requerido" },
                email: { required: "El campo es requerido" },
                nombre: { required: "El campo es requerido" },
                apellido: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncValidarFormularioRegistrar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    UsuarioRegistrar.init();
});

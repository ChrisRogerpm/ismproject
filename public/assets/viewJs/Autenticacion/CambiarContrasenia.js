let CambiarContraseniaRegistrar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnGuardar", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                EnviarDataPost({
                    url: "PerfilCambiarContraseniaJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RefrescarVentana();
                        }, 1100);
                    },
                });
            }
        });
    };
    const fncValidarFormularioRegistrar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                password: { required: true },
                NuevaContrasenia: { required: true },
                VerificarContrasenia: { required: true },
            },
            messages: {
                password: { required: "El campo es requerido" },
                NuevaContrasenia: { required: "El campo es requerido" },
                VerificarContrasenia: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncValidarFormularioRegistrar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    CambiarContraseniaRegistrar.init();
});

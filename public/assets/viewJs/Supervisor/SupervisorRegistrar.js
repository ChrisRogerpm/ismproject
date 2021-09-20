let SupervisorRegistrar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function (e) {
            RedirigirUrl(`Supervisor`);
        });
        $(document).on("click", ".btnGuardar", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                dataForm = Object.assign(dataForm, {
                    idCeo: idCeo,
                });
                EnviarDataPost({
                    url: "SupervisorRegistrarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`Supervisor`);
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
                nombre: { required: true },
            },
            messages: {
                nombre: { required: "El campo es requerido" },
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
    SupervisorRegistrar.init();
});

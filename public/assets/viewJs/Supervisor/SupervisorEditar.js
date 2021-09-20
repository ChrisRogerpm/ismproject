let SupervisorEditar = (function () {
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
                    idSupervisor: Supervisor.idSupervisor,
                });
                EnviarDataPost({
                    url: "SupervisorEditarJson",
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
    const fncInicializarData = () => {
        $("#nombre").val(Supervisor.nombre);
    };
    const fncValidarFormularioEditar = () => {
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
            fncInicializarData();
            fncValidarFormularioEditar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    SupervisorEditar.init();
});

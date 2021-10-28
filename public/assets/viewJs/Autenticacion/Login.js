let LoginVista = (function () {
    const fncAcciones = () => {
        $(document).on("click", "#btnSesion", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                EnviarDataPost({
                    url: "ValidarLoginJson",
                    data: dataForm,
                    callBackSuccess: function (response) {
                        localStorage.setItem("token", response.token);
                        localStorage.setItem("userChecked", response.user);
                        setTimeout(function () {
                            RefrescarVentana();
                        }, 1100);
                    },
                });
            }
        });
        // $(document).on('keyup')
        $("input").keyup(function(e){
            if (e.keyCode === 13) {
                e.preventDefault();
                document.getElementById("btnSesion").click();
            }
        });
    };
    const fncValidarFormularioRegistrar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                acceso: { required: true },
                password: { required: true },
            },
            messages: {
                acceso: { required: "El campo es requerido" },
                password: { required: "El campo es requerido" },
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
    LoginVista.init();
});

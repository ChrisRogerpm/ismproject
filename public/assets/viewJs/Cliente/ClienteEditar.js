let ClienteEditar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function (e) {
            RedirigirUrl(`Cliente`);
        });
        $(document).on("click", ".btnGuardar", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                dataForm = Object.assign(dataForm, {
                    idCeo: Cliente.idCeo,
                    idCliente: Cliente.idCliente,
                });
                EnviarDataPost({
                    url: "ClienteEditarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`Cliente`);
                        }, 1100);
                    },
                });
            }
        });
    };
    const fncInicializarData = () => {
        $("#canal").val(Cliente.canal);
        $("#codigoAntiguo").val(Cliente.codigoAntiguo);
        $("#codigoCliente").val(Cliente.codigoCliente);
        $("#contrasenia").val(Cliente.contrasenia);
        $("#diaReparto").val(Cliente.diaReparto);
        $("#diaVisita").val(Cliente.diaVisita);
        $("#direccion").val(Cliente.direccion);
        $("#distrito").val(Cliente.distrito);
        $("#giroNegocio").val(Cliente.giroNegocio);
        $("#modulo").val(Cliente.modulo);
        $("#negocio").val(Cliente.negocio);
        $("#nombreRazonSocial").val(Cliente.nombreRazonSocial);
        $("#nroDocumento").val(Cliente.nroDocumento);
        $("#referencia").val(Cliente.referencia);
        $("#ruta").val(Cliente.ruta);
        $("#subCanal").val(Cliente.subCanal);
        $("#telefono").val(Cliente.telefono);
        $("#usuario").val(Cliente.usuario);
    };
    const fncValidarFormularioEditar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                nroDocumento: { required: true },
                ruta: { required: true },
                modulo: { required: true },
            },
            messages: {
                nroDocumento: { required: "El campo es requerido" },
                ruta: { required: "El campo es requerido" },
                modulo: { required: "El campo es requerido" },
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
    ClienteEditar.init();
});

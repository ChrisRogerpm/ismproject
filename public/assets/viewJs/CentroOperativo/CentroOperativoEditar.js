let CentroOperativoEditar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function (e) {
            RedirigirUrl(`CentroOperativo`);
        });
        $(document).on("click", ".btnGuardar", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                dataForm = Object.assign(dataForm, {
                    idCeo: CentroOperativo.idCeo,
                });
                EnviarDataPost({
                    url: "CentroOperativoEditarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`CentroOperativo`);
                        }, 1100);
                    },
                });
            }
        });
    };
    const fncInicializarData = () => {
        $("#nombreCeo").val(CentroOperativo.nombreCeo);
        $("#codigoCeo").val(CentroOperativo.codigoCeo);
        $("#lugar").val(CentroOperativo.lugar);
        $("#empresa").val(CentroOperativo.empresa);
    };
    const fncValidarFormularioEditar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                nombreCeo: { required: true },
                codigoCeo: { required: true },
                lugar: { required: true },
                empresa: { required: true },
            },
            messages: {
                nombreCeo: { required: "El campo es requerido" },
                codigoCeo: { required: "El campo es requerido" },
                lugar: { required: "El campo es requerido" },
                empresa: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncInicializarData();
            fncAcciones();
            fncValidarFormularioEditar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    CentroOperativoEditar.init();
});

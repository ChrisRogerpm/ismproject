var ReportePedido = function () {
    const Acciones = function () {
        $(document).on('click', '#btnCargarArchivo', function () {
            $("#frmReporte").submit();
            if (_objetoForm_frmReporte.valid()) {
                let dataForm = new FormData($("#frmReporte")[0]);
                EnviarDataPost({
                    url: "ReportImportarExcelsJson",
                    data: dataForm,
                    loader: true,
                    callBackSuccess: function (response) {
                        LimpiarFormulario({
                            formulario: '#frmReporte',
                            nameVariable: 'frmReporte'
                        });
                        $.uniform.update(".form-input-styled");
                        let url = basePath + 'Excels/' + response;
                        window.open(url, "_blank");
                    }
                })
            }
        });
    };
    const InicializarData = () => {
        $('.form-input-styled').uniform({
            fileButtonClass: 'action btn bg-dark'
        });
        LimpiarFormulario({
            formulario: '#frmReporte',
            nameVariable: 'frmReporte'
        });
    }
    const ValidaroFormularioReportes = function () {
        ValidarFormulario({
            contenedor: '#frmReporte',
            nameVariable: 'frmReporte',
            rules: {
                archivoPlantilla: { required: true },
                archivoPedido: { required: true },
                archivoBonificaciones: { required: true },
            },
            messages: {
                archivoPlantilla: { required: 'El campo es requerido' },
                archivoPedido: { required: 'El campo es requerido' },
                archivoBonificaciones: { required: 'El campo es requerido' },
            }
        });
    };
    return {
        init: function () {
            Acciones();
            InicializarData();
            ValidaroFormularioReportes();
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    ReportePedido.init();
});

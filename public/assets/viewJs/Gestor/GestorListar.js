ListadeGestores = [];
let GestorListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarGestor/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idGestor = $(this).data("id");
            RedirigirUrl("EditarGestor/" + idGestor);
        });
        $(document).on("click", ".btnBuscar", function () {
            fncListarGestors({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnBloquear", function () {
            let idGestor = $(this).data("id");
            let objGestor = ListadeGestores.find(
                (ele) => ele.idGestor == idGestor
            );
            Swal.fire({
                title: `ESTA SEGURO DE BLOQUEAR ESTE GESTOR : ${objGestor.nombreCeo}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, BLOQUEAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "GestorBloquearJson",
                        data: {
                            idGestor: idGestor,
                        },
                        callBackSuccess: function () {
                            fncListarGestors();
                        },
                    });
                }
            });
        });
        $(document).on("click", ".btnRestablecer", function () {
            let idGestor = $(this).data("id");
            let objGestor = ListadeGestores.find(
                (ele) => ele.idGestor == idGestor
            );
            Swal.fire({
                title: `ESTA SEGURO DE RESTABLECER ESTE GESTOR : ${objGestor.nombreCeo}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, RESTABLECER!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "GestorRestablecerJson",
                        data: {
                            idGestor: idGestor,
                        },
                        callBackSuccess: function () {
                            fncListarGestors();
                        },
                    });
                }
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarGestors({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnImportarExcel", function () {
            LimpiarFormulario({
                formulario: "frmImportarGestor",
                nameVariable: "frmImportarGestor",
            });
            $(`.custom-file-label`).text("Elegir archivo...");
            $("#ModalImportarGestor").modal({
                keyboard: false,
                backdrop: "static",
            });
        });
        $(document).on("change", "#gestorExcel", function (e) {
            const files = e.target.files;
            if (files[0] === undefined) {
                $(`.custom-file-label`).text("Elegir archivo...");
            } else {
                $(`.custom-file-label`).text(files[0].name);
            }
        });
        $(document).on("click", "#btnImportarGestor", function () {
            $("#frmImportarGestor").submit();
            if (_objetoForm_frmImportarGestor.valid()) {
                let dataForm = new FormData($("#frmImportarGestor")[0]);
                dataForm.append("idCeo", parseInt($("#CbidCeo").val()));
                EnviarDataPost({
                    url: "GestorImportarDataJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        $("#ModalImportarGestor").modal("hide");
                        fncListarGestors();
                    },
                });
            }
        });
        $(document).on('click', '#GenerarExcel', function () {
            let idCeo = $("#CbidCeo").val();
            let url = `${basePathApi}GestoresDownload?idCeo=${idCeo}`;
            window.open(url, "_blank");
        });
        $(document).on('click', '#GenerarExcelDetalle', function () {
            let idCeo = $("#CbidCeo").val();
            let url = `${basePathApi}GestorExcelDownload?idCeo=${idCeo}`;
            window.open(url, "_blank");
        });
    };
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "CentroOperativoListarActivosJson",
            idSelect: "#CbidCeo",
            dataId: "idCeo",
            dataValor: "nombreCeo",
            withoutplaceholder: true,
            callBackSuccess: function () {
                fncListarGestors({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarGestors = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "GestorListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "codigoGestor", title: "CODIGO" },
                { data: "nombre", title: "NOMBRE" },
                { data: "telefono", title: "TELEFONO" },
                { data: "nroDocumento", title: "DNI" },
                {
                    data: null,
                    title: "ESTADO",
                    className: "text-center",
                    render: function (value) {
                        return value.estado == 1 ? "ACTIVO" : "INACTIVO";
                    },
                },
                {
                    data: null,
                    title: "OPCIONES",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idGestor}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        let bloquear = `<a class="dropdown-item btnBloquear" data-id="${value.idGestor}" href="javascript:void(0)"><i class="fa fa-lock"></i> BLOQUEAR</a>`;
                        let restablecer = `<a class="dropdown-item btnRestablecer" data-id="${value.idGestor}" href="javascript:void(0)"><i class="fa fa-lock-open"></i> RESTABLECER</a>`;
                        if (value.estado == 1) {
                            restablecer = ``;
                        } else {
                            bloquear = ``;
                            editar = ``;
                        }
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${editar}
                                    ${bloquear}
                                    ${restablecer}
                                    </div>
                                </span>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".btnEditar").tooltip();
            },
            callBackSuccess: function (response) {
                ListadeGestores = response.data;
            },
        });
    };
    const fncValidarFormularioImportarGestor = () => {
        ValidarFormulario({
            contenedor: "#frmImportarGestor",
            nameVariable: "frmImportarGestor",
            rules: {
                gestorExcel: { required: true },
            },
            messages: {
                gestorExcel: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncValidarFormularioImportarGestor();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    GestorListar.init();
});

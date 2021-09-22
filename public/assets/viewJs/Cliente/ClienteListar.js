ListadeClientees = [];
let ClienteListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnModalImportarCliente", function () {
            LimpiarFormulario({
                formulario: "frmImportarCliente",
                nameVariable: "frmImportarCliente",
            });
            $(`.custom-file-label`).text("Elegir archivo...");
            $("#ModalImportarCliente").modal({
                keyboard: false,
                backdrop: "static",
            });
        });
        $(document).on("change", "#clienteExcel", function (e) {
            const files = e.target.files;
            if (files[0] === undefined) {
                $(`.custom-file-label`).text("Elegir archivo...");
            } else {
                $(`.custom-file-label`).text(files[0].name);
            }
        });
        $(document).on("click", "#btnImportarCliente", function () {
            $("#frmImportarCliente").submit();
            if (_objetoForm_frmImportarCliente.valid()) {
                let dataForm = new FormData($("#frmImportarCliente")[0]);
                dataForm.append("idCeo", parseInt($("#CbidCeo").val()));
                EnviarDataPost({
                    url: "ClienteImportarDataJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        $("#ModalImportarCliente").modal("hide");
                        fncListarClientes({
                            data: $("#frmNuevo").serializeFormJSON(),
                        });
                    },
                });
            }
        });
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarCliente/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idCliente = $(this).data("id");
            RedirigirUrl("EditarCliente/" + idCliente);
        });
        $(document).on("click", ".btnBuscar", function () {
            fncListarClientes({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnBloquear", function () {
            let idCliente = $(this).data("id");
            let objCliente = ListadeClientees.find(
                (ele) => ele.idCliente == idCliente
            );
            Swal.fire({
                title: `ESTA SEGURO DE BLOQUEAR ESTE CLIENTE : ${objCliente.nombreRazonSocial}?`,
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
                        url: "ClienteBloquearJson",
                        data: {
                            idCliente: idCliente,
                        },
                        callBackSuccess: function () {
                            fncListarClientes();
                        },
                    });
                }
            });
        });
        $(document).on("click", ".btnRestablecer", function () {
            let idCliente = $(this).data("id");
            let objCliente = ListadeClientees.find(
                (ele) => ele.idCliente == idCliente
            );
            Swal.fire({
                title: `ESTA SEGURO DE RESTABLECER ESTE CLIENTE : ${objCliente.nombreRazonSocial}?`,
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
                        url: "ClienteRestablecerJson",
                        data: {
                            idCliente: idCliente,
                        },
                        callBackSuccess: function () {
                            fncListarClientes();
                        },
                    });
                }
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarClientes({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
    };
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "CentroOperativoListarActivosJson",
            idSelect: "#CbidCeo",
            dataId: "idCeo",
            dataValor: "nombreCeo",
            withoutplaceholder: true,
            callBackSuccess: function (response) {
                fncListarClientes({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarClientes = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ClienteListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nroSecuencia", title: "NRO" },
                { data: "nombreRazonSocial", title: "RAZÓN SOCIAL" },
                { data: "direccion", title: "DIRECCIÓN" },
                { data: "nroDocumento", title: "NRO DE DOCUMENTO" },
                { data: "ruta", title: "RUTA" },
                { data: "modulo", title: "MODULO" },
                { data: "estado", title: "ESTADO" },
                {
                    data: null,
                    title: "OPCIONES",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idCliente}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        let bloquear = `<a class="dropdown-item btnBloquear" data-id="${value.idCliente}" href="javascript:void(0)"><i class="fa fa-lock"></i> BLOQUEAR</a>`;
                        let restablecer = `<a class="dropdown-item btnRestablecer" data-id="${value.idCliente}" href="javascript:void(0)"><i class="fa fa-lock-open"></i> RESTABLECER</a>`;
                        if (value.estado == "ACTIVO") {
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
                ListadeClientees = response.data;
            },
        });
    };
    const fncValidarFormularioImportarClientes = () => {
        ValidarFormulario({
            contenedor: "#frmImportarCliente",
            nameVariable: "frmImportarCliente",
            rules: {
                clienteExcel: { required: true },
            },
            messages: {
                clienteExcel: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncValidarFormularioImportarClientes();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ClienteListar.init();
});

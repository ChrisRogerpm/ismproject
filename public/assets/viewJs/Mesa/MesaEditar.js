ListaRutasTabla = [];
ListaRutasRegistrados = [];
ListaRutas = [];

ListaSupervisorsTabla = [];
ListaSupervisorsRegistrados = [];
ListaSupervisors = [];

let MesaEditar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Mesa`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                dataForm = Object.assign(dataForm, { idMesa: Mesa.idMesa });
                console.log(dataForm);
                // EnviarDataPost({
                //     url: "MesaEditarJson",
                //     data: dataForm,
                //     callBackSuccess: function () {
                //         setTimeout(function () {
                //             RefrescarVentana();
                //         }, 1100);
                //     },
                // });
            }
        });
        //#region MODAL RUTAS
        $(document).on("click", "#btnModalRutas", function () {
            ListaRutasTabla = [];
            fncListarRutas();
        });
        $(document).on("click", "#btnAgregarRuta", function () {
            if (ListaRutasTabla.length > 0) {
                EnviarDataPost({
                    url: "MesaRutaRegistrarJson",
                    data: {
                        idMesa: Mesa.idMesa,
                        ListaRutasTabla: ListaRutasTabla,
                    },
                    callBackSuccess: function () {
                        fncListaRutasRegistrados({
                            callBackSuccess: function () {
                                $("#ModalRuta").modal("hide");
                            },
                        });
                    },
                });
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO RUTAS",
                });
            }
        });
        $(document).on("ifChecked", "#tableRutas input:checkbox", function () {
            let idRuta = $(this).val();
            let objRuta = ListaRutas.find((ele) => ele.idRuta == idRuta);
            ListaRutasTabla.push(objRuta);
        });
        $(document).on(
            "ifUnchecked",
            "#tableRutas input:checkbox",
            function () {
                let idRuta = $(this).val();
                ListaRutasTabla = ListaRutasTabla.filter(
                    (item) => item.idRuta != parseInt(idRuta)
                );
            }
        );
        $(document).on("click", ".btnRemoverRuta", function () {
            let idMesaRuta = $(this).data("id");
            let descripcion = $(this).data("descripcion");
            Swal.fire({
                title: `ESTA SEGURO DE ELIMINAR LA RUTA : ${descripcion}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, ELIMINAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "MesaRutaEliminarJson",
                        data: {
                            idMesaRuta: idMesaRuta,
                        },
                        callBackSuccess: function () {
                            fncListaRutasRegistrados();
                        },
                    });
                }
            });
        });
        //#endregion
        //#region MODAL SUPERVISOR
        $(document).on("click", "#btnModalSupervisors", function () {
            ListaSupervisorsTabla = [];
            fncListarSupervisors();
        });
        $(document).on("click", "#btnAgregarSupervisor", function () {
            if (ListaSupervisorsTabla.length > 0) {
                let SupervisorsFiltrados = ListaSupervisorsTabla.filter(
                    (ele) => {
                        return !ListaSupervisorsRegistrados.find((arg) => {
                            return arg.idSupervisor === ele.idSupervisor;
                        });
                    }
                );
                ListaSupervisorsRegistrados =
                    ListaSupervisorsRegistrados.concat(SupervisorsFiltrados);
                EnviarDataPost({
                    url: "MesaSupervisorRegistrarJson",
                    data: {
                        ListaSupervisorsRegistrados:
                            ListaSupervisorsRegistrados,
                        idMesa: Mesa.idMesa,
                    },
                    callBackSuccess: function () {
                        fncListaSupervisorsRegistrados({
                            callBackSuccess: function () {
                                $("#ModalSupervisor").modal("hide");
                            },
                        });
                    },
                });
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO SupervisorS",
                });
            }
        });
        $(document).on(
            "ifChecked",
            "#tableSupervisores input:checkbox",
            function () {
                let idSupervisor = $(this).val();
                let objSupervisor = ListaSupervisors.find(
                    (ele) => ele.idSupervisor == idSupervisor
                );
                ListaSupervisorsTabla.push(objSupervisor);
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tableSupervisores input:checkbox",
            function () {
                let idSupervisor = $(this).val();
                ListaSupervisorsTabla = ListaSupervisorsTabla.filter(
                    (item) => item.idSupervisor != parseInt(idSupervisor)
                );
            }
        );
        $(document).on("click", ".btnRemoverSupervisor", function () {
            let idMesaSupervisor = $(this).data("id");
            let nombre = $(this).data("nombre");
            Swal.fire({
                title: `ESTA SEGURO DE ELIMINAR EL SUPERVISOR : ${nombre}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, ELIMINAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "MesaSupervisorEliminarJson",
                        data: {
                            idMesaSupervisor: idMesaSupervisor,
                        },
                        callBackSuccess: function () {
                            fncListaSupervisorsRegistrados();
                        },
                    });
                }
            });
        });
        //#endregion
    };
    const fncInicializarData = () => {
        $("#CentroOperativo").val(Mesa.centro_operativo.nombreCeo);
        $("#nombre").val(Mesa.nombre);
    };
    const fncListarRutas = () => {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "RutaListarJson",
            table: "#tableRutas",
            ajaxDataSend: {
                idCeo: Mesa.idCeo,
            },
            tableColumns: [
                { data: "descripcion", title: "RUTA" },
                {
                    data: null,
                    title: "OPCION",
                    width: "20%",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input type="checkbox" value="${value.idRuta}" data-checkbox="icheckbox_square-blue">
                        </div>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".icheck-inline").iCheck({
                    checkboxClass: "icheckbox_square-blue",
                    radioClass: "iradio_square-red",
                    increaseArea: "25%",
                });
            },
            callBackSuccess: function (response) {
                ListaRutas = response.data;
                $("#ModalRuta").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            },
        });
    };
    const fncListaRutasRegistrados = (obj) => {
        let objeto = {
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        CargarDataGET({
            url: "MesaRutaListarJson",
            dataForm: {
                idMesa: Mesa.idMesa,
            },
            callBackSuccess: function (response) {
                let contenedor = $("#tablaRutaRegistrado tbody");
                contenedor.html("");
                if (response.length > 0) {
                    response.map((ele) => {
                        contenedor.append(`<tr>
                        <td>${ele.descripcion}</td>
                        <td class="text-center"><button type="button" class="btn btn-danger btn-icon btn-sm btnRemoverRuta" data-id="${ele.idMesaRuta}" data-descripcion="${ele.descripcion}"><i class="fa fa-window-close"></i></button></td>
                        </tr>`);
                    });
                } else {
                    contenedor.append(
                        `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO RUTAS</td></tr>`
                    );
                }
                options.callBackSuccess();
            },
        });
    };
    const fncListarSupervisors = () => {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "SupervisorListarJson",
            table: "#tableSupervisores",
            ajaxDataSend: {
                idCeo: Mesa.idCeo,
            },
            tableColumns: [
                { data: "nombre", title: "SUPERVISOR" },
                {
                    data: null,
                    title: "OPCION",
                    width: "20%",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input type="checkbox" value="${value.idSupervisor}" data-checkbox="icheckbox_square-blue">
                        </div>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".icheck-inline").iCheck({
                    checkboxClass: "icheckbox_square-blue",
                    radioClass: "iradio_square-red",
                    increaseArea: "25%",
                });
            },
            callBackSuccess: function (response) {
                ListaSupervisors = response.data;
                $("#ModalSupervisor").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            },
        });
    };
    const fncListaSupervisorsRegistrados = (obj) => {
        let objeto = {
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        CargarDataGET({
            url: "MesaSupervisorListarJson",
            dataForm: {
                idMesa: Mesa.idMesa,
            },
            callBackSuccess: function (response) {
                let contenedor = $("#tablaSupervisorRegistrado tbody");
                contenedor.html("");
                if (response.length > 0) {
                    response.map((ele) => {
                        contenedor.append(`<tr>
                        <td>${ele.nombre}</td>
                        <td class="text-center"><button type="button" class="btn btn-danger btn-icon btn-sm btnRemoverSupervisor" data-id="${ele.idMesaSupervisor}" data-nombre="${ele.nombre}"><i class="fa fa-window-close"></i></button></td>
                        </tr>`);
                    });
                } else {
                    contenedor.append(
                        `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO SUPERVISOR</td></tr>`
                    );
                }
                options.callBackSuccess();
            },
        });
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
            fncInicializarData();
            fncAcciones();
            fncValidarFormularioEditar();
            fncListaRutasRegistrados();
            fncListaSupervisorsRegistrados();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    MesaEditar.init();
});

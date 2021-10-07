ListaRutasTabla = [];
ListaRutasRegistrados = [];
ListaRutas = [];

ListaSupervisorsTabla = [];
ListaSupervisorsRegistrados = [];
ListaSupervisors = [];

let MesaRegistrar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function (e) {
            RedirigirUrl(`Mesa`);
        });
        $(document).on("click", ".btnGuardar", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                if (ListaRutasRegistrados.length == 0) {
                    ShowAlert({
                        type: "warning",
                        message: "NO SE HA ENCONTRADO RUTAS REGISTRADAS",
                    });
                    return false;
                }
                if (ListaSupervisorsRegistrados.length == 0) {
                    ShowAlert({
                        type: "warning",
                        message: "NO SE HA ENCONTRADO SUPERVISORES REGISTRADOS",
                    });
                    return false;
                }
                dataForm = Object.assign(dataForm, {
                    ListaRutasRegistrados: ListaRutasRegistrados,
                    ListaSupervisorsRegistrados: ListaSupervisorsRegistrados,
                    idCeo: idCeo,
                });
                EnviarDataPost({
                    url: "MesaRegistrarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`Mesa`);
                        }, 1100);
                    },
                });
            }
        });
        //#region MODAL RUTAS
        $(document).on("click", "#btnModalRutas", function () {
            ListaRutasTabla = [];
            fncListarRutas();
        });
        $(document).on("click", "#btnAgregarRuta", function () {
            if (ListaRutasTabla.length > 0) {
                let RutasFiltrados = ListaRutasTabla.filter((ele) => {
                    return !ListaRutasRegistrados.find((arg) => {
                        return arg.idRuta === ele.idRuta;
                    });
                });
                ListaRutasRegistrados =
                    ListaRutasRegistrados.concat(RutasFiltrados);
                fncfncListaRutasRegistrados({
                    lista: ListaRutasRegistrados,
                    callBackSuccess: function () {
                        $("#ModalRuta").modal("hide");
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
            let idRuta = $(this).data("id");
            ListaRutasRegistrados = ListaRutasRegistrados.filter(
                (value) => value.idRuta != idRuta
            );
            $(this).closest("tr").remove();
            if (ListaRutasRegistrados.length == 0) {
                $("#tablaRutaRegistrado tbody").append(
                    `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO RUTAS</td></tr>`
                );
            }
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
                fncListaSupervisorsRegistrados({
                    lista: ListaSupervisorsRegistrados,
                    callBackSuccess: function () {
                        $("#ModalSupervisor").modal("hide");
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
            let idSupervisor = $(this).data("id");
            ListaSupervisorsRegistrados = ListaSupervisorsRegistrados.filter(
                (value) => value.idSupervisor != idSupervisor
            );
            $(this).closest("tr").remove();
            if (ListaSupervisorsRegistrados.length == 0) {
                $("#tablaSupervisorRegistrado tbody").append(
                    `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO SUPERVISOR</td></tr>`
                );
            }
        });
        //#endregion
    };
    //#region LISTA RUTA
    const fncListarRutas = () => {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "RutaListarJson",
            table: "#tableRutas",
            ajaxDataSend: {
                idCeo: idCeo,
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
    const fncfncListaRutasRegistrados = (obj) => {
        let objeto = {
            lista: [],
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);

        let contenedor = $("#tablaRutaRegistrado tbody");
        contenedor.html("");
        if (options.lista.length > 0) {
            options.lista.map((ele) => {
                contenedor.append(`<tr>
                <td>${ele.descripcion}</td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-icon btn-sm btnRemoverRuta" data-id="${ele.idRuta}"><i class="fa fa-window-close"></i></button></td>
                </tr>`);
            });
        } else {
            contenedor.append(
                `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO RUTAS</td></tr>`
            );
        }
        options.callBackSuccess();
    };
    //#endregion
    //#region  LISTA SUPERVISOR
    const fncListarSupervisors = () => {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "SupervisorListarJson",
            table: "#tableSupervisores",
            ajaxDataSend: {
                idCeo: idCeo,
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
            lista: [],
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);

        let contenedor = $("#tablaSupervisorRegistrado tbody");
        contenedor.html("");
        if (options.lista.length > 0) {
            options.lista.map((ele) => {
                contenedor.append(`<tr>
                <td>${ele.nombre}</td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-icon btn-sm btnRemoverSupervisor" data-id="${ele.idSupervisor}"><i class="fa fa-window-close"></i></button></td>
                </tr>`);
            });
        } else {
            contenedor.append(
                `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO SUPERVISOR</td></tr>`
            );
        }
        options.callBackSuccess();
    };
    //#endregion
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
    MesaRegistrar.init();
});

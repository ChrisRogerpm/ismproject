ListaProductosTabla = [];
ListaProductosRegistrados = [];
ListaProductos = [];

ListaRutasTabla = [];
ListaRutasRegistrados = [];
ListaRutas = [];

ListaSupervisorsTabla = [];
ListaSupervisorsRegistrados = [];
ListaSupervisors = [];

let ProductoRegistrar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Gestor`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                if (ListaProductosRegistrados.length == 0) {
                    ShowAlert({
                        type: "warning",
                        message: "NO SE HA ENCONTRADO PRODUCTOS REGISTRADOS",
                    });
                    return false;
                }
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
                        message:
                            "NO SE HA ENCONTRADO SUPERVISOR(ES) REGISTRADO(S)",
                    });
                    return false;
                }
                dataForm = Object.assign(dataForm, {
                    ListaProductosRegistrados: ListaProductosRegistrados,
                    ListaRutasRegistrados: ListaRutasRegistrados,
                    ListaSupervisorsRegistrados: ListaSupervisorsRegistrados,
                    idCeo: idCeo,
                });
                EnviarDataPost({
                    url: "GestorRegistrarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`Gestor`);
                        }, 1100);
                    },
                });
            }
        });
        $(document).on("change", "#CbidMesa", function () {
            $("#tablaRutaRegistrado tbody")
                .html("")
                .append(
                    '<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO RUTAS</td></tr>'
                );
            $("#tablaSupervisorRegistrado tbody")
                .html("")
                .append(
                    '<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO SUPERVISORES</td></tr>'
                );
            ListaRutasRegistrados = [];
            ListaSupervisorsRegistrados = [];
        });
        //#region MODAL PRODUCTO
        $(document).on("click", "#btnModalProducto", function () {
            ListaProductosTabla = [];
            fncListarProductos();
        });
        $(document).on("click", "#btnAgregarProducto", function () {
            if (ListaProductosTabla.length > 0) {
                let ProductosFiltrados = ListaProductosTabla.filter((ele) => {
                    return !ListaProductosRegistrados.find((arg) => {
                        return arg.idProducto === ele.idProducto;
                    });
                });
                ListaProductosRegistrados =
                    ListaProductosRegistrados.concat(ProductosFiltrados);
                fncListaProductosRegistrados({
                    lista: ListaProductosRegistrados,
                    callBackSuccess: function () {
                        $("#ModalProducto").modal("hide");
                    },
                });
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO PRODUCTOS",
                });
            }
        });
        $(document).on(
            "ifChecked",
            "#tableProductos input:checkbox",
            function () {
                let idProducto = $(this).val();
                let objProducto = ListaProductos.find(
                    (ele) => ele.idProducto == idProducto
                );
                ListaProductosTabla.push(objProducto);
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tableProductos input:checkbox",
            function () {
                let idProducto = $(this).val();
                ListaProductosTabla = ListaProductosTabla.filter(
                    (item) => item.idProducto != parseInt(idProducto)
                );
            }
        );
        $(document).on("click", ".btnRemoverProducto", function () {
            let idProducto = $(this).data("id");
            ListaProductosRegistrados = ListaProductosRegistrados.filter(
                (value) => value.idProducto != parseInt(idProducto)
            );
            $(this).closest("tr").remove();
            if (ListaProductosRegistrados.length == 0) {
                $("#tablaProductoRegistrado tbody").append(
                    `<tr><td colspan="5" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`
                );
            }
        });

        //#endregion
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
                fncListaRutasRegistrados({
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
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "MesaListarJson",
            idSelect: "#CbidMesa",
            dataId: "idMesa",
            dataValor: "nombre",
            withoutplaceholder: true,
            dataForm: {
                idCeo: idCeo,
            },
        });
    };
    const fncListaProductosRegistrados = (obj) => {
        let objeto = {
            lista: [],
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);

        let contenedor = $("#tablaProductoRegistrado tbody");
        contenedor.html("");
        if (options.lista.length > 0) {
            options.lista.map((ele) => {
                contenedor.append(`<tr>
                <td class="text-center">${ele.sku}</td>
                <td>${ele.nombreProducto}</td>
                <td>${ele.marca}</td>
                <td>${ele.formato}</td>
                <td>${ele.sabor}</td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-icon btn-sm btnRemoverProducto" data-id="${ele.idProducto}"><i class="fa fa-window-close"></i></button></td>
                </tr>`);
            });
        } else {
            contenedor.append(
                `<tr><td colspan="6" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`
            );
        }
        options.callBackSuccess();
    };
    const fncListarProductos = function () {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ProductoListarJson",
            table: "#tableProductos",
            ajaxDataSend: {
                idCeo: idCeo,
            },
            tableColumns: [
                { data: "sku", title: "SKU" },
                { data: "nombreProducto", title: "PRODUCTO" },
                { data: "caja", title: "CAJA", className: "text-center" },
                { data: "paquete", title: "PAQUETE", className: "text-center" },
                {
                    data: "cajaxpaquete",
                    title: "CAJAXPAQUETE",
                    className: "text-center",
                },
                {
                    data: "codigoPadre",
                    title: "CODIGO PADRE",
                    className: "text-center",
                },
                {
                    data: "codigoHijo",
                    title: "CODIGO HIJO",
                    className: "text-center",
                },
                {
                    data: "nombreLinea",
                    title: "LINEA",
                    className: "text-center",
                },
                {
                    data: null,
                    title: "OPCION",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input type="checkbox" id="CheckTicket" value="${value.idProducto}" data-checkbox="icheckbox_square-blue">
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
                ListaProductos = response.data;
                $("#ModalProducto").modal({
                    backdrop: "static",
                    keyboard: false,
                });
            },
        });
    };
    const fncListarRutas = () => {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "MesaRutaListarJson",
            table: "#tableRutas",
            ajaxDataSend: {
                idMesa: $("#CbidMesa").val(),
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
    const fncListarSupervisors = () => {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "MesaSupervisorListarJson",
            table: "#tableSupervisores",
            ajaxDataSend: {
                idMesa: $("#CbidMesa").val(),
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
    const fncValidarFormularioRegistrar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                codigoGestor: { required: true },
                nombre: { required: true },
                nroDocumento: { required: true },
                telefono: { required: true },
            },
            messages: {
                codigoGestor: { required: "El campo es requerido" },
                nombre: { required: "El campo es requerido" },
                nroDocumento: { required: "El campo es requerido" },
                telefono: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncValidarFormularioRegistrar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ProductoRegistrar.init();
});

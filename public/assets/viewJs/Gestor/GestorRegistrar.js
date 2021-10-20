ListaProductosTabla = [];
ListaProductosRegistrados = [];
ListaProductos = [];

ListaRutasTabla = [];
ListaRutasRegistrados = [];
ListaRutas = [];

ListaSupervisorsTabla = [];
ListaSupervisorsRegistrados = [];
ListaSupervisors = [];

ListaProductosEliminar = [];
ListaRutasEliminar = [];
ListaSupervisorsEliminar = [];

let ProductoRegistrar = (function () {
    const fncAcciones = () => {
        //#region GENERAL
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
            $("#txtTituloRutas").text(`RUTAS`);
            $("#txtTituloSupervisores").text(`SUPERVISOR`);
        });
        $(document).on("input", "#inputBuscadorProducto", function () {
            let buscando = $(this).val().toLowerCase();
            let filtroLista = [];
            if (buscando == "") {
                filtroLista = ListaProductosRegistrados;
            } else {
                filtroLista = ListaProductosRegistrados.filter((ele) => {
                    return (
                        ele.marca.toLowerCase().includes(buscando) ||
                        ele.nombreProducto.toLowerCase().includes(buscando) ||
                        ele.codigoPadre.toLowerCase().includes(buscando) ||
                        ele.sku.toLowerCase().includes(buscando)
                    );
                });
            }
            fncListaProductosRegistrados({
                lista: filtroLista,
            });
        });
        $(document).on("input", "#inputBuscadorRuta", function () {
            let buscando = $(this).val().toLowerCase();
            let filtroLista = [];
            if (buscando == "") {
                filtroLista = ListaRutasRegistrados;
            } else {
                filtroLista = ListaRutasRegistrados.filter((ele) => {
                    return ele.descripcion.toLowerCase().includes(buscando);
                });
            }
            fncListaRutasRegistrados({
                lista: filtroLista,
            });
        });
        $(document).on("input", "#inputBuscadorSupervisor", function () {
            let buscando = $(this).val().toLowerCase();
            let filtroLista = [];
            if (buscando == "") {
                filtroLista = ListaSupervisorsRegistrados;
            } else {
                filtroLista = ListaSupervisorsRegistrados.filter((ele) => {
                    return ele.nombre.toLowerCase().includes(buscando);
                });
            }
            fncListaSupervisorsRegistrados({
                lista: filtroLista,
            });
        });
        //#endregion
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
                let NuevaListaProductosRegistrados =
                    ListaProductosRegistrados.concat(ProductosFiltrados);
                NuevaListaProductosRegistrados =
                    NuevaListaProductosRegistrados.map((ele) => ({
                        estadoEliminar: 0,
                        ...ele,
                    }));
                fncListaProductosRegistrados({
                    lista: NuevaListaProductosRegistrados,
                    callBackSuccess: function () {
                        $("#ModalProducto").modal("hide");
                        ListaProductosRegistrados =
                            NuevaListaProductosRegistrados;
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
        $(document).on("click", "#btnEliminarProductos", function () {
            if (ListaProductosEliminar.length > 0) {
                ListaProductosRegistrados = ListaProductosRegistrados.filter(
                    (ele) => {
                        return !ListaProductosEliminar.find((arg) => {
                            return arg === ele.idProducto;
                        });
                    }
                );
                ListaProductosEliminar.map((ele) => {
                    $(`.Fila${ele}`).remove();
                });
                ListaProductosEliminar = [];
                if (ListaProductosRegistrados.length > 0) {
                    $("#txtTituloProductos").text(
                        `PRODUCTOS : SELECCIONADO(S) ${ListaProductosRegistrados.length}`
                    );
                } else {
                    $("#txtTituloProductos").text(`PRODUCTOS`);
                    $("#tablaProductoRegistrado tbody").append(
                        `<tr><td colspan="11" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`
                    );
                }
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO PRODUCTO(S) A ELIMINAR",
                });
                return false;
            }
        });
        $(document).on(
            "ifChecked",
            "#tablaProductoRegistrado input:checkbox",
            function () {
                let idProducto = $(this).val();
                ListaProductosEliminar.push(parseInt(idProducto));
                let obj = ListaProductosRegistrados.find((ele) => {
                    return parseInt(ele.idProducto) == parseInt(idProducto);
                });
                obj.estadoEliminar = 1;
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tablaProductoRegistrado input:checkbox",
            function () {
                let idProducto = $(this).val();
                ListaProductosEliminar = ListaProductosEliminar.filter(
                    (item) => item != parseInt(idProducto)
                );
                let obj = ListaProductosRegistrados.find((ele) => {
                    return parseInt(ele.idProducto) == parseInt(idProducto);
                });
                obj.estadoEliminar = 0;
            }
        );
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
                let NuevaListaRutasRegistrados =
                    ListaRutasRegistrados.concat(RutasFiltrados);
                NuevaListaRutasRegistrados = NuevaListaRutasRegistrados.map(
                    (ele) => ({
                        estadoEliminar: 0,
                        ...ele,
                    })
                );
                fncListaRutasRegistrados({
                    lista: NuevaListaRutasRegistrados,
                    callBackSuccess: function () {
                        $("#ModalRuta").modal("hide");
                        ListaRutasRegistrados = NuevaListaRutasRegistrados;
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
        $(document).on("click", "#btnEliminarRutas", function () {
            if (ListaRutasEliminar.length > 0) {
                ListaRutasRegistrados = ListaRutasRegistrados.filter((ele) => {
                    return !ListaRutasEliminar.find((arg) => {
                        return arg === ele.idRuta;
                    });
                });
                ListaRutasEliminar.map((ele) => {
                    $(`.FilaRuta${ele}`).remove();
                });
                ListaRutasEliminar = [];
                if (ListaRutasRegistrados.length > 0) {
                    $("#txtTituloRutas").text(
                        `RUTAS : SELECCIONADO(S) ${ListaRutasRegistrados.length}`
                    );
                } else {
                    $("#txtTituloRutas").text(`RUTAS`);
                    $("#tablaRutaRegistrado tbody").append(
                        `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO RUTAS</td></tr>`
                    );
                }
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO RUTA(S) A ELIMINAR",
                });
                return false;
            }
        });
        $(document).on(
            "ifChecked",
            "#tablaRutaRegistrado input:checkbox",
            function () {
                let idRuta = $(this).val();
                ListaRutasEliminar.push(parseInt(idRuta));
                let obj = ListaRutasRegistrados.find((ele) => {
                    return parseInt(ele.idRuta) == parseInt(idRuta);
                });
                obj.estadoEliminar = 1;
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tablaRutaRegistrado input:checkbox",
            function () {
                let idRuta = $(this).val();
                ListaRutasEliminar = ListaRutasEliminar.filter(
                    (item) => item != parseInt(idRuta)
                );
                let obj = ListaRutasRegistrados.find((ele) => {
                    return parseInt(ele.idRuta) == parseInt(idRuta);
                });
                obj.estadoEliminar = 1;
            }
        );
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
                let NuevaListaSupervisorsRegistrados =
                    ListaSupervisorsRegistrados.concat(SupervisorsFiltrados);
                NuevaListaSupervisorsRegistrados =
                    NuevaListaSupervisorsRegistrados.map((ele) => ({
                        estadoEliminar: 0,
                        ...ele,
                    }));
                fncListaSupervisorsRegistrados({
                    lista: NuevaListaSupervisorsRegistrados,
                    callBackSuccess: function () {
                        $("#ModalSupervisor").modal("hide");
                        ListaSupervisorsRegistrados =
                            NuevaListaSupervisorsRegistrados;
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
        $(document).on("click", "#btnEliminarSupervisors", function () {
            if (ListaSupervisorsEliminar.length > 0) {
                ListaSupervisorsRegistrados =
                    ListaSupervisorsRegistrados.filter((ele) => {
                        return !ListaSupervisorsEliminar.find((arg) => {
                            return arg === ele.idSupervisor;
                        });
                    });
                ListaSupervisorsEliminar.map((ele) => {
                    $(`.FilaSupervisor${ele}`).remove();
                });
                ListaSupervisorsEliminar = [];
                if (ListaSupervisorsRegistrados.length > 0) {
                    $("#txtTituloSupervisores").text(
                        `SUPERVISOR : SELECCIONADO(S) ${ListaSupervisorsRegistrados.length}`
                    );
                } else {
                    $("#txtTituloSupervisores").text(`SUPERVISOR`);
                    $("#tablaSupervisorRegistrado tbody").append(
                        `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO SUPERVISORES</td></tr>`
                    );
                }
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO Supervisor(S) A ELIMINAR",
                });
                return false;
            }
        });
        $(document).on(
            "ifChecked",
            "#tablaSupervisorRegistrado input:checkbox",
            function () {
                let idSupervisor = $(this).val();
                ListaSupervisorsEliminar.push(parseInt(idSupervisor));
                let obj = ListaSupervisorsRegistrados.find((ele) => {
                    return parseInt(ele.idSupervisor) == parseInt(idSupervisor);
                });
                obj.estadoEliminar = 1;
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tablaSupervisorRegistrado input:checkbox",
            function () {
                let idSupervisor = $(this).val();
                ListaSupervisorsEliminar = ListaSupervisorsEliminar.filter(
                    (item) => item != parseInt(idSupervisor)
                );
                let obj = ListaSupervisorsRegistrados.find((ele) => {
                    return parseInt(ele.idSupervisor) == parseInt(idSupervisor);
                });
                obj.estadoEliminar = 0;
            }
        );
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
                contenedor.append(`<tr class="Fila${ele.idProducto}">
                <td class="text-center">${ele.nombreLinea}</td>
                <td class="text-center">${ele.sku}</td>
                <td>${ele.nombreProducto}</td>
                <td>${ele.marca}</td>
                <td>${ele.formato}</td>
                <td>${ele.sabor}</td>
                <td class="text-center">
                    <div class="icheck-inline-producto text-center">
                        <input
                        type="checkbox"
                        value="${ele.idProducto}"
                        ${ele.estadoEliminar == 0 ? "" : "checked"}
                        data-checkbox="icheckbox_square-blue">
                    </div>
                </td>
                </tr>`);
            });
            $(".icheck-inline-producto").iCheck({
                checkboxClass: "icheckbox_square-blue",
                radioClass: "iradio_square-red",
                increaseArea: "25%",
            });
            $("#txtTituloProductos").text(
                `PRODUCTOS : SELECCIONADO(S) ${options.lista.length}`
            );
        } else {
            $("#txtTituloProductos").text(`PRODUCTOS`);
            contenedor.append(
                `<tr><td colspan="7" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`
            );
        }
        options.callBackSuccess();
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
                contenedor.append(`
                <tr class="FilaRuta${ele.idRuta}">
                    <td>${ele.descripcion}</td>
                    <td class="text-center">
                        <div class="icheck-inline-ruta text-center">
                            <input
                            type="checkbox"
                            value="${ele.idRuta}"
                            ${ele.estadoEliminar == 0 ? "" : "checked"}
                            data-checkbox="icheckbox_square-blue"
                            >
                        </div>
                    </td>
                </tr>`);
            });
            $(".icheck-inline-ruta").iCheck({
                checkboxClass: "icheckbox_square-blue",
                radioClass: "iradio_square-red",
                increaseArea: "25%",
            });
            $("#txtTituloRutas").text(
                `RUTAS : SELECCIONADO(S) ${options.lista.length}`
            );
        } else {
            $("#txtTituloRutas").text(`RUTAS`);
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
                contenedor.append(`
                <tr class="FilaSupervisor${ele.idSupervisor}">
                    <td>${ele.nombre}</td>
                    <td class="text-center">
                        <div class="icheck-inline-supervisor text-center">
                            <input
                            type="checkbox"
                            value="${ele.idSupervisor}"
                            ${ele.estadoEliminar == 0 ? "" : "checked"}
                            ata-checkbox="icheckbox_square-blue"
                            >
                        </div>
                    </td>
                </tr>`);
            });
            $(".icheck-inline-supervisor").iCheck({
                checkboxClass: "icheckbox_square-blue",
                radioClass: "iradio_square-red",
                increaseArea: "25%",
            });
            $("#txtTituloSupervisores").text(
                `SUPERVISORES : SELECCIONADO(S) ${options.lista.length}`
            );
        } else {
            $("#txtTituloSupervisores").text(`SUPERVISORES`);
            contenedor.append(
                `<tr><td colspan="2" class="text-center">NO SE HA REGISTRADO SUPERVISOR</td></tr>`
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

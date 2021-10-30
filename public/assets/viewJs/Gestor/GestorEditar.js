ListaProductosTabla = [];
ListaProductos = [];

ListaRutasTabla = [];
ListaRutas = [];

ListaSupervisorsTabla = [];
ListaSupervisors = [];

ListaProductosEliminar = [];
ListaRutasEliminar = [];
ListaSupervisorsEliminar = [];

ListaProductosRegistrados = [];
ListaRutasRegistrados = [];
ListaSupervisorsRegistrados = [];

let ProductoEditar = (function () {
    const fncAcciones = () => {
        //#region GENERAL
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Gestor`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                dataForm = Object.assign(dataForm, {
                    idCeo: idCeo,
                    idGestor: Gestor.idGestor,
                    ListaProductosRegistrados: ListaProductosRegistrados,
                    ListaRutasRegistrados: ListaRutasRegistrados,
                    ListaSupervisorsRegistrados: ListaSupervisorsRegistrados,
                });
                EnviarDataPost({
                    url: "GestorEditarJson",
                    data: dataForm,
                });
            }
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
                buscador: true,
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
                buscador: true,
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
                buscador: true,
                lista: filtroLista,
            });
        });
        //#endregion
        //#region MODAL PRODUCTO
        $(document).on("click", "#btnAgregarTodoProducto", function () {
            Swal.fire({
                title: `ESTA SEGURO DE AGREGAR TODO LOS PRODUCTOS?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, AGREGAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    let ProductosFiltrados = ListaProductos.filter((ele) => {
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
                        buscador: true,
                        callBackSuccess: function () {
                            $("#ModalProducto").modal("hide");
                            ListaProductosRegistrados =
                                NuevaListaProductosRegistrados;
                        },
                    });
                }
            });
        });
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
                    buscador: true,
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
                Swal.fire({
                    title: `ESTA SEGURO DE ELIMINAR ${ListaProductosEliminar.length} PRODUCTO(S)?`,
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
                            url: "GestorProductoEliminarJson",
                            data: {
                                ListaProductosEliminar: ListaProductosEliminar,
                                idGestor: Gestor.idGestor,
                            },
                            callBackSuccess: function () {
                                ListaProductosRegistrados =
                                    ListaProductosRegistrados.filter((ele) => {
                                        return !ListaProductosEliminar.find(
                                            (arg) => {
                                                return (
                                                    parseInt(ele.idProducto) ===
                                                    parseInt(arg)
                                                );
                                            }
                                        );
                                    });
                                ListaProductosEliminar = [];
                                fncListaProductosRegistrados({
                                    buscador: true,
                                    lista: ListaProductosRegistrados,
                                });
                            },
                        });
                    }
                });
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
                    buscador: true,
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
        $(document).on("click", "#btnEliminarRutas", function () {
            if (ListaRutasEliminar.length > 0) {
                Swal.fire({
                    title: `ESTA SEGURO DE ELIMINAR ${ListaRutasEliminar.length} RUTA(S)?`,
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
                            url: "GestorRutaEliminarJson",
                            data: {
                                ListaRutasEliminar: ListaRutasEliminar,
                                idGestor: Gestor.idGestor,
                            },
                            callBackSuccess: function () {
                                ListaRutasRegistrados =
                                    ListaRutasRegistrados.filter((ele) => {
                                        return !ListaRutasEliminar.find(
                                            (arg) => {
                                                return (
                                                    parseInt(ele.idRuta) ===
                                                    parseInt(arg)
                                                );
                                            }
                                        );
                                    });
                                ListaProductosEliminar = [];
                                fncListaRutasRegistrados({
                                    buscador: true,
                                    lista: ListaRutasRegistrados,
                                });
                            },
                        });
                    }
                });
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
                    buscador: true,
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
                Swal.fire({
                    title: `ESTA SEGURO DE ELIMINAR ${ListaSupervisorsEliminar.length} SUPERVISOR(S)?`,
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
                            url: "GestorSupervisorEliminarJson",
                            data: {
                                ListaSupervisorsEliminar:
                                    ListaSupervisorsEliminar,
                                idGestor: Gestor.idGestor,
                            },
                            callBackSuccess: function () {
                                ListaSupervisorsRegistrados =
                                    ListaSupervisorsRegistrados.filter(
                                        (ele) => {
                                            return !ListaSupervisorsEliminar.find(
                                                (arg) => {
                                                    return (
                                                        parseInt(ele.idRuta) ===
                                                        parseInt(arg)
                                                    );
                                                }
                                            );
                                        }
                                    );
                                ListaSupervisorsEliminar = [];
                                fncListaSupervisorsRegistrados({
                                    buscador: true,
                                    lista: ListaSupervisorsRegistrados,
                                });
                            },
                        });
                    }
                });
            } else {
                ShowAlert({
                    type: "warning",
                    message: "NO SE HA SELECCIONADO SUPERVISOR(S) A ELIMINAR",
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
        $("#nombre").val(Gestor.nombre);
        $("#codigoGestor").val(Gestor.codigoGestor);
        $("#telefono").val(Gestor.telefono);
        $("#nroDocumento").val(Gestor.nroDocumento);
        CargarDataSelect({
            url: "MesaListarJson",
            idSelect: "#CbidMesa",
            dataId: "idMesa",
            dataValor: "nombre",
            withoutplaceholder: true,
            dataForm: {
                idCeo: idCeo,
            },
            valorSelect: Gestor.idMesa,
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
    const fncListaProductosRegistrados = (obj) => {
        let objeto = {
            buscador: false,
            lista: [],
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        if (options.buscador == false) {
            CargarDataGET({
                url: "GestorProductoListarJson",
                dataForm: {
                    idGestor: Gestor.idGestor,
                },
                callBackSuccess: function (response) {
                    ListaProductosRegistrados = response;
                    fncVisualizarProductos({
                        lista: response,
                        callBackSuccess: function () {
                            options.callBackSuccess();
                        },
                    });
                },
            });
        } else {
            fncVisualizarProductos({
                lista: options.lista,
                callBackSuccess: function () {
                    options.callBackSuccess();
                },
            });
        }
    };
    const fncListaRutasRegistrados = (obj) => {
        let objeto = {
            buscador: false,
            lista: [],
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);

        if (options.buscador == false) {
            CargarDataGET({
                url: "GestorRutaListarJson",
                dataForm: {
                    idGestor: Gestor.idGestor,
                },
                callBackSuccess: function (response) {
                    ListaRutasRegistrados = response;
                    fncVisualizarRutas({
                        lista: response,
                        callBackSuccess: function () {
                            options.callBackSuccess();
                        },
                    });
                },
            });
        } else {
            fncVisualizarRutas({
                lista: options.lista,
                callBackSuccess: function () {
                    options.callBackSuccess();
                },
            });
        }
    };
    const fncListaSupervisorsRegistrados = (obj) => {
        let objeto = {
            buscador: false,
            lista: [],
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);

        if (options.buscador == false) {
            CargarDataGET({
                url: "GestorSupervisorListarJson",
                dataForm: {
                    idGestor: Gestor.idGestor,
                },
                callBackSuccess: function (response) {
                    ListaSupervisorsRegistrados = response;
                    fncVisualizarSupervisores({
                        lista: response,
                        callBackSuccess: function () {
                            options.callBackSuccess();
                        },
                    });
                },
            });
        } else {
            fncVisualizarSupervisores({
                lista: options.lista,
                callBackSuccess: function () {
                    options.callBackSuccess();
                },
            });
        }
    };
    const fncVisualizarProductos = (obj) => {
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
                                    data-checkbox="icheckbox_square-blue"
                                    >
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
    const fncVisualizarRutas = (obj) => {
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
                    <td class="text-center">
                        <div class="icheck-inline-ruta text-center">
                            <input
                            type="checkbox"
                            value="${ele.idRuta}"
                            ${ele.estadoEliminar == 0 ? "" : "checked"}
                            data-checkbox="icheckbox_square-blue">
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
    const fncVisualizarSupervisores = (obj) => {
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
                    <td class="text-center">
                        <div class="icheck-inline-supervisor text-center">
                            <input
                            type="checkbox"
                            value="${ele.idSupervisor}"
                            ${ele.estadoEliminar == 0 ? "" : "checked"}
                            data-checkbox="icheckbox_square-blue"
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
    const fncValidarFormularioEditar = () => {
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
            fncListaProductosRegistrados();
            fncListaRutasRegistrados();
            fncListaSupervisorsRegistrados();
            fncValidarFormularioEditar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ProductoEditar.init();
});

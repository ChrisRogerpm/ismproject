ListaProductosTabla = [];
ListaProductosRegistrados = [];
ListaProductos = [];

ListaProductosEliminar = [];

let ProductoEditar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Comision`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                let objCalculado = fncObtenerListaProductosRegistrados();
                if (objCalculado.respuesta) {
                    dataForm = Object.assign(dataForm, {
                        ListaProductosComision:
                            objCalculado.ListaProductosComision,
                        idComision: Comision.idComision,
                    });
                    EnviarDataPost({
                        url: "ComisionEditarJson",
                        data: dataForm,
                        callBackSuccess: function () {},
                    });
                } else {
                    ShowAlert({
                        type: "warning",
                        message: objCalculado.mensaje,
                    });
                }
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
                        ele.nombreProducto.toLowerCase().includes(buscando) ||
                        ele.codigoPadre.toLowerCase().includes(buscando)
                    );
                });
            }
            fncListaProductosRegistrados({
                buscador: true,
                lista: filtroLista,
            });
        });
        $(document).on("change", ".CbCondicion", function () {
            let condicion = $(this).val();
            let codigoPadre = $(this).data("codigo");
            let obj = ListaProductosRegistrados.find((ele) => {
                return ele.codigoPadre == codigoPadre;
            });
            obj.condicion = condicion;
        });
        $(document).on("input", ".cantidadValor", function () {
            let cantidadValor = $(this).val();
            let codigoPadre = $(this).data("codigo");
            let obj = ListaProductosRegistrados.find((ele) => {
                return ele.codigoPadre == codigoPadre;
            });
            obj.cantidadValor = cantidadValor;
        });
        $(document).on("input", ".comisionPtoVenta", function () {
            let comisionPtoVenta = $(this).val();
            let codigoPadre = $(this).data("codigo");
            let obj = ListaProductosRegistrados.find((ele) => {
                return ele.codigoPadre == codigoPadre;
            });
            obj.comisionPtoVenta = comisionPtoVenta;
        });
        $(document).on("input", ".comisionDistribuidor", function () {
            let comisionDistribuidor = $(this).val();
            let codigoPadre = $(this).data("codigo");
            let obj = ListaProductosRegistrados.find((ele) => {
                return ele.codigoPadre == codigoPadre;
            });
            obj.comisionDistribuidor = comisionDistribuidor;
        });
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
                            url: "ComisionDetalleEliminarJson",
                            data: {
                                ListaProductosEliminar: ListaProductosEliminar,
                                idComision: Comision.idComision,
                            },
                            callBackSuccess: function () {
                                ListaProductosRegistrados =
                                    ListaProductosRegistrados.filter((ele) => {
                                        return !ListaProductosEliminar.find(
                                            (arg) => {
                                                return ele.codigoPadre == arg;
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
                let codigoPadre = $(this).val();
                ListaProductosEliminar.push(codigoPadre);
                let obj = ListaProductosRegistrados.find((ele) => {
                    return ele.codigoPadre == codigoPadre;
                });
                obj.estadoEliminar = 1;
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tablaProductoRegistrado input:checkbox",
            function () {
                let codigoPadre = $(this).val();
                ListaProductosEliminar = ListaProductosEliminar.filter(
                    (item) => item != codigoPadre
                );
                let obj = ListaProductosRegistrados.find((ele) => {
                    return ele.codigoPadre == codigoPadre;
                });
                obj.estadoEliminar = 0;
            }
        );
        //#region MODAL PRODUCTO
        $(document).on("click", "#btnModalProducto", function () {
            ListaProductosTabla = [];
            fncListarProductos();
        });
        $(document).on("click", "#btnAgregarProducto", function () {
            if (ListaProductosTabla.length > 0) {
                let ProductosFiltrados = ListaProductosTabla.filter((ele) => {
                    return !ListaProductosRegistrados.find((arg) => {
                        return arg.codigoPadre == ele.codigoPadre;
                    });
                });

                let NuevaListaProductosRegistrados =
                    ListaProductosRegistrados.concat(ProductosFiltrados);
                NuevaListaProductosRegistrados =
                    NuevaListaProductosRegistrados.map((ele) => ({
                        condicion: null,
                        cantidadValor: 0,
                        comisionPtoVenta: 0,
                        comisionDistribuidor: 0,
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
                let codigoPadre = $(this).val();
                let objProducto = ListaProductos.find(
                    (ele) => ele.codigoPadre == codigoPadre
                );
                ListaProductosTabla.push(objProducto);
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tableProductos input:checkbox",
            function () {
                let codigoPadre = $(this).val();
                ListaProductosTabla = ListaProductosTabla.filter(
                    (item) => item.codigoPadre != codigoPadre
                );
            }
        );
        //#endregion
    };
    const fncInicializarData = () => {
        $("#nombre").val(Comision.nombre);
        $("#fecha")
            .val(moment(Comision.fecha).format("YYYY-MM-DD"))
            .datepicker({ language: "es", orientation: "bottom left" });
    };
    const fncListaProductosRegistrados = (obj) => {
        let objeto = {
            buscador: false,
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        if (options.buscador == false) {
            CargarDataGET({
                url: "ComisionDetalleListarJson",
                dataForm: {
                    idComision: Comision.idComision,
                    idCeo: Comision.idCeo,
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
                contenedor.append(`<tr
                class="Fila${ele.codigoPadre}"
                data-codigopadre="${ele.codigoPadre}">
                    <td class="text-center">${ele.codigoPadre}</td>
                    <td class="text-left">${ele.nombreProducto}</td>
                    <td>
                        <select
                            class="form-control CbCondicion"
                            style="width:100%;"
                            data-codigo="${ele.codigoPadre}"
                        >
                            <option value="">-- Seleccione --</option>
                            <option
                            value="CAJA" ${
                                ele.condicion == "CAJA" ? "selected" : ""
                            }>CAJA</option>
                            <option value="PAQUETE" ${
                                ele.condicion == "PAQUETE" ? "selected" : ""
                            }>PAQUETE</option>
                            <option value="UNIDAD" ${
                                ele.condicion == "UNIDAD" ? "selected" : ""
                            }>UNIDAD</option>
                        </select>
                    </td>
                    <td>
                        <input
                        type="number"
                        class="form-control text-center cantidadValor"
                        data-codigo="${ele.codigoPadre}"
                        value="${
                            ele.cantidadValor == undefined
                                ? 0
                                : ele.cantidadValor
                        }">
                    </td>
                    <td>
                        <input
                        type="number"
                        class="form-control text-center comisionPtoVenta"
                        data-codigo="${ele.codigoPadre}"
                        value="${
                            ele.comisionPtoVenta == undefined
                                ? 0
                                : ele.comisionPtoVenta
                        }">
                    </td>
                    <td>
                        <input
                        type="number"
                        class="form-control text-center comisionDistribuidor"
                        data-codigo="${ele.codigoPadre}"
                        value="${
                            ele.comisionDistribuidor == undefined
                                ? 0
                                : ele.comisionDistribuidor
                        }">
                    </td>
                    <td class="text-center" style="padding-top:12px;">
                        <div class="icheck-inline-producto text-center">
                            <input
                            type="checkbox"
                            value="${ele.codigoPadre}"
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
        $(".CbCondicion").select2();
        options.callBackSuccess();
    };
    const fncListarProductos = function () {
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ProductoCodigoPadreListarJson",
            table: "#tableProductos",
            ajaxDataSend: {
                idCeo: idCeo,
            },
            tableColumns: [
                { data: "nombreProducto", title: "PRODUCTO" },
                {
                    data: "codigoPadre",
                    title: "CODIGO PADRE",
                    class: "text-center",
                    width: "10%",
                },
                {
                    data: null,
                    title: "OPCION",
                    width: "10%",
                    render: function (value) {
                        return `
                        <div class="icheck-inline text-center">
                            <input type="checkbox" id="CheckTicket" value="${value.codigoPadre}" data-checkbox="icheckbox_square-blue">
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
    const fncObtenerListaProductosRegistrados = () => {
        let obj = {
            respuesta: true,
            mensaje: "",
            ListaProductosComision: [],
        };
        $("#tablaProductoRegistrado tbody tr").each(function () {
            let idProducto = $(this).data("id");
            let idComisionDetalle = $(this).data("key");
            let codigoPadre = $(this).data("codigopadre");
            let condicion = $(this).find(".CbCondicion").val();
            let cantidadValor = parseInt($(this).find(".cantidadValor").val());
            let comisionPtoVenta = parseFloat(
                $(this).find(".comisionPtoVenta").val().replace(/,/g, ".")
            );
            let comisionDistribuidor = parseFloat(
                $(this).find(".comisionDistribuidor").val().replace(/,/g, ".")
            );
            if (codigoPadre == undefined) {
                obj.respuesta = false;
                obj.mensaje = "NO SE HA REGISTRADO PRODUCTOS";
                obj.ListaProductosComision = [];
                return false;
            }
            if (condicion == "") {
                obj.respuesta = false;
                obj.mensaje = "SELECCIONE EL CAMPO CONDICIÓN";
                obj.ListaProductosComision = [];
                return false;
            }
            if (isNaN(cantidadValor) || cantidadValor < 0) {
                obj.respuesta = false;
                obj.mensaje =
                    "EL VALOR EN UNIDADES (PRODUCTOS) LO MINIMO ACEPTADO ES 1";
                obj.ListaProductosComision = [];
                return false;
            }

            if (isNaN(comisionPtoVenta) || comisionPtoVenta < 0) {
                obj.respuesta = false;
                obj.mensaje =
                    "EL VALOR DE COMISIÓN DE PUNTO DE VENTA DEBE SER MAYOR A 0";
                obj.ListaProductosComision = [];
                return false;
            }

            if (isNaN(comisionDistribuidor) || comisionDistribuidor < 0) {
                obj.respuesta = false;
                obj.mensaje =
                    "EL VALOR DE COMISIÓN DE DISTRIBUIDOR DEBE SER MAYOR A 0";
                obj.ListaProductosComision = [];
                return false;
            }
            obj.ListaProductosComision.push({
                idComisionDetalle: idComisionDetalle,
                idProducto: idProducto,
                codigoPadre: codigoPadre,
                condicion: condicion,
                cantidadValor: cantidadValor,
                comisionPtoVenta: comisionPtoVenta,
                comisionDistribuidor: comisionDistribuidor,
            });
        });
        return obj;
    };
    const fncValidarFormularioEditar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                nombre: { required: true },
                fecha: { required: true },
            },
            messages: {
                nombre: { required: "El campo es requerido" },
                fecha: { required: "El campo es requerido" },
            },
        });
    };
    const fncValidarFormularioImportarComision = () => {
        ValidarFormulario({
            contenedor: "#frmImportarComision",
            nameVariable: "frmImportarComision",
            rules: {
                archivoComision: { required: true },
            },
            messages: {
                archivoComision: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncListaProductosRegistrados();
            fncValidarFormularioEditar();
            fncValidarFormularioImportarComision();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ProductoEditar.init();
});
//

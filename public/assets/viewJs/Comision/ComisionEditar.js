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
                        callBackSuccess: function () {
                            setTimeout(function () {
                                RedirigirUrl(`Comision`);
                            }, 1100);
                        },
                    });
                } else {
                    ShowAlert({
                        type: "warning",
                        message: objCalculado.mensaje,
                    });
                }
            }
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
                            },
                            callBackSuccess: function () {
                                ListaProductosEliminar = [];
                                fncListaProductosRegistrados();
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
                let idComisionDetalle = $(this).val();
                ListaProductosEliminar.push(parseInt(idComisionDetalle));
            }
        );
        $(document).on(
            "ifUnchecked",
            "#tablaProductoRegistrado input:checkbox",
            function () {
                let idComisionDetalle = $(this).val();
                ListaProductosEliminar = ListaProductosEliminar.filter(
                    (item) => item != parseInt(idComisionDetalle)
                );
            }
        );
        //#region MODAL PRODUCTO
        $(document).on("click", "#btnModalProducto", function () {
            ListaProductosTabla = [];
            fncListarProductos();
        });
        $(document).on("click", "#btnAgregarProducto", function () {
            if (ListaProductosTabla.length > 0) {
                EnviarDataPost({
                    url: "ComisionDetalleRegistrarJson",
                    data: {
                        ListaProductosComision: ListaProductosTabla,
                        idComision: Comision.idComision,
                    },
                    callBackSuccess: function (response) {
                        fncListaProductosRegistrados({
                            callBackSuccess: function () {
                                $("#ModalProducto").modal("hide");
                            },
                        });
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
        //#endregion
        //#region IMPORTAR DATA
        $(document).on("click", "#btnImportarComisionModal", function () {
            LimpiarFormulario({
                formulario: "frmImportarComision",
                nameVariable: "frmImportarComision",
            });
            $(`.custom-file-label`).text("Elegir archivo...");
            $("#ModalImportarComision").modal({
                keyboard: false,
                backdrop: "static",
            });
        });
        $(document).on("change", "#archivoComision", function (e) {
            const files = e.target.files;
            if (files[0] === undefined) {
                $(`.custom-file-label`).text("Elegir archivo...");
            } else {
                $(`.custom-file-label`).text(files[0].name);
            }
        });
        $(document).on("click", "#btnImportarComision", function () {
            $("#frmImportarComision").submit();
            if (_objetoForm_frmImportarComision.valid()) {
                let dataForm = new FormData($("#frmImportarComision")[0]);
                dataForm.append("idCeo", idCeo);
                EnviarDataPost({
                    url: "ComisionImportarDataJson",
                    data: dataForm,
                    callBackSuccess: function (response) {
                        ListaProductosRegistrados = response;
                        fncListaProductosRegistrados({
                            lista: response,
                            callBackSuccess: function () {
                                $("#ModalImportarComision").modal("hide");
                            },
                        });
                    },
                });
            }
        });
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
            callBackSuccess: function () {},
        };
        let options = $.extend({}, objeto, obj);
        CargarDataGET({
            url: "ComisionDetalleListarJson",
            dataForm: {
                idComision: Comision.idComision,
            },
            callBackSuccess: function (response) {
                let contenedor = $("#tablaProductoRegistrado tbody");
                contenedor.html("");
                if (response.length > 0) {
                    response.map((ele) => {
                        contenedor.append(`
                        <tr class="Fila${ele.codigoPadre}" data-id="${
                            ele.idProducto
                        }" data-codigopadre="${ele.codigoPadre}" data-key="${
                            ele.idComisionDetalle
                        }">
                            <td class="text-center">${ele.codigoPadre}</td>
                            <td class="text-left">${ele.nombreProducto}</td>
                            <td>
                                <select class="form-control CbCondicion" style="width:100%;">
                                    <option value="">-- Seleccione --</option>
                                    <option value="CAJA" ${
                                        ele.condicion == "CAJA"
                                            ? "selected"
                                            : ""
                                    }>CAJA</option>
                                    <option value="PAQUETE" ${
                                        ele.condicion == "PAQUETE"
                                            ? "selected"
                                            : ""
                                    }>PAQUETE</option>
                                    <option value="UNIDAD" ${
                                        ele.condicion == "UNIDAD"
                                            ? "selected"
                                            : ""
                                    }>UNIDAD</option>
                                </select>
                            </td>
                            <td><input type="number" class="form-control text-center cantidadValor" value="${
                                ele.cantidadValor == undefined
                                    ? 0
                                    : ele.cantidadValor
                            }"></td>
                            <td><input type="number" class="form-control text-center comisionPtoVenta" value="${
                                ele.comisionPtoVenta == undefined
                                    ? 0
                                    : ele.comisionPtoVenta
                            }"></td>
                            <td><input type="number" class="form-control text-center comisionDistribuidor" value="${
                                ele.comisionDistribuidor == undefined
                                    ? 0
                                    : ele.comisionDistribuidor
                            }"></td>
                            <td class="text-center" style="padding-top: 12px">
                                <div class="icheck-inline-producto text-center">
                                    <input type="checkbox" value="${
                                        ele.idComisionDetalle
                                    }" data-checkbox="icheckbox_square-blue">
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
                        `PRODUCTOS : SELECCIONADO(S) ${response.length}`
                    );
                } else {
                    $("#txtTituloProductos").text(`PRODUCTOS`);
                    contenedor.append(
                        `<tr><td colspan="7" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`
                    );
                }
                $(".CbCondicion").select2();
                options.callBackSuccess();
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

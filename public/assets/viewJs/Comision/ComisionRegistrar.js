ListaProductosTabla = [];
ListaProductosRegistrados = [];
ListaProductos = [];

ListaProductosIndependiente = [];
ListaProductosEliminar = [];

let ProductoRegistrar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function () {
            RedirigirUrl(`Bonificacion`);
        });
        $(document).on("click", ".btnGuardar", function () {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                let objCalculado = fncObtenerListaProductosRegistrados();
                if (objCalculado.respuesta) {
                    dataForm = Object.assign(dataForm, {
                        ListaProductosComision: objCalculado.ListaProductosComision,
                        idCeo: idCeo,
                    });
                    EnviarDataPost({
                        url: "ComisionRegistrarJson",
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
        //#region MODAL PRODUCTO
        $(document).on("click", "#btnModalProducto", function () {
            ListaProductosTabla = [];
            fncListarProductos();
        });
        $(document).on("click", "#btnAgregarProducto", function () {
            if (ListaProductosTabla.length > 0) {
                let ProductosFiltrados = ListaProductosTabla.filter((ele) => {
                    return !ListaProductosRegistrados.find((arg) => {
                        return arg.codigoPadre === ele.codigoPadre;
                    });
                });
                ListaProductosRegistrados = ListaProductosRegistrados.concat(ProductosFiltrados);
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
        $(document).on("ifChecked", "#tableProductos input:checkbox", function () {
            let idProducto = $(this).val();
            let objProducto = ListaProductos.find(
                (ele) => ele.idProducto == idProducto
            );
            ListaProductosTabla.push(objProducto);
        });
        $(document).on("ifUnchecked", "#tableProductos input:checkbox", function () {
            let idProducto = $(this).val();
            ListaProductosTabla = ListaProductosTabla.filter(
                (item) => item.idProducto != parseInt(idProducto)
            );
        });
        $(document).on("click", "#btnEliminarProductos", function () {
            if (ListaProductosEliminar.length > 0) {
                ListaProductosRegistrados = ListaProductosRegistrados.filter((ele) => {
                    return !ListaProductosEliminar.find((arg) => {
                        return arg === parseInt(ele.codigoPadre);
                    });
                });
                ListaProductosEliminar.map(ele => {
                    $(`.Fila${ele}`).remove();
                });
                ListaProductosEliminar = [];
                if (ListaProductosRegistrados.length > 0) {
                    $("#txtTituloProductos").text(`PRODUCTOS : SELECCIONADO(S) ${ListaProductosRegistrados.length}`);
                } else {
                    $("#txtTituloProductos").text(`PRODUCTOS`);
                    $("#tablaProductoRegistrado tbody").append(`<tr><td colspan="11" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`);
                }

            } else {
                ShowAlert({
                    type: 'warning',
                    message: "NO SE HA SELECCIONADO PRODUCTO(S) A BORRAR"
                })
                return false;
            }
        });
        $(document).on("ifChecked", "#tablaProductoRegistrado input:checkbox", function () {
            let codigoPadre = $(this).val();
            ListaProductosEliminar.push(parseInt(codigoPadre));
        });
        $(document).on("ifUnchecked", "#tablaProductoRegistrado input:checkbox", function () {
            let codigoPadre = $(this).val();
            ListaProductosEliminar = ListaProductosEliminar.filter(
                (item) => item != parseInt(codigoPadre)
            );
        });
        //#endregion
        //#region IMPORTAR DATA
        $(document).on('click', '#btnImportarComisionModal', function () {
            LimpiarFormulario({
                formulario: "frmImportarComision",
                nameVariable: "frmImportarComision",
            });
            $(`.custom-file-label`).text("Elegir archivo...");
            $("#ModalImportarComision").modal({
                keyboard: false,
                backdrop: "static",
            });
        })
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
        $(".Fecha").datepicker({ language: "es" }).datepicker("setDate", new Date());
    };
    const fncListaProductosRegistrados = (obj) => {
        let objeto = {
            lista: [],
            callBackSuccess: function () { },
            importado: false
        };
        let options = $.extend({}, objeto, obj);
        let contenedor = $("#tablaProductoRegistrado tbody");
        contenedor.html("");
        if (options.lista.length > 0) {
            options.lista.map((ele) => {
                contenedor.append(`
                <tr class="Fila${ele.codigoPadre}" data-id="${ele.idProducto}" data-codigopadre="${ele.codigoPadre}">
                    <td class="text-center">${ele.codigoPadre}</td>
                    <td class="text-left">${ele.nombreProducto}</td>
                    <td>
                        <select class="form-control CbCondicion" style="width:100%;">
                            <option value="">-- Seleccione --</option>
                            <option value="CAJA" ${ele.condicion == "CAJA" ? 'selected' : ''}>CAJA</option>
                            <option value="PAQUETE" ${ele.condicion == "PAQUETE" ? 'selected' : ''}>PAQUETE</option>
                            <option value="UNIDAD" ${ele.condicion == "UNIDAD" ? 'selected' : ''}>UNIDAD</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control text-center cantidadValor" value="${ele.cantidadValor == undefined ? 0 : ele.cantidadValor}"></td>
                    <td><input type="number" class="form-control text-center comisionPtoVenta" value="${ele.comisionPtoVenta == undefined ? 0 : ele.comisionPtoVenta}"></td>
                    <td><input type="number" class="form-control text-center comisionDistribuidor" value="${ele.comisionDistribuidor == undefined ? 0 : ele.comisionDistribuidor}"></td>
                    <td class="text-center" style="padding-top:12px;">
                        <div class="icheck-inline-producto text-center">
                            <input type="checkbox" value="${ele.codigoPadre}" data-checkbox="icheckbox_square-blue">
                        </div>
                    </td>
                </tr>`);
            });
            $(".icheck-inline-producto").iCheck({
                checkboxClass: "icheckbox_square-blue",
                radioClass: "iradio_square-red",
                increaseArea: "25%",
            });
            $("#txtTituloProductos").text(`PRODUCTOS : SELECCIONADO(S) ${options.lista.length}`);
        } else {
            $("#txtTituloProductos").text(`PRODUCTOS`);
            contenedor.append(`<tr><td colspan="7" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`);
        }
        $(".CbCondicion").select2();
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
    const fncObtenerListaProductosRegistrados = () => {
        let obj = {
            respuesta: true,
            mensaje: "",
            ListaProductosComision: [],
        };
        $("#tablaProductoRegistrado tbody tr").each(function () {
            let idProducto = $(this).data("id");
            let codigoPadre = $(this).data("codigopadre");
            let condicion = $(this).find(".CbCondicion").val();
            let cantidadValor = parseInt($(this).find(".cantidadValor").val());
            let comisionPtoVenta = parseFloat($(this).find(".comisionPtoVenta").val().replace(/,/g, '.'));
            let comisionDistribuidor = parseFloat($(this).find(".comisionDistribuidor").val().replace(/,/g, '.'));
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
                obj.mensaje = "EL VALOR EN UNIDADES (PRODUCTOS) LO MINIMO ACEPTADO ES 1";
                obj.ListaProductosComision = [];
                return false;
            }

            if (isNaN(comisionPtoVenta) || comisionPtoVenta < 0) {
                obj.respuesta = false;
                obj.mensaje = "EL VALOR DE COMISIÓN DE PUNTO DE VENTA DEBE SER MAYOR A 0";
                obj.ListaProductosComision = [];
                return false;
            }

            if (isNaN(comisionDistribuidor) || comisionDistribuidor < 0) {
                obj.respuesta = false;
                obj.mensaje = "EL VALOR DE COMISIÓN DE DISTRIBUIDOR DEBE SER MAYOR A 0";
                obj.ListaProductosComision = [];
                return false;
            }
            obj.ListaProductosComision.push({
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
    const fncValidarFormularioRegistrar = () => {
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
    const fncValidarFormularioImportarBonificacion = () => {
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
            fncValidarFormularioRegistrar();
            fncValidarFormularioImportarBonificacion();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ProductoRegistrar.init();
});
//

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
                        ListaProductosBonificacion:
                            objCalculado.ListaProductosBonificacion,
                        idCeo: idCeo,
                    });
                    EnviarDataPost({
                        url: "BonificacionRegistrarJson",
                        data: dataForm,
                        callBackSuccess: function () {
                            setTimeout(function () {
                                RedirigirUrl(`Bonificacion`);
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
        $(document).on("change", ".CbBonificar", function () {
            let valor = $(this).val();
            let sku = $(this).data("sku");
            let formato = $(this).find("option:selected").data("formato");
            let marca = $(this).find("option:selected").data("marca");
            if (valor != "") {
                $(`.valorBonificar${sku}`).text(`${marca}/${formato}`);
            } else {
                $(`.valorBonificar${sku}`).text(`--`);
            }
        });
        $(document).on("change", ".CbCondicion", function () {
            let valor = $(this).val();
            let sku = $(this).data("sku");
            let valorCondicion = $(this).find("option:selected").data("valor");
            if (valor != "") {
                $(`.valorCondicion${sku}`).text(valorCondicion);
            } else {
                $(`.valorCondicion${sku}`).text("--");
            }
        });
        $(document).on("change", ".Fecha", function () {
            let fechaInicio = moment($("#fechaInicio").val(), "YYYY-MM-DD");
            let fechafin = moment($("#fechaFin").val(), "YYYY-MM-DD");
            let diasBonificar = moment
                .duration(fechafin.diff(fechaInicio))
                .asDays();
            $("#diasBonificar").val(diasBonificar);
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
        $(document).on("click", "#btnEliminarProductos", function () {
            if (ListaProductosEliminar.length > 0) {
                ListaProductosRegistrados = ListaProductosRegistrados.filter((ele) => {
                    return !ListaProductosEliminar.find((arg) => {
                        return arg === ele.idProducto;
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
            let idProducto = $(this).val();
            ListaProductosEliminar.push(parseInt(idProducto));
        });
        $(document).on("ifUnchecked", "#tablaProductoRegistrado input:checkbox", function () {
            let idProducto = $(this).val();
            ListaProductosEliminar = ListaProductosEliminar.filter(
                (item) => item != parseInt(idProducto)
            );
        });

        //#endregion
    };
    const fncInicializarData = () => {
        CargarDataGET({
            url: "ProductoListarJson",
            dataForm: {
                idCeo: idCeo,
            },
            callBackSuccess: function (response) {
                ListaProductosIndependiente = response;
            },
        });
        $(".Fecha")
            .datepicker({
                language: "es",
            })
            .datepicker("setDate", new Date());
        let fechaInicio = moment($("#fechaInicio").val(), "YYYY-MM-DD");
        let fechafin = moment($("#fechaFin").val(), "YYYY-MM-DD");
        let diasBonificar = moment
            .duration(fechafin.diff(fechaInicio))
            .asDays();
        $("#diasBonificar").val(diasBonificar);
    };
    const fncListaProductosRegistrados = (obj) => {
        let objeto = {
            lista: [],
            callBackSuccess: function () { },
        };
        let options = $.extend({}, objeto, obj);

        let contenedor = $("#tablaProductoRegistrado tbody");
        contenedor.html("");
        if (options.lista.length > 0) {
            options.lista.map((ele) => {
                contenedor.append(`
                <tr class="Fila${ele.idProducto}" data-id="${ele.idProducto}" data-sku="${ele.sku}">
                    <td class="text-center">${ele.nombreLinea}</td>
                    <td class="text-center">${ele.marca}</td>
                    <td class="text-center">${ele.formato}</td>
                    <td class="text-center">${ele.codigoPadre}</td>
                    <td class="text-center valorCondicion${ele.sku}">--</td>
                    <td>
                        <select class="form-control CbCondicion" data-sku="${ele.sku}" style="width:100%;">
                            <option value="">-- Seleccione --</option>
                            <option value="1" data-valor="${ele.caja}">CAJA</option>
                            <option value="0" data-valor="${ele.paquete}">PAQUETE</option>
                        </select>
                    </td>
                    <td class="text-center">${ele.sku}</td>
                    <td><input type="text" class="form-control text-center" value="0"></td>
                    <td class="text-center valorBonificar${ele.sku}">--</td>
                    <td>${fncGenerarComboBonificar({ sku: ele.sku, valorSelect: ele.idProducto })}</td>
                    <td class="text-center">
                        <div class="icheck-inline-producto text-center">
                            <input type="checkbox" value="${ele.idProducto}" data-checkbox="icheckbox_square-blue">
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
            contenedor.append(
                `<tr><td colspan="11" class="text-center">NO SE HA REGISTRADO PRODUCTOS</td></tr>`
            );
        }
        options.callBackSuccess();
        $(".CbCondicion").select2();
        $(".CbBonificar").select2();
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
    const fncValidarFormularioRegistrar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                codigoBonificacion: { required: true },
                nombre: { required: true },
                nroDocumento: { required: true },
                telefono: { required: true },
            },
            messages: {
                codigoBonificacion: { required: "El campo es requerido" },
                nombre: { required: "El campo es requerido" },
                nroDocumento: { required: "El campo es requerido" },
                telefono: { required: "El campo es requerido" },
            },
        });
    };
    const fncGenerarComboBonificar = (obj) => {
        let objeto = {
            sku: "",
            valorSelect: "",
        };
        let opciones = $.extend({}, objeto, obj);
        let options = `<option value="">-- Seleccione --</option>`;
        ListaProductosIndependiente.map((ele) => {
            options += `<option value="${ele.idProducto}" ${opciones.valorSelect == ele.idProducto ? `selected` : ``} data-formato="${ele.formato}" data-marca="${ele.marca}">${ele.sku}</option>`;
        });
        return `<select class="form-control CbBonificar" data-sku="${opciones.sku}" style="width:100%">${options}</select>`;
    };
    const fncObtenerListaProductosRegistrados = () => {
        let obj = {
            respuesta: true,
            mensaje: "",
            ListaProductosBonificacion: [],
        };
        // let contenedor = $("#tablaProductoRegistrado tbody tr");
        $("#tablaProductoRegistrado tbody tr").each(function () {
            let idProducto = $(this).data("id");
            let sku = $(this).data("sku");
            let cajaX = $(this).find(`.valorCondicion${sku}`).text();
            let condicionAt = $(this).find(".CbCondicion").val();
            let nroBotellasBonificar = $(this)
                .find(`input[type='text']`)
                .val();
            let idProductoBonificar = $(this).find(`.CbBonificar`).val();

            if (idProducto == undefined) {
                obj.respuesta = false;
                obj.mensaje = "NO SE HA REGISTRADO PRODUCTOS";
                obj.ListaProductosBonificacion = [];
                return false;
            }
            if (condicionAt == "") {
                obj.respuesta = false;
                obj.mensaje = "SELECCIONE EL CAMPO CONDICIÓN AT";
                obj.ListaProductosBonificacion = [];
                return false;
            }
            if (parseInt(nroBotellasBonificar) < 1) {
                obj.respuesta = false;
                obj.mensaje =
                    "EL VALOR DE BONIFICACION (BOTELLAS) EL VALOR MINIMO ACEPTADO ES 1";
                obj.ListaProductosBonificacion = [];
                return false;
            }
            if (idProductoBonificar == "") {
                obj.respuesta = false;
                obj.mensaje = "SELECCIONE EL CAMPO SABOR A BONIFICAR";
                obj.ListaProductosBonificacion = [];
                return false;
            }
            obj.ListaProductosBonificacion.push({
                idProducto: idProducto,
                sku: sku,
                cajaX: cajaX,
                condicionAt: condicionAt,
                nroBotellasBonificar: nroBotellasBonificar,
                idProductoBonificar: idProductoBonificar,
            });
        });

        return obj;
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

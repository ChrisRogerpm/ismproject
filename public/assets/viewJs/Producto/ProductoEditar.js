let ProductoEditar = (function () {
    const fncAcciones = () => {
        $(document).on("click", ".btnVolver", function (e) {
            RedirigirUrl(`Producto`);
        });
        $(document).on("click", ".btnGuardar", function (e) {
            $("#frmNuevo").submit();
            if (_objetoForm_frmNuevo.valid()) {
                let dataForm = $("#frmNuevo").serializeFormJSON();
                dataForm = Object.assign(dataForm, {
                    idCeo: idCeo,
                    idProducto: Producto.idProducto,
                });
                EnviarDataPost({
                    url: "ProductoEditarJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        setTimeout(function () {
                            RedirigirUrl(`Producto`);
                        }, 1100);
                    },
                });
            }
        });
    };
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "LineaListarActivosJson",
            idSelect: "#CbidLinea",
            dataId: "idLinea",
            dataValor: "nombre",
            dataForm: {
                idCeo: Producto.idCeo,
            },
            valorSelect: Producto.idLinea,
        });
        $("#cajaxpaquete").val(Producto.cajaxpaquete);
        $("#codigoHijo").val(Producto.codigoHijo);
        $("#codigoPadre").val(Producto.codigoPadre);
        $("#formato").val(Producto.formato);
        $("#sabor").val(Producto.sabor);
        $("#marca").val(Producto.marca);
        $("#nombre").val(Producto.nombre);
        $("#sku").val(Producto.sku);
        $("#unidadxCaja").val(Producto.unidadxCaja);
        $("#unidadxPaquete").val(Producto.unidadxPaquete);
    };
    const fncValidarFormularioEditar = () => {
        ValidarFormulario({
            contenedor: "#frmNuevo",
            nameVariable: "frmNuevo",
            rules: {
                idLinea: { required: true },
                cajaxpaquete: { required: true, number: true, digits: true },
                codigoHijo: { required: true },
                codigoPadre: { required: true },
                formato: { required: true },
                marca: { required: true },
                sabor: { required: true },
                nombre: { required: true },
                sku: { required: true },
                unidadxCaja: { required: true, number: true, digits: true },
                unidadxPaquete: { required: true, number: true, digits: true },
            },
            messages: {
                idLinea: { required: "El campo es requerido" },
                cajaxpaquete: {
                    required: "El campo es requerido",
                    number: "El campo solo admite números",
                    digits: "El campo solo admite valores enteros",
                },
                codigoHijo: { required: "El campo es requerido" },
                codigoPadre: { required: "El campo es requerido" },
                formato: { required: "El campo es requerido" },
                marca: { required: "El campo es requerido" },
                sabor: { required: "El campo es requerido" },
                nombre: { required: "El campo es requerido" },
                sku: { required: "El campo es requerido" },
                unidadxCaja: {
                    required: "El campo es requerido",
                    number: "El campo solo admite números",
                    digits: "El campo solo admite valores enteros",
                },
                unidadxPaquete: {
                    required: "El campo es requerido",
                    number: "El campo solo admite números",
                    digits: "El campo solo admite valores enteros",
                },
            },
        });
    };
    return {
        init: function () {
            fncInicializarData();
            fncAcciones();
            fncValidarFormularioEditar();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ProductoEditar.init();
});

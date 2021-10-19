ListadeProductos = [];
let ProductoListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarProducto/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idProducto = $(this).data("id");
            RedirigirUrl("EditarProducto/" + idProducto);
        });
        $(document).on("click", ".btnBuscar", function () {
            fncListarProductos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarProductos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".btnBuscar", function () {
            fncListarProductos({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnImportarExcel", function () {
            LimpiarFormulario({
                formulario: "frmImportarProducto",
                nameVariable: "frmImportarProducto",
            });
            $(`.custom-file-label`).text("Elegir archivo...");
            $("#ModalImportarProducto").modal({
                keyboard: false,
                backdrop: "static",
            });
        });
        $(document).on("change", "#productoExcel", function (e) {
            const files = e.target.files;
            if (files[0] === undefined) {
                $(`.custom-file-label`).text("Elegir archivo...");
            } else {
                $(`.custom-file-label`).text(files[0].name);
            }
        });
        $(document).on("click", "#btnImportarProducto", function () {
            $("#frmImportarProducto").submit();
            if (_objetoForm_frmImportarProducto.valid()) {
                let dataForm = new FormData($("#frmImportarProducto")[0]);
                dataForm.append("idCeo", parseInt($("#CbidCeo").val()));
                EnviarDataPost({
                    url: "ProductoImportarDataJson",
                    data: dataForm,
                    callBackSuccess: function () {
                        $("#ModalImportarProducto").modal("hide");
                        fncListarProductos();
                    },
                });
            }
        });
        $(document).on("click", ".btnExportarExcel", function () {
            let idCeo = $("#CbidCeo").val();
            let url = `${basePathApi}ProductoDownload?idCeo=${idCeo}`;
            window.open(url, "_blank");
        });
    };
    const fncInicializarData = () => {
        CargarDataSelect({
            url: "CentroOperativoListarActivosJson",
            idSelect: "#CbidCeo",
            dataId: "idCeo",
            dataValor: "nombreCeo",
            withoutplaceholder: true,
            callBackSuccess: function () {
                fncListarProductos({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarProductos = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ProductoListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "sku", title: "CODALT", className: "text-center" },
                { data: "nombreProducto", title: "PRODUCTO" },
                { data: "marca", title: "MARCA", className: "text-center" },
                { data: "formato", title: "FORMATO", className: "text-center" },
                { data: "sabor", title: "SABOR", className: "text-center" },
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
                // {
                //     data: "estadoNombre",
                //     title: "ESTADO",
                //     className: "text-center",
                // },
                {
                    data: null,
                    title: "OPCIONES",
                    width: "10%",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idProducto}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        // let bloquear = `<a class="dropdown-item btnBloquear" data-id="${value.idProducto}" href="javascript:void(0)"><i class="fa fa-lock"></i> BLOQUEAR</a>`;
                        // let restablecer = `<a class="dropdown-item btnRestablecer" data-id="${value.idProducto}" href="javascript:void(0)"><i class="fa fa-lock-open"></i> RESTABLECER</a>`;
                        // if (value.estado == 1) {
                        //     restablecer = ``;
                        // } else {
                        //     bloquear = ``;
                        //     editar = ``;
                        // }
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${editar}
                                    </div>
                                </span>`;
                    },
                    class: "text-center",
                },
            ],
            tabledrawCallback: function () {
                $(".btnEditar").tooltip();
            },
            callBackSuccess: function (response) {
                ListadeProductos = response.data;
            },
        });
    };
    const fncValidarFormularioImportarProducto = () => {
        ValidarFormulario({
            contenedor: "#frmImportarProducto",
            nameVariable: "frmImportarProducto",
            rules: {
                productoExcel: { required: true },
            },
            messages: {
                productoExcel: { required: "El campo es requerido" },
            },
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
            fncValidarFormularioImportarProducto();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    ProductoListar.init();
});

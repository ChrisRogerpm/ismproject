ListadeLineas = [];
let LineaListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnBuscar", function () {
            fncListarLineas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarLineas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".btnBuscar", function () {
            fncListarLineas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarLinea/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idLinea = $(this).data("id");
            RedirigirUrl("EditarLinea/" + idLinea);
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
                fncListarLineas({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarLineas = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "LineaListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nombre", title: "LINEA" },
                {
                    data: null,
                    title: "OPCIONES",
                    width: "10%",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idLinea}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
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
        });
    };
    return {
        init: function () {
            fncAcciones();
            fncInicializarData();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    LineaListar.init();
});

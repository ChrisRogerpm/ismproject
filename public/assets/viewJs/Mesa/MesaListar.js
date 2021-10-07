ListadeMesas = [];
let MesaListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarMesa/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idMesa = $(this).data("id");
            RedirigirUrl("EditarMesa/" + idMesa);
        });
        $(document).on("click", ".btnBuscar", function () {
            fncListarMesas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarMesas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".btnBuscar", function () {
            fncListarMesas({
                data: $("#frmNuevo").serializeFormJSON(),
            });
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
                fncListarMesas({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarMesas = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "MesaListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nombre", title: "MESA" },
                {
                    data: null,
                    title: "OPCIONES",
                    width: "10%",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idMesa}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
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
                ListadeMesas = response.data;
            },
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
    MesaListar.init();
});

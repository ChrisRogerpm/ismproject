ListadeComisions = [];
let ComisionListar = (function () {
    const fncAcciones = function () {
        $(document).on("click", ".btnNuevo", function () {
            let idCeo = $("#CbidCeo").val();
            RedirigirUrl(`RegistrarComision/${idCeo}`);
        });
        $(document).on("click", ".btnEditar", function () {
            let idComision = $(this).data("id");
            RedirigirUrl("EditarComision/" + idComision);
        });
        $(document).on("click", ".btnBuscar", function () {
            fncListarComisions({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", "#CbidCeo", function () {
            fncListarComisions({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("change", ".btnBuscar", function () {
            fncListarComisions({
                data: $("#frmNuevo").serializeFormJSON(),
            });
        });
        $(document).on("click", ".btnActivar", function () {
            let idComision = $(this).data("id");
            let objComision = ListadeComisions.find((ele) => ele.idComision == idComision);
            Swal.fire({
                title: `ESTA SEGURO DE ACTIVAR LA COMISIÓN : ${objComision.nombre.toUpperCase()}?`,
                text: "CONSIDERE LO NECESARIO PARA REALIZAR ESTA ACCIÓN!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "SI, ACTIVAR!",
                cancelButtonText: "NO, CANCELAR!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {
                    EnviarDataPost({
                        url: "ComisionActivarJson",
                        data: {
                            idComision: idComision,
                        },
                        callBackSuccess: function () {
                            fncListarComisions();
                        },
                    });
                }
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
                fncListarComisions({
                    data: $("#frmNuevo").serializeFormJSON(),
                });
            },
        });
    };
    const fncListarComisions = function (obj) {
        let objeto = {
            data: $("#frmNuevo").serializeFormJSON(),
        };
        let options = $.extend({}, objeto, obj);
        CargarTablaDatatable({
            uniform: true,
            ajaxUrl: "ComisionListarJson",
            table: "#table",
            ajaxDataSend: options.data,
            tableColumns: [
                { data: "nombre", title: "NOMBRE" },
                { data: "fecha", title: "FECHA" },
                { data: "estadoNombre", title: "ESTADO" },
                {
                    data: null,
                    title: "OPCIONES",
                    width: "10%",
                    render: function (value) {
                        let editar = `<a class="dropdown-item btnEditar" data-id="${value.idComision}" href="javascript:void(0)"><i class="fa fa-edit"></i> EDITAR</a>`;
                        let activar = `<a class="dropdown-item btnActivar" data-id="${value.idComision}" href="javascript:void(0)"><i class="fa fa-hand-point-up"></i> ACTIVAR COMISIÓN</a>`;
                        return `<span class="dropdown">
                                    <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-align-justify"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    ${editar}
                                    ${activar}
                                    </div>
                                </span>`;
                    },
                    class: "text-center",
                },
            ],
            callBackSuccess: function (response) {
                ListadeComisions = response.data;
                let verificarComisionActiva = ListadeComisions.find(ele => parseInt(ele.estado) == 1);
                if (verificarComisionActiva == null) {
                    ShowAlert({
                        type: 'warning',
                        message: 'NO SE HA ENCONTRADO NINGUNA Comision ACTIVO'
                    })
                }
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
    ComisionListar.init();
});

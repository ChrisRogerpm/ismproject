basePath = document.location.origin + "/";
basePathApi = document.location.origin + "/"; //api/";
basePathTramite = basePath + "assets/viewJs/Archivos/PDF/";
basePathImagen = basePath + "assets/viewJs/Archivos/Imagenes/Perfiles/";
basePathSeguimiento = basePath + "assets/viewJs/Archivos/Seguimiento";
basePathSeguimientoRespuesta =
    basePath + "assets/viewJs/Archivos/SeguimientoRespuesta";
basePathSeguimientoTramiteRespuesta =
    basePath + "assets/viewJs/Archivos/SeguimientoTramiteRespuesta";
$.fn.datepicker.dates["es"] = {
    days: [
        "Domingo",
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
    ],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
    months: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    ],
    monthsShort: [
        "Ene",
        "Feb",
        "Mar",
        "Abr",
        "May",
        "Jun",
        "Jul",
        "Ago",
        "Sep",
        "Oct",
        "Nov",
        "Dic",
    ],
    today: "Hoy",
    monthsTitle: "Meses",
    clear: "Borrar",
    weekStart: 1,
    format: "yyyy-mm-dd",
};
$.fn.datetimepicker.dates["es"] = {
    days: [
        "Domingo",
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
        "Domingo",
    ],
    daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do"],
    months: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    ],
    monthsShort: [
        "Ene",
        "Feb",
        "Mar",
        "Abr",
        "May",
        "Jun",
        "Jul",
        "Ago",
        "Sep",
        "Oct",
        "Nov",
        "Dic",
    ],
    today: "Hoy",
    suffix: [],
    meridiem: [],
};
$.extend($.fn.dataTable.defaults, {
    autoWidth: false,
    responsive: true,
    // dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        search: "<span>Buscar:</span> _INPUT_",
        searchPlaceholder: "Buscar...",
        lengthMenu: "<span>Mostrar:</span> _MENU_",
        paginate: {
            first: "First",
            last: "Last",
            next: $("html").attr("dir") == "rtl" ? "&larr;" : "&rarr;",
            previous: $("html").attr("dir") == "rtl" ? "&rarr;" : "&larr;",
        },
        emptyTable: "No hay Data que Mostrar",
        infoEmpty: "Mostrando 0 al 0 de 0 Registros",
        infoFiltered: "(Filtrado de _MAX_ Registro(s))",
        info: "Mostrando _START_ al _END_ de _TOTAL_ Registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        zeroRecords: "No Se encontro Coincidencias",
    },
});
$.fn.serializeFormJSON = function () {
    let o = {};
    let a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || "");
        } else {
            o[this.name] = this.value || "";
        }
    });
    return o;
};
$(document).on("click", ".btnRecargar", function () {
    RefrescarVentana();
});
$(document).on("click", "#btnIniciarSesionToken", function () {
    $("#frmSessionToken").submit();
    if (_objetoForm_frmSessionToken.valid()) {
        let dataForm = $("#frmSessionToken").serializeFormJSON();
        EnviarDataPostWithOutApi({
            url: "RestablecerTokenUsuarioJson",
            data: dataForm,
            callBackSuccess: function (response) {
                let token = response;
                localStorage.setItem("token", token);
                $("#modalRenovarSessionToken").modal("hide");
                setTimeout(function () {
                    RefrescarVentana();
                }, 1000);
            },
        });
    }
});
$("select").on("select2:close", function (e) {
    $(this).valid();
});
const ValidarFormulario = (obj) => {
    let defaults = {
        contenedor: null,
        nameVariable: "",
        ignore: "input[type=hidden], .select2-search__field",
        rules: {},
        messages: {},
    };
    let opciones = $.extend({}, defaults, obj);
    if (opciones.contenedor == null) {
        console.warn("Advertencia - contenedor no esta definido.");
        return;
    }
    let objt = "_objetoForm";
    this[objt + "_" + opciones.nameVariable] = $(opciones.contenedor).validate({
        ignore: opciones.ignore, // ignore hidden fields
        errorClass: "validation-invalid-label",
        successClass: "validation-valid-label",
        validClass: "validation-valid-label",
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        success: function (element, errorClass) {
            $(element).remove();
        },
        // Different components require proper error label placement
        errorPlacement: function (error, element) {
            // Unstyled checkboxes, radios
            if (element.parents().hasClass("form-check")) {
                error.appendTo(element.parents(".form-check").parent());
            }
            // Input group, styled file input
            else if (
                element.parent().is(".uniform-uploader, .uniform-select") ||
                element.parents().hasClass("input-group")
            ) {
                error.appendTo(element.parent().parent());
            }

            // Input with icons and Select2
            else if (
                element.parents().hasClass("form-group-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            }

            // Input group, styled file input
            else if (
                element.parent().is(".uniform-uploader, .uniform-select") ||
                element.parents().hasClass("input-group")
            ) {
                error.appendTo(element.parent().parent());
            }
            // Other elements
            else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            // for demo
            return false;
        },
        rules: opciones.rules,
        messages: opciones.messages,
    });
};
const RedirigirUrl = (url) => {
    let ruta = basePath + url;
    window.location.href = ruta;
};
const AbrirNuevaPestania = (url) => {
    let ruta = basePath + url;
    window.open(ruta, "_blank");
};
const simpleDataTable = (obj) => {
    let defaults = {
        uniform: false,
        tableNameVariable: "",
        table: "table",
        tableColumnsData: [],
        tableColumns: [],
        tablePaging: true,
        tableOrdering: true,
        tableInfo: true,
        tableLengthChange: true,
        tableHeaderCheck: false,
        tabledrawCallback: "",
        tablerowCallback: "",
        tablefooterCallback: "",
        tablepageLength: 10,
    };
    let opciones = $.extend({}, defaults, obj);
    let objt = "_objetoDatatable";
    this[objt + "_" + opciones.tableNameVariable] = $(opciones.table).DataTable(
        {
            bDestroy: true,
            scrollCollapse: true,
            scrollX: false,
            autoWidth: false,
            bProcessing: true,
            bDeferRender: true,
            paging: opciones.tablePaging,
            ordering: opciones.tableOrdering,
            info: opciones.tableInfo,
            lengthChange: opciones.tableLengthChange,
            data: opciones.tableColumnsData,
            columns: opciones.tableColumns,
            pageLength: opciones.tablepageLength,
            initComplete: function () {
                let api = this.api();
                if (opciones.tableHeaderCheck) {
                    $(api.column(0).header()).html(
                        '<input type="checkbox" name="header_chk_all" data-children="' +
                            opciones.table +
                            '" class="form-check-input-styled-info chk_all text-center">'
                    );
                }
            },
            drawCallback: opciones.tabledrawCallback,
            rowCallback: opciones.tablerowCallback,
            footerCallback: opciones.tablefooterCallback,
        }
    );
};
const CargarDataSelect = (obj) => {
    let objeto = {
        url: null,
        dataForm: [],
        idSelect: null,
        dataId: null,
        dataValor: null,
        valorSelect: null,
        alldata: false,
        alldataTitulo: "Todos",
        disabledall: false,
        withoutplaceholder: false,
        parameter: {},
        callBackSuccess: function () {},
    };
    let options = $.extend({}, objeto, obj);
    $(options.idSelect).empty();
    $(options.idSelect).append('<option value="">Cargando...</option>');
    $.LoadingOverlay("show");

    let token = localStorage.getItem("token");
    axios.defaults.headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
    };
    axios
        .get(basePathApi + options.url, {
            params: options.dataForm,
        })
        .then(function (response) {
            let data = response.data.data;
            if (data.length > 0) {
                if (options.alldata) {
                    $(options.idSelect)
                        .empty()
                        .append(
                            '<option value="0">-- ' +
                                options.alldataTitulo +
                                " --</option>"
                        );
                } else {
                    $(options.idSelect)
                        .empty()
                        .append('<option value="">-- Seleccione --</option>');
                }

                if (options.withoutplaceholder) {
                    $(options.idSelect).empty();
                }

                $.each(data, function (key, value) {
                    let selected =
                        value[options.dataId] === options.valorSelect
                            ? "selected"
                            : "";

                    if (options.disabledall) {
                        if (selected === "") {
                            selected = "disabled";
                        }
                    }

                    let parameterdata = "";
                    if (!Object.keys(options.parameter).length > 0) {
                        parameterdata = "";
                    } else {
                        Object.keys(options.parameter).forEach((item) => {
                            if (value[obj[item]] === obj[item]) {
                                parameterdata +=
                                    item +
                                    '="' +
                                    value[options.parameter[item]] +
                                    '" ';
                            }
                        });
                    }

                    $(options.idSelect).append(
                        "<option " +
                            selected +
                            ' value="' +
                            value[options.dataId] +
                            '" ' +
                            parameterdata +
                            ">" +
                            value[options.dataValor] +
                            "</option>"
                    );
                });
                $(options.idSelect).select2();
            } else {
                $(options.idSelect)
                    .empty()
                    .append(
                        '<option value="">-- No se ha encontrado registros --</option>'
                    );
                $(options.idSelect).select2();
            }
            options.callBackSuccess(data);
        })
        .catch(function (error) {
            $(".modal").modal("hide");
            let statusCode = error.response.status;
            if (statusCode == 401) {
                swal.fire({
                    title: "LA SESIÓN HA EXPIRADO",
                    text: "VUELVA A INICIAR SESIÓN NUEVAMENTE",
                    type: "warning",
                    confirmButtonColor: "#FC8004",
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        $("#modalRenovarSessionToken").modal({
                            backdrop: "static",
                            keyboard: false,
                        });
                    }
                });
            }
        })
        .finally(function () {
            $.LoadingOverlay("hide");
        });
};
const CargarTablaDatatable = (obj) => {
    let defaults = {
        ajaxUrl: null,
        type: "GET",
        ajaxDataSend: {},
        tableColumnsData: [],
        tableColumns: [],
        loader: false,
        tableHeaderCheck: false,
        callBackSuccess: function () {},
    };
    let opciones = $.extend({}, defaults, obj);
    if (opciones.ajaxUrl == null) {
        console.warn("Advertencia - url no fue declarado.");
        return;
    }
    let url = basePathApi + opciones.ajaxUrl;
    let token = localStorage.getItem("token");
    axios.defaults.headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
    };
    $.LoadingOverlay("show");
    axios
        .get(url, { params: opciones.ajaxDataSend })
        .then(function (response) {
            opciones.tableColumnsData = response.data.data;
            simpleDataTable(opciones);
            opciones.callBackSuccess(response.data);
        })
        .catch(function (error) {
            if (error.response != undefined) {
                $(".modal").modal("hide");
                let statusCode = error.response.status;
                if (statusCode == 401) {
                    swal.fire({
                        title: "LA SESIÓN HA EXPIRADO",
                        text: "VUELVA A INICIAR SESIÓN NUEVAMENTE",
                        type: "warning",
                        confirmButtonColor: "#FC8004",
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            $("#modalRenovarSessionToken").modal({
                                backdrop: "static",
                                keyboard: false,
                            });
                        }
                    });
                }
            }
        })
        .finally(function () {
            $.LoadingOverlay("hide");
        });
};
const CargarTablaDatatableData = (obj) => {
    let defaults = {
        tableColumnsData: [],
        tableColumns: [],
    };
    let opciones = $.extend({}, defaults, obj);
    simpleDataTable(opciones);
};
const ShowAlert = (obj) => {
    let defaults = {
        message: null,
        type: null,
        timeOut: 2500,
        progressBar: true,
        closeWith: null,
        modal: false,
        custom_option: {},
    };
    let opciones = $.extend({}, defaults, obj);
    let add_options = {};
    switch (opciones.type) {
        case "success":
            add_options.title = "Excelente";
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
        case "error":
            add_options.title = "Error";
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
        case "warning":
            add_options.title = "Advertencia";
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
        case "info":
            add_options.title = "Para tu información";
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
    }
    let options = {
        text: opciones.message.toUpperCase(),
        type: opciones.type,
    };
    options = Object.assign(add_options, options);
    swal.fire(options);
};
const GenerarExcelMecanico = (objeto) => {
    let obj = {
        data: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
    };
    let opciones = $.extend({}, obj, objeto);

    let ListaHeaders = Object.keys(opciones.data[0]);
    let data = opciones.data;
    let dataForm = {
        table_data: data,
        NombreArchivo: opciones.NombreArchivo,
        ListaHeaders: ListaHeaders,
    };
    let token = localStorage.getItem("token");
    $.LoadingOverlay("show");
    axios.defaults.headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
    };
    axios({
        method: "post",
        url: basePathApi + "GenerarExcelJson",
        data: dataForm,
    })
        .then(function (response) {
            let respuesta = response.data.respuesta;
            if (respuesta === "") {
                ShowAlert({
                    type: "warning",
                    message: respuesta,
                });
            } else {
                let url = basePath + respuesta;
                window.location.href = url;
            }
        })
        .catch(function (error) {
            $(".modal").modal("hide");
            let statusCode = error.response.status;
            if (statusCode == 401) {
                swal.fire({
                    title: "LA SESIÓN HA EXPIRADO",
                    text: "VUELVA A INICIAR SESIÓN NUEVAMENTE",
                    type: "warning",
                    confirmButtonColor: "#FC8004",
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        $("#modalRenovarSessionToken").modal({
                            backdrop: "static",
                            keyboard: false,
                        });
                    }
                });
            }
        })
        .finally(function () {
            $.LoadingOverlay("hide");
        });
};
const GenerarExcel = (objeto) => {
    let obj = {
        tabla: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
        NombreTitulo: objeto.NombreTitulo,
        ColumnasEliminar: [],
        HeadersEliminar: [],
        ListaHeaderCustom: [],
    };
    let opciones = $.extend({}, obj, objeto);

    let ListaHeaders = [];
    let table = $("#" + opciones.tabla).DataTable();
    let data = table.rows({ filter: "applied" }).data().toArray();

    if (opciones.ListaHeaderCustom.length === 0) {
        $("#" + opciones.tabla + " thead th").each(function () {
            ListaHeaders.push($(this).text());
        });
        if (opciones.HeadersEliminar.length > 0) {
            $.each(ListaHeaders, function (key, value) {
                $.each(opciones.HeadersEliminar, function (k, v) {
                    if (value === v) {
                        ListaHeaders.splice(ListaHeaders.indexOf(v), 1);
                    }
                });
            });
        }
    } else {
        ListaHeaders = opciones.ListaHeaderCustom;
    }

    if (opciones.ColumnasEliminar.length > 0) {
        $.each(data, function (key, value) {
            $.each(opciones.ColumnasEliminar, function (k, v) {
                delete value[v];
            });
        });
    }

    let dataForm = {
        table_data: data,
        NombreArchivo: opciones.NombreArchivo,
        ListaHeaders: ListaHeaders,
        NombreTitulo: opciones.NombreTitulo,
    };

    if (data.length > 0) {
        let token = localStorage.getItem("token");
        $.LoadingOverlay("show");
        axios.defaults.headers = {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token,
        };
        axios({
            method: "post",
            url: basePathApi + "GenerarExcelJson",
            data: dataForm,
        })
            .then(function (response) {
                let respuesta = response.data.respuesta;
                if (respuesta === "") {
                    ShowAlert({
                        type: "warning",
                        message: respuesta,
                    });
                } else {
                    let url = basePath + respuesta;
                    window.location.href = url;
                }
            })
            .catch(function (error) {
                $(".modal").modal("hide");
                let statusCode = error.response.status;
                if (statusCode == 401) {
                    swal.fire({
                        title: "LA SESIÓN HA EXPIRADO",
                        text: "VUELVA A INICIAR SESIÓN NUEVAMENTE",
                        type: "warning",
                        confirmButtonColor: "#FC8004",
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            $("#modalRenovarSessionToken").modal({
                                backdrop: "static",
                                keyboard: false,
                            });
                        }
                    });
                }
            })
            .finally(function () {
                $.LoadingOverlay("hide");
            });
    } else {
        ShowAlert({
            type: "warning",
            message: "NO SE ENCONTRARON REGISTROS A EXPORTAR",
        });
    }
};
const RefrescarVentana = () => {
    window.location.reload(true);
};
const EnviarDataPost = (obj) => {
    let defaults = {
        url: null,
        type: "POST",
        data: [],
        refresh: false,
        loader: true,
        limpiarform: "",
        showMessag: true,
        showMessagError: true,
        callBackSuccess: function () {},
        callBackError: function () {},
    };

    let opciones = $.extend({}, defaults, obj);

    if (opciones.url == null) {
        console.warn("Advertencia - url no fue declarado.");
        return;
    }

    let url = basePathApi + opciones.url;
    let token = localStorage.getItem("token");

    if (opciones.loader) {
        $.LoadingOverlay("show");
    }
    axios({
        method: opciones.type,
        url: url,
        data: opciones.data,
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token,
        },
    })
        .then(function (response) {
            let respuesta = response.data.respuesta;
            let mensaje = response.data.mensaje;
            let data = response.data.data;
            if (respuesta) {
                if (opciones.showMessag) {
                    ShowAlert({
                        message: mensaje,
                        type: "success",
                    });
                }
                if (opciones.refresh) {
                    setTimeout(function () {
                        RefrescarVentana();
                    }, 1000);
                }
                if (opciones.limpiarform !== "") {
                    LimpiarFormulario({
                        formulario: opciones.limpiarform,
                    });
                }
                opciones.callBackSuccess(data);
            } else {
                if (opciones.showMessagError) {
                    ShowAlert({
                        message: mensaje,
                        type: "warning",
                    });
                }
                opciones.callBackError(response);
            }
        })
        .catch(function (error) {
            $(".modal").modal("hide");
            let statusCode = error.response.status;
            if (statusCode == 401) {
                let mensaje = error.response.data.mensaje;
                ShowAlert({
                    type: "error",
                    message: mensaje,
                });
            }
        })
        .finally(function () {
            if (opciones.loader) {
                $.LoadingOverlay("hide");
            }
        });
};
const LimpiarFormulario = (obj) => {
    let objeto = {
        formulario: null,
        nameVariable: null,
        callBackSuccess: function () {},
    };
    let options = $.extend({}, objeto, obj);
    $(options.formulario).trigger("reset");
    $(options.formulario + " select")
        .val(null)
        .trigger("change");
    if (options.nameVariable !== null) {
        this["_objetoForm_" + options.nameVariable].resetForm();
    }
};
const CargarDataGET = (obj) => {
    let objeto = {
        url: null,
        dataForm: [],
        callBackSuccess: function () {},
        loading: "",
    };
    let options = $.extend({}, objeto, obj);
    if (options.loading !== "") {
        $(options.loading).LoadingOverlay("show");
    } else {
        $.LoadingOverlay("show");
    }
    let token = localStorage.getItem("token");
    axios.defaults.headers = {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
    };
    axios
        .get(basePathApi + options.url, {
            params: options.dataForm,
        })
        .then(function (response) {
            let data = response.data.data;
            options.callBackSuccess(data);
        })
        .catch(function (error) {
            if (error.response != undefined) {
                $(".modal").modal("hide");
                let statusCode = error.response.status;
                if (statusCode == 401) {
                    swal.fire({
                        title: "LA SESIÓN HA EXPIRADO",
                        text: "VUELVA A INICIAR SESIÓN NUEVAMENTE",
                        type: "warning",
                        confirmButtonColor: "#FC8004",
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            $("#modalRenovarSessionToken").modal({
                                backdrop: "static",
                                keyboard: false,
                            });
                        }
                    });
                }
            }
        })
        .finally(function () {
            if (options.loading !== "") {
                $(options.loading).LoadingOverlay("hide");
            } else {
                $.LoadingOverlay("hide");
            }
        });
};
const GuardarAuditoria = (obj) => {
    let objeto = {
        formulario: "",
        metodo: "",
        dataBeforeSend: {},
        dataAfterSend: {},
    };
    let options = $.extend({}, objeto, obj);

    EnviarDataPost({
        url: "AuditoriaRegistrarJson",
        data: {
            formulario: options.formulario,
            metodo: options.metodo,
            dataBeforeSend: options.dataBeforeSend,
            dataAfterSend: options.dataAfterSend,
        },
        showMessag: false,
        loader: false,
    });
};
const EnviarDataPostWithOutApi = (obj) => {
    let defaults = {
        url: null,
        type: "POST",
        data: [],
        refresh: false,
        loader: true,
        limpiarform: "",
        showMessag: true,
        showMessagError: true,
        callBackSuccess: function () {},
        callBackError: function () {},
    };

    let opciones = $.extend({}, defaults, obj);

    if (opciones.url == null) {
        console.warn("Advertencia - url no fue declarado.");
        return;
    }

    let url = basePath + opciones.url;
    let token = localStorage.getItem("token");

    if (opciones.loader) {
        $.LoadingOverlay("show");
    }
    axios({
        method: opciones.type,
        url: url,
        data: opciones.data,
        headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + token,
        },
    })
        .then(function (response) {
            let respuesta = response.data.respuesta;
            let mensaje = response.data.mensaje;
            let data = response.data.data;
            if (respuesta) {
                if (opciones.showMessag) {
                    ShowAlert({
                        message: mensaje,
                        type: "success",
                    });
                }
                if (opciones.refresh) {
                    setTimeout(function () {
                        RefrescarVentana();
                    }, 1000);
                }
                if (opciones.limpiarform !== "") {
                    LimpiarFormulario({
                        formulario: opciones.limpiarform,
                    });
                }
                opciones.callBackSuccess(data);
            } else {
                if (opciones.showMessagError) {
                    ShowAlert({
                        message: mensaje,
                        type: "error",
                    });
                }
                opciones.callBackError(response);
            }
        })
        .finally(function () {
            if (opciones.loader) {
                $.LoadingOverlay("hide");
            }
        });
};
ValidarFormulario({
    contenedor: "#frmSessionToken",
    nameVariable: "frmSessionToken",
    rules: {
        emailAcceso: { required: true },
        password: { required: true },
    },
    messages: {
        emailAcceso: { required: "El campo es requerido" },
        password: { required: "El campo es requerido" },
    },
});

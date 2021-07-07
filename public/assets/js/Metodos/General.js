basePath = document.location.origin + "/";
basePathApi = document.location.origin + "/api/";

var swalInit = swal.mixin({
    buttonsStyling: false,
    confirmButtonClass: 'btn btn-primary',
    cancelButtonClass: 'btn btn-warning'
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function (xmlHttpRequest, textStatus, errorThrow) {
    },
    statusCode: {
        404: function () {
            ShowAlert({
                message: "No Se encuentra la Direccion.(404)",
                type: "error"
            });
        },
        405: function () {
            ShowAlert({
                message: "Metodo no Permitido.(GET,POST,PUT,DELETE)(405)",
                type: "error"
            });
        },
        500: function () {
            RefrescarVentana();
        }
    }
});
$.extend($.fn.dataTable.defaults, {
    autoWidth: false,
    responsive: true,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        search: '<span>Buscar:</span> _INPUT_',
        searchPlaceholder: '...',
        lengthMenu: '<span>Mostrar:</span> _MENU_',
        paginate: {
            'first': 'First',
            'last': 'Last',
            'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
            'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
        },
        emptyTable: "No hay Data que Mostrar",
        infoEmpty: "Mostrando 0 al 0 de 0 Registros",
        infoFiltered: "(Filtrado de _MAX_ Registro(s))",
        info: "Mostrando _START_ al _END_ de _TOTAL_ Registros",
        loadingRecords: "Cargando...",
        processing: "Procesando...",
        zeroRecords: "No Se encontro Coincidencias"
    }
});
$.fn.serializeFormJSON = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function ValidarFormulario(obj) {
    var defaults = {
        contenedor: null,
        nameVariable: "",
        ignore: 'input[type=hidden], .select2-search__field',
        rules: {},
        messages: {}
    };

    var opciones = $.extend({}, defaults, obj);

    if (opciones.contenedor == null) {
        console.warn('Advertencia - contenedor no esta definido.');
        return;
    }

    var objt = "_objetoForm";
    this[objt + '_' + opciones.nameVariable] = $(opciones.contenedor).validate({
        ignore: opciones.ignore, // ignore hidden fields
        errorClass: 'validation-invalid-label',
        successClass: 'validation-valid-label',
        validClass: 'validation-valid-label',
        // errorClass: 'form-text text-muted',
        // successClass: 'form-text text-muted',
        // validClass: 'form-text text-muted',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        success: function (element, errorClass) {
            // label.addClass('validation-valid-label').text('Correcto.'); // remove to hide Success message
            //$(element).removeClass(label);
            $(element).remove();
        },

        // Different components require proper error label placement
        errorPlacement: function (error, element) {

            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo(element.parents('.form-check').parent());
            }
            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo(element.parent().parent());
            }

            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo(element.parent());
            }

            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo(element.parent().parent());
            }



            // Other elements
            else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) { // for demo
            return false;
        },
        rules: opciones.rules,
        messages: opciones.messages
    });

}

function RedirigirUrl(url) {
    var ruta = basePath + url;
    window.location.href = ruta;
}

function AbrirNuevaPestania(url) {
    var ruta = basePath + url;
    window.open(ruta, "_blank");
}

function simpleDataTable(obj) {

    var defaults = {
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
    };

    var opciones = $.extend({}, defaults, obj);
    var objt = "_objetoDatatable";
    this[objt + '_' + opciones.tableNameVariable] = $(opciones.table).DataTable({
        "bDestroy": true,
        "scrollCollapse": true,
        "scrollX": false,
        "autoWidth": false,
        "bProcessing": true,
        "bDeferRender": true,
        "paging": opciones.tablePaging,
        "ordering": opciones.tableOrdering,
        "info": opciones.tableInfo,
        "lengthChange": opciones.tableLengthChange,
        data: opciones.tableColumnsData,
        columns: opciones.tableColumns,
        "initComplete": function () {
            var api = this.api();
            if (opciones.tableHeaderCheck) {
                $(api.column(0).header()).html('<input type="checkbox" name="header_chk_all" data-children="' + opciones.table + '" class="form-check-input-styled-info chk_all text-center">');
            }
            if (opciones.uniform) {
                if ($('input[type=checkbox]').length > 0) {
                    $('input[type=checkbox]').uniform({
                        wrapperClass: 'border-slate-600 text-slate-800'
                    });
                }
            }
        },
        "drawCallback": opciones.tabledrawCallback,
        "rowCallback": opciones.tablerowCallback,
    });
}

function CargarDataSelect(obj) {
    var objeto = {
        url: null,
        dataForm: [],
        idSelect: null,
        dataId: null,
        dataValor: null,
        valorSelect: null,
        alldata: false,
        disabledall: false,
        withoutplaceholder: false,
        parameter: {},
        callBackSuccess: function () {
        },
    };
    var options = $.extend({}, objeto, obj);
    $(options.idSelect).empty();
    $(options.idSelect).append('<option value="">Cargando...</option>');
    $.LoadingOverlay("show");


    var token = localStorage.getItem('token');
    axios.defaults.headers = {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + token
    };
    axios.get(basePathApi + options.url, {
        params: options.dataForm
    }).then(function (response) {
        var data = response.data.data;
        if (data.length > 0) {
            if (options.alldata) {
                $(options.idSelect).empty().append('<option value="0">-- Todos --</option>');
            } else {
                $(options.idSelect).empty().append('<option value="">-- Seleccione --</option>');
            }

            if (options.withoutplaceholder) {
                $(options.idSelect).empty();
            }

            $.each(data, function (key, value) {
                var selected = value[options.dataId] === options.valorSelect ? "selected" : "";

                if (options.disabledall) {
                    if (selected === "") {
                        selected = 'disabled';
                    }
                }

                var parameterdata = "";
                if (!Object.keys(options.parameter).length > 0) {
                    parameterdata = "";
                } else {
                    Object.keys(options.parameter).forEach(item => {
                        if (value[obj[item]] === obj[item]) {
                            parameterdata += item + '="' + value[options.parameter[item]] + '" ';
                        }
                    });
                }

                $(options.idSelect).append('<option ' + selected + ' value="' + value[options.dataId] + '" ' + parameterdata + '>' + value[options.dataValor] + '</option>');
            });
            $(options.idSelect).select2();
            options.callBackSuccess(data);
        } else {
            $(options.idSelect).empty().append('<option value="">-- No se ha encontrado registros --</option>');
            $(options.idSelect).select2();
        }
    }).finally(function () {
        $.LoadingOverlay("hide");
    });
}

function CargarTablaDatatable(obj) {
    var defaults = {
        ajaxUrl: null,
        type: "GET",
        ajaxDataSend: {},
        tableColumnsData: [],
        tableColumns: [],
        loader: false,
        tableHeaderCheck: false,
        callBackSuccess: function () { },
    };
    var opciones = $.extend({}, defaults, obj);
    if (opciones.ajaxUrl == null) {
        console.warn('Advertencia - url no fue declarado.');
        return;
    }
    var url = basePathApi + opciones.ajaxUrl;
    var token = localStorage.getItem('token');
    axios.defaults.headers = {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + token
    };
    $.LoadingOverlay("show");
    axios.get(url, { params: opciones.ajaxDataSend })
        .then(function (response) {
            opciones.tableColumnsData = response.data.data;
            simpleDataTable(opciones);
            opciones.callBackSuccess(response.data.data);
        }).finally(function () {
            $.LoadingOverlay("hide");
        });
}

function CargarTablaDatatableData(obj) {
    var defaults = {
        tableColumnsData: [],
        tableColumns: [],
    };
    var opciones = $.extend({}, defaults, obj);
    simpleDataTable(opciones);
}

function ShowAlert(obj) {
    var defaults = {
        message: null,
        type: null,
        timeOut: 2500,
        progressBar: true,
        closeWith: null,
        modal: false,
        custom_option: {},
        titleCustom: ''
    };
    var opciones = $.extend({}, defaults, obj);
    var add_options = {};
    switch (opciones.type) {
        case 'success':
            if (opciones.titleCustom != "") {
                add_options.title = opciones.titleCustom;
            } else {
                add_options.title = 'EXCELENTE';
            }
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
        case 'error':
            if (opciones.titleCustom != "") {
                add_options.title = opciones.titleCustom;
            } else {
                add_options.title = 'ERROR';
            }
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
        case 'warning':
            if (opciones.titleCustom != "") {
                add_options.title = opciones.titleCustom;
            } else {
                add_options.title = 'ADVERTENCIA';
            }
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
        case 'info':
            if (opciones.titleCustom != "") {
                add_options.title = opciones.titleCustom;
            } else {
                add_options.title = 'PARA TÚ INFORMACIÓN';
            }
            add_options = Object.assign(add_options, opciones.custom_option);
            break;
    }
    var options = {
        text: opciones.message,
        type: opciones.type,
    };
    options = Object.assign(add_options, options);
    swalInit(options);
}

function GenerarExcelMecanico(objeto) {
    var obj = {
        data: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
    };
    var opciones = $.extend({}, obj, objeto);

    var ListaHeaders = Object.keys(opciones.data[0]);
    var data = opciones.data;
    var dataForm = {
        'table_data': data,
        'NombreArchivo': opciones.NombreArchivo,
        'ListaHeaders': ListaHeaders,
    };
    var token = localStorage.getItem('token');
    $.LoadingOverlay("show");
    axios.defaults.headers = {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + token
    };
    axios({
        method: 'post',
        url: basePathApi + "GenerarExcelJson",
        data: dataForm
    }).then(function (response) {
        var respuesta = response.data.respuesta;
        if (respuesta === "") {
            ShowAlert({
                type: 'warning',
                message: respuesta
            })
        } else {
            var url = basePath + respuesta;
            window.location.href = url;
        }
    }).finally(function () {
        $.LoadingOverlay("hide");
    });
}

function GenerarExcel(objeto) {
    var obj = {
        tabla: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
        NombreTitulo: objeto.NombreTitulo,
        ColumnasEliminar: [],
        HeadersEliminar: [],
        ListaHeaderCustom: [],
    };
    var opciones = $.extend({}, obj, objeto);

    var ListaHeaders = [];
    var table = $('#' + opciones.tabla).DataTable();
    var data = table.rows({ filter: 'applied' }).data().toArray();

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
                delete value[v]
            });
        });
    }


    var dataForm = {
        'table_data': data,
        'NombreArchivo': opciones.NombreArchivo,
        'ListaHeaders': ListaHeaders,
        'NombreTitulo': opciones.NombreTitulo,
    };

    if (data.length > 0) {
        var token = localStorage.getItem('token');
        $.LoadingOverlay("show");
        axios.defaults.headers = {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        };
        axios({
            method: 'post',
            url: basePathApi + "GenerarExcelJson",
            data: dataForm
        }).then(function (response) {
            var respuesta = response.data.respuesta;
            if (respuesta === "") {
                ShowAlert({
                    type: 'warning',
                    message: respuesta
                })
            } else {
                var url = basePath + respuesta;
                window.location.href = url;
            }
        }).finally(function () {
            $.LoadingOverlay("hide");
        });
    } else {
        ShowAlert({
            type: 'warning',
            message: 'NO SE ENCONTRARON REGISTROS A EXPORTAR'
        })
    }
}
// GenerarExcelVentasDetalleJson
function GenerarExcelVentaDetalle(objeto) {
    var obj = {
        tabla: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
        NombreTitulo: objeto.NombreTitulo,
        ColumnasEliminar: [],
        HeadersEliminar: [],
        ListaHeaderCustom: [],
    };
    var opciones = $.extend({}, obj, objeto);

    var ListaHeaders = [];
    var table = $('#' + opciones.tabla).DataTable();
    var data = table.rows({ filter: 'applied' }).data().toArray();

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
                delete value[v]
            });
        });
    }


    var dataForm = {
        'table_data': data,
        'NombreArchivo': opciones.NombreArchivo,
        'ListaHeaders': ListaHeaders,
        'NombreTitulo': opciones.NombreTitulo,
    };

    // console.log(dataForm);

    if (data.length > 0) {
        var token = localStorage.getItem('token');
        $.LoadingOverlay("show");
        axios.defaults.headers = {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        };
        axios({
            method: 'post',
            url: basePathApi + "GenerarExcelVentasDetalleJson",
            data: dataForm
        }).then(function (response) {
            var respuesta = response.data.respuesta;
            if (respuesta === "") {
                ShowAlert({
                    type: 'warning',
                    message: respuesta
                })
            } else {
                var url = basePath + respuesta;
                window.location.href = url;
            }
        }).finally(function () {
            $.LoadingOverlay("hide");
        });
    } else {
        ShowAlert({
            type: 'warning',
            message: 'NO SE ENCONTRARON REGISTROS A EXPORTAR'
        })
    }
}
function GenerarExcelDetalle(objeto) {
    var obj = {
        tabla: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
        NombreTitulo: objeto.NombreTitulo,
        ColumnasEliminar: [],
        HeadersEliminar: [],
        ListaHeaderCustom: [],
        fechaFinal: ''
    };
    var opciones = $.extend({}, obj, objeto);

    var ListaHeaders = [];
    var table = $('#' + opciones.tabla).DataTable();
    var data = table.rows({ filter: 'applied' }).data().toArray();

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
                delete value[v]
            });
        });
    }


    var dataForm = {
        'table_data': data,
        'NombreArchivo': opciones.NombreArchivo,
        'ListaHeaders': ListaHeaders,
        'NombreTitulo': opciones.NombreTitulo,
        'fechaFinal': opciones.fechaFinal
    };

    // console.log(dataForm);

    if (data.length > 0) {
        var token = localStorage.getItem('token');
        $.LoadingOverlay("show");
        axios.defaults.headers = {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        };
        axios({
            method: 'post',
            url: basePathApi + "GenerarExcelDetalleJson",
            data: dataForm
        }).then(function (response) {
            var respuesta = response.data.respuesta;
            if (respuesta === "") {
                ShowAlert({
                    type: 'warning',
                    message: respuesta
                })
            } else {
                var url = basePath + respuesta;
                window.location.href = url;
            }
        }).finally(function () {
            $.LoadingOverlay("hide");
        });
    } else {
        ShowAlert({
            type: 'warning',
            message: 'NO SE ENCONTRARON REGISTROS A EXPORTAR'
        })
    }
}

function GenerarExcelLoteDetalle(objeto) {
    var obj = {
        tabla: objeto.tabla,
        NombreArchivo: objeto.NombreArchivo,
        NombreTitulo: objeto.NombreTitulo,
        ColumnasEliminar: [],
        HeadersEliminar: [],
        ListaHeaderCustom: [],
    };
    var opciones = $.extend({}, obj, objeto);

    var ListaHeaders = [];
    var table = $('#' + opciones.tabla).DataTable();
    var data = table.rows({ filter: 'applied' }).data().toArray();

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
                delete value[v]
            });
        });
    }


    var dataForm = {
        'table_data': data,
        'NombreArchivo': opciones.NombreArchivo,
        'ListaHeaders': ListaHeaders,
        'NombreTitulo': opciones.NombreTitulo,
    };

    if (data.length > 0) {
        var token = localStorage.getItem('token');
        $.LoadingOverlay("show");
        axios.defaults.headers = {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        };
        axios({
            method: 'post',
            url: basePathApi + "GenerarExcelLoteDetalleJson",
            data: dataForm
        }).then(function (response) {
            var respuesta = response.data.respuesta;
            if (respuesta === "") {
                ShowAlert({
                    type: 'warning',
                    message: respuesta
                })
            } else {
                var url = basePath + respuesta;
                window.location.href = url;
            }
        }).finally(function () {
            $.LoadingOverlay("hide");
        });
    } else {
        ShowAlert({
            type: 'warning',
            message: 'NO SE ENCONTRARON REGISTROS A EXPORTAR'
        })
    }
}

function RefrescarVentana() {
    window.location.reload(true);
}

function EnviarDataPost(obj) {
    var defaults = {
        url: null,
        type: "POST",
        data: [],
        refresh: false,
        loader: false,
        limpiarform: "",
        showMessag: true,
        showMessagError: true,
        callBackSuccess: function () {
        },
        callBackError: function () {
        }
    };

    var opciones = $.extend({}, defaults, obj);

    if (opciones.url == null) {
        console.warn('Advertencia - url no fue declarado.');
        return;
    }
    if (opciones.loader) {
        $.LoadingOverlay("show");
    }

    var url = basePathApi + opciones.url;
    var token = localStorage.getItem('token');
    // axios.defaults.headers = {
    //     'Content-Type': 'application/json',
    //     Authorization: 'Bearer ' + token
    // };
    axios({
        method: opciones.type,
        url: url,
        data: opciones.data,
        headers: {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
        }
    }).then(function (response) {
        var respuesta = response.data.respuesta;
        var mensaje = response.data.mensaje;
        var data = response.data.data;
        if (respuesta) {
            if (opciones.showMessag) {
                ShowAlert({
                    message: mensaje,
                    type: "success"
                });
            }
            if (opciones.refresh) {
                setTimeout(function () {
                    RefrescarVentana()
                }, 1000);
            }
            if (opciones.limpiarform !== "") {
                LimpiarFormulario({
                    formulario: opciones.limpiarform
                });
            }
            opciones.callBackSuccess(data);
        } else {
            if (opciones.showMessagError) {
                ShowAlert({
                    message: mensaje,
                    type: "error"
                });
            }
            opciones.callBackError(data);
        }
    }).finally(function () {
        if (opciones.loader) {
            $.LoadingOverlay("hide");
        }
    });
}

function EnviarDataFilePost(obj) {
    var objeto = {
        url: null,
        dataForm: [],
        callBackSuccess: function () {
        },
    };
    var options = $.extend({}, objeto, obj);

    $.ajax({
        async: false,
        type: 'POST',
        url: options.url,
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        data: options.dataForm,
        dataType: "json",
        beforeSend: function () {
            $.LoadingOverlay("show");
        },
        complete: function () {
            $.LoadingOverlay("hide");
        },
        success: function (response) {
            var respuesta = response.respuesta;
            var mensaje = response.mensaje;
            var data = response.data;
            if (respuesta === true) {
                ShowAlert({
                    message: mensaje,
                    type: "success"
                });
            } else {
                ShowAlert({
                    message: mensaje,
                    type: "warning"
                });
            }
            options.callBackSuccess(data);
        }
    });
}

function LimpiarFormulario(obj) {
    var objeto = {
        formulario: null,
        callBackSuccess: function () {
        },
    };
    var options = $.extend({}, objeto, obj);
    $(options.formulario).trigger('reset');
    $(options.formulario + ' select').val(null).trigger("change");
}

function CargarDataGET(obj) {
    var objeto = {
        url: null,
        dataForm: [],
        loader: true,
        callBackSuccess: function () {
        },
    };
    var options = $.extend({}, objeto, obj);


    if (options.loader) {
        $.LoadingOverlay("show");
    }
    var token = localStorage.getItem('token');
    axios.defaults.headers = {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + token
    };
    axios.get(basePathApi + options.url, {
        params: options.dataForm
    }).then(function (response) {
        var data = response.data.data;
        options.callBackSuccess(data);
    }).finally(function () {
        if (options.loader) {
            $.LoadingOverlay("hide");
        }
    });
}

$(document).on('click', '.btnRecargar', function () {
    RefrescarVentana();
});
$("select").on("select2:close", function (e) {
    $(this).valid();
});

function ParsearJson(_) {
    return _;
}

basePath = document.location.origin + "/";

$.fn.serializeFormJSON = function () {
    var o = {};
    var a = this.serializeArray();
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

function ValidarFormulario(obj) {
    var defaults = {
        contenedor: null,
        nameVariable: "",
        ignore: "input[type=hidden], .select2-search__field",
        rules: {},
        messages: {},
    };

    var opciones = $.extend({}, defaults, obj);

    if (opciones.contenedor == null) {
        console.warn("Advertencia - contenedor no esta definido.");
        return;
    }

    var objt = "_objetoForm";
    this[objt + "_" + opciones.nameVariable] = $(opciones.contenedor).validate({
        ignore: opciones.ignore, // ignore hidden fields
        errorClass: "validation-invalid-label",
        successClass: "validation-valid-label",
        validClass: "validation-valid-label",
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
    };
    var opciones = $.extend({}, defaults, obj);
    var add_options = {};
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
    var options = {
        text: opciones.message.toUpperCase(),
        type: opciones.type,
    };
    options = Object.assign(add_options, options);
    swal.fire(options);
}
const RefrescarVentana = () => {
    window.location.reload(true);
};
function EnviarDataPost(obj) {
    var defaults = {
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

    var opciones = $.extend({}, defaults, obj);

    if (opciones.url == null) {
        console.warn("Advertencia - url no fue declarado.");
        return;
    }

    var url = basePath + opciones.url;
    var token = localStorage.getItem("token");

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
            var respuesta = response.data.respuesta;
            var mensaje = response.data.mensaje;
            var data = response.data.data;
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
}
function RedirigirUrl(url) {
    var ruta = basePath + url;
    window.location.href = ruta;
}

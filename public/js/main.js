function sanitize(str) {
    var domainFormat = new RegExp(/^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/);
    return str.match(domainFormat);
}

function showError(str) {
    $(".alert-danger").empty();
    $(".alert-danger").append(str);
    $(".alert-danger").show();
}

function cleanScreen() {
    $(".alert-danger").hide();
    $(".list-group").empty();
}

function formValidation(form) {
    var fieldsValid = true;
    var required = form.find("[required]");
    var maxlength = form.find("[maxlength]");
    $(required).each(function () {
        if ($(this).val() == '') {
            if (!$(this).next().is('.red')) {
                $(this).after('<div class="alert alert-danger red"> This Field is Required</div>');
            }
            $(this).focus();
            fieldsValid = false;
        } else {
            $(this).next('.red').remove();
        }
    });
    return fieldsValid;
}

function dnsResolution(data) {
    var ipFormat = new RegExp(/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/);
    $.each(data, function (index, value) {
        var badge = ((value.ip.match(ipFormat)) ? "badge-primary " : "badge-danger");
        $(".list-group").append('<li class="list-group-item d-flex justify-content-between align-items-center">' + value.domain + '<span class="badge ' + badge + '">' + value.ip + '</span></li>');
    });
}


$(document).ready(function () {
    $("#btnClick").click(function () {
        cleanScreen();
        if (!formValidation($("#frmSearch"))) {
            return false;
        }

        var res = $("#domains").val().split(",");
        for (var i = 0; i < res.length; i++) {
            if (!sanitize(res[i])) {
                showError("The following domain " + res[i] + " doesn't have the required format. Please correct it and click on CHECK DOMAIN again");
                return false;
            }
        }

        $.ajax({
            type: "POST",
            url: "/api/ping",
            data: {domains: $("#domains").val()},
            async: false,
            dataType: "json",
            success: function (data) {
                dnsResolution(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var message = JSON.parse(xhr.responseText);
                console.log(message.error.message);
                showError(message.error.message);
            }
        });
        return false;
    });

});

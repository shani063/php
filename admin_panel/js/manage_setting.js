jQuery(function () {
    get_data();
});

async function get_data(data) {
    if (data && data != null && data.success == true) {
        var { company_name, company_email, admin_email, admin_email_password, contact1, contact2, address, upi, pixel } = data.data;
        $('#cmp_name').val(company_name);
        $('#cmp_email').val(company_email);
        $('#admin_email').val(admin_email);
        $('#admin_email_password').val(admin_email_password);
        $('#contact1').val(contact1);
        $('#contact2').val(contact2);
        $('#address').val(address);
        $('#upi').val(upi);
        $('#pixel').val(pixel);
        hideLoading();
        return false;
    }
    else if (data && data != null && data.success == false) {
        hideLoading();
        showError(data.message);
        return false;
    }
    else if (!data) {
        var req_data = {
            op: "get_settings"
        };
        doAPICall(req_data, get_data);
    }
    return false;
}

$("#" + FORM_NAME).on('submit', function (e) {
    e.preventDefault();
    add_record();
});

async function add_record(data) {
    if (data && data != null && data.success == true) {
        showMessage(data.message);
        hideLoading();
        return false;
    }
    else if (data && data != null && data.success == false) {
        hideLoading();
        showError(data.message);
        return false;
    }
    else if (!data) {
        if ($('#cmp_name').val() == "") {
            showError("Please enter company name");
            hideLoading();
            return false;
        }
        else if ($('#cmp_email').val() == "") {
            showError("Please enter company email");
            hideLoading();
            return false;
        }
        else if ($('#admin_email').val() == "") {
            showError("Please enter admin email");
            hideLoading();
            return false;
        }
        else if ($('#admin_email_password').val() == "") {
            showError("Please enter admin password");
            hideLoading();
            return false;
        }
        else if ($('#contact1').val() == "") {
            showError("Please enter contact1");
            hideLoading();
            return false;
        }
        else if ($('#contact2').val() == "") {
            showError("Please enter contact2");
            hideLoading();
            return false;
        }
        else if ($('#address').val() == "") {
            showError("Please enter address");
            hideLoading();
            return false;
        }
        else if ($('#upi').val() == "") {
            showError("Please enter UPI ID");
            hideLoading();
            return false;
        }

        showLoading();
        var req_data = {
            op: "update_settings"
            , cmp_name: $('#cmp_name').val()
            , cmp_email: $('#cmp_email').val()
            , admin_email: $('#admin_email').val()
            , admin_email_password: $('#admin_email_password').val()
            , contact1: $('#contact1').val()
            , contact2: $('#contact2').val()
            , address: $('#address').val()
            , upi: $('#upi').val()
            , pixel: $('#pixel').val()
        };
        doAPICall(req_data, add_record);
    }
    return false;
}

function edit_record(id, source, container, name) {
    $('#id').val(id);
    $('#source').val(source).trigger('change');
    $('#container').val(container).trigger('change');
    $('#name').val(name);
    changeView('form');
}

function delete_record(id) {
    PRIMARY_ID = id;
    $("#delete_modal").modal('show');
}

async function delete_current_record(data) {

    if (data && data != null && data.success == true) {
        hideLoading();
        PRIMARY_ID = 0;
        showMessage(data.message);
        await table.clearPipeline().draw();
        return false;
    }
    else if (data && data != null && data.success == false) {
        hideLoading();
        PRIMARY_ID = 0;
        showError(data.message);
        return false;
    }
    else if (!data) {
        showLoading();
        var req_data = {
            op: "delete_record"
            , id: PRIMARY_ID
            , type: 'SHIPPING_LINE'
        };
        doAPICall(req_data, delete_current_record);
    }
    return false;
}

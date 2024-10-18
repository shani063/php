var table;
var SUBPRIMARYID = 0;
jQuery(function () {
    get_data();
    $("#" + FORMNAME).on('submit', function (e) {
        e.preventDefault();
        add_record();
    });
});

function get_data() {
    table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        fixedHeader: true,
        pagingType: "full_numbers",
        responsive: !0,
        language: { paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" } },
        drawCallback: function () { $(".dataTables_paginate > .pagination").addClass("pagination-rounded") },
        ajax: $.fn.dataTable.pipeline({
            url: API_SERVICE_URL,
            pages: 1, // number of pages to cache
            op: "get_products"
        }),
        columns: [
            { data: 'id', name: 'id', "width": "0%", className: "d-none" },
            {
                data: null,
                render: function (data, type, row) {
                    var details = "<img style='height: 60px;' src='" + row.img1 + "'>";
                    return details;
                }, name: 'img1'
            },
            { data: 'name', name: 'name' },
        ],
        "columnDefs": [{
            "targets": 3,
            "className": "text-end",
            "data": "id",
            "render": function (data, type, row, meta) {
                return type === 'display' ?
                    '<input type="text" class="form-control w-25 d-inline me-2" id="order' + row.id + '" value="' + row.disp_order + '" placeholder="Order" />\
                    <button class="btn btn-primary rounded-pill tbl-btn" onclick="update_order(null,' + row.id + ')"><i class="ri-arrow-up-down-line"></i></button>' : "";
            }
        }, {
            "targets": 4,
            "className": "text-end",
            "data": "id",
            "render": function (data, type, row, meta) {
                return type === 'display' ?
                    '<button class="btn btn-info rounded-pill tbl-btn" onclick="view_verient(' + meta.row + ')"><i class="uil-layer-group"></i></button>\
                    <button class="btn btn-danger rounded-pill tbl-btn" onclick="delete_record(' + row.id + ')"><i class="uil-trash-alt"></i></button>' : "";
            }
        }]
    });
}

async function add_record(data) {
    if (data && data != null && data.success == true) {
        changeView('details');
        showMessage(data.message);
        resetValidation(FORMNAME);
        hideLoading();
        await table.clearPipeline().draw();
        return false;
    }
    else if (data && data != null && data.success == false) {
        hideLoading();
        showError(data.message);
        return false;
    }
    else if (!data) {
        if ($('#type').val() == "") {
            showError("Please select type");
            hideLoading();
            return false;
        }
        showLoading();
        var req_data = {
            op: "add_port_verients"
            , type: $('#type').val()
            , id: $('#id').val()
        };
        doAPICall(req_data, add_record);
    }
    return false;
}

function edit_record(index) {
    if (TBLDATA.length > 0) {
        CURRENT_DATA = TBLDATA[index];
        $('#id').val(CURRENT_DATA.id);
        $('#container_type').val(CURRENT_DATA.type);
        changeView('form');
    }
}

async function update_order(data, id) {
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
            op: "update_product_order"
            , disp_order: $("#order" + id).val()
            , id: id
        };
        doAPICall(req_data, function (res) { update_order(res, id) });
    }
    return false;
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
        if (SUBPRIMARYID > 0) {
            $("#verientListModal #rate_list #item" + SUBPRIMARYID).remove();
            SUBPRIMARYID = 0;
        }
        else {
            await table.clearPipeline().draw();
        }
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
            , id: (SUBPRIMARYID > 0) ? SUBPRIMARYID : PRIMARY_ID
            , type: (SUBPRIMARYID > 0) ? 'VERIENT' : 'PRODUCT'
        };
        doAPICall(req_data, delete_current_record);
    }
    return false;
}

//Modal
async function view_verient(index) {
    if (TBLDATA.length > 0) {
        CURRENT_DATA = TBLDATA[index];
        var current_index = $("#rate_list .index-rows").length;
        var html = '<table class="table table-striped"><tbody>';
        $.each(CURRENT_DATA.verients, function (i, verients) {
            html += `<tr class="index-rows" id="row${current_index}">
                            <td><image src="${verients.img1}" style="height: 70px;" onclick="show_photo(${verients.id})"/></td>
                            <td>${verients.color}</td>
                            <td>${verients.size}</td>
                            <td>${verients.storage}</td>
                            <td>MRP: ${verients.mrp}<br/>selling price: ${verients.selling_price}</td>
                            <td>${verients.fetaures}</td>
                            <td>
                                <button class="btn btn-danger rounded-pill delete-btn mt-1" onclick="delete_verients(${current_index})"><i class="uil-trash-alt"></i></button>
                            </td>
                        </tr>`;
        });
        html += '</tbody></table>';
        await $("#verient_list").html(html);
    }
    manage_delete_btn();
    $("#verientListModal").modal('show');
}

function show_photo(verient_id) {
    var verient = CURRENT_DATA['verients'][verient_id];
    var html = "";
    html += `<div class="carousel-item active">
        <img class="d-block img-fluid m-auto" style="height: 400px;" src="${verient.img1}">
    </div>`;
    var control_html = `<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>`;
    if (verient.img2) {
        html += `<div class="carousel-item">
            <img class="d-block img-fluid m-auto" style="height: 400px;" src="${verient.img2}">
        </div>`;
        control_html += `<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>`;
    }
    if (verient.img3) {
        html += `<div class="carousel-item">
            <img class="d-block img-fluid m-auto" style="height: 400px;" src="${verient.img3}">
        </div>`;
        control_html += `<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>`;
    }
    if (verient.img4) {
        html += `<div class="carousel-item">
            <img class="d-block img-fluid m-auto" style="height: 400px;" src="${verient.img4}">
        </div>`;
        control_html += `<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"></li>`;
    }
    if (verient.img5) {
        html += `<div class="carousel-item">
            <img class="d-block img-fluid m-auto" style="height: 400px;" src="${verient.img5}">
        </div>`;
        control_html += `<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"></li>`;
    }
    $("#imageModal #verient_img").html(html);
    $("#imageModal #slider_control").html(control_html);
    $("#imageModal").modal("show");
}

function manage_delete_btn() {
    var current_index = $("#verient_list .index-rows").length;
    if (current_index <= 1) {
        $("#verient_list .index-rows .delete-btn").addClass('hide');
    }
    else {
        $("#verient_list .index-rows .delete-btn").removeClass('hide');
    }

    // Manage index of the index-rows
    $("#verient_list .index-rows").each(function (index, tr) {
        $(this).attr('id', 'row' + index);
        $(this).find('.delete-btn').attr('onclick', `delete_verients(${index})`);
        $(this).find('.price_type').attr('id', `price_type${index}`);
        $(this).find('.price_type_label').attr('for', `price_type${index}`);
    })
}


async function delete_verients(id) {
    SUBPRIMARYID = id;
    $("#delete_modal").modal('show');
}


// Upload CSV

function uploadCSV() {
    $("#csvModal").modal('show');
}

async function upload_csv() {
    var formData = new FormData()
    formData.append('op', 'upload_csv')
    formData.append('file', $('#csv_file')[0].files[0])
    showLoading();
    $.ajax({
        type: "POST",
        url: API_SERVICE_URL,
        data: formData,
        dataType: 'json',
        "crossDomain": true,
        "headers": {},
        processData: false,
        contentType: false,
        success: async function (data) {
            if (data && data.success == 1) {
                showMessage(data.message);
                await table.clearPipeline().draw();
            }
            else {
                showError(data.message);
            }
            hideLoading();
            return false;
        },
        fail: function (err) {
            hideLoading();
            showError(data.message);
            return false;
        }
    });
}
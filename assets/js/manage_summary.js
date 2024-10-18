$(document).ready(function () {
    $("#back_btn").on("click", function () {
        history.back();
    });
    var selected_verient = localStorage.getItem("selected_verient");
    itemData = JSON.parse(selected_verient);
    $("#item_image").prop('src', itemData.img1);
    var detail = ((itemData.color) ? itemData.color : '') + ((itemData.size) ? ' (' + itemData.size + ')' : '') + ((itemData.storage) ? ' (' + itemData.storage + ')' : '');
    $("#product-title").html(itemData.name);
    $("#product-detail").html(detail);
    $(".selling_price, .payable").html("&#8377;" + itemData.selling_price);
    $(".mrp").html("&#8377;" + itemData.mrp);

    var disc_amt = itemData.mrp - itemData.selling_price;
    $(".discount-amt").html("-&#8377;" + disc_amt);

    var disc = ((itemData.selling_price * 100) / itemData.mrp).toFixed(0);
    $(".discount").html(disc + "% off");

    var add = localStorage.getItem("address");
    var address = JSON.parse(add);
    if (address) {
        $(".customer-name").html(address.name);
        $(".customer-address").html(address.flat + ", " + address.area + ", " + address.city + ", " + address.state + " " + address.pin);
        $(".customer-contact").html(address.number);
    }
});

function btnContinue() {
    window.location.href = MAIN_URL + "payment";
}
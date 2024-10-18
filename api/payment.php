<?php
include 'header.php';
$include_javscript_at_bottom = '<script defer src="' . ROOT_URL . 'assets/js/manage_payment.js"></script>';
?>
<div class="container-fluid py-2 header-container">
    <div class="row header">
        <div class="col-1">
            <div class="menu-icon" id="back_btn">
                <?php include 'assets/images/theme/back_dark.svg' ?>
            </div>
        </div>
        <div class="col-8">
            <div class="menu-logo">
                <h4 class="mb-0 mt-1 ms-2">Payments</h4>
            </div>
        </div>
    </div>
</div>

<div class="_1fhgRH mb-70">
    <div class="card pt-3">
        <div class="progress-box mb-0">
            <?php include 'assets/images/theme/progress-indicator-payments.svg' ?>
        </div>
    </div>
    <img class="mobi-only" src="<?php echo ROOT_URL; ?>img/slides/upi2.png" style="width:100%">
    <img class="desk-only" src="<?php echo ROOT_URL; ?>img/slides/upi.png" style="width:100%">
    <div class="card py-1 my-1">
        <div class="py-2 px-3">
            <div class="container-fluid px-0 offerend-container">
                <h4> Offer ends in <span class="offer-timer" id="offerend-time"></span>
            </div>
    <img class="mobi-only" src="<?php echo ROOT_URL; ?>assets/images/phonediscount.png" style="width:100%">
    <img class="desk-only" src="<?php echo ROOT_URL; ?>assets/images/phonediscount.png" style="width:100%">
    <img class="mobi-only" src="<?php echo ROOT_URL; ?>assets/images/paytmdiscount.png" style="width:100%">
    <img class="desk-only" src="<?php echo ROOT_URL; ?>assets/images/paytmdiscount.png" style="width:100%">
            <div class="form-check available-method active" pay-type="gpay">
            <label class="form-check-label">
               <img src="<?php echo ROOT_URL ?>assets/images/gpay_icon.svg" class="pay-logo" alt="button">
             <span class="unaviablee">Google Pay</span>
               </label>
            </div>
            <div class="form-check available-method" pay-type="phonepe">
                <label class="form-check-label">
                    <img src="<?php echo ROOT_URL ?>assets/images/phonepe.svg" class="pay-logo" alt="button">
                    <span class="unaviablee">PhonePe</span>
                </label>
            </div>
            <div class="form-check available-method " pay-type="paytm">
                <label class="form-check-label">
                    <img src="<?php echo ROOT_URL ?>assets/images/paytm_icon.svg" class="pay-logo" alt="button">
                    <span class="unaviablee">Paytm</span>
                </label>
            </div>
            <div class="form-check available-method" pay-type="bhim_upi">
                <label class="form-check-label">
                    <img src="<?php echo ROOT_URL ?>assets/images/bhim_upi.svg" class="pay-logo" alt="button">
                    <span class="unaviablee">BHIM UPI</span>
                </label>
            </div>
            <div class="form-check available-method" pay-type="whatspp_pay">
                <label class="form-check-label">
                    <img src="<?php echo ROOT_URL ?>assets/images/whatspp_pay.svg" class="pay-logo" alt="button">
                    <span class="unaviablee">All Other UPI</span>
                </label>
            </div>
        </div>
    </div>
    <div class="card px-3 py-4 mb-2" id="price-detail">
        <h3>Price Details</h3>
        <div class="price-detail-div mt-2">
            <div class="product-price-list my-3">
                <span class="title">Price (1 item)</span>
                <span class="data selling_price me-0 td-none">&#8377;4999</span>
            </div>
            <div class="product-price-list my-3">
                <span class="title">Delivery Charges</span>
                <span class="data text-success">FREE </span>
            </div>
            <div class="product-price-list mt-3 pt-3 total">
                <span class="title">Amount Payable</span>
                <span class="data selling_price">&#8377;99</span>
            </div>
        </div>
    </div>
    <div class="svg-100">
        <?php include 'assets/images/theme/safety-label.svg' ?>
    </div>
    <img src="<?php echo ROOT_URL ?>assets/images/theme/safety-label-badge.jpg" class="w-100" alt="button">
    <div class="button-container flex p-3 bg-white">
        <div class="col-6 footer-price">
            <span class="strike mrp ms-0 mb-1" id="mrp">&#8377;4999</span>
            <span class="selling_price" id="selling_price">&#8377;99</span>
        </div>
        <button class="buynow-button product-page-buy col-6 btn-continue" onclick="payNow()">
            Order Now
        </button>
    </div>
</div>

<?php include 'footer.php' ?>
<?php
include 'header.php';
$include_javscript_at_bottom = '<script defer src="' . ROOT_URL . 'assets/js/manage_summary.js"></script>';
?>
<style>
    .form-control {
        margin-bottom: 16px;
    }

    .card {
        --bs-border-color-translucent: rgba(0, 0, 0, 0) !important;
    }

    .card.max-height {
        height: calc(100vh - 42px) !important;
    }

    .card-footer {
        background: none !important;
        border-top: none !important;
        position: absolute;
        bottom: 0;
        width: 90%;
        left: 5%;
    }
</style>
<div class="container-fluid p-3 header-container">
    <div class="row header">
        <div class="col-1">
            <div class="menu-icon" id="back_btn">
                <?php include 'assets/images/theme/back_dark.svg' ?>
            </div>
        </div>
        <div class="col-8">
            <div class="menu-logo">
                <h4 class="mb-0 mt-1 ms-2">Order Summary</h4>
            </div>
        </div>
    </div>
</div>
<div class="_1fhgRH max-height mb-70">
    <div class="card pt-1 mb-1">
        <div class="progress-box mb-0">
            <img class="w-100" src="assets/images/theme/progress-indicator-summary.png" />
        </div>
    </div>
    <div class="card px-3 py-4 mb-2">
        <h3>Delivered to:</h3>
        <div class="address-div mt-2">
            <h4 class="customer-name">Parth</h4>
            <div class="mb-2 customer-address">G-12, Sai Home Decor, Sarthana, Surat 395006</div>
            <div class="customer-contact">9537298745</div>
        </div>
    </div>
    <div class="card px-3 py-4 mb-2">
        <ul class="list-group list-group-flush" id="deals">
            <li class="list-group-item px-0" data-timer="2000">
                <div class="flex recommended-product">
                    <img src="" id="item_image" />
                    <div class="description">
                        <div class="product-title mb-1" id="product-title"></div>
                        <div class="product-detail mb-1" id="product-detail"></div>
                        <img src="<?php echo ROOT_URL; ?>img/SwOvZ3r.png" width="77px">
                    </div>
                </div>
                <div class="flex recommended-product mt-3">
                    <div class="timer qty mx-4">
                        Qty: 1
                    </div>
                    <div class="description">
                        <div class="price flex">
                            <span class="discount" id="discount">12% off</span>
                            &nbsp;&nbsp;
                            <span class="strike mrp" id="mrp">&#8377;4999</span>
                            &nbsp;&nbsp;
                            <span class="selling_price" id="selling_price">&#8377;99</span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="card px-3 py-4 mb-2" id="price-detail">
        <h3>Price Details</h3>
        <div class="price-detail-div mt-2">
            <div class="product-price-list my-3">
                <span class="title">Price (1 item)</span>
                <span class="data mrp me-0 td-none">&#8377;4999</span>
            </div>
            <div class="product-price-list my-3">
                <span class="title">Discount</span>
                <span class="data discount-amt text-success">-&#8377;4999</span>
            </div>
            <div class="product-price-list my-3">
                <span class="title">Delivery Charges</span>
                <span class="data text-success">FREE Delivery </span>
            </div>
            <div class="product-price-list my-3 pt-3 total">
                <span class="title">Total Amount </span>
                <span class="data selling_price">&#8377;99</span>
            </div>
            <div class="product-price-list mt-3 pt-3 saved-div">
                <span class="text-success">You will save <span class="discount-amt">₹4,500</span> on this order</span>
            </div>
        </div>
    </div>
    <div class="sefty-banner">
        <img class="sefty-img" src="https://rukminim1.flixcart.com/www/60/70/promos/13/02/2019/9b179a8a-a0e2-497b-bd44-20aa733dc0ec.png?q=90" loading="lazy" alt="">
        <div dir="auto" class="sefty-txt">Safe and secure payments. Easy returns. 100% Authentic products.</div>
    </div>
    <div class="button-container flex p-3 bg-white">
        <div class="col-6 footer-price">
            <span class="strike mrp ms-0 mb-1" id="mrp">&#8377;4999</span>
            <span class="selling_price" id="selling_price">&#8377;99</span>
        </div>
        <button class="buynow-button product-page-buy col-6 btn-continue" onclick="btnContinue();">
            Continue
        </button>
    </div>
</div>

<?php include 'footer.php' ?>
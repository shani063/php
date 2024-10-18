<?php
include 'header.php';
$include_javscript_at_bottom = '<script defer src="' . ROOT_URL . 'assets/js/manage_product.js"></script>';
?>
<div class="container-fluid py-2 header-container" style="background-color:#2874f0">
    <div class="row header">
        <div class="col-1">
            <div class="menu-icon" id="back_btn">
                <?php include 'assets/images/theme/back.svg' ?>
            </div>
        </div>
        <div class="col-2">
            <div class="menu-logo">
                <img alt="menu" src="<?php echo ROOT_URL; ?>img/Q18Ifxk.png" height="30px">
            </div>
        </div>
        <div class="col-6">
        </div>
        <div class="col-1">
            <div class="menu-icon">
                <?php include 'assets/images/theme/search.svg' ?>
            </div>
        </div>
        <div class="col-1">
            <div class="menu-icon">
                <?php include 'assets/images/theme/cart.svg' ?>
            </div>
        </div>
    </div>
</div>

<div class="_1fhgRH mb-5">
    <div class="container p-1 card">
        <div class="container-fluid px-0 product-slider">
            <div class="love-icon"></div>
            <div class="share-icon"></div>
            <div id="sliderX" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#sliderX" data-slide-to="0" class="active"></li>
                    <li data-target="#sliderX" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="<?php echo ROOT_URL; ?>img/back.png" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="<?php echo ROOT_URL; ?>files/12.jpg" alt="Second slide">
                    </div>
                </div>
            </div>
            <div class="color-div">
                <h4>Select Color</h4>
                <div class="color-list p-2">

                </div>
            </div>
            <div class="storage-div mt-3">
                <h4>Select Storage</h4>
                <div class="storage-list p-2">

                </div>
            </div>
            <div class="size-div mt-3">
                <h4>Select Size</h4>
                <div class="size-list p-2">

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid p-3 mt-1 card">
        <div class="product-title">
            OnePlus 10 Pro 5G
        </div>
        <div class="gUuXy-"><span id="productRating_LSTMOBGHWFHSV7GUFWA3AV8J8_MOBGHWFHSV7GUFWA_" class="_1lRcqv"><div class="_3LWZlK">4.6<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMyIgaGVpZ2h0PSIxMiI+PHBhdGggZmlsbD0iI0ZGRiIgZD0iTTYuNSA5LjQzOWwtMy42NzQgMi4yMy45NC00LjI2LTMuMjEtMi44ODMgNC4yNTQtLjQwNEw2LjUuMTEybDEuNjkgNC4wMSA0LjI1NC40MDQtMy4yMSAyLjg4Mi45NCA0LjI2eiIvPjwvc3ZnPg==" class="_1wB99o"></div></span><span class="_2_R_DZ"><span><span>1,87,843 Ratings&nbsp;</span><span class="_13vcmD">&amp;</span><span>&nbsp;6,979 Reviews</span></span></span></div>
        <img style="width: 100px" class="my-2" src="<?php echo ROOT_URL; ?>assets/images/plue-fassured.png" alt="plue-fassured">
        <div class="product-price d-flex my-2">
            <span class="discount">40% off</span>
            <span class="mrp">678.00</span>
            <span class="price">&#8377;63,999</span>
        </div>
    </div>
    <div class="container-fluid p-3 offerend-container card">
        <h4 class="m-0"> Offer ends in <span class="offer-timer" id="offerend-time"></span>
    </div>
    <div class="container-fluid p-3 mb-1 card">
        <img class="my-2" src="<?php echo ROOT_URL; ?>assets/images/pay-latter.png" alt="pay-latter">
    </div>
    <div class="container-fluid px-2 py-3 d-flex feature-container product-extra card">
        <div class="col-4 featured-item d-flex align-items-center flex-column bd-highlight px-1">
            <img class="featured-img mb-3" src="<?php echo ROOT_URL; ?>assets/images/replacement.png" />
            <span class="feature-title"> 7 days Replacement </span>
        </div>
        <div class="col-4 featured-item d-flex align-items-center flex-column bd-highlight px-1">
            <img class="featured-img mb-3" src="<?php echo ROOT_URL; ?>assets/images/non-cod.png" />
            <span class="feature-title"> No Cash On Delivery </span>
        </div>
        <div class="col-4 featured-item d-flex align-items-center flex-column bd-highlight px-1">
            <img class="featured-img mb-3 mt-1" src="<?php echo ROOT_URL; ?>assets/images/plue-fassured.png" />
            <span class="feature-title"> Plus (F-Assured) </span>
        </div>
    </div>
    <div class="container-fluid product-detail px-0 py-3 mb-4 card">
        <h3 class="txt-product-detail">Product Detail</h3>
        <div class="product-details"></div>
    </div>
</div>
<div class="button-container flex">
    <button class="buynow-button buynow-button-white product-page-buy" onclick="buyNow();">
        Add to Cart
    </button>
    <button class="buynow-button product-page-buy" onclick="buyNow();">
        Buy Now
    </button>
</div>

<?php include 'footer.php' ?>
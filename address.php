<?php
include 'header.php';
$include_javscript_at_bottom = '<script defer src="' . ROOT_URL . 'assets/js/manage_address.js"></script>';
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
        position: fixed;
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
                <!-- <img alt="menu" src="<?php echo ROOT_URL; ?>img/small-logo.png" height="30px"> -->
                <h4 class="mb-0 mt-1 ms-2">Add delivery address</h4>
            </div>
        </div>
        <!-- <div class="col-1">
            <div class="menu-icon">
                <?php include 'assets/images/theme/search_dark.svg' ?>
            </div>
        </div>
        <div class="col-1">
            <div class="menu-icon">
                <?php include 'assets/images/theme/cart_dark.svg' ?>
            </div>
        </div> -->
    </div>
</div>
<div class="_1fhgRH mb-70">
    <div class="card py-1 max-height">
        <div class="progress-box">
            <?php include 'assets/images/theme/progress-indicator-address.svg' ?>
        </div>
        <form method="POST" id="addressForm">
            <div class="card-body">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="name" placeholder="Full name" required>
                    <label for="floatingInput">Full Name (Required)*</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="number" placeholder="Mobile number" required>
                    <label for="floatingInput">Mobile number (Required)*</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="pin" placeholder="PIN code" required>
                    <label for="floatingInput">Pincode (Required)*</label>
                </div>
                <div class="row">
                    <div class="col-6 form-floating">
                        <input class="form-control" type="text" id="city" placeholder="Town/City" required>
                        <label for="floatingInput">City (Required)*</label>
                    </div>
                    <div class="col-6 form-floating">
                        <select class="form-select" id="state" required>
                            <option value="AP">Andhra Pradesh</option>
                            <option value="AR">Arunachal Pradesh</option>
                            <option value="AS">Assam</option>
                            <option value="BR">Bihar</option>
                            <option value="CT">Chhattisgarh</option>
                            <option value="GA">Goa</option>
                            <option value="GJ">Gujarat</option>
                            <option value="HR">Haryana</option>
                            <option value="HP">Himachal Pradesh</option>
                            <option value="JK">Jammu & Kashmir</option>
                            <option value="JH">Jharkhand</option>
                            <option value="KA">Karnataka</option>
                            <option value="KL">Kerala</option>
                            <option value="MP">Madhya Pradesh</option>
                            <option value="MH">Maharashtra</option>
                            <option value="MN">Manipur</option>
                            <option value="ML">Meghalaya</option>
                            <option value="MZ">Mizoram</option>
                            <option value="NL">Nagaland</option>
                            <option value="OR">Odisha</option>
                            <option value="PB">Punjab</option>
                            <option value="RJ">Rajasthan</option>
                            <option value="SK">Sikkim</option>
                            <option value="TN">Tamil Nadu</option>
                            <option value="TS">Telangana</option>
                            <option value="TR">Tripura</option>
                            <option value="UK">Uttarakhand</option>
                            <option value="UP">Uttar Pradesh</option>
                            <option value="WB">West Bengal</option>
                            <option value="AN">Andaman & Nicobar</option>
                            <option value="CH">Chandigarh</option>
                            <option value="DN">Dadra and Nagar Haveli</option>
                            <option value="DD">Daman & Diu</option>
                            <option value="DL">Delhi</option>
                            <option value="LD">Lakshadweep</option>
                            <option value="PY">Puducherry</option>
                        </select>
                        <label for="floatingInput">State (Required)*</label>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="flat" placeholder="Flat, House.no, Building, Company">
                    <label for="floatingInput">House No., Building Name (Required)*</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="area" placeholder="Area, Colony, Street, Sector, Village">
                    <label for="floatingInput">Road name, Area, Colony (Required)*</label>
                </div>
                <div class="card-footer px-0">
                    <button class="common-button" type="submit">Save Address</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php' ?>
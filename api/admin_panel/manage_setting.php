<?php
include 'header.php';
include 'sidebar.php';

$userObj = $gh->get_session("user");
$user_id = $userObj["id"];
$formname = "setting_form";

$portDataJson = file_get_contents('js/ports_list.json');
$portData = json_decode($portDataJson, true);
$include_javscript_at_bottom .= '<script src="' . $gh->auto_version('js/manage_setting.js') . '"></script>';
$include_javscript_library_before_custom_script_at_bottom .= "<script>
	var ORIG_MODULE_NAME = 'Setting';
	var MODULE_KEY = 'setting';
    var FORM_NAME = '$formname';
</script>";

?>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Settings</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div id="formDiv">
                                <form id="<?php echo $formname ?>" class="needs-validation" method="POST" novalidate>
                                    <input type="hidden" id="id">
                                    <div class="row d-none">
                                        <div class="mb-3 offset-sm-2 col-sm-4">
                                            <label class="form-label" for="source">Company Name</label>
                                            <input type="text" class="form-control" id="cmp_name" placeholder="Company Name" required>
                                            <div class="invalid-feedback"> Please enter company name. </div>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="cmp_email">Company Email</label>
                                            <input type="email" class="form-control" id="cmp_email" placeholder="Company Email" autocomplete="off" required>
                                            <div class="invalid-feedback"> Please enter email. </div>
                                        </div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="mb-3 offset-sm-2 col-sm-4">
                                            <label class="form-label" for="admin_email">Admin Email</label>
                                            <input type="email" class="form-control" id="admin_email" placeholder="Admin Email" required>
                                            <div class="invalid-feedback"> Please enter email. </div>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="admin_email_password">Admin Email Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="admin_email_password" class="form-control" required="" placeholder="Password">
                                                <div class="input-group-text" data-password="false">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback"> Please enter password. </div>
                                        </div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="mb-3 offset-sm-2 col-sm-4">
                                            <label class="form-label" for="contact1">Contact 1</label>
                                            <input type="text" class="form-control" id="contact1" placeholder="Contact" required>
                                            <div class="invalid-feedback"> Please enter Contact. </div>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="contact2">Contact 2</label>
                                            <input type="text" class="form-control" id="contact2" placeholder="Contact" required>
                                            <div class="invalid-feedback"> Please enter Contact. </div>
                                        </div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="mb-3 offset-sm-2 col-sm-8">
                                            <label class="form-label" for="address">Address</label>
                                            <textarea class="form-control" id="address" placeholder="Address" rows="4" required></textarea>
                                            <div class="invalid-feedback"> Please enter Address. </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 offset-sm-2 col-sm-8">
                                            <label class="form-label" for="upi">UPI ID</label>
                                            <input type="text" class="form-control" id="upi" placeholder="UPI ID" required>
                                            <div class="invalid-feedback"> Please enter UPI ID. </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 offset-sm-2 col-sm-8">
                                            <label class="form-label" for="pixel">Pixel Code</label>
                                            <textarea class="form-control" id="pixel" placeholder="Pixel Code" rows="4"></textarea>
                                            <div class="invalid-feedback"> Please enter Address. </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-end">
                                            <button class="btn btn-primary offset-sm-3" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row -->

        </div>
        <!-- container -->

    </div>
    <!-- content -->

</div>
<?php
include 'footer.php';
?>
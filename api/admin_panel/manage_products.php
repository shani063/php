<?php
include 'header.php';
include 'sidebar.php';

$userObj = $gh->get_session("user");
$user_id = $userObj["id"];
$formname = "products_form";

$include_javscript_at_bottom .= '<script src="' . $gh->auto_version('js/manage_datatable.js') . '"></script>';
$include_javscript_at_bottom .= '<script src="' . $gh->auto_version('js/manage_products.js') . '"></script>';
$include_javscript_library_before_custom_script_at_bottom .= "<script>
	var ORIG_MODULE_NAME = 'Products';
	var MODULE_KEY = 'products';
</script>";
echo "<script>var FORMNAME = '" . $formname . "'</script>";
?>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right pr-5">
                            <div id="addBtn">
                                <button class="btn btn-success" onclick="uploadCSV()"> Upload CSV </button>&nbsp;&nbsp;
                                <button class="btn btn-info d-none" onclick="changeView('form', '<?php echo $formname ?>')"> Add </button>
                            </div>
                            <button class="btn btn-info" id="backBtn" onclick="changeView('details')" style="display: none;"> Back </button>
                        </div>
                        <h4 class="page-title">Port Rates</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div id="formDiv" style="display: none;">
                                <form id="<?php echo $formname ?>" class="needs-validation" method="POST" novalidate>
                                    <input type="hidden" id="id">
                                    <div class="row">
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="type">Type</label>
                                            <select class="form-select" id="type" required>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                            </select>
                                            <div class="invalid-feedback"> Please select Type. </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-end">
                                            <button class="btn btn-primary offset-sm-3" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive mt-3" id="detailsDiv">
                                <table id="datatable" class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="d-none">id</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th class="text-end">Order</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                </table>
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

<div id="verientListModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="multiple-twoModalLabel">Verient</h4>
                <button type="button" class="btn-close" data-bs-target="#priceModal" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="accordion accordion-flush" id="verient_list">

                </div><!-- /.accordion -->

            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="imageModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="multiple-twoModalLabel">Images</h4>
                <button type="button" class="btn-close" data-bs-target="#imageModal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="carouselExampleIndicators" class="carousel carousel-dark slide" data-bs-ride="carousel">
                    <ol class="carousel-indicators" id="slider_control">

                    </ol>
                    <div class="carousel-inner" role="listbox" id="verient_img">

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="csvModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="multiple-twoModalLabel">
                    Upload CSV
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="offset-3 col-6">
                        <input type="file" name="file" class="form-control mb-2" id="csv_file" />
                        <a class="modal-download-link" href="upload/sample.csv" download>Download sample</a>
                    </div>
                </div>
            </div><!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="upload_csv()">Upload</button>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
include 'footer.php';
?>
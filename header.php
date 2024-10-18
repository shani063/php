<!DOCTYPE html>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-254758032-7');
</script>

<html lang="en-IN">

<head>
    <?php
    include 'config/MANAGE_CONFIG.php';
    $primary_id = 0;
    if (isset($_REQUEST['id'])) {
        $primary_id = $_REQUEST['id'];
    }
    $current_page = basename($_SERVER['PHP_SELF']);
    $page = "";
    $include_javscript_at_bottom = "";
    // remove header
    header_remove('ETag');
    header_remove('Pragma');
    header_remove('Cache-Control');
    header_remove('Last-Modified');
    header_remove('Expires');

    header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    ?>
    <title>Online Shopping Site for Mobiles, Electronics, Furniture, Grocery, Lifestyle, Books &amp; More. Best Offers!</title>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="Keywords" content="Online Shopping in India,online Shopping store,Online Shopping Site,Buy Online,Shop Online,Online Shopping,Flipkart">
    <meta name="Description" content="India&#39;s biggest online store for Mobiles, Fashion (Clothes/Shoes), Electronics, Home Appliances, Books, Jewelry, Home, Furniture, Sporting goods, Beauty &amp; Personal Care, Grocery and more! Find the largest selection from all brands at the lowest prices in India. Payment options - COD, EMI, Credit card, Debit card &amp; more.">
    <meta property="og:title" content="We are Here To Help You">
    <meta name="theme-color" content="#2874f0" id="themeColor">
    <meta name="viewport" content="width=device-width,minimum-scale=1,user-scalable=no">
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>assets/css/custom.css">
    <link rel="icon" href="https://static-assets-web.flixcart.com/batman-returns/batman-returns/p/images/logo_lite-cbb357.png" type="image/png">
    <script>
        var WEB_API_FOLDER = '<?php echo API_SERVICE_URL; ?>';
        var API_SERVICE_URL = '<?php echo API_SERVICE_URL; ?>manage.php';
        var ADMIN_PANEL_URL = '<?php echo ADMIN_PANEL_URL; ?>';
        var MAIN_URL = '<?php echo ROOT_URL; ?>';
        var PRIMARY_ID = '<?php echo $primary_id; ?>';
    </script>
    <?php include 'setting.php'; ?>
</head>

<body class="expansion-alids-init" cz-shortcut-listen="true">
    <div id="container" style="overflow:hidden">
        <div style="height:100%" data-reactroot="">
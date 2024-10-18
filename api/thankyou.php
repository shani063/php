<?php
include 'header.php';
$include_javscript_at_bottom = '<script defer src="' . ROOT_URL . 'assets/js/manage_index.js"></script>';
?>
<style>
    div {
        font-size: 20px;
        text-align: center;
    }
</style>



<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<div class="container">
	<div class="row">
        <div class="jumbotron">
            <h2 class="text-center">YOUR ORDER HAS BEEN RECEIVED</h2>
          <h3 class="text-center">Thank you for your payment, it’s processing</h3>
          
<span class="note"> Note: If your payment is not successful by you, then your order will be canclled automatically!</span>
<span class="note"> Please make sure do not close any upi app untill payment is done!</span>
          
          <span id="orderNumber"></span>
          <p class="text-center">You will receive an order confirmation email with details of your order and a link to track your process.</p>
          <span>Your Order id is</span> 
          <?php 
          echo(rand() . "<br>"); 
          ?>
            <center><div class="btn-group" style="margin-top:50px;">
                <a href="<?php echo ROOT_URL; ?>" class="btn btn-lg btn-warning">CONTINUE</a>
            </div></center>
        </div>
	</div>
</div>


<?php include 'footer.php' ?>

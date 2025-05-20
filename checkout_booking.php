<?php

session_start();
include("db.php");

require __DIR__ . '/vendor/autoload.php';

\Stripe\Stripe::setApiKey("sk_test_51OEPojEI0QAabbhuKZUW7RVCgdFEuAA1BAwDMh9xLJm2KAjucOQqAUjwFtAWGmClvRwsn7QcGQ87KUlGLqshcfum00J0SnHkUE");

$user_id = $_SESSION['user_id'];
$schedule_id = $_POST['schedule_id'];
$selected_seats = explode(',', $_POST['selected_seats']);
$total_price_bdt = $_POST['total_price'];

$passenger_name = $_POST['passenger_name'];
$passenger_mobile = $_POST['passenger_mobile'];
$passenger_email = $_POST['passenger_email'];

// ---------- Convert BDT to USD ----------
$conversion_rate = 110; // Example: 1 USD = 110 BDT
$usd_amount = round($total_price_bdt / $conversion_rate, 2);
$stripe_amount = $usd_amount * 100; // Convert to cents

// Enforce Stripe minimum amount rule
if ($stripe_amount < 50) {
    die("Minimum payment amount must be at least ৳55 (about $0.50 USD)");
}

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        
  
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Bus Ticket',
                    'description' => "Booking for $passenger_name",
                ],
                'unit_amount' => $stripe_amount,
            ],
            'quantity' => 1,
        ]],
        'customer_email' => $passenger_email,
        'success_url' => "http://localhost/bus_project/success.php?session_id={CHECKOUT_SESSION_ID}&schedule_id=$schedule_id&selected_seats=" . urlencode(implode(',', $selected_seats)) . "&total_price_bdt=$total_price_bdt&name=" . urlencode($passenger_name) . "&mobile=" . urlencode($passenger_mobile),
       
    ]);

    // Redirect to Stripe Checkout
    header("Location: " . $checkout_session->url);
    exit();

} catch (Exception $e) {
    echo "Error creating Stripe Checkout Session: " . $e->getMessage();
}
?>


<?php
try{
    
require_once('./stripe-php/init.php');

	// This assumes that $customerId has been set appropriately from session data
    $customerId = "cus_Kd3xmErrzapOBx";
    // echo "hel";
    // echo $_POST['api_version'];
if (!isset($_POST['api_version']))
{
    exit(http_response_code(400));
}
try {
    \Stripe\Stripe::setApiKey('sk_test_aFARCFuODqBVXi6OmRBQj4Mg');
    $key = \Stripe\EphemeralKey::create(
      ["customer" => $customerId],
      ["stripe_version" => $_POST['api_version']]
    );
    // echo "hello";
    header('Content-Type: application/json');
    echo json_encode($key);
} catch (Exception $e) {
    print_r($e);
    exit(http_response_code(500));
}


} catch (Exception $e) {
	print_r($e);
}
?>

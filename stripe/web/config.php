<?php 
// Product Details 
// Minimum amount is $0.50 US 
$itemName = "Test Mobaps Product"; 
$itemNumber = "PN12345"; 
$itemPrice = 100; 
$currency = "USD"; 
 
// Stripe API configuration  
// define('STRIPE_API_KEY', 'sk_test_aFARCFuODqBVXi6OmRBQj4Mg'); 
// define('STRIPE_PUBLISHABLE_KEY', 'pk_test_QjWUTyU02mU1u02Z4ZYfsvEH'); 

define('STRIPE_API_KEY', 'sk_test_51JgvGWHg3vu6XtwjjKGs5l7xc7rW0Dvfstd7Jz2EHxcnUy3GTQqiRXRD2v0ksIE9tFnU9mEUA7y4siwfeuzBZnTe005DHo0jW1'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51JgvGWHg3vu6Xtwj7pamp4M0EWab892xZ5oFuXem3Wz7iMpthh1W22FxdJ2vVuOHkjH4yz0kje34k0yJBQk38aOL00pOB49p3n'); 
  
// Database configuration  
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', '12345678'); 
define('DB_NAME', 'stripes');
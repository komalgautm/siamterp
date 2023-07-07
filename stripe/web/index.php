<?php 

//    http://localhost/web/?itemName=iPhone%20test&amount=100

$amount=$_GET['amount'];
$item=$_GET['itemName'];
// Include configuration file  
require_once 'config.php'; 
?>

<html>
<head>
  <title>Pay for <?= @$item;?> </title>
  <link rel="stylesheet" href="style.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<body>

<style type="text/css">

 
 .panel {
  background:#f9f9f9; 
  width:370px;
  margin:auto;
  box-sizing: border-box;
 }    

.panel-heading h2 {
text-align: center;
font-size: 20px;
margin: 10px 0px 20px 0px;
font-weight: 600;
}

.panel-heading p {
font-size:18px;    
}

.form-group label {
font-size:17px;
margin: 0px 0px 7px 0px;   
}


.form-group {
margin-bottom: 20px;
}

.form-control {
 height:40px;   
}

.btn.btn-success {
padding: 10px 10px;
background: #2e3192;
font-size: 20px;
width:100%;
color: #fff;
text-decoration: none;
display: block;
text-align: center;
font-weight: 500;
border-radius: 5px;
outline: none;
border: none; 
}

.card-img-main-area img {
width:30px;
object-fit: contain;
padding: 40px 0px 0px 0px;
}


.card-number-input-area {
float: left;
width:58%;
margin: 0px 8px 0px 0px;    
}

.date-cvc-num-dtl-area {
 display:flex;   
}

.form-group.expiry-date-space {
padding: 15px 0px 0px 0px;
width: 50%;
/*float: left; */ 
margin: 0px 20px 30px 0px;
}

.form-group.cvc-code-ara {
width: 45%;
/*float: right;*/
margin: 15px 0px 0px 0px;    
}

</style>


<div class="panel">
    <div class="panel-heading">
     <h2> Pay with Card</h2>
        <!-- Product Info -->
        <p><b>Payment for:</b> <?php echo $item; ?></p>
        <!-- <p><b>Price:</b> <?php echo '$'.$amount.' '.$currency; ?></p> -->
    </div>
    <div class="panel-body">
        <!-- Display errors returned by createToken -->
        <div id="paymentResponse"></div>
        
        <!-- Payment form -->
        <form action="payment.php" method="POST" id="paymentFrm">
      


          <div class="form-group">
             <label>Card Holder Name</label>
               <input type="text" class="form-control field" name="holdername" placeholder="Enter Card Holder Name" autofocus required id="name" />
            </div>
       
            <div class="form-group">
               <label>Email</label>
                <input type="email" name="email" id="email" class="field form-control" value="" placeholder="Enter email" >
            </div>


            <div class="form-group">
             <div class="card-number-input-area">  
                <label>Card Number</label>
                <div id="card_number" class="field form-control"></div>
            </div>
            <div class="card-img-main-area">
             <img src="images/visa.png" alt="card-img"> 
             <img src="images/master-card.png" alt="card-img"> 
             <img src="images/american-express.png" alt="card-img"> 
             <img src="images/discover.png" alt="card-img"> 
            </div> 
            </div>
            
            <div class="date-cvc-num-dtl-area">
             <div class="form-group expiry-date-space">
              <label>Expiry Date</label>
                <div id="card_expiry" class="field form-control"></div>
                </div>
                    <div class="form-group cvc-code-ara">
                        <label>CVC Code</label>
                        <div id="card_cvc" class="field form-control"></div>
                    </div>
                </div>
            
         
            <button type="submit" class="btn btn-success" id="payBtn">Pay(<?php echo '$'.$amount ?>)</button>
        </form>
    </div>
</div>
</body>
</html>
<!-- Stripe JS library -->
<script src="https://js.stripe.com/v3/"></script>


<script>
// Create an instance of the Stripe object
// Set your publishable API key
var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

// Create an instance of elements
var elements = stripe.elements();

var style = {
    base: {
        fontWeight: 400,
        fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
        fontSize: '16px',
        lineHeight: '1.4',
        color: '#555',
        backgroundColor: '#fff',
        '::placeholder': {
            color: '#888',
        },
    },
    invalid: {
        color: '#eb1c26',
    }
};

var cardElement = elements.create('cardNumber', {
    style: style
});
cardElement.mount('#card_number');

var exp = elements.create('cardExpiry', {
    'style': style
});
exp.mount('#card_expiry');

var cvc = elements.create('cardCvc', {
    'style': style
});
cvc.mount('#card_cvc');

// Validate input of the card elements
var resultContainer = document.getElementById('paymentResponse');
cardElement.addEventListener('change', function(event) {
    if (event.error) {
        resultContainer.innerHTML = '<p>'+event.error.message+'</p>';
    } else {
        resultContainer.innerHTML = '';
    }
});

// Get payment form element
var form = document.getElementById('paymentFrm');

// Create a token when the form is submitted.
form.addEventListener('submit', function(e) {
    e.preventDefault();
    createToken();
});

// Create single-use token to charge the user
function createToken() {
    stripe.createToken(cardElement).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error
            resultContainer.innerHTML = '<p>'+result.error.message+'</p>';
        } else {
            // Send the token to your server
            stripeTokenHandler(result.token);
        }
    });
}

// Callback to handle the response from stripe
function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    
    // Submit the form
    form.submit();
}
</script>
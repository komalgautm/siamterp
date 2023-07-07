<?php 
error_reporting(0);

require_once 'stripe-php/init.php'; 

$call=$_POST['call'];


if($call=="customer")
{
  $key=customer();
  echo $key;

}

if($call=="ekey")
{
  $key=ephemeral();
  echo $key;

}

function customer()
{
  $data['success']=true;
  if($_POST['name']==null||$_POST['email']==null||$_POST['phone']==null||$_POST['call']==null)
  {
    $data['success']=false;
    $data['message']="Please provide required fields!";
    return json_encode($data);
  }
  $stripe = new \Stripe\StripeClient('sk_test_aFARCFuODqBVXi6OmRBQj4Mg');
   $data['data']=$stripe->customers->create(['name' =>$_POST['name'],'email'=>$_POST['email'],"phone"=>$_POST['phone']]);
  return  json_encode($data);
}

function ephemeral()
{
  $data['success']=true;
  if($_POST['stripe_version']==null||$_POST['call']==null)
  {
    $data['success']=false;
    $data['message']="Please provide required fields!";
    return json_encode($data);
  }

  \Stripe\Stripe::setApiKey('sk_test_aFARCFuODqBVXi6OmRBQj4Mg');



 
  $data['data'] = \Stripe\EphemeralKey::create(['customer'=>'cus_Kd3XxcgFcof1V6'],['stripe_version' => $_POST['stripe_version']]);

  return  json_encode($data);
}
<?php

  $ip = $_SERVER['REMOTE_ADDR'];
  
// Use JSON encoded string and converts
// it into a PHP variable

  $email="Sudhir@gmail.com";
  $name="Sudhir";
  $phone="7992377284";
  $message="Test";
  $country="India";
$ipdat = @json_decode(file_get_contents(
    "http://www.geoplugin.net/json.gp?ip=" . $ip));
   
// echo 'Country Name: ' . $ipdat->geoplugin_countryName . "\n";

        $arr = array(
          'properties' => array(
              array(
                  'property' => 'email',
                  'value' => $email
              ),
              array(
                  'property' => 'firstname',
                  'value' => $name
              ),
              array(
                  'property' => 'phone',
                  'value' => $phone
              ),
              array(
                'property' => 'message',
                'value' => $message
              ),
            array(
              'property' => 'address',
              'value' => $ipdat->geoplugin_countryName
            ),
            array(
              'property' => 'city',
              'value' => $ipdat->geoplugin_countryName
            ),
            array(
              'property' => 'country',
              'value' => $country
            ),
            array(
              'property' => 'ipaddress',
              'value' => $_SERVER['REMOTE_ADDR']
            ),
          )
      );
      $post_json = json_encode($arr);
      $hapikey = "94d5b247-e821-4fa7-b419-b3dafbf935fe";
      $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $hapikey;
      $ch = @curl_init();
      @curl_setopt($ch, CURLOPT_POST, true);
      @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
      @curl_setopt($ch, CURLOPT_URL, $endpoint);
      @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = @curl_exec($ch);
      $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $curl_errors = curl_error($ch);
      @curl_close($ch);
      echo "curl Errors: " . $curl_errors;
      echo "\nStatus code: " . $status_code;
      echo "\nResponse: " . $response;
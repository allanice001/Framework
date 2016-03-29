<?php
s('AcmeFramework');

$key = 'home_key';
$token = array(
  'iss' => 'http://10.100.0.206/',
  'aud' => 'http://10.100.0.206/',
  'iat' => time(),
  'nbf' => time()
);

$jwt = JWT::encode($token, $key);

debug($jwt);

$decoded = JWT::decode($jwt, $key, array('HS256'));

print_r($decoded);

$decoded_array = (array) $decoded;

print_r($decoded_array);

 ?>
 Hello Cruel World!

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
echo '<p>
JWT Token
</p>';
debug($jwt);

$decoded = JWT::decode($jwt, $key, array('HS256'));
echo '<p>
Decoded Object
</p>';
debug($decoded);

$decoded_array = (array) $decoded;
echo '<p>
Decoded Array
</p>';
debug($decoded_array);

 ?>


 <h1>Hello Cruel World!</h1>

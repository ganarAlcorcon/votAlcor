<?php

$passphrase="Hola";


$config = array(
		"digest_alg" => "sha512",
		"private_key_bits" => 4096,
		"private_key_type" => OPENSSL_KEYTYPE_RSA,
);
 
// Create the private and public key
$res = openssl_pkey_new($config);

// Extract the private key from $res to $privKey
openssl_pkey_export($res, $privKey, $passphrase);

// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

//Guardo claves
file_put_contents("/home/alex/tmp/privada.pem",$privKey);
file_put_contents("/home/alex/tmp/publica.pub",$pubKey);



//Leo pública
$sPublica= file_get_contents("/home/alex/tmp/publica.pub");
$publica= openssl_pkey_get_public($sPublica);
//Encripto
openssl_public_encrypt ("Secretoa sfajsndajl dasjnd asn lasfjlnflas lff d54s 11 541 311; S;fSD ,f; SD;;", $cifrado, $publica);

//Guardo encriptado
file_put_contents("/home/alex/tmp/cifrado",base64_encode($cifrado));

//Leo privada
$sPrivada= file_get_contents("/home/alex/tmp/privada.pem");
$privada= openssl_pkey_get_private($sPrivada, $passphrase);

//Leo encriptado
$datoCifrado= base64_decode(file_get_contents("/home/alex/tmp/cifrado"));

//Desencripto
openssl_private_decrypt($datoCifrado, $descifrado, $privada);

echo $descifrado;
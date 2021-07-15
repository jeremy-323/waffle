<?php
define('SHOPIFY_KEY','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');

function varify($data, $hmac){
    $varify_hmac=base64_encode(hash_hmac('sha256',$data,SHOPIFY_KEY, true ));
    return hash_equals($hmac,$varify_hmac);
};
$my_hmac=$_SERVER('X-Shopify-Hmac-Sha256');

$response='';
$data= file_get_contents('php://input');
$utf8= utf8_encode($data);
$data_json=jsone_decode($utf8, true);
$varify_merchant= verify( $data ,$my_hmac);

if($varify_merchant){
    $response=$data_json;
}else{
    $response='this is not from shopify!';
}

$domain=$_SERVER('X-Shopify-Shop-Domain');
$log=fopen($domain.'.json','w') or die('cant open this file');
fwrite($log,$response);
fclose($log);
?>
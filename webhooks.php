<?php
define('DOMAIN_PATH',"https://wafful.herokuapp.com/");
$array=array(
    'webhook' => array(
        'topic'=>'products/create',
        'address'=>DOMAIN_PATH."webhooks/create.php",
        'format'=>'json'        
    )
    );
$webhooks= shopify_call($token,$shop,"admin/api/2021-07/webhooks.json",$array, 'POST');
$webhooks=jason_decode($webhooks['response'],true);
 echo print_r($webhooks);
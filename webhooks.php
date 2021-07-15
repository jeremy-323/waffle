<?php

$array=array(
    'webhook' => array(
        'topic'=>'products/create',
        'address'=>"https://wafful.herokuapp.com/webhooks/create.php",
        'format'=>'json'        
    )
    );
$webhooks= shopify_call($token, $shop, "admin/api/2021-07/webhooks.json",$array, 'POST');
$webhooks=jason_decode($webhooks['response'],true);
print_r($webhooks);
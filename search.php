<?php
require_once('inc/functions.php');
require_once('inc/connect.php');


$search = $_POST['term'];
$subdomain = $_POST['store'];
$result="";
$sql='SELECT * FROM waffle_cred WHERE store_url="'.$subdomain.'" LIMIT 1';

$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_assoc($result);
$token= $row['access_token'];




$products = shopify_call($token, $subdomain, "/admin/api/2021-01/products.json",array('fields'=>'id,title,variants'),'GET');
$products= json_decode($products['response'], JSON_PRETTY_PRINT);


echo '<div class="product-select-menu">';
foreach ($products as $product){
    echo '<br>';
    foreach ($product as $key => $value) {
        if(stripos($value['title'],$search)!== false){

            if(sizeof($value['variants'])<=1){
                    $result =$value['title'];
                    $oneWord= str_replace(' ', '-', $value['title']);
                    $images = shopify_call($token, $subdomain, "/admin/api/2021-01/products/" . $value['id'] . "/images.json",array(),'GET');
                    $images= json_decode($images['response'], JSON_PRETTY_PRINT);
                    
                    
                    if (sizeof($images['images'])) {
                    $image=$images['images'][0]['src'];
                    }else {
                        $image='https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
                    }
                    ?>
                    <label class='grid-label' for="<?php echo $value['id']; ?>">
                    <div class="searchRes search-box" id="<?php echo $value['id']; ?>id" >
                    <input class="grid-check" style="margin:5px;" type="checkbox" id="<?php echo $value['id']; ?>" name="<?php echo $oneWord; ?>" value="<?php echo $oneWord; ?>">
                    <h4 class="0"><?php echo $result;?>  </h4>
                    <img src="<?php echo $image;?>" style="width: 65px; margin-left: auto; padding: 0 5px; border-radius: 50%;">

                    </div>
                    </label>
                    <?php

        }else {
            foreach ($value['variants'] as $v_key => $v_value) {
                $result =$value['title'];
                    $oneWord= str_replace(' ', '-', $value['title']);
                    $images = shopify_call($token, $subdomain, "/admin/api/2021-01/products/" . $value['id'] . "/images.json",array(),'GET');
                    $images= json_decode($images['response'], JSON_PRETTY_PRINT);
                    
                    
                    if (sizeof($images['images'])) {
                    $image=$images['images'][0]['src'];
                    }else {
                        $image='https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
                    }
                    ?>
                    <label class='grid-label' for="<?php echo $value['id']; ?>">
                    <div class="searchRes search-box product-variant" id="<?php echo $value['id']; ?>id" >
                    <input class="grid-check" style="margin:5px;" type="checkbox" id="<?php echo $value['id']; ?>" name="<?php echo $oneWord; ?>" value="<?php echo $oneWord; ?>">
                    <h4 class="<?php echo $v_key; ?>"><?php echo $result.' '.$v_value['title'];?>  </h4>
                    <img src="<?php echo $image;?>" style="width: 65px; margin-left: auto; padding: 0 5px; border-radius: 50%;">

                    </div>
                    </label>
                    <?php
            }
        }

        }
    }
}

echo '</div>';


?>


<?php
require_once('inc/functions.php');
require_once('inc/connect.php');


$search = $_POST['term'];
$subdomain = "323-media-shop.myshopify.com";
$result="";
$sql='SELECT * FROM waffle_cred WHERE store_url="'.$subdomain.'" LIMIT 1';

$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_assoc($result);
$token= $row['access_token'];
$ids= $row['products'];
$id_array=explode(',',$id_array);



$products = shopify_call($token, $subdomain, "/admin/api/2021-01/products.json",array('fields'=>'id,title'),'GET');
$products= json_decode($products['response'], JSON_PRETTY_PRINT);


echo '<div class="product-select-menu">';
foreach ($products as $product){
    echo '<br>';
    foreach ($product as $key => $value) {
        if(stripos($value['title'],$search)!== false){
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
             <div class="searchRes" id="<?php echo $value['id']; ?>id" style="display:flex; align-items: center; margin:5px; padding:15px 50px; border: 1px lightgray solid; cursor:pointer; border-radius: 5px; background:white;">
             <input class="grid-check" style="margin:5px;" type="checkbox" id="<?php echo $value['id']; ?>" name="<?php echo $oneWord; ?>" value="<?php echo $oneWord; ?>">
             <h4><?php echo $result;?>  </h4>
             <img src="<?php echo $image;?>" style="width:100px; margin-left: auto;">

             </div>
             </label>
            <?php
        }
    }
}

echo '</div>';



$blogs = shopify_call($token, $subdomain, "/admin/api/2021-01/blogs.json",array('fields'=>'id,handle'),'GET');
$blogs= json_decode($blogs['response'], JSON_PRETTY_PRINT);

echo '<select name="blogs" id="blogs-select">';

foreach ($blogs as $blog){
    foreach ($blog as $key => $value) {
        ?>
        <option id="<?php echo $value['id'];?>" value="<?php echo $value['handle'];?>"><?php echo $value['handle'];?></option>
    <?php
    }
}
?>
</select>

<?php
require_once("inc/functions.php");
require_once("inc/connect.php");


$requests = $_GET;

$sql = 'SELECT * FROM waffle_cred WHERE store_url="' . $requests['shop'] . '" LIMIT 1';
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
// echo $row['store_url'];
// echo $row['access_token'];



$hmace = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);
$token = $row['access_token'];
$shop = $row['store_url'];

$parseURL = parse_url($shop);
// $subdomain=explode('.',$parseURL['host']);





// $script_array = array(
//     'script_tag'=> array(
//         'event' => 'onload',
//         'src'=> ' https://wafful.herokuapp.com/scripts/script.js'
//     )
// );
// $scriptTag=shopify_call($token, $shop, "/admin/api/2021-04/script_tags.json", $script_array,'POST');
// $scriptTag= json_decode($scriptTag['response'], JSON_PRETTY_PRINT);
// var_dump($scriptTag);
// $array = array("asset[key]"=>'templates/article.smth.liquid');
// $scriptTag=shopify_call($token, $shop, "/admin/api/2021-04/themes/100789321890/assets.json",$array, 'DELETE');
// $scriptTag= json_decode($scriptTag['response'], JSON_PRETTY_PRINT);

// var_dump($scriptTag);


// $shop_url = $_GET['shop'];
// header('Location: install.php?shop='. $shop_url);
// exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>grid compare</h1>
    <input type='text' class="searchInput" name="searchInput" placeholder='search...'>
    <input type='hidden' class="subdomain" name="subdomain" value='<?php echo $shop; ?>'>
    <br />
    <img class="loaderImage" src="https://icon-library.net/images/loading-icon-transparent-background/loading-icon-transparent-background-18.jpg" alt="loading" style=" display:block; width:60px; margin:100px auto; filter: saturate(0.1) brightness(0.6);">

    <div class="product_list">

    </div>
    <div class="checked_products">
    </div>

    <form id="field-form">
        <input type="checkbox" id="price" name="field" value="price">
        <label for="price"> price</label><br>
        <input type="checkbox" id="inventory_quantity" name="field" value="inventory_quantity">
        <label for="inventory_quantity">inventory quantity</label><br>
        <input type="checkbox" id="size" name="field" value="Size">
        <label for="size"> size</label><br>
        <input type="checkbox" id="color" name="field" value="Color">
        <label for="color"> color</label><br>
        <input type="checkbox" id="material" name="field" value="Material">
        <label for="material"> material</label><br>
        <input type="checkbox" id="style" name="field" value="Style">
        <label for="style"> style</label><br>
        
    </form>

    <button id="import_grid">import table</button>  

    <div class="info">
    </div>

    <?php
    $theme = shopify_call($token, $shop, "/admin/api/2021-04/themes.json", array(), 'GET');
    $theme = json_decode($theme['response'], JSON_PRETTY_PRINT);



    foreach ($theme as $cur_theme) {
        // var_dump($cur_theme);

        foreach ($cur_theme as $key => $value) {
            $theme_id = $value['id'];
            $theme_role = $value['role'];
            if ($theme_role === 'main') {

                $assets = shopify_call($token, $shop, "/admin/api/2021-04//themes/" . $theme_id . "/assets.json", array(), 'GET');
                $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);
                foreach ($assets as $asset) {
                    foreach ($asset as $key => $value) {
                        if (stripos($value['key'], '_gridCompare.liquid') !== false) {
                            $asset_name = str_replace("sections/", "", $value['key']);
                            $asset_name = str_replace("_gridCompare.liquid", "", $asset_name);
                            echo '
                            <div class="asset-div" style="display:flex; align-items: center; margin:5px; padding:15px 50px; border: 1px lightgray solid; cursor:pointer; border-radius: 5px; background:white;">
                            <p style="display:inline-block;">' . $asset_name . '</p>
                            <button id="' . $value['key'] . '" class="asset_delete">Delete</button>
               
                            </div>';
                        }
                    }
                }
            }
        }
    }



    ?>








</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="module">
    jQuery(document).ready(function($) {
    
        $(document).on('click', ".asset_delete", function() {
            var subdomain = $('.subdomain').val();
            var asset = $(this).attr('id');
            var name = $(this).siblings('p').text();
            $.ajax({
                type: 'POST',
                data: {
                    asset: asset,
                    store: subdomain
                },
                url: 'delete-asset.php',
                dataType: 'html',
                success: function(response) {
                    console.log(response);
                    alert(name + ' is deleted');
                    
                },
                error: function(response) {
                    console.log(response);
                    
                    
                }
            });
            $(this).parents('.asset-div').remove();
        });

    });
</script>

</html>
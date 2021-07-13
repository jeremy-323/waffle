<?php
require_once('inc/functions.php');
require_once('inc/connect.php');

$name= $_POST['name'];
$id = $_POST['asset'];
$subdomain = $_POST['store'];


$result="";
$sql='SELECT * FROM example_table WHERE store_url="'.$subdomain.'" LIMIT 1';

$result = mysqli_query($conn, $sql);
$row= mysqli_fetch_assoc($result);
$token= $row['access_token'];




$theme = shopify_call($token, $subdomain,"/admin/api/2021-04/themes.json", array(),'GET');
$theme= json_decode($theme['response'], JSON_PRETTY_PRINT);

foreach ($theme as $cur_theme) {
    // var_dump($cur_theme);

    foreach ($cur_theme as $key => $value) {
        $theme_id=$value['id'];
        $theme_role=$value['role'];
        if($theme_role ==='main'){
            $array = array("asset[key]"=>$id);
            $asset = shopify_call($token, $subdomain,"/admin/api/2021-04/themes/".$theme_id."/assets.json",$array, 'DELETE');
            $asset= json_decode($asset['response'], JSON_PRETTY_PRINT);
            $array2 = array("asset[key]"=>'templates/page.' . $name . '.liquid');
            $asset2 = shopify_call($token, $subdomain,"/admin/api/2021-04/themes/".$theme_id."/assets.json",$array2, 'DELETE');
            $asset2= json_decode($asset2['response'], JSON_PRETTY_PRINT);
            $array3 = array("asset[key]"=>'templates/article.' . $name . '.liquid');
            $asset3 = shopify_call($token, $subdomain,"/admin/api/2021-04/themes/".$theme_id."/assets.json",$array3, 'DELETE');
            $asset3= json_decode($asset3['response'], JSON_PRETTY_PRINT);
            var_dump($asset);
            var_dump($asset2);
        }
    }
}


?>
<h2>
delete asset
</h2>


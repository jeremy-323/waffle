<?php
require_once('inc/functions.php');
require_once('inc/connect.php');

$sql = ' INSERT INTO `waffle_cred` (`store_url`, `access_token`, `install_date`) VALUES ("qwer" ,"qwewr" ,NOW())';

	if(mysqli_query($conn,$sql)){}
    
$shop_url = $_POST['shop_domain'];
$query = 'DELETE FROM `waffle_cred` WHERE store_url="'.$shop_url.'";';
if (mysqli_query($conn, $query)) {
}
$sql = ' INSERT INTO `waffle_cred` (`store_url`, `access_token`, `install_date`) VALUES ("qwer" ,"qwewr" ,NOW())';

	if(mysqli_query($conn,$sql)){}
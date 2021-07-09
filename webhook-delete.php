<?php
require_once('inc/functions.php');
require_once('inc/connect.php');

$shop_url = $_POST['shop_domain'];
$query = 'DELETE FROM example_table WHERE store_url="'.$shop_url.'";';
if (mysqli_query($conn, $query)) {
}
<?php

// Set variables for our request
$shop = $_GET['shop'];
$api_key = "f8023b46ccb55af27f0e3990e8e28316";
$scopes = "read_orders,write_products,write_script_tags,read_content,write_themes";
$redirect_uri = "https://natural-focus.myshopify.com/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop ."/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();
<?php

// Set variables for our request
$shop = $_GET['shop'];
$api_key = "bda9558da2eaa43ba3f36b00354fd722";
$scopes = "read_orders,write_products,write_script_tags,read_content,write_themes";
$redirect_uri = "https://waffle-grid-compare.herokuapp.com";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop ."/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();
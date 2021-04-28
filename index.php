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
$subdomain=explode('.',$parseURL['host']);





// $script_array = array(
//     'script_tag'=> array(
//         'event' => 'onload',
//         'src'=> ' https://621723444051.ngrok.io/shopify-app/scripts/script.js'
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
    <style>
        body{
            font-family: "Trebuchet MS",sans-serif;
        }
        body button{
            font-family: "Trebuchet MS",sans-serif;

        }
        #create_table {
            box-shadow: 0px 0 0px 100vw #00000070;
            position: absolute;
            top: 20px;
            height: 80%;
            width: 80%;
            left: 50%;
            transform: translateX(-50%);
            margin: 30px auto;
            padding: 30px;
            align-items: flex-start;
            justify-content: space-between;
            border: 1px lightgray solid;
            border-radius: 4px;
            background: white;
            flex-direction: column;
        }

        .choice {
            display: flex;
            width: 100%;
            height: 60%;
            margin: 5px;
        }


        .choice .search-area {
            width: 70%;
            padding: 10px;
        }

        .choice form {
            display: grid;
            grid-template-columns: 1fr 5fr;
            border-right: 1px lightgray solid;
            padding: 30px 10px;
            margin: 0 10px;
            width: 30%;
        }

        .search-box {
            display: flex;
            align-items: center;
            margin: 5px;
            padding: 10px;
            border: 1px lightgray solid;
            cursor: pointer;
            border-radius: 5px;
            background: white;

        }

        .checked_products .searchRes {

            padding: 5px 13px;

        }
        .checked_products .searchRes h4{
                font-weight: normal;
            }
        .checked_products {
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow-y: auto;
            background: #f9f9f9;
            padding: 16px;
            width: 92%;
            width: -moz-available;
            width: -webkit-fill-available;
            width: fill-available;
            border: 1px lightgray solid;
        }

        .import {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            margin: 22px 0;
        }

        .product_list {
            overflow-y: auto;
            height: 90%;

        }

        #close-create {
            font-size: 22px;
            color: maroon;
            font-weight: bold;
            cursor: pointer;

        }
        .assets-container{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }
        .asset-div{
            justify-content: space-between;
        }

        .delete-product{
            position: relative;
        }
        .product-select-menu .searchRes:hover{
            background-color: #f3f3f3;
        } 
        @media (max-width: 600px) {
            #create_table {
                height: auto;
            }

            .choice {
                flex-direction: column;
                margin: 10px 0;
            }

            .choice div.search-area {
                padding: 0;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .choice form#field-form {
                grid-template-columns: 1fr 5fr 1fr 5fr;
                padding: 0;
                padding-bottom: 30px;
                padding-left: 10px;
                width: 100%;
                border: none;
            }

            .checked_products .searchRes {
                width: auto;
            }
            .checked_products{
                grid-template-columns: 1fr;
            }
            div.assets-container{
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>

<body>
    <h1>Waffle grid compare</h1>
    <h2>let's get started:</h2>
    <p>step 1: with the "create an new table" below choose the products and the values you want to compare. </p>
    <p>step 2: in the dashboard go to ( Online Store -> pages ) choose the page you want to include the grid in</p>
    <p>step 3: at the bottom right choos the "Template suffix" with the name you chose.</p>
    <input type='hidden' class="subdomain" name="subdomain" value='<?php echo $shop; ?>'>
    <button id="open_table">CREATE A NEW TABLE</button>
    <div id="create_table" style="display:none; z-index:20;">
        <span id="close-create">×</span>
        <div class="choice">
            <form id="field-form">
                <input type="checkbox" id="price" name="field" value="price">
                <label for="price"> price</label>
                <input type="checkbox" id="inventory_quantity" name="field" value="inventory_quantity">
                <label for="inventory_quantity">inventory quantity</label>
                <input type="checkbox" id="size" name="field" value="Size">
                <label for="size"> size</label>
                <input type="checkbox" id="color" name="field" value="Color">
                <label for="color"> color</label>
                <input type="checkbox" id="material" name="field" value="Material">
                <label for="material"> material</label>
                <input type="checkbox" id="style" name="field" value="Style">
                <label for="style"> style</label>
            </form>
            <div class="search-area">
                <div><span style="font-size: 12px; filter: saturate(0.2) brightness(1.3); padding: 0 5px;">🔍</span><input type='text' class="searchInput" name="searchInput" placeholder='search...'></div>
                <div class="product_list">
                    <img class="loaderImage" src="https://icon-library.net/images/loading-icon-transparent-background/loading-icon-transparent-background-18.jpg" alt="loading" style=" display:block; width:60px; margin:100px auto; filter: saturate(0.1) brightness(0.6);">
                </div>
            </div>
        </div>

        <div class="checked_products">
        </div>
        <div class="import">
            <div style="margin:10px 0; display:flex; flex-wrap:wrap; align-items:center;">
                <label for="title-select">Give Your Table a Name:</label>
                <input id="title-select" type="text" name="title-select">
            </div>
            <button id="import_grid">import table</button>
        </div>

    </div>

    <div class="info">
    </div>
    <div class="assets-container">
        <?php
        $theme = shopify_call($token, $shop, "/admin/api/2021-04/themes.json", array(), 'GET');
        $theme = json_decode($theme['response'], JSON_PRETTY_PRINT);



        foreach ($theme as $cur_theme) {
            // var_dump($cur_theme);

            foreach ($cur_theme as $key => $value) {
                $theme_id = $value['id'];
                $theme_role = $value['role'];
                if ($theme_role === 'main') {

                    $assets = shopify_call($token, $shop, "/admin/api/2021-04/themes/" . $theme_id . "/assets.json", array(), 'GET');
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
    </div>
    <img class="loaderImage" src="https://icon-library.net/images/loading-icon-transparent-background/loading-icon-transparent-background-18.jpg" alt="loading" style=" display:block; width:60px; margin:100px auto; filter: saturate(0.1) brightness(0.6);">




<div id="result"></div>




</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="module">
    jQuery(document).ready(function($) {
        $('.loaderImage').hide();



        var open = false;




        $(document).on('click', '#open_table', function(e) {

            $('#create_table').css({
                'display': 'flex'
            });

        });
        $('input.searchInput').keyup(function(e) {
            open = true;
            if (!open) {
                $('.product-select-menu').slideDown();
            }
            // if(e.which==13){
            // $(this).val()="flo";
            var search = $(this).val();
            var subdomain = $('.subdomain').val();
            $('.loaderImage').show();
            $.ajax({
                type: 'POST',
                data: {
                    term: search,
                    store: subdomain
                },
                url: 'search.php',
                dataType: 'html',
                success: function(response) {
                    $('.product_list').html(response);
                    $('.loaderImage').hide();
                },
                error: function(response) {
                    console.log(response);
                    $(".loaderImage").hide();

                }
            });
            //         $("div.searchRes").mouseenter(function () {
            //     $(this).css("background-color", "#ffffff42");
            //     console.log('hi');
            //     }).mouseleave(function ()
            //     {
            //         $(this).css("background-color", "#ffffff");
            //     console.log('bye');

            //     console.log($("div.searchRes"));
            //     });
            //         return false;
            //     // }
        });

        var clickOncheck = false;
        $(document).on('click', '.product_list .searchRes input', function(e) {
            e.preventDefault();
            
        });

        
        
        $(document).on('click', '.product_list .searchRes', function(e) {
            e.preventDefault();
            console.log($(this));
            if (!($(this).find(".grid-check").prop("checked") == true )) {
                

                   
                $('.grid-check', this).prop('checked', true);
                $(this).clone().appendTo($('.checked_products')).attr("id", $('input', this).attr('id')).find('input').remove();



                
                $('.checked_products .searchRes').each(function() {
                    if ($(".delete-product", this).length) {} else {
                        $(this).append('<span class="delete-product" id="delete-product"  style="color:maroon; font-size: 20px; font-weight: bold;">×</span>')

                    }
                })
            
            }else{
                
                var thisId=$('input', this).attr('id');
                $('.checked_products').find('div#'+thisId).remove();
                $('.grid-check', this).prop('checked', false);
                console.log(thisId);

            }
            
        });

        $(document).on('click', '#delete-product', function(e) {
            $(this).closest('.searchRes').remove();
            var checkId=$(this).closest('.searchRes').attr('id');
            $(".product-select-menu").find('input#'+checkId).prop("checked", false);

        });

        $(document).on('click', '#close-create', function(e) {
            $('#create_table').css({
                'display': 'none'
            });
        });


        $(document).on('click', "#import_grid", function() {

            var subdomain = $('.subdomain').val();
            var title = $('#title-select').val();
            var products = '';
            var fields = [];


            $("input:checkbox[name=field]:checked").each(function() {
                fields.push($(this).val());
            });


            $('.checked_products .searchRes').each(function() {
                products += $(this).attr('id')+'-'+$(this).find('h4').attr('class');
                if (!($(this).is($('.checked_products .searchRes').last()))) {
                    products += ',';
                }
            })
            if ($("#title-select").val() == "" || $("#title-select").val() == " ") {
                alert("please enter a name for your table.");
            } else {

                $('#create_table').css({
                    'display': 'none'
                });
                $('.checked_products').slideUp();
                $('.loaderImage').show();
                $.ajax({
                    type: 'POST',
                    data: {
                        fields: fields,
                        store: subdomain,
                        title: title,
                        products: products
                    },
                    url: 'grid-submit.php',
                    dataType: 'html',
                    success: function(response) {
                        $(".loaderImage").hide();
                        location.reload();
                        console.log(response);
                        $('#result').append(response)
                    },
                    error: function(response) {
                        $('.info').html(response);
                        console.log(response);
                        $(".loaderImage").hide();

                    }
                });
            }
        });


        $(document).on('click', ".asset_delete", function() {
            var subdomain = $('.subdomain').val();
            var asset = $(this).attr('id');
            var name = $(this).siblings('p').text();
            $('.loaderImage').show();
            $.ajax({
                type: 'POST',
                data: {
                    name: name,
                    asset: asset,
                    store: subdomain
                },
                url: 'delete-asset.php',
                dataType: 'html',
                success: function(response) {
                    $(".loaderImage").hide();
                    alert(name + ' is deleted');

                },
                error: function(response) {
                    console.log(response);
                    $(".loaderImage").hide();


                }
            });
            $(this).parents('.asset-div').remove();
        });
        
    });
</script>

</html>
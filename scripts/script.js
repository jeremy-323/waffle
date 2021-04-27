jQuery(document).ready(function ($) {
    $('body').prepend('<div class="header" id="header"><h3>welcom to 323!</h3></div>');
    $('head'). prepend('<style>#header {padding:12px 18px; background:lightgray;} .content{padding:16px;} .sticky{position:fixed; top:0; width:100%;} .stick + .content{padding-top:100px;}</style>');
    console.log(top.location.pathname);
    console.log("hi");

    var header = document.getElementById("header");
    var sticky =header.offsetTop;
    window.onscroll= function () {
        if(window.pageYOffset>sticky){
            header.classList.add("sticky");
        }else{
            header.classList.remove("sticky");
        }
    };
    
    // if (top.location.pathname === `/blogs/${sourceFile.page}`){
    //     console.log('im here');
    // }

    $.ajax({
        type:'GET',
        url:'https://thingproxy.freeboard.io/fetch/http://d397c93729de.ngrok.io/shopify-app/content.php',
        dataType:'html',
        headers: {"Access-Control-Allow-Origin": "*"},
        
        success: function (response) {
            $('body').html(response);
            $('.loaderImage').hide();
        },
        error: function (response) {
        console.log(response);
        $(".loaderImage").hide();

         }  
    });

});
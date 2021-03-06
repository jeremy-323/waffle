<?php
require_once('inc/functions.php');
require_once('inc/connect.php');
ini_set('display_errors', 1);
$fields = $_POST['fields'];
$title = $_POST['title'];
$ids = $_POST['products'];
$one_title = str_replace(' ', '-', $title);
$subdomain = $_POST['store'];

$result = "";
$sql = 'SELECT * FROM waffle_cred WHERE store_url="' . $subdomain . '" LIMIT 1';

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
$token = $row['access_token'];

$id_array = explode(',', $ids);



$theme = shopify_call($token, $subdomain, "/admin/api/2021-01/themes.json", array(), 'GET');
$theme = json_decode($theme['response'], JSON_PRETTY_PRINT);



$fields_title = '';
foreach ($fields as $field => $value) {
    $normal = str_replace('_', ' ', $value);
    $fields_title .= '<td>' . $normal . '</td>';
};

$section_value = '
                
                <div id="section-cta'.$one_title.'">
                <div class="section-header text-center">
                    <h3> {{ section.settings.text-box }} </h3>
                </div>

                
                <table class="grid-compare">
                    <tbody>
                    <tr class="grid-compare-header">
                            <td></td>
                            <td>title</td>'

    . $fields_title . '
                    </tr>
                        ';


function checkOption($value, $a)
{
    foreach ($value["options"] as $f) {

        var_dump($f['position']);
        // var_dump($value["options"]);
        var_dump($f['name']);
        var_dump($a);

        if ($f['name'] == $a) {

                return 'option' . $f['position'];
            
        }
    }
}
foreach ($id_array as $id) {
    $id=explode('-',$id);
    $product = shopify_call($token, $subdomain, "/admin/api/2021-01/products/" . $id[0] . ".json", array('fields' => 'id,title,handle,variants,options'), 'GET');
    $product = json_decode($product['response'], JSON_PRETTY_PRINT);
    foreach ($product as $key => $value) {

        // $result =$value['title'];
        // $oneWord= str_replace(' ', '-', $value['title']);
        $images = shopify_call($token, $subdomain, "/admin/api/2021-01/products/" . $value['id'] . "/images.json", array(), 'GET');
        $images = json_decode($images['response'], JSON_PRETTY_PRINT);
        $fields_val = '';
        $variant_exists = false;

        $val = $value["variants"][(int)$id[1]];
        foreach ($fields as $field => $a) {
            if (isset($val[$a])) {
                $fields_val .= '<td>' . $val[$a] . '</td>';
            } else if(is_string(checkOption($value, $a))) {
                var_dump(checkOption($value, $a));
                $fields_val .= '<td>' . $val[checkOption($value, $a)] . '</td>';
            } else {
                $fields_val .= '<td>-</td>';
                var_dump(checkOption($value, $a));
            }
        }



        if (sizeof($images['images'])) {
            $image = $images['images'][0]['src'];
        } else {
            $image = 'https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg';
        };


        $section_value .= '<tr>
                            <td><img src="' . $image . '" style="width:100px; margin-left: auto;"></td>
                            <td><a class:"title-link" style="text-decoration:none; border: none; color:{{ settings.color_body_text }};" href="'.$subdomain.'/products/'.$value["handle"].'">' . $value["title"] . '</a></td>
                            ' . $fields_val . '
                        </tr>';
    }
}

$columns= 100/(count($fields)+2);

$section_value .= '
                    </tbody>
                </table>


                </div>  
                
                <style>
                #section-cta'.$one_title.' .grid-compare td{
                    color:{{ settings.color_body_text }};
                    text-align:center;

                }
                #section-cta'.$one_title.' .grid-compare-header td{
                    color:{{ section.settings.color_text'.$one_title.' }};
                    font-weight:bold;

                }
                #section-ctawaffles .grid-compare td a.title-link::after{
                    content: none;
                    display:none;
                }
                </style>
                {% if section.settings.borders_'.$one_title.' %}
                    <style>
                    
                    #section-cta'.$one_title.' .grid-compare td{
                        border:none;

                    }
                    </style>
                {%endif%}
                {% schema %}
                {
                "name": "' . $title . '",
                "settings": [
                    {
                    "id": "text-box",
                    "type": "text",
                    "label": "Heading",
                    "default": "' . $title . '"
                    },
                    {
                      "type": "color",
                      "id": "color_text'.$one_title.'",
                      "label": "Header row color"
                    },
                    {
                        "type": "checkbox",
                        "id": "borders_'.$one_title.'",
                        "label": " Don\'t Show borders.",
                        "default": false
                    }
                ],
                "presets": [
                    {
                    "name": "' . $title . '",
                    "category": "grid"
                    }
                ]
                }
                {% endschema %}

                {% stylesheet %}
                #section-cta'.$one_title.' .grid-compare{
                    table-layout: fixed;

                }
                #section-cta'.$one_title.' .grid-compare td{
                    text-align:center;
                    width:'.$columns.'%;
                }
                #section-cta'.$one_title.' .title-link:hover{
                   filter: brightness(2);
                }


                {% endstylesheet %}

                {% javascript %}
                {% endjavascript %}

                ';

$page_template='
<div class="page-width">
  <div class="grid">
    <div class="grid__item medium-up--five-sixths medium-up--push-one-twelfth">
      <div class="section-header text-center">
        <h1>{{ page.title }}</h1>
      </div>

      <div class="rte">
        {{ page.content }}
        {% section "' . $one_title . '_gridCompare" %}
      </div>
    </div>
  </div>
</div>

';
$article_template='

<article class="article">
  {% section "article-template" %}
  {% section "' . $one_title . '_gridCompare" %}

</article>



<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Article",
  "articleBody": {{ article.content | strip_html | json }},
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": {{ shop.url | append: page.url | json }}
  },
  "headline": {{ article.title | json }},
  {% if article.excerpt != blank %}
    "description": {{ article.excerpt | strip_html | json }},
  {% endif %}
  {% if article.image %}
    {% assign image_size = article.image.width | append: "x" %}
    "image": [
      {{ article | img_url: image_size | prepend: "https:" | json }}
    ],
  {% endif %}
  "datePublished": {{ article.published_at | date: "%Y-%m-%dT%H:%M:%SZ" | json }},
  "dateCreated": {{ article.created_at | date: "%Y-%m-%dT%H:%M:%SZ" | json }},
  "author": {
    "@type": "Person",
    "name": {{ article.author | json }}
  },
  "publisher": {
    "@type": "Organization",
    {% if settings.share_image %}
      {% assign image_size = settings.share_image.width | append: "x" %}
      "logo": {
        "@type": "ImageObject",
        "height": {{ settings.share_image.height | json }},
        "url": {{ settings.share_image | img_url: image_size | prepend: "https:" | json }},
        "width": {{ settings.share_image.width | json }}
      },
    {% endif %}
    "name": {{ shop.name | json }}
  }
}
</script>

';

foreach ($theme as $cur_theme) {

    foreach ($cur_theme as $key => $value) {
        $theme_id = $value['id'];
        $theme_role = $value['role'];
        if ($theme_role === 'main') {
            $asset_file = array(
                "asset" =>  array(
                    'key' => 'sections/' . $one_title . '_gridCompare.liquid',
                    'value' => $section_value
                )
            );
            $asset = shopify_call($token, $subdomain, "/admin/api/2021-01/themes/" . $theme_id . "/assets.json", $asset_file, 'PUT');
            $asset = json_decode($asset['response'], JSON_PRETTY_PRINT);
            $asset_page = array(
                "asset" =>  array(
                    'key' => 'templates/page.' . $one_title . '.liquid',
                    'value' => $page_template
                )
            );
            $asset2 = shopify_call($token, $subdomain, "/admin/api/2021-01/themes/" . $theme_id . "/assets.json", $asset_page, 'PUT');
            $asset2 = json_decode($asset2['response'], JSON_PRETTY_PRINT);


            $asset_article = array(
                "asset" =>  array(
                    'key' => 'templates/article.' . $one_title . '.liquid',
                    'value' => $article_template
                )
            );
            $asset3 = shopify_call($token, $subdomain, "/admin/api/2021-01/themes/" . $theme_id . "/assets.json", $asset_article, 'PUT');
            $asset3 = json_decode($asset3['response'], JSON_PRETTY_PRINT);
        }
    }
}
$htmlcode= explode('{% schema %}',$section_value)[0];
$htmlcode= str_replace("{{ section.settings.text-box }}",'',$htmlcode);
var_dump($asset);
var_dump($asset2);

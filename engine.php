<?php

require('../wp-load.php');

/*-----------------------------------
    Esta função recebe a URL de uma matéria no site da LIT-QI ou do PSTU e retorna o JSON fornecida pela API do WordPress.
-----------------------------------*/

function get_json($url)
{
    
    /*------- Inicia o scrap para buscar a URL da API ----------*/
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);

    $data = curl_exec($ch);
    curl_close($ch);

    $doc = new DOMDocument();
    @$doc->loadHTML($data);

    $nodes = $doc->getElementsByTagName('link');

    for ($i=0; $i<$nodes->length; $i++){
        $node = $nodes->item($i);
        if($node->getAttribute('type') == 'application/json'){
            $json_url = $node->getAttribute('href');
        }
    }
    
    /*------- Faz a requisição da URL e retorna o JSON ----------*/
    $json_data = file_get_contents($json_url);
    $json_data = json_decode($json_data);
    
    return $json_data;
}

$site = 'http://site.com';
$materia = get_json($site);


/*-----------------------------------
    Aqui o post é preparado e executado
-----------------------------------*/

$url_api = 'https://pstu.org.br/wp-json/wp/v2/posts';
$user_wp = 'AAA';
$pass_wp = 'BBB';

$api_response = wp_remote_post( $url_api, array(
 	'headers' => array(
		'Authorization' => 'Basic ' . base64_encode( $user_wp.':'.$pass_wp )
	),
	'body' => array(
	  	'status' => 'draft',
    		'title' => $materia->title->rendered,
		'content' => $materia->content,
		'slug' => $materia->slug
	)
) );

$body = json_decode( $api_response['body'] );

if( wp_remote_retrieve_response_message( $api_response ) === 'Created' ) {
	echo 'O post <b> ' . $body->title->rendered . ' </b> foi criado corretamente';
}

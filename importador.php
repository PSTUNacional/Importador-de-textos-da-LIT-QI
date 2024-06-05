<?php

// É preciso estar de acordo com o servidor
require('../wp-load.php');
require('image_importer.php');

$url = $_POST['furl'];
if (isset($_POST['fstatus'])) {
   $status = $_POST['fstatus'];
} else {
    $status = 'draft'; // Define 'rascunho' como padrão em caso de ausência
}

/*-----------------------------------
    
    Essas editorias são definidas
    pelos respectivos IDs no BD.
    É preciso adequá-las.

-----------------------------------*/

$editoria = $_POST['feditoria'];
switch ($editoria){
    case "internacional":
        $editoria = array('927');
        break;
    case "debate":
        $editoria = array('6986');
        break;
    case "socialismo":
        $editoria = array('936');
        break;
}
/*-----------------------------------
    1.Converte a URL da matéria na URL da API / JSON.
    2.Recebe o JSON.
    3.Prepara o conteúdo da matéria, execeto imagem destacada.
-----------------------------------*/

$to_insert = 'pt/wp-json/wp/v2/posts?slug=';
$json_url = str_replace('pt/', $to_insert, $url);
$materia = file_get_contents($json_url);
$materia = json_decode($materia);

// Adiciona um parágrafo no início para fazer o link cruzado de referência
$referencia = '<p>Publicado originalmente no <a target="_blank" href="'.$url.'">site da LIT-QI</a></p>';

/*-----------------------------------
    Aqui o post é preparado e executado
-----------------------------------*/

$url_api = 'https://pstu.org.br/wp-json/wp/v2/posts'; // Substitua pela API destino
$user = '__INSERT__YOUR__USERNAME__';
$pass = '__INSERT__YOUR__TOKEN__HERE__'; // Existe um pliugin que gera tokens de autorização para usuários.

$api_response = wp_remote_post( $url_api, array(
 	'headers'     => array(
		'Authorization' => 'Basic ' . base64_encode( $user.':'.$pass ),
	),
	'body'        => array(
	    'status' => $status,
	    'date_gmt' => $materia[0]->date_gmt,
    	'title' => $materia[0]->title->rendered,
		'content' => $referencia.$materia[0]->content->rendered,
		'slug' => $materia[0]->slug,
		'categories' => $editoria,
    ),
) );

$body = json_decode( $api_response['body'] );

/*-----------------------------------
    Este é o ID do post recém criado
-----------------------------------*/

$newpost_id = $body->id;

/*-----------------------------------
    1. Baixa a imagem destacada da matéria original.
    2. Faz upload na galeria.
    3. Atualiza imagem destacada da matéria recém criada.
-----------------------------------*/

function km_set_remote_image_as_featured_image( $post_id, $url, $attachment_data = array() ) {

  $download_remote_image = new KM_Download_Remote_Image( $url, $attachment_data );
  $attachment_id         = $download_remote_image->download();

  if ( ! $attachment_id ) {
    return false; 
  }

  return set_post_thumbnail( $post_id, $attachment_id );
}

$post_id = $newpost_id;
$url     = $materia[0]->jetpack_featured_media_url;

km_set_remote_image_as_featured_image( $post_id, $url );

/*-----------------------------------
    Redireciona o usuário para a página de edição do novo post.
-----------------------------------*/

header('Location: https://www.pstu.org.br/wp-admin/post.php?post='.$body->id.'&action=edit'); 

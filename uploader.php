<?php

require('../wp-load.php');
require('importador/image_import.php');


function km_set_remote_image_as_featured_image( $post_id, $url, $attachment_data = array() ) {

  $download_remote_image = new KM_Download_Remote_Image( $url, $attachment_data );
  $attachment_id         = $download_remote_image->download();

  if ( ! $attachment_id ) {
    return false; 
  }

  return set_post_thumbnail( $post_id, $attachment_id );
}



// Example 1: Here we are downloading and setting Google's logo as the featured image for post 123:

$post_id = 85586;
$url     = 'https://litci.org/pt/wp-content/uploads/2021/07/protesta-cuba-la-habana.jpg';

km_set_remote_image_as_featured_image( $post_id, $url );

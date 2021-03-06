<?php
/**
 * Add new image sizes
 */
add_image_size('post-thumb', 225, 160, TRUE);
add_image_size('post-image', 730, 390, TRUE ); //image to float at the top of the post. Reversed Out does these a lot.
add_image_size('homepage-widget',255,168, TRUE);
add_image_size('headshot', 144, 202, array('top','center'));

/* Display a custom favicon */
add_filter( 'genesis_pre_load_favicon', 'msdlab_favicon_filter' );
function msdlab_favicon_filter( $favicon_url ) {
    return get_stylesheet_directory_uri().'/lib/img/favicon.ico';
}
/**
 * Manipulate the featured image
 */
function msdlab_post_image() {
    global $post;
    //setup thumbnail image args to be used with genesis_get_image();
    if(is_cpt('team_member')){
        $size = 'headshot'; // Change this to whatever add_image_size you want
        $default_attr = array(
                'class' => "alignleft featured-image attachment-$size $size",
                'alt'   => $post->post_title,
                'title' => $post->post_title,
        );
    } else {
        $size = 'post-image'; // Change this to whatever add_image_size you want
        $default_attr = array(
                'class' => "alignnone featured-image attachment-$size $size",
                'alt'   => $post->post_title,
                'title' => $post->post_title,
        );
    }
    // This is the most important part!  Checks to see if the post has a Post Thumbnail assigned to it. You can delete the if conditional if you want and assume that there will always be a thumbnail
    if ( has_post_thumbnail() ) {
        print genesis_get_image( array( 'size' => $size, 'attr' => $default_attr ) );
    }

}

/**
 * Add new image sizes to the media panel
 */
if(!function_exists('msd_insert_custom_image_sizes')){
    function msd_insert_custom_image_sizes( $sizes ) {
    	global $_wp_additional_image_sizes;
    	if ( empty($_wp_additional_image_sizes) )
    		return $sizes;
    
    	foreach ( $_wp_additional_image_sizes as $id => $data ) {
    		if ( !isset($sizes[$id]) )
    			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
    	}
    
    	return $sizes;
    }
}
add_filter( 'image_size_names_choose', 'msd_insert_custom_image_sizes' );

add_shortcode('carousel','msd_bootstrap_carousel');
function msd_bootstrap_carousel($atts){
    $slidedeck = new SlideDeck();
    extract( shortcode_atts( array(
        'id' => NULL,
    ), $atts ) );
    $sd = $slidedeck->get($id);
    $slides = $slidedeck->fetch_and_sort_slides( $sd );
    $i = 0;
    foreach($slides AS $slide){
        $active = $i==0?' active':'';
        $items .= '
        <div style="background: url('.$slide['image'].') center top no-repeat #000000;background-size: cover;" class="item'.$active.'">
           '.$slide['content'].'
        </div>';
        $i++;
    }
    return msd_carousel_wrapper($items,array('id' => $id));
}

function msd_carousel_wrapper($slides,$params = array()){
    extract( array_merge( array(
    'id' => NULL,
    'navleft' => '‹',
    'navright' => '›'
    ), $params ) );
    return '
<div class="carousel slide" id="myCarousel_'.$id.'">
    <div class="carousel-inner">'.($slides).'</div>
    <a data-slide="prev" href="#myCarousel_'.$id.'" class="left carousel-control">'.$navleft.'</a>
    <a data-slide="next" href="#myCarousel_'.$id.'" class="right carousel-control">'.$navright.'</a>
</div>';
}
<?php
/**
 * Widget template. This template can be overriden using the "sp_template_image-widget_widget.php" filter.
 * See the readme.txt file for more info.
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

echo $before_widget;
if(is_front_page()){
    $image_details = wp_get_attachment_image_src( $instance['attachment_id'], $size );
    echo '<div class="widget-content" style="background-image: url('.$image_details[0].');">';
    if ( $link ) {
        $linktext = !empty( $title )?$title:'Read More >';
        echo '<div class="link"><a class="'.$this->widget_options['classname'].'-link button" href="'.$link.'" target="'.$linktarget.'">'.$linktext.' ></a><div class="clear"></div></div>';
    }
    echo '<div class="clear"></div>
    </div>';
}else{
    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
    echo '<div class="widget-content">';
    echo $this->get_image_html( $instance, true );
    
    if ( !empty( $description ) ) {
    	echo '<div class="'.$this->widget_options['classname'].'-description" >';
    	echo wpautop( $description );
    	echo "</div>";
    }
    if ( $link && $linktext != '' ) {
    	echo '<div class="link"><a class="'.$this->widget_options['classname'].'-link readmore" href="'.$link.'" target="'.$linktarget.'">'.$linktext.' ></a><div class="clear"></div></div>';
    }
    echo '<div class="clear"></div>
    </div>';
}
echo $after_widget;
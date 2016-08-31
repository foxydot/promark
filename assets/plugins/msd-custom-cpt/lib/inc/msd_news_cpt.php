<?php
/**
 * @package MSD News CPT
 * @version 0.1
 */

class MSDNewsCPT {

    //Properties
    var $cpt = 'msd_news';
	/**
    * PHP 4 Compatible Constructor
    */
    public function MSDNewsCPT(){$this->__construct();}

    /**
     * PHP 5 Constructor
     */
    function __construct(){
        global $current_screen;
        //"Constants" setup
        $this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
        $this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
        
        //Actions
        add_action( 'init', array(&$this,'add_metaboxes') );
        add_action( 'init', array(&$this,'register_cpt_news') );
        add_action('admin_enqueue_scripts', array(&$this,'add_admin_styles') );
        
        
        //Filters
        
        //Shortcodes
        add_shortcode( 'news-items', array(&$this,'list_news_stories') );
    }
        
        
    function add_metaboxes(){
        global $news_info,$wpalchemy_media_access;
        $news_info = new WPAlchemy_MetaBox(array
        (
            'id' => '_news_info',
            'title' => 'News Info',
            'types' => array($this->cpt),
            'context' => 'normal',
            'priority' => 'high',
            'template' => WP_PLUGIN_DIR.'/'.plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php').'lib/template/news-info.php',
            'autosave' => TRUE,
            'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
            'prefix' => '_news_' // defaults to NULL
        ));
    }
	
	function register_cpt_news() {
	
	    $labels = array( 
	        'name' => _x( 'News Items', 'news' ),
	        'singular_name' => _x( 'News Item', 'news' ),
	        'add_new' => _x( 'Add New', 'news' ),
	        'add_new_item' => _x( 'Add New News Item', 'news' ),
	        'edit_item' => _x( 'Edit News Item', 'news' ),
	        'new_item' => _x( 'New News Item', 'news' ),
	        'view_item' => _x( 'View News Item', 'news' ),
	        'search_items' => _x( 'Search News Items', 'news' ),
	        'not_found' => _x( 'No news items found', 'news' ),
	        'not_found_in_trash' => _x( 'No news items found in Trash', 'news' ),
	        'parent_item_colon' => _x( 'Parent News Item:', 'news' ),
	        'menu_name' => _x( 'News Items', 'news' ),
	    );
	
	    $args = array( 
	        'labels' => $labels,
	        'hierarchical' => false,
	        'description' => 'Customer News Items',
	        'supports' => array( 'title', 'editor', 'author', ),
	        'public' => true,
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'menu_position' => 20,
	        
	        'show_in_nav_menus' => true,
	        'publicly_queryable' => true,
	        'exclude_from_search' => false,
	        'has_archive' => true,
	        'query_var' => true,
	        'can_export' => true,
	        'rewrite' => array('slug'=>'news','with_front'=>false),
            'capability_type' => 'post'
	    );
	
	    register_post_type( $this->cpt, $args );
	    flush_rewrite_rules();
	}
		
	function list_news_stories( $atts ) {
	    global $news_info;
        add_action('print_footer_scripts',array(&$this,'print_footer_scripts'));
		extract( shortcode_atts( array(
		  'order_by' => 'post_date',
		  'order' => 'DES',
		  'perpage' => 100
		), $atts ) );
		
		$args = array( 'post_type' => $this->cpt, 'numberposts' => -1, 'order_by' => $order_by, 'order' => $order);

		$items = get_posts($args);
        if(count($items)>0){
        $i = 0;$p = 1;$year = 0;
	    foreach($items AS $item){
	        if($i%$perpage == 0){
	            $active = $p==1?' active':'';
                $publication_list .= '<div class="page page-'.$p.$active.'">';
	        }
            $curyear = date("Y",strtotime($item->post_date));
            if($order_by == 'post_date'){
                if($year != $curyear){
                    if($year != 0){
                        $publication_list .= '</ul></li>';
                    }
                    $publication_list .= '<li><h3>'.$curyear.'</h3><ul class="sublist sublist-'.$curyear.'">';
                }
            }
	        $news_info->the_meta($item->ID);
	        $title = $news_info->get_the_value('pdf-news-label')!=''?$news_info->get_the_value('pdf-news-label'):$item->post_title;
            if($news_info->get_the_value('pdf-news')!=''){
                $title = '<a class="pdf" href="'.$news_info->get_the_value('pdf-news').'">'.$title.'</a>';
            }
	     	$publication_list .= '
	     	<li>
	     		<h4><strong>'.$title.'</strong></h4>
			</li>';
            $year = $curyear;
            if($i%$perpage == $perpage-1){
                $paging .= '<li data-page="'.$p.'">'.$p.'</li>';
                $publication_list .= '</div>';
                $p++;
            }
	       $i++;
	     }
        if($order_by == 'post_date'){
            $publication_list .= '</ul></li>';
        }
        if($i%$perpage != $perpage-1){
            $publication_list .= '</div>';
            $paging .= '<li data-page="'.$p.'">'.$p.'</li>';
        }
		
		$ret = '<ul class="publication-list news-items">'.$publication_list.'</ul>';
		if($i>$perpage){
		    $ret .= '<ul class="publication-list-pagination">'.$paging.'</ul>';
        }
		$ret .= '<div class="clear"></div>';
        $ret .= '<style>
            .publication-list li{
                list-style: none outside none !important;
            }
        </style>';
        return $ret;
		}
	}	

        function print_footer_scripts(){
            print '
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $(".publication-list-pagination li").click(function(){
                        var page = $(this).attr("data-page");
                        $(".page").removeClass("active");
                        $(".page-"+page).addClass("active");
                    });
                });
            </script>
            ';
        }

        function add_admin_styles() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                wp_enqueue_style('thickbox');
                wp_enqueue_style('custom_meta_css',plugin_dir_url(dirname(__FILE__)).'/css/meta.css');
                wp_enqueue_style('jqueryui_smoothness','//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
            }
        }  

}
<?php 
if (!class_exists('MSDSyndicatedCPT')) {
    class MSDSyndicatedCPT {
        //Properties
        var $cpt = 'syndicated';

        //Methods
        /**
        * PHP 4 Compatible Constructor
        */
        public function MSDSyndicatedCPT(){$this->__construct();}
    
        /**
         * PHP 5 Constructor
         */
        function __construct(){
            global $current_screen;
            //"Constants" setup
            $this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
            $this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
            //Actions
            add_action( 'init', array(&$this,'register_cpt_syndicated') );
        }

        function register_cpt_syndicated() {
        
            $labels = array( 
                'name' => _x( 'Syndicated Posts', 'syndicated' ),
                'singular_name' => _x( 'Syndicated Post', 'syndicated' ),
                'add_new' => _x( 'Add New', 'syndicated' ),
                'add_new_item' => _x( 'Add New Syndicated Post', 'syndicated' ),
                'edit_item' => _x( 'Edit Syndicated Post', 'syndicated' ),
                'new_item' => _x( 'New Syndicated Post', 'syndicated' ),
                'view_item' => _x( 'View Syndicated Post', 'syndicated' ),
                'search_items' => _x( 'Search Syndicated Post', 'syndicated' ),
                'not_found' => _x( 'No syndicated found', 'syndicated' ),
                'not_found_in_trash' => _x( 'No syndicated found in Trash', 'syndicated' ),
                'parent_item_colon' => _x( 'Parent Syndicated Post:', 'syndicated' ),
                'menu_name' => _x( 'Syndicated Post', 'syndicated' ),
            );
        
            $args = array( 
                'labels' => $labels,
                'hierarchical' => false,
                'description' => 'Syndicated Post',
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ),
                'taxonomies' => array( 'category', 'post_tag' ),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,
                
                'show_in_nav_menus' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => true,
                'has_archive' => true,
                'query_var' => true,
                'can_export' => true,
                'rewrite' => array('slug'=>'cpiblog','with_front'=>false),
                'capability_type' => 'post'
            );
        
            register_post_type( $this->cpt, $args );
        }

        function custom_query( $query ) {
            if(!is_admin()){
                $is_project = ($query->query_vars['project_type'] || $query->query_vars['market_sector'])?TRUE:FALSE;
                if($query->is_main_query() && $query->is_search){
                    $searchterm = $query->query_vars['s'];
                    // we have to remove the "s" parameter from the query, because it will prevent the posts from being found
                    $query->query_vars['s'] = "";
                    
                    if ($searchterm != "") {
                        $query->set('meta_value',$searchterm);
                        $query->set('meta_compare','LIKE');
                    };
                    $query->set( 'post_type', array('post','page',$this->cpt) );
                }
                elseif( $query->is_main_query() && $query->is_archive && $is_project ) {
                    $meta_query = array(
                           array(
                               'key' => '_project_feature',
                               'value' => 'true',
                               'compare' => '='
                           )
                       );
                    $query->set( 'meta_query', $meta_query);
                    $query->set( 'post_type', array('post','page',$this->cpt) );
                    $query->set('posts_per_page', 30);
                }
            }
        }           
  } //End Class
} //End if class exists statement
<?php
/*
Plugin Name: MSD Feed Fixer
Description: Utility plugin to modify FeedWordPress (https://wordpress.org/plugins/feedwordpress/) output. Based on suggestions by ThatStevensGuy (http://www.thatstevensguy.com/programming/how-to-improve-feedwordpress/).
Author: Catherine Sandrick
Version: 0.0.1
Author URI: http://msdlab.com
*/

/*
 * Pull in some stuff from other files
*/
if(!function_exists('requireDir')){
	function requireDir($dir){
		$dh = @opendir($dir);

		if (!$dh) {
			throw new Exception("Cannot open directory $dir");
		} else {
			while($file = readdir($dh)){
				$files[] = $file;
			}
			closedir($dh);
			sort($files); //ensure alpha order
			foreach($files AS $file){
				if ($file != '.' && $file != '..') {
					$requiredFile = $dir . DIRECTORY_SEPARATOR . $file;
					if ('.php' === substr($file, strlen($file) - 4)) {
						require_once $requiredFile;
					} elseif (is_dir($requiredFile)) {
						requireDir($requiredFile);
					}
				}
			}
		}
		unset($dh, $dir, $file, $requiredFile);
	}
}
if (!class_exists('MSDFixFeedWordPress')) {
    class MSDFixFeedWordPress {
    	//Properites
    	/**
    	 * @var string The plugin version
    	 */
    	var $version = '0.0.1';
    	
    	/**
    	 * @var string The options string name for this plugin
    	 */
    	var $optionsName = 'msd_fix_feedwordpress';
    	
    	/**
    	 * @var string $nonce String used for nonce security
    	 */
    	var $nonce = 'msd_fix_feedwordpress-update-options';
    	
    	/**
    	 * @var string $localizationDomain Domain used for localization
    	 */
    	var $localizationDomain = "msd_fix_feedwordpress";
    	
    	/**
    	 * @var string $pluginurl The path to this plugin
    	 */
    	var $plugin_url = '';
    	/**
    	 * @var string $pluginurlpath The path to this plugin
    	 */
    	var $plugin_path = '';
    	
    	/**
    	 * @var array $options Stores the options for this plugin
    	 */
    	var $options = array();
        //Methods
        /**
        * PHP 4 Compatible Constructor
        */
        function MSDFixFeedWordPress(){$this->__construct();}
        
        /**
        * PHP 5 Constructor
        */        
        function __construct(){
        	//"Constants" setup
        	$this->plugin_url = plugin_dir_url(__FILE__).'/';
        	$this->plugin_path = plugin_dir_path(__FILE__).'/';
        	//Initialize the options
        	$this->get_options();
        	//check requirements
        	register_activation_hook(__FILE__, array(&$this,'check_requirements'));
        	//get sub-packages
        	//requireDir(plugin_dir_path(__FILE__).'/lib/inc');
            /**
             * FeedWordPress - Assign the WP default category to posts if FWP 
             * doesn't find a matching category.
             */
            //add_action('update_syndicated_item', array(&$this,'tsg_ensure_fwp_post_has_cats'), 10, 2);
            //add_action('post_syndicated_item', array(&$this,'tsg_ensure_fwp_post_has_cats'), 10, 2);
            /**
             * FeedWordPress - Remove 'Read More' permalinks from excerpts.
             */
            //add_action('post_syndicated_item', array(&$this,'tsg_strip_fwp_excerpt_permalink'), 10, 2);
            /**
             * FeedWordPress - Remove duplicate posts during syndication.
             */
            add_action('post_syndicated_item', array(&$this,'tsg_remove_fwp_duplicate_posts'), 11, 2);
            /**
             * Yoast WordPress SEO - Set Robots 'noindex' for FeedWordPress posts.
             */
            add_action( 'wpseo_head', array(&$this,'tsg_noindex_fwp_posts') );
        }

        /**
         * FeedWordPress - Assign the WP default category to posts if FWP 
         * doesn't find a matching category.
         */
        
        function tsg_ensure_fwp_post_has_cats( $id, SyndicatedPost $class )
        {
            $categories = wp_get_post_categories( $id );
        
            if ( empty($categories) )
                wp_set_post_categories( $id, array(get_option('default_category')) );
        }
        
        /**
         * FeedWordPress - Remove 'Read More' permalinks from excerpts.
         */
        function tsg_strip_fwp_excerpt_permalink( $id, SyndicatedPost $class )
        {
            global $wpdb;
        
            $excerpt = get_post_field('post_excerpt', $id);
            $excerpt = trim(preg_replace("/(&hellip;|&#8230;) <a href=.+<\/a>/", '', $excerpt));
        
            $wpdb->update(
                $wpdb->posts,
                array( 'post_excerpt' => $excerpt ),
                array( 'ID' => intval($id) )
            );
        }
        
        /**
         * FeedWordPress - Remove duplicate posts during syndication.
         */
        function tsg_remove_fwp_duplicate_posts( $id, SyndicatedPost $class )
        {
            global $wpdb;
            $syndicated_post = get_post( $id );
        
            $duplicate_post = $wpdb->get_row( $wpdb->prepare("
                SELECT * FROM $wpdb->posts
                WHERE post_title = %s
                AND ( post_date BETWEEN DATE_SUB( %s, INTERVAL 1 HOUR ) AND DATE_ADD( %s, INTERVAL 1 HOUR ) )
                AND ID != %d
            ", array(
                $syndicated_post->post_title,
                $syndicated_post->post_date,
                $syndicated_post->post_date,
                intval( $id )
            )) );
        
            if ( !$duplicate_post )
                return;
        
            wp_delete_post( $id );
        }
        
        /**
         * Yoast WordPress SEO - Set Robots 'noindex' for FeedWordPress posts.
         */
        function tsg_noindex_fwp_posts()
        {
            if ( is_single() && is_syndicated() )
                echo '<meta name="robots" content="noindex, follow"/>' . "\n";
        }
        

        /**
         * @desc Loads the options. Responsible for handling upgrades and default option values.
         * @return array
         */
        function check_options() {
        	$options = null;
        	if (!$options = get_option($this->optionsName)) {
        		// default options for a clean install
        		$options = array(
        				'version' => $this->version,
        				'reset' => true
        		);
        		update_option($this->optionsName, $options);
        	}
        	else {
        		// check for upgrades
        		if (isset($options['version'])) {
        			if ($options['version'] < $this->version) {
        				// post v1.0 upgrade logic goes here
        			}
        		}
        		else {
        			// pre v1.0 updates
        			if (isset($options['admin'])) {
        				unset($options['admin']);
        				$options['version'] = $this->version;
        				$options['reset'] = true;
        				update_option($this->optionsName, $options);
        			}
        		}
        	}
        	return $options;
        }
        
        /**
         * @desc Retrieves the plugin options from the database.
         */
        function get_options() {
        	$options = $this->check_options();
        	$this->options = $options;
        }
        /**
         * @desc Check to see if requirements are met
         */
        function check_requirements(){
        	if(!is_plugin_active('feedwordpress')){
        	    print 'You need FeedWordPress for this plugin.';
                return FALSE;
        	}
        }
        /**
         * @desc Checks to see if the given plugin is active.
         * @return boolean
         */
        function is_plugin_active($plugin) {
        	return in_array($plugin, (array) get_option('active_plugins', array()));
        }
        /***************************/
  } //End Class
} //End if class exists statement

//instantiate
$msd_fox_feedwordpress = new MSDFixFeedWordPress();
<?php
/*
Plugin Name: MSD Site Settings
Description: Provides settings panel for several social/address options and widgets/shortcodes/functions for display.
Version: 0.1
Author: Catherine M OBrien Sandrick (CMOS)
Author URI: http://msdlab.com/biological-assets/catherine-obrien-sandrick/
License: GPL v2
*/
define('MSD_ALT_API','http://msdlab.com/plugin-api/');

class MSDSocial{
	private $the_path;
	private $the_url;
	public $icon_size;
	
	function MSDSocial(){
		$this->the_path = plugin_dir_path(__FILE__);
		$this->the_url = plugin_dir_url(__FILE__);
		$this->icon_size = get_option('msdsocial_icon_size')?get_option('msdsocial_icon_size'):'24';
		/*
		 * Pull in some stuff from other files
		 */
		$this->requireDir($this->the_path . '/inc');
		//wp_enqueue_style('msd-social-style',$this->the_url.'css/style.css');
		//wp_enqueue_style('msd-social-style-'.$this->icon_size,$this->the_url.'css/style'.$this->icon_size.'.css');
		add_shortcode('msd-address',array(&$this,'get_address'));
		add_shortcode('msd-bizname',array(&$this,'get_bizname'));
		add_shortcode('msd-copyright',array(&$this,'get_copyright'));
		add_shortcode('msd-digits',array(&$this,'get_digits'));
		add_shortcode('msd-social',array(&$this,'social_media'));
	}

//contact information
function get_bizname(){
	$ret .= (get_option('msdsocial_biz_name')!='')?get_option('msdsocial_biz_name'):get_bloginfo('name');
	return $ret;
}
function get_address(){
	if((get_option('msdsocial_street')!='') || (get_option('msdsocial_city')!='') || (get_option('msdsocial_state')!='') || (get_option('msdsocial_zip')!='')) {
		$ret = '<address itemscope itemtype="http://schema.org/LocalBusiness">';
			$ret .= (get_option('msdsocial_street')!='')?'<span itemprop="streetAddress">'.get_option('msdsocial_street').'</span> ':'';
			$ret .= (get_option('msdsocial_street2')!='')?'<span itemprop="streetAddress">'.get_option('msdsocial_street2').'</span> ':'';
			$ret .= (get_option('msdsocial_city')!='')?'<span itemprop="addressLocality">'.get_option('msdsocial_city').'</span>, ':'';
			$ret .= (get_option('msdsocial_state')!='')?'<span itemprop="addressRegion">'.get_option('msdsocial_state').'</span> ':'';
			$ret .= (get_option('msdsocial_zip')!='')?'<span itemprop="postalCode">'.get_option('msdsocial_zip').'</span> ':'';
		$ret .= '</address>';
		return $ret;
		} else {
			return false;
		} 
}

function get_digits($dowrap = TRUE,$sep = " | "){
        $sepsize = count($sep);
		if((get_option('msdsocial_phone')!='') || (get_option('msdsocial_tollfree')!='') || (get_option('msdsocial_fax')!='')) {
		    if((get_option('msdsocial_tracking_phone')!='')){
		        if(wp_is_mobile()){
		          $phone .= 'Phone: <a href="tel:+1'.get_option('msdsocial_tracking_phone').'">'.get_option('msdsocial_tracking_phone').'</a> ';
		        } else {
		          $phone .= 'Phone: <span>'.get_option('msdsocial_tracking_phone').'</span> ';
		        }
		      $phone .= '<span itemprop="telephone" style="display: none;">'.get_option('msdsocial_phone').'</span> ';
		    } else {
		        if(wp_is_mobile()){
		          $phone .= (get_option('msdsocial_phone')!='')?'Phone: <a href="tel:+1'.get_option('msdsocial_phone').'" itemprop="telephone">'.get_option('msdsocial_phone').'</a> ':'';
		        } else {
                  $phone .= (get_option('msdsocial_phone')!='')?'Phone: <span itemprop="telephone">'.get_option('msdsocial_phone').'</span> ':'';
		        }
		    }
            if((get_option('msdsocial_tracking_tollfree')!='')){
                if(wp_is_mobile()){
                  $tollfree .= 'Phone: <a href="tel:+1'.get_option('msdsocial_tracking_tollfree').'">'.get_option('msdsocial_tracking_tollfree').'</a> ';
                } else {
                  $tollfree .= 'Phone: <span>'.get_option('msdsocial_tracking_tollfree').'</span> ';
                }
              $tollfree .= '<span itemprop="telephone" style="display: none;">'.get_option('msdsocial_tollfree').'</span> ';
            } else {
                if(wp_is_mobile()){
                  $tollfree .= (get_option('msdsocial_tollfree')!='')?'Phone: <a href="tel:+1'.get_option('msdsocial_tollfree').'" itemprop="telephone">'.get_option('msdsocial_tollfree').'</a> ':'';
                } else {
                  $tollfree .= (get_option('msdsocial_tollfree')!='')?'Phone: <span itemprop="telephone">'.get_option('msdsocial_tollfree').'</span> ':'';
                }
            }
            $fax = (get_option('msdsocial_fax')!='')?'Fax: <span itemprop="faxNumber">'.get_option('msdsocial_fax').'</span> ':'';
            $ret = $phone;
            $ret .= ($phone!='' && $tollfree!='')?$sep:'';
            $ret .= $tollfree;
            $ret .= (!strpos($ret,$sep,$sepsize))?$sep:'';
            $ret .= $fax;
 		  if($dowrap){$ret = '<address itemscope itemtype="http://schema.org/LocalBusiness">'.$ret.'</address>';}
		return $ret;
		} else {
			return false;
		} 
}

function get_phone($dowrap = TRUE){
        if((get_option('msdsocial_phone')!='')) {
            if((get_option('msdsocial_tracking_phone')!='')){
                if(wp_is_mobile()){
                  $ret .= '<a href="tel:+1'.get_option('msdsocial_tracking_phone').'">'.get_option('msdsocial_tracking_phone').'</a> ';
                } else {
                  $ret .= '<span>'.get_option('msdsocial_tracking_phone').'</span> ';
                }
              $ret .= '<span itemprop="telephone" style="display: none;">'.get_option('msdsocial_phone').'</span> ';
            } else {
                if(wp_is_mobile()){
                  $ret .= (get_option('msdsocial_phone')!='')?'<a href="tel:+1'.get_option('msdsocial_phone').'" itemprop="telephone">'.get_option('msdsocial_phone').'</a> ':'';
                } else {
                  $ret .= (get_option('msdsocial_phone')!='')?'<span itemprop="telephone">'.get_option('msdsocial_phone').'</span> ':'';
                }
            }
          if($dowrap){$ret = '<address itemscope itemtype="http://schema.org/LocalBusiness">'.$ret.'</address>';}
        return $ret;
        } else {
            return false;
        } 
}
//create copyright message
function copyright($address = TRUE){
	if($address){
		$ret .= $this->msdsocial_get_address();
		$ret .= $this->msdsocial_get_digits();
	}
	$ret .= 'Copyright &copy;'.date('Y').' ';
	$ret .= $this->msdsocial_get_bizname();
	print $ret;
}


function social_media($attr){
    ?>
    <div id="social-media" class="social-media">
            <?php if(get_option('msdsocial_linkedin_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_linkedin_link'); ?>" class="li fa fa-linkedin" title="LinkedIn" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_twitter_user')!=""){ ?>
            <a href="http://www.twitter.com/<?php echo get_option('msdsocial_twitter_user'); ?>" class="tw fa fa-twitter" title="Follow Us on Twitter!" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_google_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_google_link'); ?>" class="gl fa fa-google-plus" title="Google+" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_facebook_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_facebook_link'); ?>" class="fb fa fa-facebook" title="Join Us on Facebook!" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_flickr_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_flickr_link'); ?>" class="fl fa fa-flickr" title="Flickr" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_youtube_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_youtube_link'); ?>" class="yt fa fa-youtube" title="YouTube" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_sharethis_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_sharethis_link'); ?>" class="st fa fa-share-this" title="ShareThis" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_pinterest_link')!=""){ ?>
            <a href="<?php echo get_option('msdsocial_pinterest_link'); ?>" class="pin fa fa-pinterest" title="Pinterest" target="_blank"></a>
            <?php }?>
            <?php if(get_option('msdsocial_show_feed')!=""){ ?>
            <a href="<?php bloginfo('rss2_url'); ?>" class="rss fa fa-rss" title="RSS Feed" target="_blank"></a>
            <?php }?>
        </div>
        <?php 
}

function requireDir($dir){
	$dh = @opendir($dir);

	if (!$dh) {
		throw new Exception("Cannot open directory $dir");
	} else {
		while (($file = readdir($dh)) !== false) {
			if ($file != '.' && $file != '..') {
				$requiredFile = $dir . DIRECTORY_SEPARATOR . $file;
				if ('.php' === substr($file, strlen($file) - 4)) {
					require_once $requiredFile;
				} elseif (is_dir($requiredFile)) {
					requireDir($requiredFile);
				}
			}
		}
	closedir($dh);
	}
	unset($dh, $dir, $file, $requiredFile);
}
	//end of class
}
$msd_social = new MSDSocial();
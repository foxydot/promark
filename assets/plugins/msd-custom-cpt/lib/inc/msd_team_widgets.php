<?php
class MSD_Widget_Team_Viewpoints extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_team_viewpoints', 'description' => __('Viewpoint for a team user. Show on '));
        $control_ops = array('width' => 400, 'height' => 350);
        
        parent::__construct('team_viewpoints', __('Team Viewpoints'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        global $wp_query,$contact_info;
        $team_member = $wp_query->get_queried_object();
        $team_member_id = $team_member->ID;
        $contact_info->the_meta($team_member_id);
        $team_member_author_id = $contact_info->get_the_value('_team_user_id');
        if($team_member_author_id){
        extract($args);
        $query_args = array(
        'post_type' => 'post',
        'author' => $team_member_author_id,
        'posts_per_page'   => 3,
        );
        $viewpoints = get_posts($query_args);
        if(count($viewpoints)>0){
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        echo $before_widget; ?>
        <div class="team-viewpoints">
            <?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
            <div class="col-md-12">
            <?php foreach($viewpoints AS $viewpoint){
                print '<div class="article">
                <div class="title-link">'.$viewpoint->post_title.'</div>
                <div class="author">'.get_the_author_meta('display_name',$viewpoint->post_author).'</div>
                <div class="date">'.get_the_time( 'l, F j, Y', $viewpoint ).'</div>
                <a class="read-more" href="'.get_post_permalink($viewpoint->ID).'">Read More ></a>
                </div>';
            } ?>
            </div>
            <div class="clear"></div>
        </div>
        <?php
        
        print $url?'<a href="'.$url.'"'.$target.' class="msd-widget-text"></a>':'';
        echo $after_widget;
        }
        }
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
        $title = strip_tags($instance['title']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>        
<?php
    }
    
    function init() {
        if ( !is_blog_installed() )
            return;
        register_widget('MSD_Widget_Team_Viewpoints');
    }    
}

    
    add_action('widgets_init',array('MSD_Widget_Team_Viewpoints','init'),10);
?>
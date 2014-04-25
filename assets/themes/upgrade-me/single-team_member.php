<?php
$msd_team_display = new MSDTeamDisplay;
add_action('msdlab_after_team_member_headshot',array(&$msd_team_display,'msd_team_member_contact_info'));
add_action('genesis_entry_header',array(&$msd_team_display,'msd_do_team_member_job_title'));
remove_action( 'genesis_entry_header', 'genesis_post_info', 12);
add_action('genesis_entry_content',array(&$msd_team_display,'msd_team_member_contact_info'),6);
//global $wp_filter; ts_var( $wp_filter['genesis_entry_header'] );
genesis();
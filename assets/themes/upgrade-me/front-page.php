<?php
//remove sidebars (jsut in case)
remove_all_actions('genesis_sidebar');
remove_all_actions('genesis_sidebar_alt');
/**
 * hero + 3 widgets
 */
//add the hero
add_action('genesis_after_header','msd_child_hero');
//add the callout
//move footer and add three homepage widgets
remove_action('genesis_before_footer','genesis_footer_widget_areas');
add_action('genesis_before_footer','msdlab_homepage_widgets');
add_action('genesis_before_footer','msdlab_callout');
/**
 * long scrollie
 */
//remove_all_actions('genesis_loop');
//add_action('genesis_loop','msd_scrollie_page');

genesis();
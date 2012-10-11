<?php
/**
 * The Sidebar containing the primary widget area.
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */
 
/* When we call the dynamic_sidebar() function, it'll spit out
 * the widgets for that widget area. If it instead returns false,
 * then the sidebar simply doesn't exist, so we'll hard-code in
 * some default sidebar stuff just in case.
 */
 
get_sidebar('standard');

$sidebar = '';
if ( is_page() ) {
	global $post;
	$custom = get_post_meta($post->ID,'_progo_sidebar');
	$sidebar = $custom[0];
}
if ( $sidebar == '' ) {
	$sidebar = 'main';
	if ( is_front_page() ) {
		$sidebar = 'home';
	}
}
dynamic_sidebar( $sidebar ); ?>
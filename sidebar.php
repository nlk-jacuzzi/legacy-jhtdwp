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
 
$options = get_option('progo_options');

if ( is_active_sidebar('calltoday') ) {
	dynamic_sidebar('calltoday');
} else {
	$locs = get_posts( array(
				'numberposts'	=> -1,
				'post_type'		=> 'progo_loc',
				'orderby'		=> 'menu_order',
				'order'			=> 'ASC'
			));
	if ( count($locs) > 0 ) {
		$loc = get_post_meta($locs[0]->ID, '_progo_loc', true);
?>
<div class="block calltoday"><h2><strong>CALL TODAY :</strong> <?php echo esc_attr($loc['businessphone']); ?></h2></div>
<?php
	}
}
wp_nav_menu( array( 'container' => false, 'theme_location' => 'mainmenu', 'menu_id' => 'mainmenu', 'fallback_cb' => 'progo_nomenu_cb' ) );

$sidebar = 'main';
/*
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
*/
dynamic_sidebar( $sidebar );

get_sidebar('jhtblog');

?>
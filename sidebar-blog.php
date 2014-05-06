<?php
/**
 * Sidebar for Blog areas.
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */
?>
<?php
/* When we call the dynamic_sidebar() function, it'll spit out
 * the widgets for that widget area. If it instead returns false,
 * then the sidebar simply doesn't exist, so we'll hard-code in
 * some default sidebar stuff just in case.
 */
$options = get_option('progo_options');

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
wp_nav_menu( array( 'container' => false, 'theme_location' => 'mainmenu', 'menu_id' => 'mainmenu', 'fallback_cb' => 'progo_nomenu_cb' ) );
 
if ( ! dynamic_sidebar( 'blog' ) ) :

if ( $options['whatblog'] == 0 ) {
	// include from JHT
	?>
    <div class="block">
    <h3 class="title">Read Our Hot Tub Blog</h3>
    <?php
		/*
		global $post;
		$myposts = get_posts('numberposts=3');
		foreach($myposts as $post) :
		echo '<p><a href="'. get_permalink() .'">'. get_the_title() .'</a><br /><small>Added by: <a href="'. get_author_posts_url(get_the_author_meta('ID', $post->post_author)) .'">'.get_the_author_meta('display_name',$post->post_author) .'</a> on '. get_the_date('F d, Y') .'</small></p>';
		//echo '<pre style="display:none">'. print_r($post,true) .'</pre>';
		endforeach;
		*/
		$nlkfeed = 'http://www.jacuzzi.com/hot-tubs/feed/';
		include_once(ABSPATH . WPINC . '/feed.php');
		$rss = fetch_feed($nlkfeed);
		$instamax = 0;
		if (!is_wp_error( $rss ) ) { // Checks that the object is created correctly 
			// Figure out how many total items there are, but limit it to 5. 
			$instamax = $rss->get_item_quantity(4); 
			
			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items(0, $instamax); 
			if ($instamax > 0) {
				// Loop through each feed item and display each item as a hyperlink.
				$itemcount = 0;
				foreach ( $rss_items as $item ) {
					/*
					$bod = $item->get_description();
					if ( strlen( $bod ) > 140 ) {
						$bod = substr( $bod, 0, strrpos( substr($bod,0,140), ' ') ) .'... <a href="'. $item->get_link() .'" target="_blank" class="rm">Read More</a>';
					}
					*/
					echo '<p class="blogpost"><a href="'. $item->get_link() .'" target="_blank" class="title"><em>'. $item->get_title() .'</em><span class="rm">Read More</span></a></p>';
					$itemcount++;
				}
			}
		}
        ?>
    </div>
    <?php
}
endif; // end primary widget area ?>
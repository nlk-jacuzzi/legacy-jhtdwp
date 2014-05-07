<?php
/**
 * reusable Sidebar code to include blog posts from jacuzzi.com/hot-tubs instead of from this particular site
 *
 * @package JHTDWP
 * @since JHTDWP 1.3.2
 */

$options = get_option('progo_options');
if ( $options['whatblog'] == 0 ) {
// include from main JHT site
?>
    <div class="block">
    <h3 class="title">Read Our Hot Tub Blog</h3>
    <?php
		$jhtfeed = 'http://www.jacuzzi.com/hot-tubs/feed/';
		include_once(ABSPATH . WPINC . '/feed.php');
		$rss = fetch_feed($jhtfeed);
		$instamax = 0;
		if (!is_wp_error( $rss ) ) {
			// Checks that the object is created correctly 
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
?>
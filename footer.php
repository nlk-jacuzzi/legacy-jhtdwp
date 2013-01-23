<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */
?>
<div id="ptop">
<div id="pagetop" class="slides grid_8 Layout1">
<?php
global $wp_query, $post;
$original_query = $wp_query;
$slides = get_posts('post_type=progo_homeslide&post_status=publish&posts_per_page=-1&orderby=menu_order&order=ASC');
$count = count($slides);
$oneon = false;
foreach ( $slides as $s ) {
	$on = '';
	if ( $oneon == false ) {
		$oneon = true;
		$on = ' on';
	}
	
	$slidecustom = get_post_meta($s->ID,'_progo_slidecontent');
	$slidecontent = (array) $slidecustom[0];
	$bg = ' '. $slidecontent['textcolor'];
	if ( get_post_thumbnail_id( $s->ID ) ) {
		$thm = get_the_post_thumbnail($s->ID, 'homeslide');
		$thmsrc = strpos($thm, 'src="') + 5;
		$thmsrc = substr($thm, $thmsrc, strpos($thm,'"',$thmsrc+1)-$thmsrc);
		$bg .= ' custombg " style="background-image: url('. $thmsrc .')';
	}
	
	echo '<div class="textslide slide'. $on . $bg .'"><div class="inside">';
	if ( $slidecontent['showtitle'] == 'Show' ) echo '<div class="page-title">'. wp_kses($s->post_title,array()) .'</div>';
	echo '<div class="content productcol">'. apply_filters('the_content',$slidecontent['text']) .'</div></div>'. ($pagetopW==12 ? '<div class="shadow"></div>' : '') .'</div>';
}
if ( $oneon == true && $count > 1 ) { ?>
<div class="ar"><a href="#p" title="Previous Slide"></a><a href="#n" class="n" title="Next Slide"></a></div>
<script type="text/javascript">
progo_timing = <?php $hsecs = absint($options['homeseconds']); echo $hsecs > 0 ? $hsecs * 1000 : "0"; ?>;
</script>
<?php
}
do_action('progo_pagetop');
if ($pagetopW==8) echo '<div class="shadow"></div>';
?>
</div>
<?php
	get_sidebar('pbpform');
?>
</div>
	</div><!-- #page -->
	<div id="ftr" class="container_12">
    <div class="grid_12<?php
$fmenu = wp_nav_menu( array( 'container' => false, 'theme_location' => 'ftrlnx', 'echo' => 0, 'fallback_cb' => 'progo_nomenu_cb' ) );
if( strpos( $fmenu, '</li>' ) > 0 ) {
	$fmenu = str_replace('</li>','&nbsp;&nbsp;|&nbsp;&nbsp;</li>',substr($fmenu,0,strrpos($fmenu,'</li>'))) . "</li>\n</ul>";
	echo '">'. $fmenu .'<br />';
} else {
	echo ' nom">';
}
	
$options = get_option('progo_options');

$locs = get_posts( array(
			'numberposts'	=> -1,
			'post_type'		=> 'progo_loc',
			'orderby'		=> 'menu_order',
			'order'			=> 'ASC'
		));
if ( count($locs) > 0 ) {
	foreach ( $locs as $k => $l ) {
		if ( $k > 0 ) echo ' &nbsp;|&nbsp; ';
		$loc = get_post_meta($l->ID, '_progo_loc', true);
		if( ($loc['businessaddy'] != "") || ($loc['businessCSZ'] != "") || ($loc['businessphone'] != "") ) {
			echo ( ($loc['businessaddy'] != "") ? $loc['businessaddy']. ", " : "") . $loc['businessCSZ'];
			if( ($loc['businessaddy'] != "") || ($loc['businessCSZ'] != "") ) echo " - ";
			echo $loc['businessphone'];
		}
	}
}
echo '<br />';
if ( isset( $options['copyright'] ) ) {
	echo wp_kses($options['copyright'],array());
} else {
	echo '&copy; Copyright '. date('Y') .', All Rights Reserved';
}
?><br />Features and specifications are subject to change. See dealer for details.
</div>
</div><!-- #ftr -->
</div><!-- #wrap -->
</div>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>

<?php
/**
 * Homepage Form stored in another Sidebar.
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */

$options = get_option( 'progo_options' );
if ( is_array($options) == false ) {
	$options = array(
		'form' => ''
	);
}
?>
<div class="pbpform"><a name="pbpform"></a>
<table class="tar" width="100%"><tr><td><?php
if ( is_active_sidebar('topright') ) {
	dynamic_sidebar('topright');
} else {
	$locs = get_posts( array(
				'numberposts'	=> 1,
				'post_type'		=> 'location',
				'orderby'		=> 'menu_order',
				'order'			=> 'ASC'
	));
	if ( count($locs) == 0 ) {
		$loc = array(
			'businessaddy' => '',
			'businessCSZ' => ''
		);
	} else {
		$loc = get_post_meta($locs[0]->ID, '_location', true);
	}
	echo esc_attr($loc['businessaddy']) .'<br />'.  esc_attr($loc['businessCSZ']);
}
?></td></tr></table>
<?php echo apply_filters('the_content', $options['form']); ?>
</div>
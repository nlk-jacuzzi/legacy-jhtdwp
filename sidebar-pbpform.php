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
		'businessaddy' => '',
		'businessCSZ' => '',
	);
}
?>
<div class="pbpform"><a name="pbpform"></a>
<table class="tar" width="100%"><tr><td><?php
echo esc_attr($options['businessaddy']) .'<br />'.  esc_attr($options['businessCSZ']);
?></td></tr></table>
<?php echo apply_filters('the_content', $options['form']); ?>
</div>
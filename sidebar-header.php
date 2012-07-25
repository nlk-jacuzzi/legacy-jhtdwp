<?php
/**
 * Widget area in the header? Yes.
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */
?>
<div class="grid_4">
<?php
/* When we call the dynamic_sidebar() function, it'll spit out
 * the widgets for that widget area. If it instead returns false,
 * then the sidebar simply doesn't exist, so we'll hard-code in
 * some default sidebar stuff just in case.
 */
if ( ! dynamic_sidebar( 'header' ) ) :
?>
<div class="hblock support">
    <div class="inside">
        <?php
		$options = get_option('progo_options');
		
		echo esc_attr($options['businessaddy']) .'<br />'.  esc_attr($options['businessCSZ']);
		?>
    </div>
</div>
<?php endif; // end primary widget area ?>
</div>
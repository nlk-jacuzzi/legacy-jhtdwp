<?php
/**
 * Template Name: Advanced Hydrotherapy
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */

get_header();
?>
<div id="container">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="main">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry">
<?php
	global $post;
	$pcontent = $post->post_content;
	if ( $pcontent == '... Page content supplied by theme template file ...' ) {
		$pcontent = jhtdwp_default_page_content('hydrotherapy');
	}
	echo apply_filters('the_content', $pcontent);
?>
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/hydrotherapy.jpg" alt="Advanced Hydrotherapy" width="640" height="781" /></p>
<h1 class="bigger">Jets</h1>
<p>High-valume, low-pressure pumps support the exclusive PowerPro jet system in delivering a bold hydromassage. A Patented process creates a 50/50 air-to-water mixture that introduces air from all around the jets for a soothing, yet effective, professional-quality massage. To test the jets in a hot tub, contact your local dealer.</p>
<img src="<?php bloginfo('template_url'); ?>/images/dwp/jets.jpg" alt="Jets" width="640" height="390" /><br />
<img src="<?php bloginfo('template_url'); ?>/images/dwp/reflexology.jpg" alt="Reflexology" width="640" height="240" /><br />
<img src="<?php bloginfo('template_url'); ?>/images/dwp/aromatherapy.jpg" alt="Aromatherapy" width="640" height="241" /><br />
<img src="<?php bloginfo('template_url'); ?>/images/dwp/chromatherapy.jpg" alt="Chromatherapy" width="640" height="242" />
</div><!-- .entry -->
</div><!-- #post-## -->
</div><!-- #main -->
<?php endwhile; ?>
<div class="secondary">
<?php get_sidebar(); ?>
</div>
</div><!-- #container -->
<?php get_footer(); ?>

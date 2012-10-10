<?php
/**
 * Template Name: The Jacuzzi Difference
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
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/about.jpg" alt="About Jacuzzi" width="640" height="512" /></p>
<h1 class="bigger">Innovation Has Always Been at Our Core</h1>
<p>Jacuzzi believes baths and spas are about more than just getting clean, so we provide high-performance products that celebrate water's ability to refresh and rejuvenate in inspiring ways. Our product innovations are sparked by consumer insights and needs and we constantly strive to deliver experiences that enable you to transition to a better state of mind and body.</p>
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/innovation.jpg" alt="Innovation" width="640" height="390" /></p>
<img src="<?php bloginfo('template_url'); ?>/images/dwp/awards.jpg" alt="Awards and Recognition" width="637" height="59" />
</div><!-- .entry -->
</div><!-- #post-## -->
</div><!-- #main -->
<?php endwhile; ?>
<div class="secondary">
<?php get_sidebar(); ?>
</div>
</div><!-- #container -->
<?php get_footer(); ?>

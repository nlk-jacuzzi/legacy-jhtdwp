<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
<?php the_content(); ?>
</div><!-- .entry -->
</div><!-- #post-## -->
</div><!-- #main -->
<?php endwhile; ?>
<div class="secondary">
<?php get_sidebar(); ?>
</div>
</div><!-- #container -->
<?php get_footer(); ?>

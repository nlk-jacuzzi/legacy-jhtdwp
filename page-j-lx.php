<?php
/**
 * Template Name: J-LX Collection
 *
 * @package JHTDWP
 * @since JHTDWP 1.0
 */

get_header();
?>
<div id="container">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="main">
<div id="coll">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry">
<?php the_content(); ?>
</div><!-- .entry -->
</div><!-- #post-## -->
</div>
<div id="colr">
<div class="tubs">
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-lx.jpg" alt="J-LX" width="171" height="186" />
        <div class="info">
            <strong>J-LX</strong><br />
            Seats: 6 Adults<br />
            Jets: 36<br />
            <a href="http://www.jacuzzihottubs.com/j-lx/j-lx/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-lxl.jpg" alt="J-LXL" width="171" height="186" />
        <div class="info">
            <strong>J-LXL</strong><br />
            Seats: 5 Adults<br />
            Jets: 38<br />
            <a href="http://www.jacuzzihottubs.com/j-lx/j-lxl/">view details</a>
        </div>
    </div>
</div>
</div>
</div><!-- #main -->
<?php endwhile; ?>
<div class="secondary">
<?php get_sidebar(); ?>
</div>
</div><!-- #container -->
<?php get_footer(); ?>

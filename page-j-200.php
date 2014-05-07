<?php
/**
 * Template Name: J-200 Collection
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
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-280.jpg" alt="J-280" width="171" height="186" />
        <div class="info">
            <strong>J-280</strong><br />
            Seats: 6-7 Adults<br />
            Jets: 44<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-200/j-280/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-275.jpg" alt="J-275" width="171" height="186" />
        <div class="info">
            <strong>J-275</strong><br />
            Seats: 6 Adults<br />
            Jets: 45<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-200/j-275/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-245.jpg" alt="J-245" width="171" height="186" />
        <div class="info">
            <strong>J-245</strong><br />
            Seats: 7 Adults<br />
            Jets: 35<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-200/j-245/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-235.jpg" alt="J-235" width="171" height="186" />
        <div class="info">
            <strong>J-235</strong><br />
            Seats: 6 Adults<br />
            Jets: 35<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-200/j-235/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-210.jpg" alt="J-210" width="171" height="186" />
        <div class="info">
            <strong>J-210</strong><br />
            Seats: 4 Adults<br />
            Jets: 19<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-200/j-210/" target="_blank">view details</a>
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

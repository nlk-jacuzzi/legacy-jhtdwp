<?php
/**
 * Template Name: J-300 Collection
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
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-385.jpg" alt="J-385" width="171" height="186" />
        <div class="info">
            <strong>J-385</strong><br />
            Seats: 6-7 Adults<br />
            Jets: 49<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-385/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-375.jpg" alt="J-375" width="171" height="186" />
        <div class="info">
            <strong>J-375</strong><br />
            Seats: 6 Adults<br />
            Jets: 50<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-375/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-365.jpg" alt="J-365" width="171" height="186" />
        <div class="info">
            <strong>J-365</strong><br />
            Seats: 6-7 Adults<br />
            Jets: 44<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-365/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-355.jpg" alt="J-355" width="171" height="186" />
        <div class="info">
            <strong>J-355</strong><br />
            Seats: 5-6 Adults<br />
            Jets: 42<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-355/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-345.jpg" alt="J-345" width="171" height="186" />
        <div class="info">
            <strong>J-345</strong><br />
            Seats: 5-6 Adults<br />
            Jets: 39<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-345/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-335.jpg" alt="J-335" width="171" height="186" />
        <div class="info">
            <strong>J-335</strong><br />
            Seats: 5 Adults<br />
            Jets: 40<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-335/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-325.jpg" alt="J-325" width="171" height="186" />
        <div class="info">
            <strong>J-325</strong><br />
            Seats: 4-5 Adults<br />
            Jets: 21<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-325/">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-315.jpg" alt="J-315" width="171" height="186" />
        <div class="info">
            <strong>J-315</strong><br />
            Seats: 2-3 Adults<br />
            Jets: 21<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-300/j-315/">view details</a>
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

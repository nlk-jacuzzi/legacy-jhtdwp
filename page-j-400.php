<?php
/**
 * Template Name: J-400 Collection
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
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-495.jpg" alt="J-495" width="171" height="186" />
        <div class="info">
            <strong>J-495</strong><br />
            Seats: 7-9 Adults<br />
            Jets: 62<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-400/j-495/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-480.jpg" alt="J-480" width="171" height="186" />
        <div class="info">
            <strong>J-480</strong><br />
            Seats: 6 Adults<br />
            Jets: 52<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-400/j-480/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-470.jpg" alt="J-470" width="171" height="186" />
        <div class="info">
            <strong>J-470</strong><br />
            Seats: 6-7 Adults<br />
            Jets: 43<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-400/j-470/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-465.jpg" alt="J-465" width="171" height="186" />
        <div class="info">
            <strong>J-465</strong><br />
            Seats: 6 Adults<br />
            Jets: 39<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-400/j-465/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-425.jpg" alt="J-425" width="171" height="186" />
        <div class="info">
            <strong>J-425</strong><br />
            Seats: 4-5 Adults<br />
            Jets: 27<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-400/j-425/" target="_blank">view details</a>
        </div>
    </div>
    <div class="tub">
        <img src="<?php bloginfo('template_url'); ?>/images/tubs/j-415.jpg" alt="J-415" width="171" height="186" />
        <div class="info">
            <strong>J-415</strong><br />
            Seats: 2-3 Adults<br />
            Jets: 21<br />
            <a href="http://www.jacuzzi.com/hot-tubs/j-400/j-415/" target="_blank">view details</a>
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

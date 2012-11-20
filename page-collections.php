<?php
/**
 * Template Name: The Collections
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
<h2><strong>J-LX</strong> Collection</h2>
<h4>Breaks the mold in hot tub design</h4>
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/j-lx.jpg" alt="J-LX Collection" width="161" height="129" class="alignleft" /><br />&middot; Optional ProEndure&trade;<br />
&middot; Exclusive Jacuzzi Design<br />
&middot; New ProStep&trade;:<br />easy entry + storage<br />
&middot; Upgraded filtration system<br /><br />
<a href="<?php bloginfo('url'); ?>/j-lx/">J-LX COLLECTION</a></p>
<div class="hr"></div>
<h2><strong>J-400</strong> Collection</h2>
<h4>Breaks the mold in hot tub design</h4>
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/j-400.jpg" alt="J-400 Collection" width="161" height="129" class="alignleft" /><br />&middot; Optional ProEndure&trade;<br />
&middot; Exclusive Jacuzzi Design<br />
&middot; New ProStep&trade;:<br />easy entry + storage<br />
&middot; Upgraded filtration system<br /><br />
<a href="<?php bloginfo('url'); ?>/j-400/">J-400 COLLECTION</a></p>
<div class="hr"></div>
<h2><strong>J-300</strong> Collection</h2>
<h4>The Jets that started an industry.</h4>
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/j-300.jpg" alt="J-300 Collection" width="161" height="129" class="alignleft" /><br />&middot; Auxiliary MP3 input jack<br />
&middot; ProAir Lounge<br />
&middot; Rainbow Waterfall<br />
&middot; Lighted beverage coasters<br />
&middot; ProLites LED lighting<br /><br />
<a href="<?php bloginfo('url'); ?>/j-300/">J-300 COLLECTION</a></p>
<div class="hr"></div>
<h2><strong>J-200</strong> Collection</h2>
<h4>Classic Designs from the first name in hot tubs.</h4>
<p><img src="<?php bloginfo('template_url'); ?>/images/dwp/j-200.jpg" alt="J-200 Collection" width="161" height="129" class="alignleft" /><br />&middot; Easy-to-reach controls<br />
&middot; Beverage Holders<br />
&middot; Pillow headrests<br />
&middot; Full lounge seat<br />
&middot; Whirlpool jets<br /><br />
<a href="<?php bloginfo('url'); ?>/j-200/">J-200 COLLECTION</a></p>
</div>
</div><!-- #main -->
<?php endwhile; ?>
<div class="secondary">
<?php get_sidebar(); ?>
</div>
</div><!-- #container -->
<?php get_footer(); ?>

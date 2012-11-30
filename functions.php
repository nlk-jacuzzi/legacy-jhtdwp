<?php
/**
 * @package JHTDWP
 * @since JHTDWP 1.0
 *
 * Defines all the functions, actions, filters, widgets, etc., for ProGo Themes' JHTDWP theme.
 */

$content_width = 640;

/** Tell WordPress to run progo_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'progo_setup' );

if ( ! function_exists( 'progo_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_theme_support( 'post-thumbnails' ) To add support for post thumbnails.
 *
 * @since JHTDWP 1.0
 */
function progo_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style
	add_editor_style();
	
	// This theme uses wp_nav_menu() in two locations
	register_nav_menus( array(
		'mainmenu' => 'Main Menu',
		'ftrlnx' => 'Footer Links',
	) );
	
	// Add support for custom backgrounds
	add_custom_background();
	
	// Add support for post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'medium', 199, 152, true );
	add_image_size( 'large', 640, 425, true );
	add_image_size( 'homeslide', 651, 487, true );
	
	// Add custom actions
	add_action( 'admin_init', 'progo_admin_init' );
	add_action( 'widgets_init', 'progo_jhtdwp_widgets' );
	add_action( 'admin_menu', 'progo_admin_menu_cleanup', 200 );
	add_action( 'login_head', 'progo_custom_login_logo' );
	add_action( 'login_headerurl', 'progo_custom_login_url' );
	add_action( 'save_post', 'progo_save_meta' );
	add_action('wp_print_scripts', 'progo_add_scripts');
	add_action('wp_print_styles', 'progo_add_styles');
	add_action( 'admin_bar_menu', 'progo_admin_bar_menu', 88 );
	
	// add custom filters
	add_filter( 'body_class', 'progo_bodyclasses' );
	add_filter( 'wp_nav_menu_objects', 'progo_menufilter', 10, 2 );
	add_filter( 'site_transient_update_themes', 'progo_update_check' );
	add_filter( 'admin_post_thumbnail_html', 'progo_admin_post_thumbnail_html' );
	add_filter( 'custom_menu_order', 'progo_admin_menu_order', 99 );
	add_filter( 'menu_order', 'progo_admin_menu_order', 99 );
	// force some metaboxes turned ON
	add_filter('get_user_option_managenav-menuscolumnshidden', 'progo_metaboxhidden_defaults', 10, 3 );
	
	
	// cleanup some
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	$bye = array( 'wp_generator' ); //array( 'rsd_link', 'wlwmanifest_link', 'wp_generator' );
	foreach ( $bye as $b ) remove_action( 'wp_head', $b );
	
	if ( is_admin() ) {
		add_action( 'admin_notices', 'progo_admin_notices' );
	} else {
		// brick site if theme is not activated
		if ( get_option( 'progo_jhtdwp_apiauth' ) != 100 ) {
			add_action( 'template_redirect', 'progo_to_twentyten' );
		}
	}
}
endif;

/********* Front-End Functions *********/

if ( ! function_exists( 'progo_sitelogo' ) ):
/**
 * prints out the HTML for the #logo area in the header of the front-end of the site
 * wrapped so child themes can overwrite if desired
 * @since JHTDWP 1.0
 */
function progo_sitelogo() {
	$options = get_option( 'progo_options' );
	$progo_logo = $options['logo'];
	$upload_dir = wp_upload_dir();
	$dir = trailingslashit($upload_dir['baseurl']);
	$imagepath = $dir . $progo_logo;
	if($progo_logo) {
		echo '<table id="logo"><tr><td><a href="'. get_bloginfo('url') .'"><img src="'. esc_attr( $imagepath ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" /></a></td></tr></table>';
	} else {
		echo '<a href="'. get_bloginfo('url') .'" id="logo" title="'. esc_attr( get_bloginfo( 'name' ) ) .'">'. esc_html( get_bloginfo( 'name' ) ) .'</a>';
	}
}
endif;
if ( ! function_exists( 'progo_nav_fallback' ) ):
/**
 * fallback callback for header nav menu
 * @since BusinessPro 1.2.1
 */
function progo_nav_fallback() {
	echo '<ul class="menu" id="nav">';
	wp_list_pages('title_li=');
	echo '</ul>';
}
endif;
if ( ! function_exists( 'progo_posted_on' ) ):
/**
 * Prints HTML with meta information for the current post—date/time and author.
 * @since ProGo Business Pro 1.0
 */
function progo_posted_on() {
	printf( __( '<span class="meta-sep">Posted by</span> %1$s <span class="%2$s">on</span> %3$s', 'progo' ),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'progo' ), get_the_author() ),
			get_the_author()
		),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
	edit_post_link( __( 'Edit', 'progo' ), '<span class="meta-sep"> : </span> <span class="edit-link">', '</span>' );
}
endif;
if ( ! function_exists( 'progo_posted_in' ) ):
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 * @since ProGo Business Pro 1.0
 */
function progo_posted_in() {
	/* Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	*/
	echo 'Categories : '. get_the_category_list( ', ' );
}
endif;
if ( ! function_exists( 'progo_comments' ) ):
/**
 * walker function for comment display
 * @since JHTDWP 1.0
 */
function progo_comments($comment, $args, $depth) {	
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-meta"><div class="comment-author vcard">
	<?php echo get_comment_author_link() ?>
	</div>
	<div class="meta"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			/* translators: 1: date, 2: time */
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'&nbsp;&nbsp;','' );
		?>
	</div>
    </div>
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
	<?php endif; ?>
	<?php comment_text() ?>
	
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
	<?php
}
endif;
/********* Back-End Functions *********/
if ( ! function_exists( 'progo_admin_menu_cleanup' ) ):
/**
 * hooked to 'admin_menu' by add_action in progo_setup()
 * @since JHTDWP 1.0
 */
function progo_admin_menu_cleanup() {
	global $menu, $submenu;
	
	$sub1 = array_shift($submenu['themes.php']);
	$sub1[0] = 'Change Theme';
	$submenu['tools.php'][] = $sub1;
	$sub1 = array_pop($submenu['themes.php']);
	$sub1[0] = 'Edit Theme Files';
	$submenu['tools.php'][] = $sub1;
	// add Theme Options and Homepage Slides pages under APPEARANCE
	add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'progo_admin', 'progo_admin_page' );
	rsort($submenu['themes.php']);
	/*
	$menu[60][0] = 'Theme';
	$menu[60][4] = 'menu-top menu-icon-progo';
	*/
}
endif;
if ( ! function_exists( 'progo_metaboxhidden_defaults' ) ):
function progo_metaboxhidden_defaults( $result, $option, $user ) {
	$alwayson = array();
	switch ( $option ) {
		case 'managenav-menuscolumnshidden':
			$alwayson = array( 'link-target', 'css-classes' );
			break;
	}
	if ( count( $alwayson ) > 0 ) {
		if ( is_array( $result ) ) {
			if ( count( $result ) > 0 ) {
				foreach ( $result as $k => $v ) {
					if ( in_array( $v, $alwayson) ) {
						unset( $result[$k] );
					}
				}
			}
		}
	}
	return $result;
}
endif;
if ( ! function_exists( 'progo_admin_menu_order' ) ):
function progo_admin_menu_order($menu_ord) {
	if ( ! $menu_ord ) return true;
	return array(
		'index.php', // this represents the dashboard link
		'separator1',
		'themes.php', // which we changed to ProGo Theme menu area
//		'admin.php?page=wpcf7', // failed
		// to do : GRAVITY FORMS and TESTIMONIALS
		'separator2',
		'edit.php?post_type=page', // Pages
		'edit.php?post_type=progo_facebooktabs',
		'edit.php?post_type=progo_ppc',
		'edit.php', // Posts
		'upload.php', // Media
		'edit-comments.php', // Comments
		'link-manager.php' // Links
	);
}
endif;
if ( ! function_exists( 'progo_admin_menu_finder' ) ):
/**
 * helper function to find the $key for the menu item with given $slug
 * @since JHTDWP 1.0
 */
function progo_admin_menu_finder($menu, $slug) {
	$id = 0;
	foreach ( $menu as $k => $v ) {
		if( $v[2] == $slug ) {
			$id = $k;
		}
	}
	return $id;
}
endif;
if ( ! function_exists( 'progo_admin_page' ) ):
/**
 * ProGo Themes' Business Pro Admin Page function
 * switch statement creates Pages for Installation, Shipping, Payment, Products, Appearance
 * from admin_menu_cleanup()
 
 * @since JHTDWP 1.0
 */
function progo_admin_page() {
	//must check that the user has the required capability 
	if ( current_user_can('edit_theme_options') == false) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} ?>
<script type="text/javascript">/* <![CDATA[ */
var wpsc_adminL10n = {
	unsaved_changes_detected: "Unsaved changes have been detected. Click OK to lose these changes and continue.",
	dragndrop_set: "false"
};
try{convertEntities(wpsc_adminL10n);}catch(e){};
/* ]]> */
</script>
    <?php
	$thispage = $_GET['page'];
	switch($thispage) {
		case "progo_admin":
	?>
	<div class="wrap">
    <div class="icon32" id="icon-themes"><br /></div>
    <h2>JHT DWP Theme Options</h2>
	<form action="options.php" method="post" enctype="multipart/form-data"><?php
		settings_fields( 'progo_options' );
		do_settings_sections( 'progo_api' );
		?>
        <p class="submit"><input type="submit" value="Save Changes" class="button-primary" /></p>
        <?php
		do_settings_sections( 'progo_info' );
		do_settings_sections( 'progo_dealer' );
		do_settings_sections( 'progo_hours' );
		do_settings_sections( 'progo_adv' );
		?>
        <p class="submit"><input type="submit" value="Save Changes" class="button-primary" /></p>
		<p><br /></p>
		</form>
        <h3>Additional Theme Options</h3>
        <table class="form-table">
        <?php
		$addl = array(
			'Homepage Slides' => array(
				'url' => 'edit.php?post_type=progo_homeslide',
				'btn' => 'Manage Homepage Slides',
				'desc' => 'Edit existing slides, change text, upload images, and add more slides.'
			),
			'Background' => array(
				'url' => 'themes.php?page=custom-background',
				'btn' => 'Customize Your Background',
				'desc' => 'Change the underlying color, or upload your own custom background image.'
			),
			'Widgets' => array(
				'url' => 'widgets.php',
				'btn' => 'Manage Widgets',
				'desc' => 'Customize what appears in the right column on various areas of your site.'
			),
			'Menus' => array(
				'url' => 'nav-menus.php',
				'btn' => 'Manage Menu Links',
				'desc' => 'Control the links in the Header &amp; Footer area of your site.'
			),
			'Contact Forms' => array(
				'url' => 'admin.php?contactform=1&page=wpcf7',
				'btn' => 'Manage Contact Forms',
				'desc' => 'Edit Contact Form 7 Forms that appear on your site, like on the Homepage.'
			)
		);
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) === false ) {
			unset($addl['Contact Forms']);
		}
		foreach ( $addl as $k => $v ) {
			echo '<tr><th scope="row">'. wp_kses($k,array()) .'</th><td><a href="'. esc_url($v['url']) .'" class="button" target="_blank">'. wp_kses($v['btn'],array()) .' &raquo;</a> <span class="description">'. wp_kses($v['desc'],array()) .'</span></td></tr>';
		} ?>
        </table><p><br /></p>
        <h3><a name="recommended"></a>Recommended Plugins</h3>
		<?php if ( function_exists( 'alex_recommends_widget' ) ) {
            alex_recommends_widget();
        } else { ?>
            <p>The following plugins can help improve various aspects of your WordPress + JHT DWP Themes site:</p>
            <ul style="list-style:outside; padding: 0 1em">
            <?php
            $pRec = array();
            $pRec[] = array('name'=>'WordPress SEO by Yoast','stub'=>'wordpress-seo','desc'=>'Out-of-the-box SEO. Easily control your keywords, meta descriptions, and more');
            $pRec[] = array('name'=>'Google Analytics for WordPress','stub'=>'google-analytics-for-wordpress','desc'=>'Add Google Analytics to your site, with options to track external links, mailto\'s, and downloads');
            $pRec[] = array('name'=>'Google Tag Manager','stub'=>'google-tag-manager','desc'=>'Makes it even easier to use Google Tag Manager, adding all the code itself');
            $pRec[] = array('name'=>'ShareThis','stub'=>'share-this','desc'=>'Get more exposure for your site as visitors share it with their friends');
            $pRec[] = array('name'=>'NextGEN Gallery','stub'=>'nextgen-gallery','desc'=>'A fully integrated Image Gallery plugin with dozens of options and features');
            $pRec[] = array('name'=>'Duplicate Post','stub'=>'duplicate-post','desc'=>'Add functionality to Save Page As...');
            $pRec[] = array('name'=>'WB DB Backup','stub'=>'wp-db-backup','desc'=>'On-demand backup of your WordPress database');
            
            foreach( $pRec as $plug ) {
                echo '<li>';
                echo '<a title="Learn more &amp; install '. esc_attr( $plug['name'] ) .'" class="thickbox" href="'. get_bloginfo('url') .'/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin='. $plug['stub'] .'&amp;TB_iframe=true&amp;width=640&amp;height=560">';
                echo esc_html($plug['name']) .'</a> : '. esc_html($plug['desc']) .'</li>';
            }
            ?>
            <li><a href="http://www.gravityforms.com/" target="_blank">Gravity Forms</a> : when Contact Form 7 just isn't cutting it. Gravity Forms is a super robust Forms plugin, with Drag and Drop form creation, and so much more</li>
            </ul>
    <?php } ?>
            <p><br /></p>
    <div class="clear"></div>
    </div>
	<?php
			break;
		default: ?>
	<div class="wrap">
    <div class="icon32" id="icon-themes"><br /></div><h2>Huh?</h2>
    </div>
    <?php
			break;
	}
}
endif;
if ( ! function_exists( 'progo_custom_login_logo' ) ):
/**
 * hooked to 'login_head' by add_action in progo_setup()
 * @since JHTDWP 1.0
 */
function progo_custom_login_logo() {
	if ( get_option('progo_logo') != '' ) {
		#needswork
		echo "<!-- login screen here... overwrite logo with custom logo -->\n"; 
	}else { ?>
<style type="text/css">
.login h1 a { background: url(<?php bloginfo( 'template_url' ); ?>/images/logo_progo.png) no-repeat top center; height: 80px; }
</style>
<?php }
}
endif;
if ( ! function_exists( 'progo_custom_login_url' ) ):
/**
 * hooked to 'login_headerurl' by add_action in progo_setup()
 * @uses get_option() To check if a custom logo has been uploaded to the back end
 * @return the custom URL
 * @since JHTDWP 1.0
 */
function progo_custom_login_url() {
	return get_bloginfo( 'url' );
}
endif;
if ( ! function_exists( 'progo_admin_page_styles' ) ):
/**
 * hooked to 'admin_print_styles' by add_action in progo_setup()
 * adds thickbox js for WELCOME screen styling
 * @since JHTDWP 1.0
 */
function progo_admin_page_styles() {
	global $pagenow;
	if ( $pagenow == 'themes.php' && isset( $_GET['page'] ) ) {
		if ( 'progo_admin' == $_GET['page'] ) {
				wp_enqueue_style( 'global' );
				wp_enqueue_style( 'wp-admin' );
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_style('farbtastic');
		}
	}
	wp_enqueue_style( 'progo_admin', get_bloginfo( 'template_url' ) .'/admin-style.css' );
}
endif;
if ( ! function_exists( 'progo_admin_page_scripts' ) ):
/**
 * hooked to 'admin_print_scripts' by add_action in progo_setup()
 * adds thickbox js for WELCOME screen Recommended Plugin info
 * @since JHTDWP 1.0
 */
function progo_admin_page_scripts() {
	global $pagenow;
	if ( $pagenow == 'themes.php' && isset( $_GET['page'] ) ) {
		switch ( $_GET['page'] ) {
			case 'progo_admin':
        		wp_enqueue_script( 'thickbox' );
				break;
        }
	}
}
endif;
if ( ! function_exists( 'progo_admin_init' ) ):
/**
 * hooked to 'admin_init' by add_action in progo_setup()
 * sets admin action hooks
 * registers Site Settings
 * @since JHTDWP 1.0
 */
function progo_admin_init() {
	global $pagenow;
	if ( isset( $_REQUEST['progo_admin_action'] ) ) {
		switch( $_REQUEST['progo_admin_action'] ) {
			case 'reset_logo':
				progo_reset_logo();
				break;
			case 'colorGreyscale':
				progo_colorscheme_switch( 'Greyscale' );
				break;
			case 'colorDarkGreen':
				progo_colorscheme_switch( 'DarkGreen' );
				break;
			case 'colorBlackOrange':
				progo_colorscheme_switch( 'BlackOrange' );
				break;
			case 'colorLightBlue':
				progo_colorscheme_switch( 'LightBlue' );
				break;
			case 'colorGreenBrown':
				progo_colorscheme_switch( 'GreenBrown' );
				break;
			case 'permalink_recommended':
				progo_permalink_check( 'recommended' );
				break;
			case 'permalink_default':
				progo_permalink_check( 'default' );
				break;
			case 'businfo_set':
				progo_businfo_set();
				break;
			case 'firstform':
				progo_firstform();
				break;
			case 'firstform_set':
				progo_firstform_set();
				break;
		}
	}
	
	if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) ) {
		if ( $_GET['page'] == 'progo_admin' ) {
			wp_redirect( admin_url( 'themes.php?page=progo_admin' ) );
		}
	}
	
	// ACTION hooks
	add_action( 'admin_print_styles', 'progo_admin_page_styles' );
	add_action( 'admin_print_scripts', 'progo_admin_page_scripts' );
	
	// Installation (api key) settings
	// register_setting( 'progo_api_options', 'progo_api_options', 'progo_validate_options' );
	
	// Appearance settings
	register_setting( 'progo_options', 'progo_options', 'progo_validate_options' );
	
	add_settings_section( 'progo_api', 'JHT DWP API Key', 'progo_section_text', 'progo_api' );
	add_settings_field( 'progo_api_key', 'API Key', 'progo_field_apikey', 'progo_api', 'progo_api' );
	

	add_settings_section( 'progo_info', 'General Site Information', 'progo_section_text', 'progo_info' );
	add_settings_field( 'progo_logo', 'Logo', 'progo_field_logo', 'progo_info', 'progo_info' );
	add_settings_field( 'progo_blogname', 'Site Name', 'progo_field_blogname', 'progo_info', 'progo_info' );
	add_settings_field( 'progo_blogdescription', 'Slogan', 'progo_field_blogdesc', 'progo_info', 'progo_info' );
	add_settings_field( 'progo_showdesc', 'Show/Hide Slogan', 'progo_field_showdesc', 'progo_info', 'progo_info' );
	add_settings_field( 'progo_whatblog', 'Show Which Blog Posts', 'progo_field_whatblog', 'progo_info', 'progo_info' );
	
	add_settings_section( 'progo_dealer', 'Dealership Information', 'progo_section_text', 'progo_dealer' );
	//Dealer Location Info for Office Info Widget and Footer display.
	add_settings_field( 'progo_businessaddy', 'Street Address', 'progo_field_businessaddy', 'progo_dealer', 'progo_dealer' );
	add_settings_field( 'progo_businessCSZ', 'City, State, Zip', 'progo_field_businessCSZ', 'progo_dealer', 'progo_dealer' );
	add_settings_field( 'progo_businessphone', 'Business Phone', 'progo_field_businessphone', 'progo_dealer', 'progo_dealer' );
	add_settings_field( 'progo_businessemail', 'Business Email', 'progo_field_businessemail', 'progo_dealer', 'progo_dealer' );
	
	add_settings_section( 'progo_hours', 'Business Hours', 'progo_section_text', 'progo_hours' );
	$days = jhtdwp_busdays();
	foreach ( $days as $k => $v ) {
		add_settings_field( 'progo_hours_'. $k, $v, 'progo_field_businesshours', 'progo_hours', 'progo_hours', array($k) );
	}
	
	add_settings_section( 'progo_adv', 'Advanced Options', 'progo_section_text', 'progo_adv' );
	add_settings_field( 'progo_homeseconds', 'Homepage Slide Rotation Speed', 'progo_field_homeseconds', 'progo_adv', 'progo_adv' );
	add_settings_field( 'progo_homeform', 'Form Code', 'progo_field_form', 'progo_adv', 'progo_adv' );
	add_settings_field( 'progo_copyright', 'Copyright Notice', 'progo_field_copyright', 'progo_adv', 'progo_adv' );
	add_settings_field( 'progo_footercolor', 'Footer Text Color', 'progo_field_footercolor', 'progo_adv', 'progo_adv' );
	
	// since there does not seem to be an actual THEME_ACTIVATION hook, we'll fake it here
	if ( get_option( 'progo_jhtdwp_installed' ) != true ) {
		// also want to create a few other pages (Terms & Conditions, Privacy Policy), set up the FOOTER menu, and add these pages to it...
		
		$post_date = date( "Y-m-d H:i:s" );
		$post_date_gmt = gmdate( "Y-m-d H:i:s" );
		
		// create new menus in the Menu system
		$new_menus = array(
			'mainmenu' => 'Main Menu',
			'ftrlnx' => 'Footer Links',
		);
		$aok = 1;
		foreach ( $new_menus as $k => $m ) {
			$new_menus[$k] = wp_create_nav_menu($m);
			if ( is_numeric( $new_menus[$k] ) == false ) {
				$aok--;
			}
		}
		//set_theme_mod
		if ( $aok == 1 ) {
			// register the new menus as THE menus in theme's menu areas
			set_theme_mod( 'nav_menu_locations' , $new_menus );
		}
		
		// create a few new pages, and populate some menus
		
		$new_pages = array(
			'home' => array(
				'title' => __( 'Home', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'ftrlnx' ),
			),
			'about' => array(
				'title' => __( 'About Us', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu', 'ftrlnx' ),
			),
			'blog' => array(
				'title' => __( 'Blog', 'progo' ),
				'content' => "This Page pulls in your Blog posts",
				'id' => '',
				'menus' => array(),
			),
			'collections' => array(
				'title' => __( 'The Collections', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu', 'ftrlnx' ),
			),
			'j-lx' => array(
				'title' => __( 'The New J-LX Collection', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu' ),
			),
			'j-400' => array(
				'title' => __( 'The J-400 Collection', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu' ),
			),
			'j-300' => array(
				'title' => __( 'The J-300 Collection', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu' ),
			),
			'j-200' => array(
				'title' => __( 'The J-200 Collection', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu' ),
			),
			'difference' => array(
				'title' => __( 'The Jacuzzi Difference', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu', 'ftrlnx' ),
			),
			'hydrotherapy' => array(
				'title' => __( 'Advanced Hydrotherapy', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu', 'ftrlnx' ),
			),
			'accessories' => array(
				'title' => __( 'Accessories', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu', 'ftrlnx' ),
			),
			'contact' => array(
				'title' => __( 'Contact Us', 'jhtdwp' ),
				'id' => '',
				'menus' => array( 'mainmenu', 'ftrlnx' ),
			),
		);
		foreach ( $new_pages as $slug => $page ) {
			$menu_parent_id = 0;
			$pcontent = jhtdwp_default_page_content( $slug );
			
			$new_pages[$slug]['id'] = wp_insert_post( array(
				'post_title' 	=>	$page['title'],
				'post_type' 	=>	'page',
				'post_name'		=>	$slug,
				'comment_status'=>	'closed',
				'ping_status' 	=>	'closed',
				'post_content' 	=>	$pcontent,
				'post_status' 	=>	'publish',
				'post_author' 	=>	1,
				'menu_order'	=>	1
			));
			
			if ( $new_pages[$slug]['id'] != false ) {
				// set "Home" & "Blog" page IDs
				switch ( $slug ) {
					case 'home':
						update_option( 'page_on_front', $new_pages[$slug]['id'] );
						update_option( 'progo_homepage_id', $new_pages[$slug]['id'] );
						break;
					case 'blog':
						update_option( 'page_for_posts', $new_pages[$slug]['id'] );
						update_option( 'progo_blog_id', $new_pages[$slug]['id'] );
						break;
					case 'j-lx':
					case 'j-400':
					case 'j-300':
					case 'j-200':
						$menu_parent_id = $new_pages['collections']['mainmenu_id'];
						break;
				}
				
				$menu_args = array(
					'menu-item-object-id' => $new_pages[$slug]['id'],
					'menu-item-object' => 'page',
					'menu-item-parent-id' => $menu_parent_id,
					'menu-item-type' => 'post_type',
					'menu-item-title' => $page['title'],
					'menu-item-status' => 'publish',
				);
				foreach ( $new_pages[$slug]['menus'] as $menu_key ) {
					$menu_id = $new_menus[$menu_key];
					if ( is_numeric( $menu_id ) ) {
						$menu_item_id = wp_update_nav_menu_item( $menu_id , 0, $menu_args );
						$new_pages[$slug][$menu_key .'_id'] = $menu_item_id;
					}
				}
			}
		}
		// also set option so homepage shows PAGE not POSTS
		update_option( 'show_on_front', 'page' );
		
		// and lets also add our first HOMEPAGE SLIDE ?
		$slide1 = wp_insert_post( array(
			'post_title' 	=>	'Slide 1',
			'post_type' 	=>	'progo_homeslide',
			'post_name'		=>	'slide1',
			'comment_status'=>	'closed',
			'ping_status' 	=>	'closed',
			'post_content' 	=>	'',
			'post_status' 	=>	'publish',
			'post_author' 	=>	1,
			'menu_order'	=>	1
		));
		
		$slidecontent = array(
			'text' => "<h2>Experience the New Jacuzzi J-LX Collection<br />&nbsp;&nbsp;&nbsp;The Most Energy Efficient Spas in their Class</h2>",
			'textcolor' => 'Light',
			'showtitle' => 'Hide',
		);
		update_post_meta($slide1, "_progo_slidecontent", $slidecontent);
		
		// set our default SITE options
		progo_options_defaults();
		
		// and send to WELCOME page
		wp_redirect( get_option( 'siteurl' ) . '/wp-admin/themes.php?page=progo_admin' );
	}
}
endif;
if ( ! function_exists( 'progo_jhtdwp_init' ) ):
/**
 * registers our "Homepage Slides" Custom Post Type
 * @since JHTDWP 1.0
 */
function progo_jhtdwp_init() {
	// HOMESLIDER SLIDES
	register_post_type( 'progo_homeslide',
		array(
			'labels' => array(
				'name' => _x('Homepage Slides', 'post type general name'),
				'singular_name' => _x('Slide', 'post type singular name'),
				'add_new_item' => _x('Add New Slide', 'Homepage Slides'),
				'edit_item' => __('Edit Slide'),
				'new_item' => __('New Slide'),
				'view_item' => __('View Slide'),
				'search_items' => __('Search Slides'),
				'not_found' =>  __('No slides found'),
				'not_found_in_trash' => __('No slides found in Trash'), 
				'parent_item_colon' => '',
				'menu_name' => __('Homepage Slides')
			),
			'public' => true,
			'public_queryable' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'themes.php',
			'hierarchical' => true,
			'supports' => array( 'title', 'thumbnail', 'revisions', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'progo_jhtdwp_init' );
endif;
if ( ! function_exists( 'progo_jhtdwp_widgets' ) ):
/**
 * registers a sidebar area for the WIDGETS page
 * and registers various Widgets
 * @since JHTDWP 1.0
 */
function progo_jhtdwp_widgets() {
	
	register_sidebar(array(
		'name' => 'Blog',
		'id' => 'blog',
		'description' => 'Sidebar for the Blog area',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	register_sidebar(array(
		'name' => 'Standard Pages',
		'id' => 'main',
		'description' => 'Widget area in the sidebar of all pages, in between main nav &amp; Blog section',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	
	$progo_widgets = array( 'FBLikeBox', 'Tweets', 'Share', 'Social' );
	foreach ( $progo_widgets as $w ) {
		require_once( 'widgets/widget-'. strtolower($w) .'.php' );
		register_widget( 'ProGo_Widget_'. $w );
	}
}
endif;
if ( ! function_exists( 'progo_metabox_cleanup' ) ):
/**
 * fires after wpsc_meta_boxes hook, so we can overwrite a lil bit
 * @since JHTDWP 1.0
 */
function progo_metabox_cleanup() {
	global $wp_meta_boxes;
	global $post_type;
	global $post;
	
	switch($post_type) {
		case 'page':
			add_meta_box( 'progo_sidebar_box', 'Sidebar', 'progo_sidebar_box', 'page', 'side', 'low' );
			break;
		case 'progo_homeslide':
			$wp_meta_boxes['progo_homeslide']['side']['low']['postimagediv']['title'] = 'Slide Background Image';
			
			add_meta_box( 'progo_slidecontent_box', 'Slide Content', 'progo_slidecontent_box', 'progo_homeslide', 'normal', 'high' );
			// no need for SEO metaboxes on Homeslides
			if(isset($wp_meta_boxes['progo_homeslide']['normal']['high']['wpseo_meta'])) unset($wp_meta_boxes['progo_homeslide']['normal']['high']['wpseo_meta']);
			break;
	}
}
endif;
add_action( 'do_meta_boxes', 'progo_metabox_cleanup' );
if ( ! function_exists( 'progo_sidebar_box' ) ):
/**
 * outputs html for "Sidebar" meta box on EDIT PAGE
 * lets Admins choose which Sidebar area is displayed on each Page
 * called by add_meta_box( "progo_direct_box", "Direct Response", "progo_direct_box"...
 * in progo_admin_init()
 * @uses progo_direct_meta_defaults()
 * @since JHTDWP 1.0
 */
function progo_sidebar_box() {
	global $post;
	$custom = get_post_meta($post->ID,'_progo_sidebar');
	$sidebar = $custom[0];
	
	$ids = array('main', 'home', 'blog', 'contact');
	$titles = array('Standard sidebar', 'Homepage', 'Blog', 'Contact');
	
	if( ! in_array( $sidebar, $ids ) ) {
		$sidebar = 'main';
	}
	?>
	<p>Choose a Sidebar to display on this Page</p>
	<select name="_progo_sidebar"><?php
for ( $i = 0; $i < count($ids); $i++) {
		echo '<option value="'. $ids[$i] .'"'. ( $ids[$i] == $sidebar ? ' selected="selected"' : '' ) .'>'. esc_attr( $titles[$i] ) .'</option>';
	} ?></select>
    <p><a href="<?php echo admin_url('widgets.php'); ?>" target="_blank">Configure Widgets Here &raquo;</a></p>
	<?php
}
endif;
if ( ! function_exists( 'progo_slidecontent_box' ) ):
/**
 * custom metabox for Homepage Slides content area
 * @since JHTDWP 1.0
 */
function progo_slidecontent_box() {
	global $post;
	$custom = get_post_meta($post->ID,'_progo_slidecontent');
	$content = (array) $custom[0];
	if ( ! isset( $content['text'] ) ) {
		$slidetext = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam.  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt.\n<a href=\"". trailingslashit(get_bloginfo('url')) ."/about/\"><strong>View Details</strong></a>";
	} else {
		$slidetext = $content['text'];
	}
	if ( ! isset( $content['textcolor'] ) ) {
		$content['textcolor'] = 'Light';
	}
	if ( ! isset( $content['showtitle'] ) ) {
		$content['showtitle'] = 'Show';
	}
	?>
    <div class="slidecontent" id="slidetypeTextContent">
    	<p><em>Title (above) will be used as the main text Headline for this Slide</em></p>
        <p><strong>Additional Copy (optional)</strong><br />
        <textarea name="progo_slidecontent[text]" rows="6" style="width: 100%"><?php echo esc_attr($slidetext); ?></textarea><br /><em>Line Breaks in the text above will be converted to "&lt;br /&gt;" on display.<br />In order to have a link around the entire slide image, use the code: &lt;a href="[your-url-here]" class="fulllink"&gt;Learn More&lt;/a&gt;</em></p>
    </div>
    <table id="slideTextColor"><tr><th scope="row" width="141" align="left">Slide Text Color :</th><?php
	
	$opts = array( 'Light', 'Dark');
	foreach ( $opts as $c ) {
		?><td width="82"><label for="slideTextColor<?php esc_attr_e( $c ); ?>"><input type="radio" name="progo_slidecontent[textcolor]" id="slideTextColor<?php esc_attr_e( $c ); ?>" value="<?php esc_attr_e( $c ); ?>" <?php checked($content['textcolor'], $c) ?> /> <?php esc_attr_e( $c ); ?></label></td><?php
	} ?></tr></table>
    <table id="showhideTitle"><tr><th scope="row" width="141" align="left">Show/Hide Slide Title :</th><?php
	
	$opts = array( 'Show', 'Hide');
	foreach ( $opts as $c ) {
		?><td width="82"><label for="showhideTitle<?php esc_attr_e( $c ); ?>"><input type="radio" name="progo_slidecontent[showtitle]" id="showhideTitle<?php esc_attr_e( $c ); ?>" value="<?php esc_attr_e( $c ); ?>" <?php checked($content['showtitle'], $c) ?> /> <?php esc_attr_e( $c ); ?></label></td><?php
	} ?></tr></table>
    <script type="text/javascript">
/* <![CDATA[ */
jQuery(function() {
	jQuery('#parent_id').hide().prevAll().hide();
	jQuery('#edit-slug-box').hide();
});
/* ]]> */
	</script>
    <?php
}
endif;

/********* core ProGo Themes' Business Pro functions *********/

if ( ! function_exists( 'progo_add_scripts' ) ):
/**
 * hooked to 'wp_print_scripts' by add_action in progo_setup()
 * adds front-end js
 * @since JHTDWP 1.0
 */
function progo_add_scripts() {
	if ( ! is_admin() ) {
		wp_enqueue_script( 'progo', get_bloginfo('template_url') .'/js/progo-frontend.js', array('jquery'), '1.0' );
		do_action('progo_frontend_scripts');
	} else {
		if ( isset( $_GET['page'] ) ) {
			if ( $_GET['page'] == 'progo_admin' ) {
				wp_enqueue_script('custom-background');
			}
		}
	}
}
endif;

if ( ! function_exists( 'progo_add_styles' ) ):
/**
 * hooked to 'wp_print_styles' by add_action in progo_setup()
 * checks for Color Scheme setting and adds appropriate front-end stylesheet
 * @since JHTDWP 1.0
 */
function progo_add_styles() {
	if ( ! is_admin() ) {
		/*
		if ( $options['footercolor'] != '' ) {
			add_action('wp_head', 'progo_custombg_color', 1000 );
		}
		*/
		$theme = wp_get_theme();
		wp_enqueue_style( 'jhtdwp', get_bloginfo( 'stylesheet_url' ), array(), $theme->Version );
	}
	do_action('progo_frontend_styles');
}
endif;
if ( ! function_exists( 'progo_custombg_color' ) ):
function progo_custombg_color() {
	$options = get_option('progo_options');
	echo '<style type="text/css">#ftr, #ftr a { color: #'. esc_attr($options['footercolor']) .' }</style>';
}
endif;
if ( ! function_exists( 'progo_reset_logo' ) ):
/**
 * wipe out any custom logo image setting
 * @since JHTDWP 1.0
 */
function progo_reset_logo(){
	check_admin_referer( 'progo_reset_logo' );
	
	// reset logo settings
	$options = get_option('progo_options');
	$options['logo'] = '';
	update_option( 'progo_options', $options );
	update_option( 'progo_settings_just_saved', 1 );
	
	wp_redirect( get_option('siteurl') .'/wp-admin/themes.php?page=progo_admin' );
	exit();
}
endif;
if ( ! function_exists( 'progo_permalink_check' ) ):
/**
 * @since JHTDWP 1.0
 */
function progo_permalink_check( $arg ){
	check_admin_referer( 'progo_permalink_check' );
	
	if ( $arg == 'recommended' ) {
		update_option( 'permalink_structure', '/%year%/%monthnum%/%day%/%postname%/' );
	} elseif ( $arg == 'default' ) {
		update_option( 'progo_permalink_checked', true );
	}
	wp_redirect( admin_url('options-permalink.php') );
	exit();
}
endif;
if ( ! function_exists( 'progo_businfo_set' ) ):
/**
 * @since JHTDWP 1.0
 */
function progo_businfo_set(){
	check_admin_referer( 'progo_businfo_set' );
	// menus are set - proceed to next step
	update_option( 'progo_jhtdwp_onstep', 10);
	
	wp_redirect( admin_url("themes.php?page=progo_admin") );
	exit();
}
endif;
if ( ! function_exists( 'progo_firstform' ) ):
/**
 * @since JHTDWP 1.0
 */
function progo_firstform(){
	check_admin_referer( 'progo_firstform' );
	// update first CF7 form to use on the Homepage area
	// NOTE : as of CF7 v3.0, they no longer have own table, just use CPT, so
	$firstform = get_posts( array(
				'numberposts'	=> 1,
				'post_type'		=> 'wpcf7_contact_form',
				'order'			=> 'ASC'
			));
	$firstformID = $firstform->ID;
	$hformID = wp_insert_post( array(
				'post_title' 	=>	'Homepage Form',
				'post_type' 	=>	'wpcf7_contact_form',
				'post_name'		=>	'homepage-form',
				'comment_status'=>	'open',
				'ping_status' 	=>	'open',
				'post_content' 	=>	'',
				'post_status' 	=>	'publish',
				'post_author' 	=>	1,
				'menu_order'	=>	0
			));
	$rq = '<span title="Required">*</span>';
	$n = "\n";
	$form = '<h3>REQUEST AN APPOINTMENT</h3>'. $n .'<table class="dform" width="274">'. $n
		.'<tr><td colspan="3"><label for="name">Your Name'. $rq .'</label>[text* name id:name class:text akismet:author]</td></tr>' . $n
		.'<tr><td colspan="3"><label for="email">Email'. $rq .'</label>[email* email id:email class:text akismet:author_email]</td></tr>'. $n
		.'<tr class="two"><td width="132"><label for="phone">Phone'. $rq .'</label>[text* phone id:phone class:text]</td>'. $n .'<td width="12">&nbsp;</td>'. $n
		.'<td width="130"><label for="zip">Zip'. $rq .'</label>[text* zip id:zip class:text]</td></tr>'. $n
		.'<tr><td colspan="3"><label for="comments">Comments/Questions</label>[textarea comments x2]</td></tr>'. $n
		.'<tr><td colspan="3"><em class="rq">* Indicates required fields.</em>[submit class:goldbtn "SUBMIT REQUEST"]'
		.'<span class="disc">We will never sell, share, or rent your personal information to anyone.</td></tr>'. $n
		.'</table>';
	
	$subject = get_option( 'blogname' ) .' : Contact Form';
	$sender = '[name] <[email]>';
	$body = sprintf( __( 'Name: %s', 'wpcf7' ), '[name]' ) . "\n"
		. sprintf( __( 'Email: %s', 'wpcf7' ), '[email]' ) . "\n"
		. sprintf( __( 'Phone: %s', 'wpcf7' ), '[phone]' ) . "\n"
		. sprintf( __( 'Zip: %s', 'wpcf7' ), '[zip]' ) . "\n"
		. sprintf( __( 'Comments/Questions: %s', 'wpcf7' ), '[comments]' ) . "\n\n" . '--' . "\n"
		. sprintf( __( 'This mail is sent via contact form on %1$s %2$s', 'wpcf7' ),
			get_bloginfo( 'name' ), get_bloginfo( 'url' ) );
	$recipient = get_option( 'admin_email' );
	$additional_headers = '';
	$attachments = '';
	$use_html = 0;
	$mail = compact( 'subject', 'sender', 'body', 'recipient', 'additional_headers', 'attachments', 'use_html' );
	/*
	$wpdb->update( $wpcf7->contactforms,
		array(
			'title' => '',
			'form' => $form,
			'mail' => maybe_serialize( $mail )
		),
		array( 'cf7_unit_id' => 1 ),
		array( '%s', '%s', '%s' ),
		array( '%d' )
	);
	*/
	update_post_meta( $hformID, 'form', $form );
	update_post_meta( $hformID, 'mail', $mail );
	update_post_meta( $hformID, 'mail_2', get_post_meta( $firstformID, 'messages', true ) );
	update_post_meta( $hformID, 'messages', get_post_meta( $firstformID, 'messages', true ) );
	update_post_meta( $hformID, 'additional_settings', '' );
	update_option( 'progo_jhtdwp_onstep', 7);
	
	$opt = get_option( 'progo_options' );
	$opt['form'] = '[contact-form-7 id="'. $hformID .'"]';
	update_option( 'progo_options', $opt );
	
	wp_redirect( admin_url( 'admin.php?contactform='. $hformID .'&page=wpcf7' ) );
	exit();
}
endif;
if ( ! function_exists( 'progo_firstform_set' ) ):
/**
 * @since JHTDWP 1.0
 */
function progo_firstform_set(){
	check_admin_referer( 'progo_firstform_set' );
	// first form is set - proceed to next step
	update_option( 'progo_jhtdwp_onstep', 8);
	
	wp_redirect( admin_url("options-permalink.php") );
	exit();
}
endif;
if ( ! function_exists( 'progo_arraytotop' ) ):
/**
 * helper function to bring a given element to the start of an array
 * @param parent array
 * @param element to bring to the top
 * @return sorted array
 * @since JHTDWP 1.0
 */
function progo_arraytotop($arr, $totop) {
	// Backup and delete element from parent array
	$toparr = array($totop => $arr[$totop]);
	unset($arr[$totop]);
	// Merge the two arrays together so our widget is at the beginning
	return array_merge( $toparr, $arr );
}
endif;
if ( ! function_exists( 'progo_save_meta' ) ):
/**
 * hooked to 'save_post' by add_action in progo_setup()
 * checks for _progo (direct) meta data, and performs validation & sanitization
 * @param post_id to check meta on
 * @return post_id
 * @since JHTDWP 1.0
 */
function progo_save_meta( $post_id ){
	// verify if this is an auto save routine. If it is,
	// our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { 
		return $post_id;
	}
	// check permissions
	switch( $_POST['post_type'] ) {
		case 'page':
			if ( current_user_can( 'edit_page', $post_id ) ) {
				// OK, we're authenticated: we need to find and save the data
				if ( isset( $_POST['_progo_sidebar'] ) ) {
					$sidebar = $_POST['_progo_sidebar'];
					
					if ( in_array ( $sidebar, array('main', 'home', 'blog', 'contact') ) ) {
						update_post_meta($post_id, "_progo_sidebar", $sidebar);
						return $sidebar;
					}
				}
			}
			break;
		case 'progo_homeslide':
			if ( current_user_can( 'edit_page', $post_id ) ) {
				// OK, we're authenticated: we need to find and save the data
				if ( isset( $_POST['progo_slidecontent'] ) ) {
					$slidecontent = $_POST['progo_slidecontent'];
					$slidecontent['textcolor'] = $slidecontent['textcolor'] == 'Light' ? 'Light' : 'Dark';
					$slidecontent['showtitle'] = $slidecontent['showtitle'] == 'Show' ? 'Show' : 'Hide';
					
					update_post_meta($post_id, "_progo_slidecontent", $slidecontent);
					return $slidecontent;
					
				}
			}
			break;
	}
	
	return $post_id;
}
endif;
/**
 * ProGo Site Settings Options defaults
 * @since JHTDWP 1.0
 */
function progo_options_defaults() {
	// Define default option settings
	$tmp = get_option( 'progo_options' );
    if ( ! is_array( $tmp ) ) {
		$def = array(
			// THEME CUSTOMIZATION
			"colorscheme" => "Greyscale",
			"logo" => "",
			// GENERAL SITE INFORMATION
			"blogname" => get_option( 'blogname' ),
			"blogdescription" => get_option( 'blogdescription' ),
			"showdesc" => 1,
			"whatblog" => 0,
			"copyright" => "© Copyright ". date('Y') .", All Rights Reserved",
			// OFFICE INFORMATION
			"businessaddy" => "",
			"businessCSZ" => "",
			"businessphone" => "",
			"businessemail" => "",
			"businesshours" => "",
			// HOMEPAGE SETTINGS
			"form" => "",
			"frontpage" => get_option( 'show_on_front' ),
			"homeseconds" => 6,
			// ADVANCED OPTIONS
			"menuwidth" => "fixed",
			"footercolor" => "",
		);
		update_option( 'progo_options', $def );
	}
	$tmp = get_option( 'progo_slides' );
    if ( ! is_array( $tmp ) ) {
		$def = array('count'=>0);
		update_option( 'progo_slides', $def );
	}
	
	update_option( 'progo_jhtdwp_installed', true );
	update_option( 'progo_jhtdwp_apikey', '' );
	update_option( 'progo_jhtdwp_apiauth', '100' );
	
	update_option( 'wpsc_ignore_theme', true );
	
	// set large image size
	update_option( 'large_size_w', 650 );
	update_option( 'large_size_h', 413 );
	
	// no SHARETHIS automatically all over the place?
	update_option( 'st_add_to_content', 'no' );
	update_option( 'st_add_to_page', 'no' );
	
	// how about setting widgets?
	$ourwidgets = wp_get_sidebars_widgets();
	foreach ( $ourwidgets as $a => $w ) {
		switch( $a ) {
			case 'blog':
				if ( count($w) == 6 ) {
					// default so many widgets, clean up plz
					$newblogw = array();
					foreach ( $w as $k => $v ) {
						$lastd = strrpos( $v, '-' );
						$wbase = substr( $v, 0, $lastd );
						if ( !in_array($wbase, array( 'search', 'recent-comments', 'meta' ) ) ) {
							$newblogw[] = $v;
						}
					}
					$ourwidgets[$a] = $newblogw;
				}
				break;
			case 'home':
				if ( count($w) == 0 ) {
					$newhomew = array(
						'progo-office-info-2',
						'progo-share-2',
					);
					$ourwidgets[$a] = $newhomew;
				}
				break;
		}
	}
	
	wp_set_sidebars_widgets($ourwidgets);
}
if ( ! function_exists( 'progo_validate_options' ) ):
/**
 * ProGo Site Settings Options validation function
 * from register_setting( 'progo_options', 'progo_options', 'progo_validate_options' );
 * in progo_admin_init()
 * also handles uploading of custom Site Logo
 * @param $input options to validate
 * @return $input after validation has taken place
 * @since JHTDWP 1.0
 */
function progo_validate_options( $input ) {
	if( isset($input['apikey']) ) {
		$input['apikey'] = wp_kses( $input['apikey'], array() );
		// store API KEY in its own option
		if ( $input['apikey'] != get_option( 'progo_jhtdwp_apikey' ) ) {
			update_option( 'progo_jhtdwp_apikey', substr( $input['apikey'], 0, 39 ) );
		}
	}
	
	// do validation here...
	$arr = array( 'blogname', 'blogdescription', 'support', 'copyright', 'footercolor', 'headline' );
	foreach ( $arr as $opt ) {
		$input[$opt] = wp_kses( $input[$opt], array() );
	}
	
	$color = preg_replace('/[^0-9a-fA-F]/', '', $input['footercolor']);
	if ( strlen($color) == 6 || strlen($color) == 3 ) {
		$input['footercolor'] = $color;
	} else {
		$input['footercolor'] = '';
		if ( in_array( $input['colorscheme'], array( 'BlackOrange', 'DarkGreen', 'GreenBrown' ) ) ) {
			$opt['footercolor'] = 'fff';
		}
	}
	
	$choices = array(
		'posts',
		'page',
	);
	if ( ! in_array( $input['frontpage'], $choices ) ) {
		$input['frontpage'] = get_option('show_on_front');
	}
	switch ( $input['frontpage'] ) {
		case 'posts':
			update_option( 'show_on_front', 'posts' );
			break;
		case 'page':
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', get_option('progo_homepage_id') );
			break;
	}
	$choices = array(
		'fixed',
		'auto',
	);
	if ( ! in_array( $input['menuwidth'], $choices ) ) {
		$input['frontpage'] = 'fixed';
	}
	// opt[showdesc] can only be 1 or 0
	$bincheck = array( 'showdesc', 'whatblog' );
	foreach( $bincheck as $f ) {
		if ( (int) $input[$f] != 1 ) {
			$input[$f] = 0;
		}
	}
	
	// opt[layout] can only be an int  1 <= int <= 4
	$intcheck = absint( $input['layout'] );
	if ( $intcheck < 1 || $intcheck > 4 ) {
		$intcheck = 1;
	}
	$input['layout'] = absint( $intcheck );
	
	// save blogname & blogdescription to other options as well
	$arr = array( 'blogname', 'blogdescription' );
	foreach ( $arr as $opt ) {
		if ( $input[$opt] != get_option( $opt ) ) {
			update_option( $opt, $input[$opt] );
		}
	}
	
	// check SUPPORT field & set option['support_email'] flag if we have an email
	$input['support_email'] = is_email( $input['support'] );
	
		// upload error?
		$error = '';
	// upload the file - BASED OFF WP USERPHOTO PLUGIN
	if ( isset($_FILES['progo_options']) && @$_FILES['progo_options']['name']['logotemp'] ) {
		if ( $_FILES['progo_options']['error']['logotemp'] ) {
			switch ( $_FILES['progo_options']['error']['logotemp'] ) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error = "The uploaded file exceeds the max upload size.";
					break;
				case UPLOAD_ERR_PARTIAL:
					$error = "The uploaded file was only partially uploaded.";
					break;
				case UPLOAD_ERR_NO_FILE:
					$error = "No file was uploaded.";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$error = "Missing a temporary folder.";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$error = "Failed to write file to disk.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$error = "File upload stopped by extension.";
					break;
				default:
					$error = "File upload failed due to unknown error.";
			}
		} elseif ( ! $_FILES['progo_options']['size']['logotemp'] ) {
			$error = "The file &ldquo;". $_FILES['progo_options']['name']['logotemp'] ."&rdquo; was not uploaded. Did you provide the correct filename?";
		} elseif ( ! in_array( $_FILES['progo_options']['type']['logotemp'], array( "image/jpeg", "image/pjpeg", "image/gif", "image/png", "image/x-png" ) ) ) {
			$error = "The uploaded file type &ldquo;". $_FILES['progo_options']['type']['logotemp'] ."&rdquo; is not allowed.";
		}
		$tmppath = $_FILES['progo_options']['tmp_name']['logotemp'];
		
		$imageinfo = null;
		if ( ! $error ) {			
			$imageinfo = getimagesize($tmppath);
			if ( ( ! $imageinfo ) || ( ! $imageinfo[0] ) || ( ! $imageinfo[1] ) ) {
				$error = __("Unable to get image dimensions.", 'user-photo');
			} else if ( $imageinfo[0] > 598 || $imageinfo[1] > 75 ) {
				/*
				if(userphoto_resize_image($tmppath, null, $userphoto_maximum_dimension, $error)) {
					$imageinfo = getimagesize($tmppath);
				}
				*/
				$filename = $tmppath;
				$newFilename = $filename;
				$jpeg_compression = 86;
				#if(empty($userphoto_jpeg_compression))
				#	$userphoto_jpeg_compression = USERPHOTO_DEFAULT_JPEG_COMPRESSION;
				
				$info = @getimagesize($filename);
				if ( ( ! $info ) || ( ! $info[0] ) || ( ! $info[1] ) ) {
					$error = __("Unable to get image dimensions.", 'user-photo');
				}
				//From WordPress image.php line 22
				else if (
					! function_exists( 'imagegif' ) && $info[2] == IMAGETYPE_GIF
					||
					! function_exists( 'imagejpeg' ) && $info[2] == IMAGETYPE_JPEG
					||
					! function_exists( 'imagepng' ) && $info[2] == IMAGETYPE_PNG
				) {
					$error = __( 'Filetype not supported.', 'user-photo' );
				}
				else {
					// create the initial copy from the original file
					if ( $info[2] == IMAGETYPE_GIF ) {
						$image = imagecreatefromgif( $filename );
					}
					elseif ( $info[2] == IMAGETYPE_JPEG ) {
						$image = imagecreatefromjpeg( $filename );
					}
					elseif ( $info[2] == IMAGETYPE_PNG ) {
						$image = imagecreatefrompng( $filename );
					}
					if ( ! isset( $image ) ) {
						$error = __("Unrecognized image format.", 'user-photo');
						return false;
					}
					if ( function_exists( 'imageantialias' ))
						imageantialias( $image, TRUE );
			
					// make sure logo is within max 598 x 75 dimensions
					
					// figure out the longest side
					if ( ( $info[0] / $info[1] ) > 8 ) { // resize width to fit 
						$image_width = $info[0];
						$image_height = $info[1];
						$image_new_width = 598;
			
						$image_ratio = $image_width / $image_new_width;
						$image_new_height = round( $image_height / $image_ratio );
					} else { // resize height to fit
						$image_width = $info[0];
						$image_height = $info[1];
						$image_new_height = 75;
			
						$image_ratio = $image_height / $image_new_height;
						$image_new_width = round( $image_width / $image_ratio );
					}
			
					$imageresized = imagecreatetruecolor( $image_new_width, $image_new_height);
					@ imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );
			
					// move the thumbnail to its final destination
					if ( $info[2] == IMAGETYPE_GIF ) {
						if ( ! imagegif( $imageresized, $newFilename ) ) {
							$error = __( "Logo path invalid" );
						}
					}
					elseif ( $info[2] == IMAGETYPE_JPEG ) {
						if ( ! imagejpeg( $imageresized, $newFilename, $jpeg_compression ) ) {
							$error = __( "Logo path invalid" );
						}
					}
					elseif ( $info[2] == IMAGETYPE_PNG ) {
						@ imageantialias($imageresized,true);
						@ imagealphablending($imageresized, false);
						@ imagesavealpha($imageresized,true);
						$transparent = imagecolorallocatealpha($imageresized, 255, 255, 255, 0);
						for($x=0;$x<$image_new_width;$x++) {
							for($y=0;$y<$image_new_height;$y++) {
							@ imagesetpixel( $imageresized, $x, $y, $transparent );
							}
						}
						@ imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );

						if ( ! imagepng( $imageresized, $newFilename ) ) {
							$error = __( "Logo path invalid" );
						}
					}
				}
				if(empty($error)) {
					$imageinfo = getimagesize($tmppath);
				}
			}
		}
		
		if ( ! $error ) {
			$upload_dir = wp_upload_dir();
			$dir = trailingslashit( $upload_dir['basedir'] );
			$imagepath = $dir . $_FILES['progo_options']['name']['logotemp'];
			
			if ( ! move_uploaded_file( $tmppath, $imagepath ) ) {
				$error = "Unable to place the user photo at: ". $imagepath;
			}
			else {
				chmod($imagepath, 0666);
				
				$input['logo'] = $_FILES['progo_options']['name']['logotemp'];
	
				/*
				if($oldFile && $oldFile != $newFile)
					@unlink($dir . '/' . $oldFile);
				*/
			}
		}
	}
	update_option('progo_settings_just_saved',1);
	
	return $input;
}
endif;

/********* more helper functions *********/

if ( ! function_exists( 'progo_field_logo' ) ):
/**
 * outputs HTML for custom "Logo" on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_logo() {
	$options = get_option('progo_options');
	if ( $options['logo'] != '' ) {
		$upload_dir = wp_upload_dir();
		$dir = trailingslashit( $upload_dir['baseurl'] );
		$imagepath = $dir . $options['logo'];
		echo '<img src="'. esc_attr( $imagepath ) .'" /> [<a href="'. wp_nonce_url("admin.php?progo_admin_action=reset_logo", 'progo_reset_logo') .'">Delete Logo</a>]<br /><span class="description">Replace Logo</span><br />';
	} ?>
<input type="hidden" id="progo_logo" name="progo_options[logo]" value="<?php echo esc_attr( $options['logo'] ); ?>" />
<input type="file" id="progo_logotemp" name="progo_options[logotemp]" />
<span class="description">Upload your logo here.<br />
Maximum dimensions: 598px Width x 75px Height. Larger images will be automatically scaled down to fit size.<br />
Maximum upload file size: <?php echo ini_get( "upload_max_filesize" ); ?>. Allowable formats: gif/jpg/png. Transparent png's / gif's are recommended.</span>
<?php
#needswork
}
endif;
/**
 * outputs HTML for "API Key" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_apikey() {
	$opt = get_option( 'progo_jhtdwp_apikey', true );
	echo '<input id="apikey" name="progo_options[apikey]" class="regular-text" type="text" value="'. esc_html( $opt ) .'" maxlength="39" />';
	$apiauth = get_option( 'progo_jhtdwp_apiauth', true );
	switch($apiauth) {
		case 100:
			echo ' <img src="'. get_bloginfo('template_url') .'/images/check.png" alt="aok" class="kcheck" />';
			break;
		default:
			echo ' <img src="'. get_bloginfo('template_url') .'/images/x.gif" alt="X" class="kcheck" title="'. $apiauth .'" />';
			break;
	}
	echo '<br /><span class="description">You API Key should have been provided via email when you were signed up for the Dealer Website Program.</span>';
}
if ( ! function_exists( 'progo_field_blogname' ) ):
/**
 * outputs HTML for "Site Name" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_blogname() {
	$opt = get_option( 'blogname' );
	echo '<input id="blogname" name="progo_options[blogname]" class="regular-text" type="text" value="'. esc_html( $opt ) .'" />';
}
endif;
if ( ! function_exists( 'progo_field_blogdesc' ) ):
/**
 * outputs HTML for "Slogan" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_blogdesc() {
	$opt = get_option( 'blogdescription' ); ?>
<input id="blogdescription" name="progo_options[blogdescription]" class="regular-text" type="text" value="<?php esc_html_e( $opt ); ?>" />
<?php }
endif;
if ( ! function_exists( 'progo_field_showdesc' ) ):
/**
 * outputs HTML for checkbox "Show/Hide Slogan" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_showdesc() {
	$options = get_option( 'progo_options' ); ?>
<fieldset><legend class="screen-reader-text"><span>Show Slogan</span></legend><label for="progo_showdesc">
<input type="checkbox" value="1" id="progo_showdesc" name="progo_options[showdesc]"<?php
	if ( (int) $options['showdesc'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
Show the Site Slogan next to the Logo at the top of <a target="_blank" href="<?php echo esc_url( trailingslashit( get_bloginfo( 'url' ) ) ); ?>">your site</a></label>
</fieldset>
<?php }
endif;
if ( ! function_exists( 'progo_field_whatblog' ) ):
/**
 * outputs HTML for checkbox "Show Which Blog Posts" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_whatblog() {
	$options = get_option( 'progo_options' ); ?>
<select id="progo_whatblog" name="progo_options[whatblog]"><?php
	for ( $i = 0; $i < 2; $i++ ) {
		echo '<option value="'. $i .'"';
		if ( absint( $options['whatblog'] ) == $i ) echo ' selected="selected"';
		echo '>';
		echo ( $i == 0 ? 'Show Official Jacuzzi Hot Tubs Blog Posts' : 'Use Your Own Blog Posts' );
		echo '</option>';
	}
	if ( (int) $options['showdesc'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
<?php }
endif;
if ( ! function_exists( 'progo_field_copyright' ) ):
/**
 * outputs HTML for "Copyright Notice" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_copyright() {
	$options = get_option( 'progo_options' );
	?>
<input id="progo_copyright" name="progo_options[copyright]" value="<?php esc_html_e( $options['copyright'] ); ?>" class="regular-text" type="text" />
<span class="description">Copyright notice that appears on the right side of your site's footer.</span>
<?php }
endif;
if (!function_exists('progo_field_businessaddy') ):
/**
 * outputs HTML for "Business Address" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessaddy() {
$options = get_option( 'progo_options' ); ?>
<input id="progo_businessaddy" name="progo_options[businessaddy]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessaddy'] ); ?>" />
<span class="description">This address will appear in the Office Info widget.</span>
<?php }
endif;
if (!function_exists('progo_field_businessCSZ') ):
/**
 * outputs HTML for "Business City, State, Zip" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessCSZ() {
$options = get_option( 'progo_options' ); ?>
<input id="progo_businessCSZ" name="progo_options[businessCSZ]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessCSZ'] ); ?>" />
<span class="description">This address will appear in the Office Info widget under the street address above.</span>
<?php }
endif;
if (!function_exists('progo_field_businessphone') ):
/**
 * outputs HTML for "Business Phone" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessphone() {
$options = get_option( 'progo_options' ); ?>
<input id="progo_businessphone" name="progo_options[businessphone]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessphone'] ); ?>" />
<span class="description">This phone will appear in the Office Info widget.</span>
<?php }
endif;
if (!function_exists('progo_field_businessemail') ):
/**
 * outputs HTML for "Business Email" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessemail() {
$options = get_option( 'progo_options' ); ?>
<input id="progo_businessemail" name="progo_options[businessemail]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessemail'] ); ?>" />
<span class="description">This email address will appear in the Office Info widget.</span>
<?php }
endif;
if (!function_exists('progo_field_businesshours') ):
/**
 * outputs HTML for "Business Hours" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businesshours($args) {
$options = get_option( 'progo_options' );
print '<input id="progo_hours_'. $args[0] .'" name="progo_options[hours_'. $args[0] .']" class="regular-text" type="text" value="'. esc_html( $options['hours_'. $args[0]] ) .'" />';
}
endif;
if ( ! function_exists( 'progo_field_footercolor' ) ):
/**
 * outputs HTML for "Footer Text Color" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_footercolor() {
	$options = get_option( 'progo_options' );
	?>
<fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Color' ); ?></span></legend>
<?php $show_clear = ( $options['footercolor'] != '' ) ? '' : ' style="display:none"'; ?>
<input type="text" name="progo_options[footercolor]" id="background-color" value="#<?php echo esc_attr( $options['footercolor'] ) ?>" />
<a class="hide-if-no-js" href="#" id="pickcolor"><?php _e('Select a Color'); ?></a> <span <?php echo $show_clear; ?>class="hide-if-no-js" id="clearcolor"> (<a href="#"><?php _e( 'Clear' ); ?></a>)</span>
<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
</fieldset>
<?php }
endif;
if ( ! function_exists( 'progo_field_form' ) ):
/**
 * outputs HTML for "Customer Support" field on Site Settings page
 * @since JHTDWP 1.0
 */
function progo_field_form() {
	$options = get_option( 'progo_options' );
	?>
<textarea id="progo_homeform" name="progo_options[form]" rows="3" cols="100%"><?php esc_attr_e( $options['form'] ); ?></textarea>
<?php }
endif;
if ( ! function_exists( 'progo_field_homeseconds' ) ):
/**
 * outputs HTML for Homepage "Cycle Seconds" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_homeseconds() {
	$options = get_option( 'progo_options' );
	// check just in case show_on_front changed since this was last updated?
	// $options['frontpage'] = get_option('show_on_front');
	?><p><input id="progo_homeseconds" name="progo_options[homeseconds]" type="text" size="2" value="<?php echo absint($options['homeseconds']); ?>"><span class="description"> sec. per slide. Enter "0" to disable auto-rotation.</span></p>
<?php }
endif;
if ( ! function_exists( 'progo_section_text' ) ):
/**
 * (dummy) function called by 
 * add_settings_section( [id] , [title], 'progo_section_text', 'progo_site_settings' );
 * echos anchor link for that section
 * @since JHTDWP 1.0
 */
function progo_section_text( $args ) {
	echo '<a name="'. $args['id'] .'"></a>';
}
endif;
if ( ! function_exists( 'progo_bodyclasses' ) ):
/**
 * adds some additional classes to the <body> based on what page we're on
 * @param array of classes to add to the <body> tag
 * @since JHTDWP 1.0
 */
function progo_bodyclasses($classes) {
	switch ( get_post_type() ) {
		case 'post':
			$classes[] = 'blog';
			break;
	}
	if ( is_front_page() ) {
		$options = get_option( 'progo_options' );
	}
	// add another class to body if we have a custom bg image
	if ( get_background_image() != '' ) {
		$classes[] = 'custombg';
	}
	
	return $classes;
}
endif;
if ( ! function_exists( 'progo_menufilter' ) ):
/**
 * adds some additional classes to Menu Items
 * so we can mark active menu trails easier
 * @param array of classes to add to the <body> tag
 * @since JHTDWP 1.0
 */
function progo_menufilter($items, $args) {
	$blogID = get_option('progo_blog_id');
	foreach ( $items as $i ) {
		if ( $i->object_id == $blogID ) {
			$options = get_option('progo_options');
			if ( absint($options['whatblog']) == 0 ) {
				$i->classes[] = 'hidden';
			} else {
				$i->classes[] = 'blog';
			}
		}
	}
	return $items;
}
endif;
if ( ! function_exists( 'progo_jhtdwp_completeness' ) ):
/**
 * check which step / % complete current site is at
 * @since JHTDWP 1.0
 */
function progo_jhtdwp_completeness( $onstep ) {
	if ( $onstep < 1 || $onstep > 10 ) {
		$onstep = 1;
	}
	
	if ( $onstep < 10 ) { // ok check it
		switch($onstep) {
			case 1: // check API auth
				$apiauth = get_option( 'progo_jhtdwp_apiauth', true );
				if( $apiauth == '100' ) {
					$onstep = 2;
				}
				break;
			case 2: // CF7 INSTALLED
				$plugs = get_plugins();
				if( isset( $plugs['contact-form-7/wp-contact-form-7.php'] ) == true ) {
					$onstep = 3;
				}
				break;
			case 3: // CF7 ACTIVATED
				if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
					$onstep = 4;
				}
				break;
			case 4: // CF7DB INSTALLED
				$plugs = get_plugins();
				if( isset( $plugs['contact-form-7-to-database-extension/contact-form-7-db.php'] ) == true ) {
					$onstep = 5;
				}
				break;
			case 5: // CF7DB ACTIVATED
				if ( is_plugin_active( 'contact-form-7-to-database-extension/contact-form-7-db.php' ) ) {
					$onstep = 6;
				}
				break;
			case 8: // Permalinks
				$permalink = get_option( 'permalink_structure', '' );
				$defaultok = get_option( 'progo_permalink_checked', false );
				if ( ( $permalink != '' ) || ( ( $permalink == '' ) &&  ( $defaultok == true ) ) ) {
					$onstep = 9;
				}
				break;
		}
	}
	return $onstep;
}
endif;
/**
 * hooked to 'admin_notices' by add_action in progo_setup()
 * used to display "Settings updated" message after Site Settings page has been saved
 * @uses get_option() To check if our Site Settings were just saved.
 * @uses update_option() To save the setting to only show the message once.
 * @since JHTDWP 1.0
 */
function progo_admin_notices() {
	global $pagenow;
	// api auth check
	$apiauth = get_option( 'progo_jhtdwp_apiauth', true );
	if( $apiauth != '100' ) {
	?>
	<div class="error">
		<p><?php
        switch($apiauth) {
			case 'new':	// key has not been entered yet
				echo '<a href="themes.php?page=progo_admin" title="Site Settings">Please enter your JHT DWP API Key to Activate your theme.</a>';
				break;
			case '999': // invalid key?
				echo 'Your JHT DWP API Key appears to be invalid. <a href="themes.php?page=progo_admin" title="Site Settings">Please double check it.</a>';
				break;
			case '300': // wrong site URL?
				echo '<a href="themes.php?page=progo_admin" title="Site Settings">The JHT DWP API Key you entered</a> is already bound to another URL.';
				break;
		}
		?></p>
	</div>
<?php
	}
	
	if( get_option('progo_settings_just_saved')==true ) { ?>
	<div class="updated fade">
		<p>Settings updated. <a href="<?php bloginfo('url'); ?>/">View site</a></p>
	</div>
<?php
		update_option('progo_settings_just_saved',false);
	}
	
	$onstep = absint(get_option('progo_jhtdwp_onstep', true));
	
	if ( $onstep < 10 ) {
		$onstep = progo_jhtdwp_completeness( $onstep );
		update_option( 'progo_jhtdwp_onstep', $onstep);
		// couldnt check step 2 before but now we have get_plugins() function
		if ( ( in_array($onstep, array(2,4))) && ( $_REQUEST['action'] == 'install-plugin' ) ) {
			return;
		}
		// quick check if the ACTIVATE link was just clicked...
		if ( ( $onstep == 3 ) && is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$onstep = 4;
			update_option( 'progo_jhtdwp_onstep', $onstep);
		}
		// quick check if the ACTIVATE link was just clicked...
		if ( ( $onstep == 5 ) && is_plugin_active( 'contact-form-7-to-database-extension/contact-form-7-db.php' ) ) {
			$onstep = 6;
			update_option( 'progo_jhtdwp_onstep', $onstep);
		}
		
		echo '<div class="updated progo-steps">';
		$pct = 0;
		$nst = '';
		switch($onstep) {
			case 1: // theme has been activated but no good API key yet
				$pct = 15;
				$nst = 'Activate your JHT DWP API Key';
				break;
			case 2: // INSTALL CF7
				$lnk = ( function_exists( 'wp_nonce_url' ) ) ? wp_nonce_url( 'update.php?action=install-plugin&amp;plugin=contact-form-7', 'install-plugin_contact-form-7' ) : 'plugin-install.php';
				$pct = 25;
				$nst = '<a href="'. esc_url( $lnk ) .'">Click Here to Install the Contact Form 7 Plugin</a>';
				break;
			case 3: // ACTIVATE CF7
				$lnk = ( function_exists( 'wp_nonce_url' ) ) ? wp_nonce_url( 'plugins.php?action=activate&amp;plugin=contact-form-7/wp-contact-form-7.php', 'activate-plugin_contact-form-7/wp-contact-form-7.php' ) : 'plugins.php';
				$pct = 35;
				$nst = '<a href="'. esc_url( $lnk ) .'">Click Here to Activate the Contact Form 7 Plugin</a>';
				break;
			case 4: // INSTALL CF7DB
				$lnk = ( function_exists( 'wp_nonce_url' ) ) ? wp_nonce_url( 'update.php?action=install-plugin&amp;plugin=contact-form-7-to-database-extension', 'install-plugin_contact-form-7-to-database-extension' ) : 'plugin-install.php';
				$pct = 45;
				$nst = '<a href="'. esc_url( $lnk ) .'">Click Here to Install the Contact Form 7 to Database Extension Plugin</a>';
				break;
			case 5: // ACTIVATE CF7DB
				$lnk = ( function_exists( 'wp_nonce_url' ) ) ? wp_nonce_url( 'plugins.php?action=activate&amp;plugin=contact-form-7-to-database-extension/contact-form-7-db.php', 'activate-plugin_contact-form-7-to-database-extension/contact-form-7-db.php' ) : 'plugins.php';
				$pct = 55;
				$nst = '<a href="'. esc_url( $lnk ) .'">Click Here to Activate the Contact Form 7 Plugin</a>';
				break;
			case 6: // CREATE CF7 Form1
				$pct = 65;
				$nst = 'CF7 is Installed &amp; Activated! <a href="'. wp_nonce_url("admin.php?progo_admin_action=firstform", 'progo_firstform') .'">Click Here to set up the main Form for your site</a>.';
				break;
			case 7: // Customize further
				$pct = 75;
				$nst = 'When you are done configuring your first Contact Form, <a href="'. wp_nonce_url("admin.php?progo_admin_action=firstform_set", 'progo_firstform_set') .'">click here to proceed to the next step</a>.';
				break;
			case 8: // Permalinks
				$pct = 80;
				$nst = 'Your <em>Permalinks</em> settings are still set to the Default option. <a href="'. wp_nonce_url("admin.php?progo_admin_action=permalink_recommended", 'progo_permalink_check') .'">Use the Recommended "Day and name" setting</a>, <a href="'. admin_url("options-permalink.php") .'">Choose another non-Default option for yourself</a>, or <a href="'. wp_nonce_url("admin.php?progo_admin_action=permalink_default", 'progo_permalink_check') .'">keep the Default setting and move to the next step</a>.';
				break;
			case 9: // Business Info?
				$pct = 90;
				$nst = '<a href="'. admin_url('themes.php?page=progo_admin#progo_info') .'">Enter your Business Information</a>, like Street Address, Phone Number, and Hours of Operation. <strong>This is the last step to setting up your new Dealer site!</strong> When you are all set, <a href="'. wp_nonce_url("admin.php?progo_admin_action=businfo_set", 'progo_businfo_set') .'">click here to remove this message</a>.';
				break;
		}
		echo '<p>Your Jacuzzi Hot Tubs Dealer site is <strong>'. $pct .'% Complete</strong> - Next Step: '. $nst .'</p></div>';
	}
}

/**
 * hooked to 'site_transient_update_themes' by add_filter in progo_setup()
 * checks ProGo-specific URL to see if our theme is up to date!
 * @param array of checked Themes
 * @uses get_allowed_themes() To retrieve list of all installed themes.
 * @uses wp_remote_post() To check remote URL for updates.
 * @return checked data array
 * @since JHTDWP 1.0
 */
function progo_update_check($data) {
	if ( is_admin() == false ) {
		return $data;
	}
	
	$themes = get_allowed_themes();
	
	if ( isset( $data->checked ) == false ) {
		$checked = array();
		// fill CHECKED array - not sure if this is necessary for all but doesnt take a long time?
		foreach ( $themes as $thm ) {
			// we don't care to check CHILD themes
			if( $thm['Parent Theme'] == '') {
				$checked[$thm[Template]] = $thm[Version];
			}
		}
		$data->checked = $checked;
	}
	if ( isset( $data->response ) == false ) {
		$data->response = array();
	}
	
	$request = array(
		'slug' => "jhtdwp",
		'version' => $data->checked[jhtdwp],
		'siteurl' => get_bloginfo('url')
	);
	
	// Start checking for an update
	global $wp_version;
	$apikey = get_option('progo_jhtdwp_apikey',true);
	if ( $apikey != '' ) {
		$apikey = substr( strtolower( str_replace( '-', '', $apikey ) ), 0, 32);
	}
	$checkplz = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($request),
			'api-key' => $apikey
		),
		'user-agent' => 'WordPress/'. $wp_version .'; '. get_bloginfo('url')
	);

	$raw_response = wp_remote_post('http://www.progo.com/updatecheck/', $checkplz);
	
	if ( ( ! is_wp_error( $raw_response ) ) && ( $raw_response['response']['code'] == 200 ) )
		$response = unserialize($raw_response['body']);
		
	if ( ! empty( $response ) ) {
		// got response back. check authcode
		// wp_die('response:<br /><pre>'. print_r($response,true) .'</pre><br /><br />apikey: '. $apikey );
		// only save AUTHCODE if APIKEY is not blank.
		if ( $apikey != '' ) {
			update_option( 'progo_jhtdwp_apiauth', $response[authcode] );
		} else {
			update_option( 'progo_jhtdwp_apiauth', 'new' );
		}
		if ( version_compare($data->checked[jhtdwp], $response[new_version], '<') ) {
			$data->response[jhtdwp] = array(
				'new_version' => $response[new_version],
				'url' => $response[url],
				'package' => $response[package]
			);
		}
	}
	
	return $data;
}

function progo_to_twentyten() {
	$brickit = true;
	global $wp_query;
	// check for PREVIEW theme
	if ( isset( $wp_query->query_vars['preview'] ) ) {
		if ( $wp_query->query_vars['preview'] == 1 ) {
			$brickit = false;
		}
	}
	if ( $brickit === true ) {
		$msg = 'This ProGo Themes site is currently not Activated.';
		
		if(current_user_can('edit_pages')) {
			$msg .= '<br /><br /><a href="'. trailingslashit(get_bloginfo('url')) .'wp-admin/themes.php?page=progo_admin">Click here to update your API Key</a>';
		}
		wp_die($msg);
	}
}
if ( ! function_exists( 'progo_admin_post_thumbnail_html' ) ):
/**
 * hooked by add_filter to 'admin_post_thumbnail_html'
 * @since JHTDWP 1.0
 */
function progo_admin_post_thumbnail_html($html) {
	global $post_type;
	global $post;
	if( $post_type=='progo_homeslide' ) {
		$options = get_option( 'progo_options' );
		switch( $options['layout'] ) {
			case 3:
				$size = '305px W x 322px H';
				break;
			case 4:
				$size = '647px W x 322px H';
				break;
			default:
				$size = '646px W x 382px H';
				break;
		}
		$html = str_replace(__('Set featured image').'</a>',__('Upload/Select a Background Image') .'</a> '. __('Recommended Size') .': '. $size, $html );
	}
	return $html;
}
endif;
/**
 * hooked by add_filter to 'wpseo_admin_bar_menu'
 * to tweak the new WP 3.1 ADMIN BAR
 * @since JHTDWP 1.0
 */
function progo_admin_bar_menu() {
	global $wp_admin_bar;
	
	$wp_admin_bar->remove_menu('widgets');
	$wp_admin_bar->add_menu( array( 'id' => 'progo', 'title' => __('JHT DWP Theme'), 'href' => admin_url('themes.php?page=progo_admin'), ) );
	// move Appearance > Widgets & Menus submenus to below our new ones
	$wp_admin_bar->remove_menu('widgets');
	$wp_admin_bar->remove_menu('menus');
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'progothemeoptions', 'title' => __('Theme Options'), 'href' => admin_url('themes.php?page=progo_admin') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'homeslides', 'title' => __('Homepage Slides'), 'href' => admin_url('edit.php?post_type=progo_homeslide') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'background', 'title' => __('Background'), 'href' => admin_url('themes.php?page=custom-background') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'widgets', 'title' => __('Widgets'), 'href' => admin_url('widgets.php') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'menus', 'title' => __('Menus'), 'href' => admin_url('nav-menus.php') ) );
}

if ( ! function_exists( 'progo_nomenu_cb' ) ):
function progo_nomenu_cb() {
	return '<ul></ul>';
}
endif;

function jhtdwp_busdays() {
	$days = array(
		'm' => 'Monday',
		't' => 'Tuesday',
		'w' => 'Wednesday',
		'r' => 'Thursday',
		'f' => 'Friday',
		's' => 'Saturday',
		'u' => 'Sunday'
		);
		return $days;
//	foreach ( $days as $k => $v ) {
}

function jhtdwp_hr( $atts ) {
	return '<div class="hr"></div>';
}
add_shortcode('hr', 'jhtdwp_hr');

function jhtdwp_callout_warranty( $atts ) {
	return '<table width="613"><tr><td width="217"><img src="'. get_bloginfo('template_url') .'/images/dwp/10yearwarranty.jpg" alt="10 Year Warranty" width="140" height="136" /></td><td width="396"><h3>Jacuzzi Offers a 10 Year Warranty<br />on all Hot Tubs</h3><p>When shopping for a hot tub, be sure to consider the warranty. Other brands guarantee\'s last 1 or 2 years, but our quality hot tubs feature limited warranties for up to 10 years! In addition, Jacuzzi\'s network of authorized dealers and technicians is standing by to ensure years of worry-free enjoyment. <a href="http://www.jacuzzihottubs.com/warranty-options/" target="_blank">VIEW WARRANTY OPTIONS</a></p></td></tr></table>';
}
add_shortcode('dwp-warranty', 'jhtdwp_callout_warranty');

function jhtdwp_callout_broch( $atts ) {
	return '<table width="613"><tr><td width="217"><img src="'. get_bloginfo('template_url') .'/images/dwp/brochure.jpg" alt="Brochure" width="176" height="194" /></td><td width="396"><h3>Download your <strong>Free Brochure</strong><br /><span style="font-family:\'GSL\'; text-transform: none">See all the new features the Jacuzzi has to offer</h3><a href="http://www.jacuzzihottubs.com/request-brochure/" target="_blank" class="button">FREE DOWNLOAD</a></td></tr></table>';
}
add_shortcode('dwp-brochure', 'jhtdwp_callout_broch');

function jhtdwp_callout_contact( $atts ) {
	$options = get_option( 'progo_options' );
	$mapaddy = str_replace(' ', '+', $options['businessaddy'] .' '. $options['businessCSZ']);
	$maplink = 'http://maps.google.com/maps?q='. $mapaddy;
	$oot = '<table class="contacttable" width="632" height="218"><tr valign="top"><td width="233"><div class="cmap">';
	$oot .= '<iframe width="206" height="206" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q='. $mapaddy .'&amp;iwloc=&amp;output=embed"></iframe>';
	$oot .= '</div></td><td width="212"><h4>ADDRESS</h4><p>'. esc_attr($options['businessaddy']) .'<br />'. esc_attr($options['businessCSZ']) .'<br /><a href="'. esc_url($maplink) .'" target="_blank">VIEW MAP</a><br />'. esc_attr($options['businessphone']) .'<br /><span class="eml">'. esc_attr($options['businessemail']) .'</span></p></td><td width="187"><h4>HOURS</h4><p>';
	
	$days = jhtdwp_busdays();
	foreach ( $days as $k => $v ) {
		$oot .= '<strong>'. $v .'</strong> '. $options['hours_'. $k] .'<br />';
	}
	$oot .= '</p></td></tr></table>';
	
	return $oot;
}
add_shortcode('dwp-contact', 'jhtdwp_callout_contact');

function jhtdwp_default_page_content( $slug ) {
	$oot = '';
	$n = "\n";
	$n2 = "\n\n";
	$bbase = trailingslashit( get_bloginfo('url') );
	$ibase = get_bloginfo('template_url') .'/images/dwp/';
	$dname = get_bloginfo('name');
	switch ( $slug ) {
		case 'home':
			$oot = '<h1>This is where you put your headline</h1>'. $n .'
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla.'. $n2
.'<table class="col3">'. $n .'<tr valign="top">'. $n .'<td width="199"><a href="'. $bbase .'about/"><img src="'. $ibase .'dealer.gif" alt="Dealer" width="199" height="152" /></a><h5>ABOUT US</h5>'. $n2 .'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper'. $n2 .'</td>'. $n .'<td width="18">&nbsp;</td>'. $n .'<td width="199"><a href="'. $bbase .'collections/"><img src="'. $ibase .'collections.jpg" alt="The Collections" width="199" height="152" /></a><h5>THE COLLECTIONS</h5>'. $n2 .'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper '. $n2 .'</td>'. $n .'<td width="18">&nbsp;</td>'. $n .'<td width="199"><a href="'. $bbase .'difference/"><img src="'. $ibase .'difference.jpg" alt="The Jacuzzi Difference" width="199" height="152" /></a><h5>THE JACUZZI DIFFERENCE</h5>'. $n2 .'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper '. $n2 .'</td>'. $n .'</tr>'. $n .'</table>'. $n2 .'[hr]'. $n2 .'[dwp-warranty]'. $n2 .'[hr]'. $n2 .'[dwp-brochure]';
			break;
		case 'about':
			$oot = '<h1>Why '. $dname .' and a Jacuzzi Hot Tub?</h1>'. $n .'There are a lot of different reasons to visit us and see the Jacuzzi difference.  I assume by now you have done a little research, and by now you have probably generated some questions.  How many places have you contacted and were unable to give clear, concise answers to your questions?  How many people did you talk to who didn’t even answer your questions and just beat around the bush?  Its frustrating.  We know it is.  This is a big purchase and you should be properly informed.  '. $n2 .'That is why when you call us, we will have the answers you are looking for.  There is no replacement for experience in all areas of hot tub ownership.  It doesn’t matter if you are looking for service related information or reasons to buy a new hot tub.  We want to help.'. $n2 .'We are happy to give you the hands on experience today’s consumer desires.  This means clean, current model hot tubs full of water and ready for you to try out.  This means the proper parts and supplies you demand to keep your Jacuzzi running at it’s best.  And this also means having someone willing to put in the time to show you how to use that brand new Jacuzzi you just put in your backyard.'. $n .'<h4>Quality, commitment, and value how you want it, when you want it.'. $n .'So bring your bathing suits, towels, and smiles and come see us at:</h4>'. $n .'[dwp-contact]'. $n2 .'[dwp-warranty]'. $n2 .'[hr]'. $n2 .'[dwp-brochure]';
			break;
		case 'blog':
			$oot = 'This Page pulls in your Blog posts';
			break;
		case 'collections':
			$oot = '<h1>JACUZZI HOT TUBS OVERVIEW</h1>'. $n .'<strong>Jacuzzi® hot tubs, water that moves you!</strong> When you are looking for all the comforts of home, make sure that one of those comforts is a quality Jacuzzi hot tub. Relax and enjoy your own private hydrotherapy session. Rejuvenate sore muscles and joints after a long day of work or play. Spend some family time together. Beat the chill of a cold day by soaking in your own hot tub. With so many models to choose from, there’s a relaxing Jacuzzi hot tub to fit any home. And any budget. Jacuzzi produces a full line of affordable hot tubs that will fit your lifestyle and your budget. We categorized our vast selection of hot tubs and spas into four collections:'. $n2 .'<a href="../j-lx/">The Jacuzzi J-LX Collection</a>'. $n .'<a href="../j-400/">The Jacuzzi J-400 Designer Collection</a>'. $n .'<a href="../j-300/">The Jacuzzi J-300 Signature Collection</a>'. $n .'<a href="../j-200/">The Jacuzzi J-200 Classic Collection</a>'. $n2 .'Why not come in today to wet test a Jacuzzi Hot Tub and find out what you’re missing!';
			break;
		case 'j-lx':
			$oot = '<h1>THE NEW<br /><strong>J-LX</strong> COLLECTION</h1>'. $n .'Experience famous Jacuzzi® patented-jet hydrotherapy in spas that maximize energy efficiency. Designed and tested in an independently certified chamber*, the J-LX and J-LXL with lounge seating minimize your hot tub operating costs.'. $n2 .'Fresh styling includes a new, patented top-deck design that eliminates exposed acrylic. Stainless steel accents add a clean, contemporary look, and UV- and weather-resistant materials improve durability. Besides being beautiful, the J-LX spas are all about luxury and ease:'. $n2 .'<a href="http://www.jacuzzihottubs.com/j-lx/" target="_blank">Click here to learn more about the J-LX Collection, features, and options</a>';
			break;
		case 'j-400':
			$oot = '<h1><strong>J-400</strong> COLLECTION</h1>'. $n .'Step into a Jacuzzi J-400 Designer Collection hot tub and let the inventors of the modern hot tub help you step outside the box. In creating the J-400 Collection, Jacuzzi designers and engineers were inspired to rethink traditional hot tub design. And the ingenious result literally breaks the mold by taking Jacuzzi WaterColour™ waterfall technology to new heights. Graceful curves present a contemporary profile - and a natural backdrop for cascading water and light. This Jacuzzi hot tub will change the way you see water and experience hydrotherapy.'. $n2 .'<a href="http://www.jacuzzihottubs.com/j-400/" target="_blank">Click here</a> to learn more about the J-400 Designer Collection, features, and options.';
			break;
		case 'j-300':
			$oot = '<h1><strong>J-300</strong> COLLECTION</h1>'. $n .'Imagine the difference a Jacuzzi J-300 hot tub will make in your life. Enjoy a spa-quality massage delivered by the numerous adjustable jets of a Jacuzzi FX10 Therapy Seat. Luxuriate in the unique feeling of water flowing over your shoulders from a Water Rainbow® waterfall. Listen to music generated by high-quality micro-speakers that are an integral part of Jacuzzi\'s remarkable new AquaSound™ stereo option. There\'s even an auxiliary MP3 jack for your iPOD®.'. $n2 .'<a href="http://www.jacuzzihottubs.com/j-300/" target="_blank">Click here</a> to learn more about the J-300 Signature Collection, features, and options.';
			break;
		case 'j-200':
			$oot = '<h1><strong>J-200</strong> COLLECTION</h1>'. $n .'Experience the J-200 Collection of hot tubs that are as stylish as they are affordable. A Jacuzzi J-200 Collection hot tub is the perfect place to relax, rejuvenate and even socialize. Treat yourself to a reinvigorating hydrotherapeutic massage after a long day of work. Indulge and pamper yourself, or enjoy time with your family on the weekend.'. $n2 .'<a href="http://www.jacuzzihottubs.com/j-200/" target="_blank">Click here</a> to learn more about the J-200 Classic Collection, features, and options.';
			break;
		case 'difference':
		case 'hydrotherapy':
			$oot = '... Page content supplied by theme template file ...';
			break;
		case 'accessories':
			$oot = '<h1>Hot Tubs, Accessories and Spa Living</h1>'. $n .'<img src="'. $ibase .'accessories.jpg" alt="Hot Tubs, Accessories and Spa Living" width="300" height="239" class="alignright" />Jacuzzi livens up the entertaining possibilities and puts practical matters in place to enrich your enjoyment of your hot tub. From remote controls for the stereo system option to non-slip steps that match our hot tubs, accessories fit in exactly where you want them.'. $n2 .'The most practical items among the Jacuzzi® hot tub accessories selections are spa steps, including the Handi-Step™ and the Jacuzzi ProLite™ step, each made of durable plastic for years of use. The variety of colors coordinate with the cabinet colors of the hot tubs. Accessories that can add fun and function to your spa include tables that fit next to your hot tub and stools. The stools can be used with a table or as a stand-alone convenience.'. $n2 .'Matching planters are ideal for adding a touch of green or surrounding the spa with a tall screen of green. Each planter measures one cubic foot, making them handy for storage, too.'. $n2 .'If you were to ask hot tub owners what is the most important accessory to complement your spa, the cover lift would be among the most popular. Designed to mount on the side of almost any spa and raise or lower your cover, a cover lift makes it quick, easy and convenient to use your hot tub.'. $n2 .'There’s also a soft side to extras for Jacuzzi hot tubs. Accessories such as Jacuzzi towels and the Jacuzzi kimono robe will wrap you in soft comfort when entering or leaving your spa. The Jacuzzi Exclusives collection of accessories includes covers, lifts, filters, cleaners, and more. Discover how your hot tub’s accessories can pull together a complete at-home spa lifestyle by visiting your Jacuzzi dealer.'. $n .'<h3>More Jacuzzi accessories</h3>'. $n .'<table width="640"><tr><td width="162"><p><a href="http://www.jacuzzihottubs.com/accessories/covers-lifts/" target="_blank"><img src="'. $ibase .'acc-covers.jpg" alt="Covers & Lifts" width="152" height="124" /></a></p><h5><a href="http://www.jacuzzihottubs.com/accessories/covers-lifts/" target="_blank">Covers & Lifts</a></h5></td><td width="162"><a href="http://www.jacuzzihottubs.com/accessories/synthetic-accessories/" target="_blank"><img src="'. $ibase .'acc-synthetic.jpg" alt="Synthetic Accessories" width="152" height="124" /></a></p><h5><a href="http://www.jacuzzihottubs.com/accessories/synthetic-accessories/" target="_blank">Synthetic Accessories</a></h5></td><td width="162"><a href="http://www.jacuzzihottubs.com/accessories/complements/" target="_blank"><img src="'. $ibase .'acc-complements.jpg" alt="Hot Tub Complements" width="152" height="124" /></a></p><h5><a href="http://www.jacuzzihottubs.com/accessories/complements/" target="_blank">Hot Tub Complements</a></h5></td><td width="162"><a href="http://www.jacuzzihottubs.com/accessories/water-purification-systems/" target="_blank"><img src="'. $ibase .'acc-purification.jpg" alt="Water Purification" width="152" height="124" /></a></p><h5><a href="http://www.jacuzzihottubs.com/accessories/water-purification-systems/" target="_blank">Water Purification</a></h5></td></tr></table>';
			break;
		case 'contact':
			$oot = '<h1>Contact Us Today!</h1>'. $n .'<h4>Quality, commitment, and value how you want it, when you want it.'. $n .'So bring your bathing suits, towels, and smiles and come see us at:</h4>'. $n .'[dwp-contact]'. $n2 .'[dwp-warranty]'. $n2 .'[hr]'. $n2 .'[dwp-brochure]';
			break;
	}
	return $oot;
}
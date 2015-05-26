<?php
	// Cargar los ficheros CSS
	function ecometro_my_theme_styles()
	{
		if( !is_admin() ) {
			wp_enqueue_style('bootstrap-min', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', false, '1.1', 'all');
			wp_enqueue_style('bootstrap-responsive-min', get_stylesheet_directory_uri() . '/css/bootstrap-responsive.min.css', false, '1.1', 'all');
			wp_enqueue_style('docs', get_stylesheet_directory_uri() . '/css/docs.css', false, '1.1', 'all');
			wp_enqueue_style('prettify', get_stylesheet_directory_uri() . '/css/prettify.css', false, '1.1', 'all');
			wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', array('bootstrap-min','bootstrap-responsive-min'), '1.1', 'all');
		}
	}
	
	add_action('wp_enqueue_scripts', 'ecometro_my_theme_styles');
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'header' => __( 'Header Menu', 'twentytwelve' ),
		'footer' => __( 'Footer Menu', 'twentytwelve' ),		
	) );
	
	function ecometro_remove_default_menu()
	{
		unregister_nav_menu('primary');
	}
	
	add_action('after_setup_theme', 'ecometro_remove_default_menu', 11);
	
	function ecometro_add_menu_class($items)
	{
		return preg_replace('/<a rel="toggle"/', '<a rel="toggle" class="dropdown-toggle" data-toggle="dropdown"', $items);
	}
	
	add_filter('wp_nav_menu_items', 'ecometro_add_menu_class');
	
	class Ecometro_Walker_Nav_Menu extends Walker_Nav_Menu
	{
		function start_lvl(&$output, $depth)
		{
			$indent = str_repeat("\t", $depth);
    		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
		}
	}
	
	function ecometro_remove_widgets()
	{
		unregister_sidebar('sidebar-1');
		unregister_sidebar('sidebar-2');
		unregister_sidebar('sidebar-3');
	}
	add_action('widgets_init', 'ecometro_remove_widgets', 11);
	
	function ecometro_widgets_init()
	{
		register_sidebar( array(
			'name' => __( 'Footer Icons', 'twentytwelve' ),
			'id' => 'sidebar-footer-icons',
			'description' => __( 'Appears in the footer of the page', 'twentytwelve' ),
			'before_widget' => '<li>',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
		register_sidebar( array(
			'name' => __( 'Colaboradores', 'twentytwelve' ),
			'id' => 'sidebar-colaboradores',
			'description' => __( 'Appears in the footer of the page', 'twentytwelve' ),
			'before_widget' => '<li>',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));
		register_sidebar( array(
			'name' => __( 'Patrocinadores', 'twentytwelve' ),
			'id' => 'sidebar-patrocinadores',
			'description' => __( 'Appears in the footer of the page', 'twentytwelve' ),
			'before_widget' => '<li>',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		));

		register_widget('WP_Widget_Ecometro_RSS');
	}
	add_action('widgets_init', 'ecometro_widgets_init');

	// Modify excerpt length
	function new_excerpt_length($length) {
		return 35;
	}

	add_filter('excerpt_length', 'new_excerpt_length', 11, 1);

	// Modify continue reading
	function new_excerpt_more($more) {
	    global $post;
		return '[...] <a href="' . get_permalink($post->ID) . '" class="no-underline"><span class="continue-reading">' . __('Sigue leyendo') . '</span></a>';
	}
	add_filter('excerpt_more', 'new_excerpt_more', 11, 1);
/**
 * RSS widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Ecometro_RSS extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __('Entries from any RSS or Atom feed.') );
		$control_ops = array( 'width' => 400, 'height' => 200 );
		parent::__construct( 'rss', __('RSS'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {

		if ( isset($instance['error']) && $instance['error'] )
			return;

		extract($args, EXTR_SKIP);

		$url = ! empty( $instance['url'] ) ? $instance['url'] : '';
		while ( stristr($url, 'http') != $url )
			$url = substr($url, 1);

		if ( empty($url) )
			return;

		// self-url destruction sequence
		if ( in_array( untrailingslashit( $url ), array( site_url(), home_url() ) ) )
			return;

		$rss = fetch_feed($url);
		$title = $instance['title'];
		$desc = '';
		$link = '';

		if ( ! is_wp_error($rss) ) {
			$desc = esc_attr(strip_tags(@html_entity_decode($rss->get_description(), ENT_QUOTES, get_option('blog_charset'))));
			if ( empty($title) )
				$title = esc_html(strip_tags($rss->get_title()));
			$link = esc_url(strip_tags($rss->get_permalink()));
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);
		}

		if ( empty($title) )
			$title = empty($desc) ? __('Unknown Feed') : $desc;

		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
		$url = esc_url(strip_tags($url));
		$icon = includes_url('images/rss.png');
		if ( $title )
			$title = "<a class='rsswidget' href='$url' title='" . esc_attr__( 'Syndicate this content' ) ."'><img style='border:0' width='14' height='14' src='$icon' alt='RSS' /></a> <a class='rsswidget' href='$link' title='$desc'>$title</a>";

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		ecometro_widget_rss_output( $rss, $instance );
		echo $after_widget;

		if ( ! is_wp_error($rss) )
			$rss->__destruct();
		unset($rss);
	}

	function update($new_instance, $old_instance) {
		$testurl = ( isset( $new_instance['url'] ) && ( !isset( $old_instance['url'] ) || ( $new_instance['url'] != $old_instance['url'] ) ) );
		return ecometro_widget_rss_process( $new_instance, $testurl );
	}

	function form($instance) {

		if ( empty($instance) )
			$instance = array( 'title' => '', 'url' => '', 'items' => 10, 'error' => false, 'show_summary' => 0, 'show_author' => 0, 'show_date' => 0 );
		$instance['number'] = $this->number;

		ecometro_widget_rss_form( $instance );
	}
}

/**
 * Display the RSS entries in a list.
 *
 * @since 2.5.0
 *
 * @param string|array|object $rss RSS url.
 * @param array $args Widget arguments.
 */
function ecometro_widget_rss_output( $rss, $args = array() ) {
	if ( is_string( $rss ) ) {
		$rss = fetch_feed($rss);
	} elseif ( is_array($rss) && isset($rss['url']) ) {
		$args = $rss;
		$rss = fetch_feed($rss['url']);
	} elseif ( !is_object($rss) ) {
		return;
	}

	if ( is_wp_error($rss) ) {
		if ( is_admin() || current_user_can('manage_options') )
			echo '<p>' . sprintf( __('<strong>RSS Error</strong>: %s'), $rss->get_error_message() ) . '</p>';
		return;
	}

	$default_args = array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0 );
	$args = wp_parse_args( $args, $default_args );
	extract( $args, EXTR_SKIP );

	$items = (int) $items;
	if ( $items < 1 || 20 < $items )
		$items = 10;
	$show_summary  = (int) $show_summary;
	$show_author   = (int) $show_author;
	$show_date     = (int) $show_date;

	if ( !$rss->get_item_quantity() ) {
		echo '<ul><li>' . __( 'An error has occurred, which probably means the feed is down. Try again later.' ) . '</li></ul>';
		$rss->__destruct();
		unset($rss);
		return;
	}

	echo '<ul>';
	foreach ( $rss->get_items(0, $items) as $item ) {
		$link = $item->get_link();
		while ( stristr($link, 'http') != $link )
			$link = substr($link, 1);
		$link = esc_url(strip_tags($link));
		$title = esc_attr(strip_tags($item->get_title()));
		if ( empty($title) )
			$title = __('Untitled');

		$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
		//$excerpt = wp_html_excerpt( $desc, 70 );
		$excerpt = wp_trim_words($desc, 12, '');

		// Append ellipsis. Change existing [...] to [&hellip;].
		if ( '[...]' == substr( $excerpt, -5 ) )
			$excerpt = substr( $excerpt, 0, -5 ) . '[&hellip;]';
		elseif ( '[&hellip;]' != substr( $excerpt, -10 ) && $desc != $excerpt )
			$excerpt .= ' [&hellip;]';

		$excerpt = esc_html( $excerpt );

		//$excerpt .= " <a class='rsswidget rsswidget-excerpt' href='$link' title='$desc'>" . __('Sigue leyendo') . "</a>";

		if ( $show_summary ) {
			$summary = "<div class='rssSummary'>$excerpt</div>";
		} else {
			$summary = '';
		}

		$date = '';
		if ( $show_date ) {
			$date = $item->get_date( 'U' );

			if ( $date ) {
				$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
			}
		}

		$author = '';
		if ( $show_author ) {
			$author = $item->get_author();
			if ( is_object($author) ) {
				$author = $author->get_name();
				$author = ' <cite>' . esc_html( strip_tags( $author ) ) . '</cite>';
			}
		}

		if ( $link == '' ) {
			echo "<li>$title{$date}{$summary}{$author}</li>";
		} else {
			echo "<li><a class='rsswidget' href='$link' title='$desc' target='_blank'>$title</a>{$date}{$summary}{$author}</li>";
		}
	}
	echo '</ul>';
	$rss->__destruct();
	unset($rss);
}

/**
 * Display RSS widget options form.
 *
 * The options for what fields are displayed for the RSS form are all booleans
 * and are as follows: 'url', 'title', 'items', 'show_summary', 'show_author',
 * 'show_date'.
 *
 * @since 2.5.0
 *
 * @param array|string $args Values for input fields.
 * @param array $inputs Override default display options.
 */
function ecometro_widget_rss_form( $args, $inputs = null ) {

	$default_inputs = array( 'url' => true, 'title' => true, 'items' => true, 'show_summary' => true, 'show_author' => true, 'show_date' => true );
	$inputs = wp_parse_args( $inputs, $default_inputs );
	extract( $args );
	extract( $inputs, EXTR_SKIP );

	$number = esc_attr( $number );
	$title  = esc_attr( $title );
	$url    = esc_url( $url );
	$items  = (int) $items;
	if ( $items < 1 || 20 < $items )
		$items  = 10;
	$show_summary   = (int) $show_summary;
	$show_author    = (int) $show_author;
	$show_date      = (int) $show_date;

	if ( !empty($error) )
		echo '<p class="widget-error"><strong>' . sprintf( __('RSS Error: %s'), $error) . '</strong></p>';

	if ( $inputs['url'] ) :
?>
	<p><label for="rss-url-<?php echo $number; ?>"><?php _e('Enter the RSS feed URL here:'); ?></label>
	<input class="widefat" id="rss-url-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][url]" type="text" value="<?php echo $url; ?>" /></p>
<?php endif; if ( $inputs['title'] ) : ?>
	<p><label for="rss-title-<?php echo $number; ?>"><?php _e('Give the feed a title (optional):'); ?></label>
	<input class="widefat" id="rss-title-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" /></p>
<?php endif; if ( $inputs['items'] ) : ?>
	<p><label for="rss-items-<?php echo $number; ?>"><?php _e('How many items would you like to display?'); ?></label>
	<select id="rss-items-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][items]">
<?php
		for ( $i = 1; $i <= 20; ++$i )
			echo "<option value='$i' " . selected( $items, $i, false ) . ">$i</option>";
?>
	</select></p>
<?php endif; if ( $inputs['show_summary'] ) : ?>
	<p><input id="rss-show-summary-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][show_summary]" type="checkbox" value="1" <?php if ( $show_summary ) echo 'checked="checked"'; ?>/>
	<label for="rss-show-summary-<?php echo $number; ?>"><?php _e('Display item content?'); ?></label></p>
<?php endif; if ( $inputs['show_author'] ) : ?>
	<p><input id="rss-show-author-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][show_author]" type="checkbox" value="1" <?php if ( $show_author ) echo 'checked="checked"'; ?>/>
	<label for="rss-show-author-<?php echo $number; ?>"><?php _e('Display item author if available?'); ?></label></p>
<?php endif; if ( $inputs['show_date'] ) : ?>
	<p><input id="rss-show-date-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][show_date]" type="checkbox" value="1" <?php if ( $show_date ) echo 'checked="checked"'; ?>/>
	<label for="rss-show-date-<?php echo $number; ?>"><?php _e('Display item date?'); ?></label></p>
<?php
	endif;
	foreach ( array_keys($default_inputs) as $input ) :
		if ( 'hidden' === $inputs[$input] ) :
			$id = str_replace( '_', '-', $input );
?>
	<input type="hidden" id="rss-<?php echo $id; ?>-<?php echo $number; ?>" name="widget-rss[<?php echo $number; ?>][<?php echo $input; ?>]" value="<?php echo $$input; ?>" />
<?php
		endif;
	endforeach;
}

/**
 * Process RSS feed widget data and optionally retrieve feed items.
 *
 * The feed widget can not have more than 20 items or it will reset back to the
 * default, which is 10.
 *
 * The resulting array has the feed title, feed url, feed link (from channel),
 * feed items, error (if any), and whether to show summary, author, and date.
 * All respectively in the order of the array elements.
 *
 * @since 2.5.0
 *
 * @param array $widget_rss RSS widget feed data. Expects unescaped data.
 * @param bool $check_feed Optional, default is true. Whether to check feed for errors.
 * @return array
 */
function ecometro_widget_rss_process( $widget_rss, $check_feed = true ) {
	$items = (int) $widget_rss['items'];
	if ( $items < 1 || 20 < $items )
		$items = 10;
	$url           = esc_url_raw( strip_tags( $widget_rss['url'] ) );
	$title         = isset( $widget_rss['title'] ) ? trim( strip_tags( $widget_rss['title'] ) ) : '';
	$show_summary  = isset( $widget_rss['show_summary'] ) ? (int) $widget_rss['show_summary'] : 0;
	$show_author   = isset( $widget_rss['show_author'] ) ? (int) $widget_rss['show_author'] :0;
	$show_date     = isset( $widget_rss['show_date'] ) ? (int) $widget_rss['show_date'] : 0;

	if ( $check_feed ) {
		$rss = fetch_feed($url);
		$error = false;
		$link = '';
		if ( is_wp_error($rss) ) {
			$error = $rss->get_error_message();
		} else {
			$link = esc_url(strip_tags($rss->get_permalink()));
			while ( stristr($link, 'http') != $link )
				$link = substr($link, 1);

			$rss->__destruct();
			unset($rss);
		}
	}

	return compact( 'title', 'url', 'link', 'items', 'error', 'show_summary', 'show_author', 'show_date' );
}

?>
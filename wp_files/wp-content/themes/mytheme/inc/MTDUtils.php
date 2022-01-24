<?php

class MTDUtils {
	public static function init() {
		add_action( 'after_setup_theme', array( __CLASS__, 'setup' ), 30 );
	}

	public static function setup() {
		self::disable_emoji();
	}

	/**
	 * Disable emoji
	 *
	 * @since 2022-01-24
	 */
	public static function disable_emoji() {
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		// filter to remove TinyMCE emojis
		if ( !has_filter( 'use_block_editor_for_post' ) && !has_filter( 'use_block_editor_for_page' ) ) {
			add_filter( 'tiny_mce_plugins', array( __CLASS__, 'disable_emojicons_tinymce' ) );
		}
	}

	/**
	 * Disable emoji for TinyMCE editor
	 *
	 * @param $plugins
	 *
	 * @return array
	 * @since 2022-01-24
	 */
	static function disable_emojicons_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	/**
	 * Disable Gutenberg editor for posts and pages
	 * usage: MTDUtils::disable_gutenberg();
	 *
	 * @since 2022-01-24
	 */
	public static function disable_gutenberg() {
		add_filter('use_block_editor_for_post', '__return_false', 10);
		add_filter('use_block_editor_for_page', '__return_false', 10);
	}

	/**
	 * Get svg icon from sprite
	 * usage: MTDUtils::icon( 'check' ); or MTDUtils::icon( 'check', 'test_mod' );
	 *
	 * @param $icon_name
	 * @param null $icon_mod
	 *
	 * @return false
	 * @since 2022-01-24
	 */
	static function icon( $icon_name, $icon_mod = null ) {
		if ( $icon_name ) {
			$out     = '';
			$classes = ( ! $icon_mod ) ? 'icon icon-' . $icon_name : 'icon icon-' . $icon_name . ' ' . $icon_mod;
			$out    .= '<svg class="' . $classes . '"><use xlink:href="' . get_template_directory_uri() . '/images/sprite/sprite.svg#' . $icon_name . '"></use></svg>';

			echo $out;
		} else {
			return false;
		}
	}

	/**
	 * Estimate reading time
	 *
	 * @param $post
	 *
	 * @return false|float
	 * @since 2022-01-24
	 */
	public static function get_reading_time( $post ) {
		$content     = get_the_content( $post );
		$text        = wp_strip_all_tags( $content, true );
		$word_count  = count( preg_split( '~[^\p{L}\p{N}\']+~u', $text ) );
		$readingtime = ceil( $word_count * 300 / 1000 / 60 );

		return $readingtime;
	}

	/**
	 * Get specific menu items
	 *
	 * @return array
	 * @since 2022-01-24
	 */
	public static function get_menu( $menu_name ) {
		$menus_locs = get_nav_menu_locations();
		$array_menus = wp_get_nav_menu_items( $menus_locs[$menu_name] );
		$menu = array();

		foreach ( $array_menus as $array_menu ) {
			if ( empty( $array_menu->menu_item_parent ) ) {
				$current_id = $array_menu->ID;
				$menu[] = array(
					'id'    => $current_id,
					'title' => $array_menu->title,
					'url'   => $array_menu->url
				);
			}
		}

		return $menu;
	}
}

// Initialization
MTDUtils::init();
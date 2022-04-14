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
	static function the_icon( $icon_name, $icon_mod = null ) {
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

	/**
	 * Display <picture> element with <source> and <img> tag.
	 *
	 * @param $source Required. Array of images.
	 * @param null $attr Optional. Array of attributes. Default empty.
	 *
	 * @return string|false
	 * @since 2022-02-03
	 */
	public static function the_picture( $source, $attr = null ) {
		if ( ! empty( $source ) ) {
			if ( ! empty( $attr ) && array_key_exists( 'class', $attr ) ) {
				$out = '<picture class="' . $attr['class'] . '">';
			} else {
				$out = '<picture class="picture">';
			}

			// get <img> class
			if ( ! empty( $attr ) && array_key_exists( 'img_class', $attr ) ) {
				$img_class = 'class="' . $attr['img_class'] . '"';
			} else {
				$img_class = '';
			}

			true == $source['toggle'] ? $desktop_media = 'media="(min-width:'. $source['screens']['min_width'] .'px)"' : $desktop_media = '';
			true == $source['toggle'] ? $mobile_media = 'media="(max-width:'. $source['screens']['max_width'] .'px)"' : $mobile_media = '';

			if ( ! empty( $source['desktop']['image_webp'] ) ) {
				$out .= '<source ' . $desktop_media . ' srcset="' . esc_url( $source['desktop']['image_webp'] ) . '" type="image/webp">';
			}

			if ( true == $source['toggle'] && ! empty( $source['mobile']['image_webp'] ) ) {
				$out .= '<source ' . $mobile_media . ' srcset="' . esc_url( $source['mobile']['image_webp'] ) . '" type="image/webp">';
			}

			if ( true == $source['toggle'] && ! empty( $source['mobile']['image'] ) ) {
				$out .= '<source ' . $mobile_media . ' srcset="' . esc_url( $source['mobile']['image']['url'] ) . '" type="' . $source['mobile']['image']['mime_type'] . '">';
			}

			if ( ! empty( $source['desktop']['image'] ) ) {
				true == $source['toggle'] ?: $out .= '<source ' . $desktop_media . ' srcset="' . esc_url( $source['desktop']['image']['url'] ) . '" type="' . $source['desktop']['image']['mime_type'] . '">';

				$out .= '<img ' . $img_class . 'src="' . esc_url( $source['desktop']['image']['url'] ) . '" alt="' . esc_attr( $source['desktop']['image']['alt'] ) . '" />';
			}
			$out .= '</picture>';

			echo $out;
		} else {
			return false;
		}
	}
}

// Initialization
MTDUtils::init();
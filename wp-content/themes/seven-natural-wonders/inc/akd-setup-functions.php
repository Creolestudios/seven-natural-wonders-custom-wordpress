<?php
/**
 * Theme Setup functions.
 *
 * @version 1.0.0
 *
 * @package akd
 */

/**
 * Enable custom support in the theme.
 *
 * @version 1.0.0
 */
function akd_custom_theme_setup() {
	// Enable menu support.
	add_theme_support( 'menus' );
	// Enable feature image support.
	add_theme_support( 'post-thumbnails' );
	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'akd_custom_theme_setup' );

/**
 * Register custom menus in the theme.
 *
 * @version 1.0.0
 */
function akd_register_custom_menus() {
	register_nav_menus(
		[
			'header-menu'        => 'Header Menu',
			'header-mobile-menu' => 'Header Mobile Menu',
			'footer-menu'        => 'Footer Menu',
		]
	);
}
add_action( 'init', 'akd_register_custom_menus' );

/**
 * Allow SVG Support
 *
 * @param array $mimes Array of allowed mime types.
 * @return array Modified array of allowed mime types.
 */
function akd_allow_svg_support( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'akd_allow_svg_support' );

/**
 * Register a custom post type called "Wonders".
 *
 * @version 1.0.0
 */
function akd_register_posttype_wonders() {
	$wonder_labels = [
		'name'                  => _x( 'Wonders', 'Wonders', 'akd' ),
		'singular_name'         => _x( 'Wonder', 'wonder', 'akd' ),
		'menu_name'             => _x( 'Wonders', 'Admin Menu text', 'akd' ),
		'name_admin_bar'        => _x( 'Wonder', 'Add New on Toolbar', 'akd' ),
		'add_new'               => __( 'Add New', 'akd' ),
		'add_new_item'          => __( 'Add New wonder', 'akd' ),
		'new_item'              => __( 'New wonder', 'akd' ),
		'edit_item'             => __( 'Edit wonder', 'akd' ),
		'view_item'             => __( 'View wonder', 'akd' ),
		'all_items'             => __( 'All Wonders', 'akd' ),
		'search_items'          => __( 'Search wonder', 'akd' ),
		'parent_item_colon'     => __( 'Parent wonder:', 'akd' ),
		'not_found'             => __( 'No wonder found.', 'akd' ),
		'not_found_in_trash'    => __( 'No wonder found in Trash.', 'akd' ),
		'featured_image'        => _x( 'Wonder Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'akd' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'akd' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'akd' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'akd' ),
	];

	$wonders_args = [
		'labels'             => $wonder_labels,
		'has_archive'        => true,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'menu_icon'          => 'dashicons-admin-site',
		'menu_position'      => 9,
		// 'rewrite'            => [ 'slug' => 'wonder' ],
		'supports'           => [ 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields' ],
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
	];

	register_post_type( 'wonders', $wonders_args );
}
add_action( 'init', 'akd_register_posttype_wonders' );

/**
 * Additional custom taxonomies.
 *
 * @version 1.0.0
 */
function akd_add_custom_taxonomies() {

	// Wonder categories taxonomy.
	register_taxonomy(
		'wonder-categories',
		'wonders',
		[
			'label'             => __( 'Country & Region' ),
			'hierarchical'      => true,
			'public'            => true,
			'has_archive'       => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		]
	);

	// Wonder tags taxonomy.
	register_taxonomy(
		'wonder-tags',
		'wonders',
		[
			'label'             => __( 'Tags' ),
			'rewrite'           => [ 'slug' => 'wonder-tags' ],
			'hierarchical'      => true,
			'has_archive'       => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
		]
	);
}
add_action( 'init', 'akd_add_custom_taxonomies' );

/**
 * Change term request for "wonder-categories"
 *
 * @version 1.0.0
 */
function akd_change_term_request( $query ) {

	$tax_name = 'wonder-categories'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'

	// Request for child terms differs, we should make an additional check
	if ( $query['attachment'] ) :
		$include_children = true;
		$name             = $query['attachment'];
	else :
		$include_children = false;
		$name             = $query['name'];
	endif;

	$term = get_term_by( 'slug', $name, $tax_name ); // get the current term to make sure it exists

	if ( isset( $name ) && $term && ! is_wp_error( $term ) ) : // check it here

		if ( $include_children ) {
			unset( $query['attachment'] );
			$parent = $term->parent;
			while ( $parent ) {
				$parent_term = get_term( $parent, $tax_name );
				$name        = $parent_term->slug . '/' . $name;
				$parent      = $parent_term->parent;
			}
		} else {
			unset( $query['name'] );
		}

		switch ( $tax_name ) :
			case 'category':{
				$query['category_name'] = $name; // for categories
				break;
			}
			case 'post_tag':{
				$query['tag'] = $name; // for post tags
				break;
			}
			default:{
				$query[ $tax_name ] = $name; // for another taxonomies
				break;
			}
		endswitch;

	endif;

	return $query;

}
add_filter( 'request', 'akd_change_term_request', 1, 1 );

/**
 * Change term permalink for "wonder-categories"
 *
 * @version 1.0.0
 */
function akd_change_term_permalink( $url, $term, $taxonomy ) {

	$taxonomy_name = 'wonder-categories';
	$taxonomy_slug = 'wonder-categories';

	// exit the function if taxonomy slug is not in URL
	if ( strpos( $url, $taxonomy_slug ) === false || $taxonomy != $taxonomy_name ) {
		return $url;
	}

	$url = str_replace( '/' . $taxonomy_slug, '', $url );

	return $url;
}
add_filter( 'term_link', 'akd_change_term_permalink', 10, 3 );

/**
 * Modify permalink for custom post type 'wonders'.
 *
 * @param string  $permalink The post's permalink.
 * @param WP_Post $post The post object.
 * @return string The modified permalink.
 */
function akd_wonders_cpt_custom_permalink( $permalink, $post ) {
	// Check if the post is of the custom post type 'wonders' and is published.
	if ( $post->post_type === 'wonders' && $post->post_status !== 'draft' ) {
		// Get the terms (taxonomies) associated with the post.
		$terms = get_the_terms( $post->ID, 'wonder-categories' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			// Check if the post has a parent term, then use the parent's slug; otherwise, use the child's slug.
			$term_slug = '';
			foreach ( $terms as $term ) {
				if ( $term->parent === 0 ) {
					// Creating slug from term name as some term has big slug.
					$parent_custom_slug = get_field( 'custom_slug_for_wonders', 'wonder-categories_' . $term->term_id );
					$parent_term_name   = str_replace( ' ', '-', strtolower( $term->name ) );
					$term_slug          = empty( $parent_custom_slug ) ? $parent_term_name : $parent_custom_slug;
					break;
				}
			}
			// If not have parent term then use child one.
			if ( empty( $term_slug ) ) {
				$child_custom_slug = get_field( 'custom_slug_for_wonders', 'wonder-categories_' . $terms[0]->term_id );
				$child_term_name   = str_replace( ' ', '-', strtolower( $terms[0]->name ) );
				$term_slug         = empty( $child_custom_slug ) ? $child_term_name : $child_custom_slug;
			}
			$country_slug = $term_slug;
			// Generate new permalink.
			$permalink = home_url( $country_slug . '/' . $post->post_name );
		}
	}
	return $permalink;
}
add_filter( 'post_type_link', 'akd_wonders_cpt_custom_permalink', 10, 4 );

/**
 * Add custom rewrite rules.
 */
function akd_custom_rewrite_rules() {
	add_rewrite_rule(
		'^([^/]+)/([^/]+)/?$',
		'index.php?wonder-categories=$matches[1]&wonders=$matches[2]',
		'top'
	);
}
add_action( 'init', 'akd_custom_rewrite_rules' );

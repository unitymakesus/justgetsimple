<?php

// Signatures Post Type
function sp_create_post_type() {
  $argsSig = array(
    'labels' => array(
				'name' => 'Signatures',
				'singular_name' => 'Signature',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Signature',
				'edit' => 'Edit',
				'edit_item' => 'Edit Signature',
				's_item' => 'New Signature',
				'view_item' => 'View Signature',
				'search_items' => 'Search Signatures',
				'not_found' =>  'Nothing found in the Database.',
				'not_found_in_trash' => 'Nothing found in Trash',
				'parent_item_colon' => ''
    ),
    'public' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    'menu_position' => 20,
    'menu_icon' => 'dashicons-groups',
    'capability_type' => 'page',
    'hierarchical' => false,
    'supports' => array(
      'title',
      'editor',
      'revisions',
      'page-attributes',
      'thumbnail'
    ),
    'has_archive' => false,
    'rewrite' => array(
      'slug' => 'bio'
    )
  );
  register_post_type( 'simple-signatures', $argsSig );
}
add_action( 'init', 'sp_create_post_type' );

function sp_create_taxonomies() {

	$argsSigCategories = array(
		'labels' => array(
			'name' => __( 'Types' ),
			'singular_name' => __( 'Type' )
		),
		'publicly_queryable' => true,
		'show_ui' => true,
    'show_admin_column' => true,
		'show_in_nav_menus' => false,
		'hierarchical' => true,
		'rewrite' => false
	);
	register_taxonomy('simple-signature-category', 'simple-signatures', $argsSigCategories);

}
add_action( 'init', 'sp_create_taxonomies' );

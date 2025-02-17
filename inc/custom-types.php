<?php

/**
 * Register the Genre Taxonomy
 */
function twentytwentyfive_register_genre_taxonomy() {
  $labels = array(
    'name'              => _x( 'Genres', 'taxonomy general name', 'twentytwentyfive' ),
    'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'twentytwentyfive' ),
    'search_items'      => __( 'Search Genres', 'twentytwentyfive' ),
    'all_items'         => __( 'All Genres', 'twentytwentyfive' ),
    'parent_item'       => __( 'Parent Genre', 'twentytwentyfive' ),
    'parent_item_colon' => __( 'Parent Genre:', 'twentytwentyfive' ),
    'edit_item'         => __( 'Edit Genre', 'twentytwentyfive' ),
    'update_item'       => __( 'Update Genre', 'twentytwentyfive' ),
    'add_new_item'      => __( 'Add New Genre', 'twentytwentyfive' ),
    'new_item_name'     => __( 'New Genre Name', 'twentytwentyfive' ),
    'menu_name'         => __( 'Genre', 'twentytwentyfive' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'book-genre' ),
    'show_in_rest'      => true, 
  );

  register_taxonomy('genre', array('book'), $args);
}
add_action('init', 'twentytwentyfive_register_genre_taxonomy');

/**
 * Register the Books Custom Post Type
 */
function twentytwentyfive_register_books_post_type() {
  $labels = array(
    'name'                  => _x( 'Books', 'Post type general name', 'twentytwentyfive' ),
    'singular_name'         => _x( 'Book', 'Post type singular name', 'twentytwentyfive' ),
    'menu_name'             => _x( 'Books', 'Admin Menu text', 'twentytwentyfive' ),
    'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'twentytwentyfive' ),
    'add_new'               => __( 'Add New', 'twentytwentyfive' ),
    'add_new_item'          => __( 'Add New Book', 'twentytwentyfive' ),
    'new_item'              => __( 'New Book', 'twentytwentyfive' ),
    'edit_item'             => __( 'Edit Book', 'twentytwentyfive' ),
    'view_item'             => __( 'View Book', 'twentytwentyfive' ),
    'all_items'             => __( 'All Books', 'twentytwentyfive' ),
    'search_items'          => __( 'Search Books', 'twentytwentyfive' ),
    'parent_item_colon'     => __( 'Parent Books:', 'twentytwentyfive' ),
    'not_found'             => __( 'No books found.', 'twentytwentyfive' ),
    'not_found_in_trash'    => __( 'No books found in Trash.', 'twentytwentyfive' ),
    'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'twentytwentyfive' ),
  );

  $args = array(
    'labels'              => $labels,
    'description'         => __( 'Description.', 'twentytwentyfive' ),
    'public'              => true,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'query_var'           => true,
    'rewrite'             => array( 'slug' => 'library' ),
    'capability_type'     => 'post',
    'has_archive'         => true,
    'hierarchical'        => false,
    'menu_position'       => null,
    'supports'            => array( 'title', 'editor', 'author', 'thumbnail','custom-fields'),
    'show_in_rest'        => true, // Enable Gutenberg editor
    'menu_icon'           => 'dashicons-book-alt',
  );

  register_post_type('book', $args);
}  
add_action('init', 'twentytwentyfive_register_books_post_type');

// Flush rewrite rules on theme activation
function flush_rewrite_on_activation() {
  twentytwentyfive_register_genre_taxonomy();
  twentytwentyfive_register_books_post_type();
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_on_activation');
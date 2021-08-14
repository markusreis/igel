<?php
$labels = array(
    'name'                  => _x('Immobilien', 'Post type general name', 'Immobilie'),
    'singular_name'         => _x('Immobilie', 'Post type singular name', 'Immobilie'),
    'menu_name'             => _x('Immobilien', 'Admin Menu text', 'Immobilie'),
    'name_admin_bar'        => _x('Immobilie', 'Add New on Toolbar', 'Immobilie'),
    'add_new'               => __('Hinzufügen', 'Immobilie'),
    'add_new_item'          => __('Immobilie hinzufügen', 'Immobilie'),
    'new_item'              => __('Neue Immobilie', 'Immobilie'),
    'edit_item'             => __('Immobilie bearbeiten', 'Immobilie'),
    'view_item'             => __('Immobilie ansehen', 'Immobilie'),
    'all_items'             => __('Alle Immobilien', 'Immobilie'),
    'search_items'          => __('Immobilien suchen', 'Immobilie'),
    'parent_item_colon'     => __('Eltern-Immobilien:', 'Immobilie'),
    'not_found'             => __('Keine Immobilien gefunden.', 'Immobilie'),
    'not_found_in_trash'    => __('Keine Immobilien im Abfall gefunden.', 'Immobilie'),
    'featured_image'        => _x('Titelbild der Immobilie', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'Immobilie'),
    'set_featured_image'    => _x('Titelbild festlegen', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'Immobilie'),
    'remove_featured_image' => _x('Titelbild wieder herstellen', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'Immobilie'),
    'use_featured_image'    => _x('Als Titelbild verwenden', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'Immobilie'),
    'archives'              => _x('Immobilien-Archiv', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'Immobilie'),
    'insert_into_item'      => _x('In Immobilie einfügen', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'Immobilie'),
    'uploaded_to_this_item' => _x('Zu dieser Immobilie hochladen', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'Immobilie'),
    'filter_items_list'     => _x('Filter Immobilien-Liste', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'Immobilie'),
    'items_list_navigation' => _x('Immobilien Navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'Immobilie'),
    'items_list'            => _x('Immobilien Liste', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'Immobilie'),
);
$args   = array(
    'labels'             => $labels,
    'description'        => 'Von JustImmo importierte Immobilien.',
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'kaufen'),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 20,
    'menu_icon'          => 'dashicons-admin-home',
    'supports'           => array('title', 'editor', 'author', 'thumbnail', 'post-thumbnails'),
    'taxonomies'         => array(),
    'show_in_rest'       => true
);

register_post_type('realty', $args);
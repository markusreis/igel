<?php

$labels = array(
    'name'                  => _x('Neubauprojekte', 'Post type general name', 'igel'),
    'singular_name'         => _x('Neubauprojekt', 'Post type singular name', 'igel'),
    'menu_name'             => _x('Neubauprojekte', 'Admin Menu text', 'igel'),
    'name_admin_bar'        => _x('Neubauprojekt', 'Add New on Toolbar', 'igel'),
    'add_new'               => __('Neues anlegen', 'igel'),
    'add_new_item'          => __('Neubauprojekt hinzufügen', 'igel'),
    'new_item'              => __('Neues Neubauprojekt', 'igel'),
    'edit_item'             => __('Neubauprojekt bearbeiten', 'igel'),
    'view_item'             => __('Neubauprojekt ansehen', 'igel'),
    'all_items'             => __('Alle Neubauprojekte', 'igel'),
    'search_items'          => __('Neubauprojekte suchen', 'igel'),
    'parent_item_colon'     => __('Eltern-Neubauprojekte:', 'igel'),
    'not_found'             => __('Kein Neubauprojekt gefunden.', 'igel'),
    'not_found_in_trash'    => __('Kein Neubauprojekte im Trash gefunden.', 'igel'),
    'featured_image'        => _x('Neubauprojekt Titelbild', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'igel'),
    'set_featured_image'    => _x('Titelbild festlegen', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'igel'),
    'remove_featured_image' => _x('Titelbild löschen', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'igel'),
    'use_featured_image'    => _x('Als Titelbild verwenden', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'igel'),
    'archives'              => _x('Neubauprojekt-Archiv', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'igel'),
    'insert_into_item'      => _x('In Neubauprojekt einfügen', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'igel'),
    'uploaded_to_this_item' => _x('Zu diesem Neubauprojekt hochgeladen', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'igel'),
    'filter_items_list'     => _x('Neubauprojekte-Liste filtern', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'igel'),
    'items_list_navigation' => _x('Neubauprojekte Navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'igel'),
    'items_list'            => _x('Neubauprojekte Liste', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'igel'),
);

$args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'neubauprojekte'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_icon'          => 'dashicons-admin-multisite',
    'menu_position'      => null,
    'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
);

register_post_type('newbuild', $args);



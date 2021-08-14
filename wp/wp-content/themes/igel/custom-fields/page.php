<?php
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60a125c40c160',
                                  'title'                 => 'Hero-Titel',
                                  'fields'                => array_merge(
                                      getTitleFields('field_60a125c943427', 'field_612232c943427'),
                                      [

                                          array(
                                              'key'               => 'field_62221223abf28',
                                              'label'             => 'Titel Hintergrundbild Desktop',
                                              'name'              => 'hero_bg_desktop',
                                              'type'              => 'image',
                                              'instructions'      => 'Ist kein Bild hinterlegt wird der grüne Verlauf angezeigt. Es müssen immer Desktop und Mobil hinterlegt sein, damit ein Bild angezeigt wird.',
                                              'required'          => 0,
                                              'conditional_logic' => 0,
                                              'wrapper'           => array(
                                                  'width' => '50',
                                                  'class' => '',
                                                  'id'    => '',
                                              ),
                                              'return_format'     => 'array',
                                              'preview_size'      => 'medium',
                                              'library'           => 'all',
                                              'min_width'         => '',
                                              'min_height'        => '',
                                              'min_size'          => '',
                                              'max_width'         => '',
                                              'max_height'        => '',
                                              'max_size'          => '',
                                              'mime_types'        => '',
                                          ),
                                          array(
                                              'key'               => 'field_609fccaccbf28',
                                              'label'             => 'Titel Hintergrundbild Mobil',
                                              'name'              => 'hero_bg_mobile',
                                              'type'              => 'image',
                                              'instructions'      => 'Ist kein Bild hinterlegt wird der grüne Verlauf angezeigt. Es müssen immer Desktop und Mobil hinterlegt sein, damit ein Bild angezeigt wird.',
                                              'required'          => 0,
                                              'conditional_logic' => 0,
                                              'wrapper'           => array(
                                                  'width' => '50',
                                                  'class' => '',
                                                  'id'    => '',
                                              ),
                                              'return_format'     => 'array',
                                              'preview_size'      => 'medium',
                                              'library'           => 'all',
                                              'min_width'         => '',
                                              'min_height'        => '',
                                              'min_size'          => '',
                                              'max_width'         => '',
                                              'max_height'        => '',
                                              'max_size'          => '',
                                              'mime_types'        => '',
                                          ),
                                      ]
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'post_type',
                                              'operator' => '==',
                                              'value'    => 'page',
                                          ),
                                      ),
                                  ),
                                  'menu_order'            => 0,
                                  'position'              => 'acf_after_title',
                                  'style'                 => 'default',
                                  'label_placement'       => 'top',
                                  'instruction_placement' => 'label',
                                  'hide_on_screen'        => '',
                                  'active'                => true,
                                  'description'           => '',
                              ));

endif;
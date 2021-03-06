<?php
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60afb25161321',
                                  'title'                 => 'Portraits',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_60abab7b73333',
                                          'label'             => 'Position innerhalb der Firma',
                                          'name'              => 'role',
                                          'type'              => 'text',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'default_value'     => '',
                                          'delay'             => 0,
                                      ),
                                      array(
                                          'key'               => 'field_60afb25babf28',
                                          'label'             => 'Hochformat',
                                          'name'              => 'portrait',
                                          'type'              => 'image',
                                          'instructions'      => '',
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
                                          'key'               => 'field_60afb26eabf29',
                                          'label'             => 'Querformat',
                                          'name'              => 'landscape',
                                          'type'              => 'image',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '',
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
                                          'key'               => 'field_60afbd7576be5',
                                          'label'             => 'Mitarbeiter in Makler-Liste anzeigen',
                                          'name'              => 'show_agent',
                                          'type'              => 'true_false',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'message'           => '',
                                          'default_value'     => 1,
                                          'ui'                => 0,
                                          'ui_on_text'        => '',
                                          'ui_off_text'       => '',
                                      ),
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'user_form',
                                              'operator' => '==',
                                              'value'    => 'all',
                                          ),
                                      ),
                                  ),
                                  'menu_order'            => 0,
                                  'position'              => 'normal',
                                  'style'                 => 'default',
                                  'label_placement'       => 'top',
                                  'instruction_placement' => 'label',
                                  'hide_on_screen'        => '',
                                  'active'                => true,
                                  'description'           => '',
                              ));

endif;
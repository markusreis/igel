<?php
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_61754c43508f3',
                                  'title'                 => 'Suchaufträg',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_61754c89727b4',
                                          'label'             => 'Listings',
                                          'name'              => 'listings',
                                          'type'              => 'repeater',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'collapsed'         => '',
                                          'min'               => 0,
                                          'max'               => 0,
                                          'layout'            => 'block',
                                          'button_label'      => '',
                                          'sub_fields'        => array(
                                              array(
                                                  'key'               => 'field_61754c91727b5',
                                                  'label'             => 'Titel',
                                                  'name'              => 'title',
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
                                                  'placeholder'       => '',
                                                  'prepend'           => '',
                                                  'append'            => '',
                                                  'maxlength'         => '',
                                              ),
                                              array(
                                                  'key'               => 'field_61754c96727b6',
                                                  'label'             => 'Text',
                                                  'name'              => 'text',
                                                  'type'              => 'wysiwyg',
                                                  'instructions'      => '',
                                                  'required'          => 0,
                                                  'conditional_logic' => 0,
                                                  'wrapper'           => array(
                                                      'width' => '',
                                                      'class' => '',
                                                      'id'    => '',
                                                  ),
                                                  'default_value'     => '',
                                                  'tabs'              => 'all',
                                                  'toolbar'           => 'full',
                                                  'media_upload'      => 1,
                                                  'delay'             => 0,
                                              ),
                                          ),
                                      ),
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'options_page',
                                              'operator' => '==',
                                              'value'    => 'acf-options-suchauftraege',
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
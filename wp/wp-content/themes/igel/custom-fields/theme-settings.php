<?php
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60332dc263462',
                                  'title'                 => 'Globale Einstellungen',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_622441214f57b',
                                          'label'             => 'E-Mail empfänger für Kontaktformulare',
                                          'name'              => 'default_recipient',
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
                                          'key'               => 'field_991821214f57b',
                                          'label'             => 'Telefonnummer für Icon oben rechts',
                                          'name'              => 'default_phone',
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
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'options_page',
                                              'operator' => '==',
                                              'value'    => 'acf-options-theme-settings',
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

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60ab8dc263462',
                                  'title'                 => 'Footer-Einstellungen',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_60ab8dc52f57a',
                                          'label'             => 'Details',
                                          'name'              => 'footer_settings',
                                          'type'              => 'group',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'layout'            => 'block',
                                          'sub_fields'        => array(
                                              array(
                                                  'key'               => 'field_60ab8df12f57b',
                                                  'label'             => 'Copyright Text',
                                                  'name'              => 'copyright_text',
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
                                                  'key'               => 'field_60ab8dfc2f57c',
                                                  'label'             => 'Social Media Links',
                                                  'name'              => 'social_media_links',
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
                                                  'layout'            => 'table',
                                                  'button_label'      => '',
                                                  'sub_fields'        => array(
                                                      array(
                                                          'key'               => 'field_60ab8e052f57d',
                                                          'label'             => 'Link',
                                                          'name'              => 'link',
                                                          'type'              => 'url',
                                                          'instructions'      => '',
                                                          'required'          => 0,
                                                          'conditional_logic' => 0,
                                                          'wrapper'           => array(
                                                              'width' => '75',
                                                              'class' => '',
                                                              'id'    => '',
                                                          ),
                                                          'default_value'     => '',
                                                          'placeholder'       => '',
                                                      ),
                                                      array(
                                                          'key'               => 'field_60ab8e1e2f57e',
                                                          'label'             => 'Icon',
                                                          'name'              => 'icon',
                                                          'type'              => 'select',
                                                          'instructions'      => '',
                                                          'required'          => 0,
                                                          'conditional_logic' => 0,
                                                          'wrapper'           => array(
                                                              'width' => '20',
                                                              'class' => '',
                                                              'id'    => '',
                                                          ),
                                                          'choices'           => array(
                                                              'facebook'  => 'Facebook',
                                                              'instagram' => 'Instagram',
                                                              'linkedin'  => 'Linkedin',
                                                          ),
                                                          'default_value'     => false,
                                                          'allow_null'        => 0,
                                                          'multiple'          => 0,
                                                          'ui'                => 0,
                                                          'return_format'     => 'value',
                                                          'ajax'              => 0,
                                                          'placeholder'       => '',
                                                      ),
                                                  ),
                                              ),
                                              array(
                                                  'key'               => 'field_60ab8e5d2f581',
                                                  'label'             => 'Kontakt',
                                                  'name'              => 'contact',
                                                  'type'              => 'textarea',
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
                                                  'maxlength'         => '',
                                                  'rows'              => 5,
                                                  'new_lines'         => '',
                                              ),
                                          ),
                                      ),
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'options_page',
                                              'operator' => '==',
                                              'value'    => 'acf-options-theme-settings',
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


if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60fd688848d5e',
                                  'title'                 => 'Seiten-Zuweisung',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_6ff3288df59bb',
                                          'label'             => 'Neubauporjekte',
                                          'name'              => 'newbuilds',
                                          'type'              => 'post_object',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '20',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'post_type'         => array(
                                              0 => 'page',
                                          ),
                                          'taxonomy'          => '',
                                          'allow_null'        => 0,
                                          'multiple'          => 0,
                                          'return_format'     => 'object',
                                          'ui'                => 1,
                                      ),
                                      array(
                                          'key'               => 'field_60fd6541259bb',
                                          'label'             => 'Immobilienangebot',
                                          'name'              => 'realties',
                                          'type'              => 'post_object',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '20',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'post_type'         => array(
                                              0 => 'page',
                                          ),
                                          'taxonomy'          => '',
                                          'allow_null'        => 0,
                                          'multiple'          => 0,
                                          'return_format'     => 'object',
                                          'ui'                => 1,
                                      ),
                                      array(
                                          'key'               => 'field_60fd688df59bb',
                                          'label'             => 'Über uns / Makler',
                                          'name'              => 'employees',
                                          'type'              => 'post_object',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '20',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'post_type'         => array(
                                              0 => 'page',
                                          ),
                                          'taxonomy'          => '',
                                          'allow_null'        => 0,
                                          'multiple'          => 0,
                                          'return_format'     => 'object',
                                          'ui'                => 1,
                                      ),
                                      array(
                                          'key'               => 'field_60f2222df59bb',
                                          'label'             => 'Verkaufen',
                                          'name'              => 'sell',
                                          'type'              => 'post_object',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '20',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'post_type'         => array(
                                              0 => 'page',
                                          ),
                                          'taxonomy'          => '',
                                          'allow_null'        => 0,
                                          'multiple'          => 0,
                                          'return_format'     => 'object',
                                          'ui'                => 1,
                                      ),
                                      array(
                                          'key'               => 'field_60f99182f59bb',
                                          'label'             => 'Kontakt',
                                          'name'              => 'contact',
                                          'type'              => 'post_object',
                                          'instructions'      => '',
                                          'required'          => 0,
                                          'conditional_logic' => 0,
                                          'wrapper'           => array(
                                              'width' => '20',
                                              'class' => '',
                                              'id'    => '',
                                          ),
                                          'post_type'         => array(
                                              0 => 'page',
                                          ),
                                          'taxonomy'          => '',
                                          'allow_null'        => 0,
                                          'multiple'          => 0,
                                          'return_format'     => 'object',
                                          'ui'                => 1,
                                      ),
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'options_page',
                                              'operator' => '==',
                                              'value'    => 'acf-options-theme-settings',
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
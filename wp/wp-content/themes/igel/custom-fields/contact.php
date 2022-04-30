<?php
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60a137d1e62f1',
                                  'title'                 => 'Inhalt',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_68188233321b0',
                                          'label'             => 'Kontaktformular',
                                          'name'              => 'contact',
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
                                          'sub_fields'        => getTitleFields('field_60a4144413127', 'field_6551531332427')
                                      ),
                                      array(
                                          'key'               => 'field_69187761321b0',
                                          'label'             => 'Inhalt nach Kontaktformular (optional)',
                                          'name'              => 'contact_after',
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
                                          'sub_fields'        => array_merge(
                                              getTitleFields('field_60a4113113127', 'field_6551716232427'),
                                              [
                                                  array(
                                                      'key'               => 'field_6091997b721eb',
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
                                                  )
                                              ]
                                          )
                                      ),
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'page_template',
                                              'operator' => '==',
                                              'value'    => 'template-contact.php',
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
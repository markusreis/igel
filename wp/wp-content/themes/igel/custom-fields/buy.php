<?php
if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
                                  'key'                   => 'group_60a13744e6cf1',
                                  'title'                 => 'Inhalt',
                                  'fields'                => array(
                                      array(
                                          'key'               => 'field_60a512d7921b0',
                                          'label'             => '"Zu den Suchaufträgen" Sektion',
                                          'name'              => 'listings',
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
                                              getTitleFields('field_60a125c223127', 'field_612fafc931427'),
                                              [
                                                  array(
                                                      'key'               => 'field_60abab7b1131b',
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
                                              ]
                                          ),
                                      ),
                                  ),
                                  'location'              => array(
                                      array(
                                          array(
                                              'param'    => 'page_template',
                                              'operator' => '==',
                                              'value'    => 'template-buy.php',
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
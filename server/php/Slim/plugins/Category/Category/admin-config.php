<?php
$menus = array (
    'Master' => array (
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-database fa-fw"></span>',
        'child_sub_menu' => array (
            'categories' => array (
                'title' => 'Categories',
                'icon_template' => '<span class="fa fa-tags"></span>',
                'suborder' => 1
            ) ,
        ) ,
        'order' => 4
    ) ,
);
$tables = array (
    'categories' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ) ,
                4 => array (
                    'name' => 'is_enable_common_hourly_rate_for_all_sub_services ',
                    'label' => 'Enable Common Hourly Rate For All Sub Services?',
                    'type' => 'boolean'
                ) , 
                5 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class'
                ) ,   
                6 => array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'boolean'
                ) ,                                                
                7 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Categories',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'batchActions' => array (
                0 => 'delete'
            ) ,
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
                1 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                2=> array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,                
                3 => array (
                    'name' => 'is_enable_multiple_booking',
                    'label' => 'Enable Multiple Booking?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'is_enable_common_hourly_rate_for_all_sub_services',
                    'label' => 'Enable Common Hourly Rate For All Sub Services?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
               2 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,                
                3 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                7 => 
                array (
                    'name' => 'image',
                    'label' => 'Image',
                    'type' => 'file',
                    'uploadInformation' => array (
                        'url' => 'api/v1/attachments',
                        'apifilename' => 'attachment',
                        'multiple' => false
                    ),
                    'validation' => 
                    array (
                        'required' => false,
                    ),
                ),  
                4 => array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,                                                              
                13 => array (
                    'name' => 'form_field_groups',
                    'label' => 'Form Fields',
                    'type' => 'embedded_list',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'name',
                            'validation' => 
                            array (
                                'required' => true,
                            ),
                            'label' => 'Group Name',
                            'type' => 'string',
                        ) ,
                        1 => array (
                            'name' => 'form_fields',
                            'label' => '',
                            'type' => 'embedded_list',
                            'targetFields' => array (
                                0 => array (
                                    'name' => 'name',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Field Name',
                                    'type' => 'string',
                                ) ,
                                1 => array (
                                    'name' => 'input_type_id',
                                    'targetEntity' => 'input_types',
                                    'targetField' => 'name',
                                    'type' => 'reference',
                                    'label' => 'Input Type',
                                    'remoteComplete' => true,
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                ) ,
                                2 => array (
                                    'name' => 'is_required',
                                    'type' => 'choice',
                                    'label' => 'Required',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    )
                                ) ,
                                3 => array (
                                    'name' => 'label',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Label',
                                    'type' => 'string'
                                ) ,
                                4 => array (
                                    'name' => 'label_for_search_form',
                                    'label' => 'Label for search form',
                                    'type' => 'string',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                ) ,                                  
                                5 => array (
                                    'name' => 'options',
                                    'type' => 'string',
                                    'label' => 'Options (Comma Seperated)'
                                ) ,
                                6 => array (
                                    'name' => 'info',
                                    'type' => 'string',
                                    'label' => 'Info'
                                ) ,
                                7 => array (
                                    'name' => 'display_order',
                                    'type' => 'number',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Display Order'
                                ) ,
                                8 => array (
                                    'name' => 'is_enable_this_field_in_search_form',
                                    'type' => 'choice',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Enable this field in search form?',
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    )
                                ) ,                                
                                9 => array (
                                    'name' => 'is_active',
                                    'type' => 'choice',
                                    'label' => 'Active?',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    )
                                )
                            )
                        ),
                        2 => array (
                            'name' => 'is_belongs_to_service_provider',
                            'type' => 'choice',
                            'label' => 'Belongs To Service Provider?',
                            'validation' => 
                            array (
                                'required' => true,
                            ),
                            'choices' => array (
                                0 => array (
                                    'label' => 'Yes',
                                    'value' => true,
                                ) ,
                                1 => array (
                                    'label' => 'No',
                                    'value' => false,
                                ) ,
                            )
                        )                         
                    )
                )
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
               2 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,                
                3 => array (
                    'name' => 'is_enable_common_hourly_rate_for_all_sub_services',
                    'label' => 'Enable Common Hourly Rate For All Sub Services?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                7 => 
                array (
                    'name' => 'image',
                    'label' => 'Image',
                    'type' => 'file',
                    'uploadInformation' => array (
                        'url' => 'api/v1/attachments',
                        'apifilename' => 'attachment',
                        'multiple' => false
                    ),
                    'validation' => 
                    array (
                        'required' => false,
                    ),
                ),
                4 => array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,                                                  
                13 => array (
                    'name' => 'form_field_groups',
                    'label' => 'Form Fields',
                    'type' => 'embedded_list',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'name',
                            'validation' => 
                            array (
                                'required' => true,
                            ),
                            'label' => 'Group Name',
                            'type' => 'string',
                        ) ,
                        1 => array (
                            'name' => 'form_fields',
                            'label' => '',
                            'type' => 'embedded_list',
                            'targetFields' => array (
                                0 => array (
                                    'name' => 'name',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Field Name',
                                    'type' => 'string',
                                ) ,
                                1 => array (
                                    'name' => 'input_type_id',
                                    'targetEntity' => 'input_types',
                                    'targetField' => 'name',
                                    'type' => 'reference',
                                    'label' => 'Input Type',
                                    'remoteComplete' => true,
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                ) ,
                                2 => array (
                                    'name' => 'is_required',
                                    'type' => 'choice',
                                    'label' => 'Required',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    )
                                ) ,
                                3 => array (
                                    'name' => 'label',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Label',
                                    'type' => 'string'
                                ) ,
                                4 => array (
                                    'name' => 'options',
                                    'type' => 'string',
                                    'label' => 'Options (Comma Seperated)'
                                ) ,
                                5 => array (
                                    'name' => 'info',
                                    'type' => 'string',
                                    'label' => 'Info'
                                ) ,
                                6 => array (
                                    'name' => 'display_order',
                                    'type' => 'number',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Display Order'
                                ) ,
                                7 => array (
                                    'name' => 'is_enable_this_field_in_search_form',
                                    'type' => 'choice',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'label' => 'Enable this field in search form?',
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    )
                                ) ,                                
                                8 => array (
                                    'name' => 'is_active',
                                    'type' => 'choice',
                                    'label' => 'Active?',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    )
                                )
                            )
                        ),
                        2 => array (
                            'name' => 'is_belongs_to_service_provider',
                            'type' => 'choice',
                            'label' => 'Belongs To Service Provider?',
                            'validation' => 
                            array (
                                'required' => true,
                            ),
                            'choices' => array (
                                0 => array (
                                    'label' => 'Yes',
                                    'value' => true,
                                ) ,
                                1 => array (
                                    'label' => 'No',
                                    'value' => false,
                                ) ,
                            )
                        )                         
                    )
                )
            ) ,
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array (
                    'name' => 'service_count',
                    'label' => 'Services',
                    'type' => 'number'
                ) ,
                3 => array (
                    'name' => 'is_enable_common_hourly_rate_for_all_sub_services ',
                    'label' => 'Enable Common Hourly Rate For All Sub Services?',
                    'type' => 'boolean'
                ) ,    
                4 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                ) ,                            
                5 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ) ,
                6 => array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'boolean'
                ) ,                 
                11 => array (
                    'name' => "Service Provider's Additional Information",
                    'label' => "Service Provider's Additional Information",
                    'targetEntity' => 'form_field_submissions',
                    'targetReferenceField' => 'foreign_id',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'id',
                            'label' => 'ID',
                            'isDetailLink' => true,
                        ),
                        1 => array (
                            'name' => 'created_at',
                            'label' => 'Created',
                        ),
                        2 => array (
                            'name' => 'form_field.name',
                            'label' => 'Form Field',
                        ),
                        3 => array (
                            'name' => 'response',
                            'label' => 'Response',
                            'map' => 
                            array (
                                0 => 'truncate',
                            ),          
                        ), 
                        4 => array (
                            'name' => 'class',
                            'label' => 'Class',
                        ),
                        5 => array (
                            'name' => 'is_custom_form_field',
                            'label' => 'Custom Form Field?',
                            'type' => 'boolean'
                        ),
                    ) ,
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'referenced_list',
                    'permanentFilters' => array (
                        'class' => 'Category'
                    ),
                    'perPage' => 10
                    ) ,
            ) ,
        ) ,
    ) ,
);

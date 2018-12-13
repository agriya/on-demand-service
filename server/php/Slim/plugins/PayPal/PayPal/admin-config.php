<?php
$menus = array (
    'Payments' => array (
        'title' => 'Payments',
        'icon_template' => '<span class="fa fa-money fa-fw"></span>',
        'child_sub_menu' => array (
            'user_credit_cards' => array (
                'title' => 'Credit Cards',
                'icon_template' => '<span class="fa fa-credit-card"></span>',
                'suborder' => 1
            ) ,
        ) ,
        'order' => 4
    ) ,
);
$tables = array (
    'user_credit_cards' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                     'name' => 'user.email',
                    'label' => 'User',
                    
                ) ,
                2 => array (
                    'name' => 'masked_card_number',
                    'label' => 'Card Number'
                ) ,  
                3 => array (
                    'name' => 'credit_card_type',
                    'label' => 'Credit card type'
                ) ,  
                4 => array (
                    'name' => 'is_primary',
                    'label' => 'Primary?',
                    'type' => 'boolean'
                ) ,                                                
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Credit Cards',
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
                    'name' => 'is_primary',
                    'label' => 'Primary?',
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
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter'
            ) ,
        ) ,  
        'creationview' => array (
            'fields' => array (                
                1 => array (
                    'name' => 'is_primary',
                    'label' => 'primary?',
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
            ),             
        ) ,     
        'editionview' => array (
            'fields' => array (                
                1 => array (
                    'name' => 'is_primary',
                    'label' => 'primary?',
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
            ),             
        ) ,
        'showview' => array (
            'fields' => array (
               0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                     'name' => 'user.email',
                    'label' => 'User',
                    
                ) ,
                2 => array (
                    'name' => 'masked_card_number',
                    'label' => 'Card Number'
                ) ,  
                3 => array (
                    'name' => 'credit_card_type',
                    'label' => 'Credit card type'
                ) ,  
                4 => array (
                    'name' => 'is_primary',
                    'label' => 'Primary?',
                    'type' => 'boolean'
                ) ,                                                
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) , 
            ) ,
        ) ,
    ) ,
);

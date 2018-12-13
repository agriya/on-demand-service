<?php
$menus = array (
    'Users' => array (
        'title' => 'Users',
        'icon_template' => '<span class="fa fa-users fa-fw"></span>',
        'child_sub_menu' => array (
            'blocked_users' => array (
                'title' => 'Blocked Users',
                'icon_template' => '<span class="fa fa-ban"></span>',
                'suborder' => 7
            ) ,
        ) ,
        'order' => 7
    ) ,
);
$tables = array (
    'blocked_users' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID'
                ) ,
                1 => array (
                    'name' => 'user.username',
                    'label' => 'Username',
                ) ,
                3 => array (
                    'name' => 'blocked_user.username',
                    'label' => 'Blocked Username',
                ),                                                
                4 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Blocked Users',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'delete',
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
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter'
            ) ,
        ) ,
    ) ,
);
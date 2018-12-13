<?php
$menus = array(
    'Master' => array(
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-database fa-fw"></span>',
        'child_sub_menu' => array(
            'cancellation_policies' => array(
                'title' => 'Cancellation Policies',
                'icon_template' => '<span class="fa fa-close"></span>',
                'suborder' => 4
            ) ,
        ) ,
        'order' => 8
    )
);
$tables = array(
    'cancellation_policies' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                3 => array(
                    'name' => 'days_before',
                    'label' => 'Days Before',
                    'type' => 'number'
                ) ,
                4 => array(
                    'name' => 'days_before_refund_percentage',
                    'label' => 'Days Before Refund Percentage',
                    'type' => 'number'
                ) ,
                5 => array(
                    'name' => 'days_after',
                    'label' => 'Days After',
                    'type' => 'number'
                ) ,
                6 => array(
                    'name' => 'days_after_refund_percentage',
                    'label' => 'Days After Refund Percentage',
                    'type' => 'number'
                ) ,
                7 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Cancellation Policies',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'text',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'days_before',
                    'label' => 'Days Before',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'days_before_refund_percentage',
                    'label' => 'Days Before Refund Percentage',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'days_after',
                    'label' => 'Days After',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'days_after_refund_percentage',
                    'label' => 'Days After Refund Percentage',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'text',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'days_before',
                    'label' => 'Days Before',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'days_before_refund_percentage',
                    'label' => 'Days Before Refund Percentage',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'days_after',
                    'label' => 'Days After',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'days_after_refund_percentage',
                    'label' => 'Days After Refund Percentage',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                3 => array(
                    'name' => 'days_before',
                    'label' => 'Days Before',
                    'type' => 'number'
                ) ,
                4 => array(
                    'name' => 'days_before_refund_percentage',
                    'label' => 'Days Before Refund Percentage',
                    'type' => 'number'
                ) ,
                5 => array(
                    'name' => 'days_after',
                    'label' => 'Days After',
                    'type' => 'number'
                ) ,
                6 => array(
                    'name' => 'days_after_refund_percentage',
                    'label' => 'Days After Refund Percentage',
                    'type' => 'number'
                ) ,
            ) ,
        ) ,
    ) ,
);

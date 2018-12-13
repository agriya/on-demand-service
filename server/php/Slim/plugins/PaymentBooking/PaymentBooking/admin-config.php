<?php
$menus = array(
    'Payments' => array(
        'title' => 'Payments',
        'icon_template' => '<span class="fa fa-usd"></span>',
        'child_sub_menu' => array(
            'payment_gateways' => array(
                'title' => 'Payment Gateways',
                'icon_template' => '<span class="fa fa-cc-visa"></span>',
                'suborder' => 1
            ) ,
            'transactions' => array(
                'title' => 'Transactions',
                'icon_template' => '<span class="fa fa-exchange"></span>',
                'link' => '/transactions',
                'suborder' => 2
            ) ,
        ) ,
        'order' => 4
    ) ,
);
$tables = array(
    'payment_gateways' => array(
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
                    'name' => 'is_test_mode',
                    'label' => 'Test Mode?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Payment Gateways',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
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
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'display_name',
                    'label' => 'Display Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'slug',
                    'label' => 'Slug',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'gateway_fees',
                    'label' => 'Gateway Fees',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_test_mode',
                    'label' => ' Test Mode?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'is_enable_for_wallet',
                    'label' => 'Is Enable For Wallet',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'editable' => false,
                ) ,
              /*  1 => array(
                    'name' => 'description',
                    'label' => 'description',
                    'editable' => false,
                ) ,*/
                2 => array(
                    'name' => 'is_test_mode',
                    'label' => '',
                    'template' => '<payment-gateways entry="entry" entity="entity" label="Edit"></payment-gateways>',
                ) ,
            ) ,
            'actions' => array()
        ) ,
    ) ,
    'transactions' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                2 => array(
                    'name' => 'other_user.username',
                    'label' => 'To User',
                ) ,
                3 => array(
                    'name' => 'foreign_id',
                    'label' => 'Foreign',
                ) ,
                4 => array(
                    'name' => 'class',
                    'label' => 'Class',
                ) ,
                5 => array(
                    'name' => 'transaction_type',
                    'label' => 'Transaction Type',
                ) ,
                6 => array(
                    'name' => 'payment_gateway.name',
                    'label' => 'Payment Gateway',
                ) ,
                7 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                8 => array(
                    'name' => 'site_revenue_from_freelancer',
                    'label' => 'Site Revenue From Freelancer',
                ) ,
                9 => array(
                    'name' => 'coupon_id',
                    'label' => 'Coupon',
                ) ,
                10 => array(
                    'name' => 'site_revenue_from_employer',
                    'label' => 'Site Revenue From Employer',
                ) ,
                11 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Transactions',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                //0 => 'edit',
                //0 => 'show',
                //2 => 'delete',
                
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
                1 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'to_user_id',
                    'label' => 'To User',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                4 => array(
                    'name' => 'payment_gateway_id',
                    'label' => 'Payment Gateway',
                    'targetEntity' => 'payment_gateways',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                5 => array(
                    'name' => 'coupon_id',
                    'label' => 'Coupon',
                    'targetEntity' => 'coupons',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'to_user_id',
                    'label' => 'To User',
                    'targetEntity' => 'to_users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'foreign_id',
                    'label' => 'Foreign',
                    'targetEntity' => 'foreigns',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'class',
                    'label' => 'Class',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'transaction_type',
                    'label' => 'Transaction Type',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'payment_gateway_id',
                    'label' => 'Payment Gateway',
                    'targetEntity' => 'payment_gateways',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'site_revenue_from_freelancer',
                    'label' => 'Site Revenue From Freelancer',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'coupon_id',
                    'label' => 'Coupon',
                    'targetEntity' => 'coupons',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                9 => array(
                    'name' => 'site_revenue_from_employer',
                    'label' => 'Site Revenue From Employer',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'to_user_id',
                    'label' => 'To User',
                    'targetEntity' => 'to_users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'foreign_id',
                    'label' => 'Foreign',
                    'targetEntity' => 'foreigns',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'class',
                    'label' => 'Class',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'transaction_type',
                    'label' => 'Transaction Type',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'payment_gateway_id',
                    'label' => 'Payment Gateway',
                    'targetEntity' => 'payment_gateways',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'site_revenue_from_freelancer',
                    'label' => 'Site Revenue From Freelancer',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'coupon_id',
                    'label' => 'Coupon',
                    'targetEntity' => 'coupons',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                9 => array(
                    'name' => 'site_revenue_from_employer',
                    'label' => 'Site Revenue From Employer',
                    'type' => 'string',
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
                    'name' => 'user_id',
                    'label' => 'User',
                ) ,
                2 => array(
                    'name' => 'to_user_id',
                    'label' => 'To User',
                ) ,
                3 => array(
                    'name' => 'foreign_id',
                    'label' => 'Foreign',
                ) ,
                4 => array(
                    'name' => 'class',
                    'label' => 'Class',
                ) ,
                5 => array(
                    'name' => 'transaction_type',
                    'label' => 'Transaction Type',
                ) ,
                6 => array(
                    'name' => 'payment_gateway_id',
                    'label' => 'Payment Gateway',
                ) ,
                7 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                8 => array(
                    'name' => 'site_revenue_from_freelancer',
                    'label' => 'Site Revenue From Freelancer',
                ) ,
                9 => array(
                    'name' => 'coupon_id',
                    'label' => 'Coupon',
                ) ,
                10 => array(
                    'name' => 'site_revenue_from_employer',
                    'label' => 'Site Revenue From Employer',
                ) ,
            ) ,
        ) ,
    ) ,
);

<?php
$menus = array(
    'Payments' => array(
        'title' => 'Payments',
        'icon_template' => '<span class="glyphicon glyphicon-usd"></span>',
        'child_sub_menu' => array(
            'user_cash_withdrawals' => array(
                'title' => 'Withdraw Requests ',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                'suborder' => 4
            ) ,
            'money_transfer_accounts' => array(
                'title' => 'Money Transfer Accounts',
                'icon_template' => '<span class="fa fa-money"></span>',
                'suborder' => 5
            ) ,
        ) ,
        'order' => 4
    ) ,
);
$tables = array(
    'user_cash_withdrawals' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'suborder' => 1
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                    // 'isDetailLink' => false,
                    'suborder' => 2,
                    'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
                ) ,
                2 => array(
                    'name' => 'money_transfer_account.account',
                    'label' => 'Money Transfer Accounts',
                    'type' => 'wysiwyg',
                    'suborder' => 3
                ) ,
                7 => array(
                    'name' => 'remark',
                    'label' => 'Payment Note',
                    'suborder' => 5
                ) ,
                3 => array(
                    'name' => 'withdrawal_status.name',
                    'label' => ' Status?',
                    'suborder' => 4
                ) ,
                4 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                    'suborder' => 8
                ) ,
            ) ,
            'title' => 'User Cash Withdrawals List',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show'
            ) ,
            'batchActions' => array(
                0 => '<batch-withdraw-pending type="pending" action="user_cash_withdrawals" selection="selection"></batch-withdraw-pending>',
                1 => '<batch-withdraw-process type="process" action="user_cash_withdrawals" selection="selection"></batch-withdraw-process>',
                2 => '<batch-withdraw-reject type="reject" action="user_cash_withdrawals" selection="selection"></batch-withdraw-reject>',
                3 => '<batch-withdraw-success type="success" action="user_cash_withdrawals" selection="selection"></batch-withdraw-success>'
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
                    'name' => 'withdrawal_status_id',
                    'label' => 'Status?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Pending',
                            'value' => 1,
                        ) ,
                        1 => array(
                            'label' => 'Under Process',
                            'value' => 2,
                        ) ,
                        2 => array(
                            'label' => 'Approved',
                            'value' => 5,
                        ) ,
                        3 => array(
                            'label' => 'Rejected',
                            'value' => 3,
                        ) ,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true
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
                    'name' => 'money_transfer_account_id',
                    'label' => 'Money Transfer Account',
                    'targetEntity' => 'money_transfer_accounts',
                    'targetField' => 'account',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'remark',
                    'label' => 'Remark',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'money_transfer_account_id',
                    'label' => 'Money Transfer Account',
                    'targetEntity' => 'money_transfer_accounts',
                    'targetField' => 'account',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'editable' => false,
                    'suborder' => 1
                ) ,
                2 => array(
                    'name' => 'remark',
                    'label' => 'Payment Note',
                    'type' => 'text',
                    'validation' => array(
                        'required' => false,
                    ) ,
                    'suborder' => 5
                ) ,
                4 => array(
                    'name' => 'withdrawal_status_id',
                    'label' => ' Status?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Pending',
                            'value' => 1,
                        ) ,
                        1 => array(
                            'label' => 'Under Process',
                            'value' => 2,
                        ) ,
                        2 => array(
                            'label' => 'Approved',
                            'value' => 5,
                        ) ,
                        3 => array(
                            'label' => 'Rejected',
                            'value' => 3,
                        ) ,
                    ) ,
                    'suborder' => 6
                ) ,
            ) ,
            'actions' => array(
                0 => 'list'
            )
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'suborder' => 1
                ) ,
                1 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                    'suborder' => 2
                ) ,
                2 => array(
                    'name' => 'updated_at',
                    'label' => 'Updated',
                    'suborder' => 3
                ) ,
                3 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'type' => 'reference',
                    'suborder' => 4
                ) ,
                7 => array(
                    'name' => 'money_transfer_account.account',
                    'label' => 'Money Transfer Accounts',
                    'type' => 'wysiwyg',
                    'suborder' => 5
                ) ,
                8 => array(
                    'name' => 'remark',
                    'label' => 'Payment Note',
                    'suborder' => 10
                ) ,
                9 => array(
                    'name' => 'withdrawal_status_id',
                    'label' => ' Status?',
                    'targetEntity' => 'withdrawal_statuses',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'suborder' => 6
                ) ,
            ) ,
        ) ,
    ) ,
    'money_transfer_accounts' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                1 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    // 'targetEntity' => 'users',
                    // 'targetField' => 'email',
                    // 'type' => 'reference',
                    // 'singleApiCall' => 'getUsers',
                    // 'isDetailLink' => false
                    'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
                ) ,
                2 => array(
                    'name' => 'account',
                    'label' => 'Account',
                    'type' => 'wysiwyg'
                ) ,
                5 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Money Transfer Accounts',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'show',
                1 => 'delete',
            ) ,
            'batchActions' => array(
                0 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
                3 => array(
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
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
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
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'type' => 'reference',
                    'singleApiCall' => 'getUsers',
                    'isDetailLink' => false
                ) ,
                2 => array(
                    'name' => 'account',
                    'label' => 'Account',
                )
            ) ,
        ) ,
    ) ,
);
if (!empty(WITHDRAW_REQUEST_FEE)) {
    $milestone_table = array(
        'user_cash_withdrawals' => array(
            'listview' => array(
                'fields' => array(
                    4 => array(
                        'name' => 'amount',
                        'label' => 'Requested Amount',
                        'suborder' => 5
                    ) ,
                    5 => array(
                        'name' => 'withdrawal_fee',
                        'label' => 'Withdrawal Fee',
                        'suborder' => 6
                    ) ,
                    6 => array(
                        'name' => '',
                        'map' => array(
                            0 => 'withdrawn',
                        ) ,
                        'label' => 'Final Amount',
                        'suborder' => 7
                    )
                )
            ) ,
            'showview' => array(
                'fields' => array(
                    4 => array(
                        'name' => 'amount',
                        'label' => 'Requested Amount',
                        'suborder' => 7
                    ) ,
                    5 => array(
                        'name' => 'withdrawal_fee',
                        'label' => 'Withdrawal Fee',
                        'suborder' => 8
                    ) ,
                    6 => array(
                        'name' => '',
                        'map' => array(
                            0 => 'withdrawn',
                        ) ,
                        'label' => 'Final Amount',
                        'suborder' => 9
                    )
                )
            ) ,
            'editionview' => array(
                'fields' => array(
                    4 => array(
                        'name' => 'amount',
                        'label' => 'Requested Amount',
                        'editable' => false,
                        'suborder' => 2
                    ) ,
                    5 => array(
                        'name' => 'withdrawal_fee',
                        'label' => 'Withdrawal Fee',
                        'editable' => false,
                        'suborder' => 3
                    ) ,
                    6 => array(
                        'name' => '',
                        'map' => array(
                            0 => 'withdrawn',
                        ) ,
                        'label' => 'Final Amount',
                        'editable' => false,
                        'suborder' => 4
                    )
                )
            )
        )
    );
    $tables = merge_details($tables, $milestone_table);
} else {
    $milestone_table = array(
        'user_cash_withdrawals' => array(
            'listview' => array(
                'fields' => array(
                    2 => array(
                        'name' => 'amount',
                        'label' => 'Requested Amount',
                        'suborder' => 5
                    )
                )
            ) ,
            'showview' => array(
                'fields' => array(
                    4 => array(
                        'name' => 'amount',
                        'label' => 'Requested Amount',
                        'suborder' => 7
                    )
                )
            ) ,
            'editionview' => array(
                'fields' => array(
                    4 => array(
                        'name' => 'amount',
                        'label' => 'Requested Amount',
                        'editable' => false,
                        'suborder' => 2
                    )
                )
            )
        )
    );
    $tables = merge_details($tables, $milestone_table);
}

<?php
$menus = array (
    'Bookings' => array (
        'title' => 'Bookings',
        'icon_template' => '<span class="fa fa-ticket fa-fw"></span>',
        'child_sub_menu' => array (
            'user_reviews' => array (
                'title' => 'Reviews',
                'icon_template' => '<span class="fa fa-star fa-fw"></span>',
                'suborder' => 3
            ) ,
        ) ,
        'order' => 3
    ) ,
);
$tables = array (
    'user_reviews' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    // 'name' => 'user.email',
                    'label' => 'Customer',
                    'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
                ) ,
                2 => array (
                    // 'name' => 'to_user.email',
                    'label' => 'Service Provider',
                    'template' => '<display-provider-reviews entry="entry" entity="entity" size="sm" label="Change Name" ></display-provider-reviews>'
                ),
                3 => array (
                    'name' => 'to_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                ),
                4 => array (
                    'name' => 'rating',
                    'label' => 'Rating',
                    'template' => '<star-rating stars="{{entry.values.rating}}"></star-rating>',
                ) ,
                5 => array (
                    'name' => 'message',
                    'label' => 'Review',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                ) ,
                6 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Reviews',
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
                0 => 'delete',
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
                2 => array (
                    'name' => 'to_user_id',
                    'label' => 'Service Provider',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'permanentFilters' => array (
                        'role_id' => 3,
                    ), 
                    'remoteCompleteAdditionalParams' => array (
                        'role_id' => 3,
                    )
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
                0 => array (
                    'name' => 'rating',
                    'label' => 'Rating',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'editable' => false
                ) ,
                2 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'editable' => false
                ) ,
                3 => array (
                    'name' => 'to_user_id',
                    'label' => 'Service Provider',
                    'editable' => false
                ) ,
                4 => array (
                    'name' => 'to_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                    'editable' => false
                ) ,
                5 => array (
                    'name' => 'rating',
                    'label' => 'Rating',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                6 => array (
                    'name' => 'message',
                    'label' => 'Review',
                    'type' => 'text',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
            ) ,
            'prepare' => array (
                'class' => 'Appointment'
            )
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User'
                ) ,
                2 => array (
                    'name' => 'to_user.username',
                    'label' => 'Service Provider'
                ) ,
                4 => array (
                    'name' => 'rating',
                    'label' => 'Rating',
                    'template' => '<star-rating stars="{{entry.values.rating}}"></star-rating>',
                ) ,
                5 => array (
                    'name' => 'message',
                    'label' => 'Message'
                ) ,
                6 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'actions' => array (
                0 => 'list'
            )
        ) ,
    ) ,
);
$dashboard = array (
    /*'user_reviews' => array (
        'addCollection' => array (
            'fields' => array (
                0 => array (
                    'name' => 'user.username',
                    'label' => 'User'
                ) ,
                1 => array (
                    'name' => 'to_user.username',
                    'label' => 'To User'
                ) ,
                2 => array (
                    'name' => 'rating',
                    'label' => 'Rating',
                    'template' => '<star-rating stars="{{entry.values.rating}}"></star-rating>'
                ) ,
                3 => array (
                    'name' => 'message',
                    'label' => 'Message'
                ) ,
            ) ,
            'title' => 'Recent Reviews',
            'name' => 'recent_reviews',
            'perPage' => 5,
            'order' => 7,
            'template' => '<div class="col-lg-6"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_reviews" entries="dashboardController.entries.recent_reviews" datastore="dashboardController.datastore"></ma-dashboard-panel></div></div>'
        )
    )*/
);

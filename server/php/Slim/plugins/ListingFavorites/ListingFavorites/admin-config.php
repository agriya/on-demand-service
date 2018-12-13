<?php
$menus = array (
    'Listings' => array (
        'title' => 'Listings',
        'icon_template' => '<span class="fa fa-list fa-fw"></span>',
        'child_sub_menu' => array (
            'listing_favorites' => array (
                'title' => 'Listing Favorites',
                'icon_template' => '<span class="fa fa-heart fa-fw"></span>',
                'suborder' => 6
            ),
        ),
        'order' => 2
    ),
);
$tables = array (
    'listing_favorites' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ),
                1 => array (
                    // 'name' => 'user.username',
                    'label' => 'User',
                    'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
                ),
                2 => array (
                    // 'name' => 'provider_user.username',
                    'label' => 'Listing Owner',
                    'template' => '<display-provider-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-provider-favourite>'
                ),
                3 => array (
                    'name' => 'provider_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                ),
                4 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ),
            ),
            'title' => 'Listing Favorites',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'delete',
            ),
            'batchActions' => array (
                0 => 'delete',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ),
                1 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                ),
                2 => array (
                    'name' => 'provider_user_id',
                    'label' => 'Listing Owner',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'map' => array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'permanentFilters' => array (
                        'role_id' => 3,
                    ), 
                    'remoteCompleteAdditionalParams' => array (
                        'role_id' => 3,
                    )
                ),
            ),
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
            ),
        ),
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                1 => array (
                    'name' => 'provider_user_id',
                    'label' => 'Listing Owner',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
            ),
        ),
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
                1 => array (
                    'name' => 'provider_user_id',
                    'label' => 'Listing Owner',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ),
                ),
            ),
        )
    ),
);

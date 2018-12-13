<?php
$menus = array (
    'Users' => array (
        'title' => 'Users',
        'icon_template' => '<span class="fa fa-users fa-fw"></span>',
        'child_sub_menu' => array (
            'service_providers' => array (
                'title' => 'Service Providers',
                'icon_template' => '<span class="fa fa-users fa-fw"></span>',
                'suborder' => 1
            ) ,
            'customers' => array (
                'title' => 'Customers',
                'icon_template' => '<span class="fa fa-users fa-fw"></span>',
                'suborder' => 2
            ) ,
            'administrators' => array (
                'title' => 'Administrators',
                'icon_template' => '<span class="fa fa-user-secret fa-fw"></span>',
                'suborder' => 3
            ) ,            
            'add_users' => array (
                'title' => 'Add New User',
                'icon_template' => '<span class="fa fa-user fa-fw"></span>',
                'suborder' => 4,
                'link' => '/add_users/create'
            ) ,
            'user_logins' => array (
                'title' => 'User Logins',
                'icon_template' => '<span class="fa fa-reply fa-fw"></span>',
                'suborder' => 5
            ) ,
            'contacts' => array (
                'title' => 'Contacts',
                'icon_template' => '<span class="fa fa-book fa-fw"></span>',
                'suborder' => 6
            ) ,
        ) ,
        'order' => 1
    ) ,
    'Listings' => array (
        'title' => 'Listings',
        'icon_template' => '<span class="fa fa-list fa-fw"></span>',
        'child_sub_menu' => array (
            'listings' => array (
                'title' => 'Listings',
                'icon_template' => '<span class="fa fa-bars"></span>',
                'suborder' => 1
            ),            
            'listing_views' => array (
                'title' => 'Listing Views',
                'icon_template' => '<span class="fa fa-th-list"></span>',
                'suborder' => 3
            )
        ) ,
        'order' => 2
    ) ,    
    'Bookings' => array (
        'title' => 'Bookings',
        'icon_template' => '<span class="fa fa-ticket fa-fw"></span>',
        'child_sub_menu' => array (
            /*'appointment_statuses' => array (
                'title' => 'Appointment Statuses',
                'icon_template' => '<span class="fa fa-users fa-fw"></span>',
                'suborder' => 1
            ) ,
            'appointment_settings' => array (
                'title' => 'Appointment Settings',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                'suborder' => 2
            ) ,
            'appointment_modifications' => array (
                'title' => 'Appointment Modifications',
                'icon_template' => '<span class="fa fa-list fa-fw"></span>',
                'suborder' => 3
            ) ,*/
            'bookings' => array (
                'title' => 'Bookings',
                'icon_template' => '<span class="fa fa-ticket fa-fw"></span>',
                'suborder' => 1
            ) ,
        ) ,
        'order' => 3
    ) ,
    'Master' => array (
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-database fa-fw"></span>',
        'child_sub_menu' => array (
            'pages' => array (
                'title' => 'StaticPages',
                'icon_template' => '<span class="fa fa-file fa-fw"></span>',
                'suborder' => 16
            ) ,
            'email_templates' => array (
                'title' => 'Email Templates',
                'icon_template' => '<span class="fa fa-inbox fa-fw"></span>',
                'suborder' => 17
            ) ,
            'languages' => array (
                'title' => 'Languages',
                'icon_template' => '<span class="fa fa-language fa-fw"></span>',
                'suborder' => 15
            ) ,
            'translations' => array(
                'title' => 'Translations',
                'icon_template' => '<span class="fa fa-language"></span>',
                'link' => '/translations/all',
                'suborder' => 18
            ) ,
            'countries' => array (
                'title' => 'Countries',
                'icon_template' => '<span class="fa fa-globe fa-fw"></span>',
                'suborder' => 14
            ) ,
            'cities' => array (
                'title' => 'Cities',
                'icon_template' => '<span class="glyphicon glyphicon-road"></span>',
                'suborder' => 12
            ) ,
            'states' => array (
                'title' => 'States',
                'icon_template' => '<span class="fa fa-flag fa-fw"></span>',
                'suborder' => 13
            ) ,
            'services' => array (
                'title' => 'Services',
                'icon_template' => '<span class="fa fa-barcode"></span>',
                'suborder' => 2
            ) ,          
            'faqs' => array (
                'title' => 'FAQs',
                'icon_template' => '<span class="fa fa-question"></span>',
                'suborder' => 11
            ) ,
            'account_close_reasons' => array (
                'title' => 'Account Close Reasons',
                'icon_template' => '<span class="fa fa-times"></span>',
                'suborder' => 3
            ) ,
            'pages' => array (
                'title' => 'Pages',
                'icon_template' => '<i class="fa fa-book" aria-hidden="true"></i>',
                'suborder' => 19
            ) ,
        ) ,
        'order' => 8
    ) ,
    'Settings' => array (
        'title' => 'Settings',
        'icon_template' => '<span class="fa fa-cog fa-fw"></span>',
        'child_sub_menu' => array (
            'setting_categories' => array (
                'title' => 'Site Settings',
                'icon_template' => '<span class="fa fa-cog"></span>',
                'suborder' => 1
            ) ,
            'providers' => 
            array (
                'title' => 'Providers',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                'suborder' => 2
            ),
            'servicelocations' => array (
                'title' => 'Service Locations',
                'icon_template' => '<span class="fa fa-map-marker fa-fw"></span>',
                'link' => '/servicelocation/110',
                'suborder' => 3
            ) ,
        ) ,
        'order' => 6
    ) ,
    'Plugins' => array (
        'title' => 'Plugins',
        'icon_template' => '<span class="fa fa-edit fa-fw"></span>',
        'child_sub_menu' => array (
            'Plugins' => array (
                'title' => 'Plugins',
                'icon_template' => '<span class="fa fa-edit"></span>',
                'link' => '/plugins',
                'suborder' => 1
            ) ,
        ) ,
        'order' => 7
    ) ,
);
$tables = array (
    'service_providers' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ) ,
                1 => array (
                    'label' => 'Service Provider',
                    'template' => '<display-customer-name entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-name>'
                ) ,
                2 => array (
                    'name' => 'category.name',
                    'label' => 'Category'
                ) ,
                4 => array (
                    'name' => 'user_profile.listing_appointment_count',
                    'label' => 'Bookings',
                    'type' => 'number'
                ) ,
                10 => array (
                    'name' => 'user_login_count',
                    'label' => 'Logins',
                    'type' => 'number'
                )
            ) ,
            'title' => 'Service Providers',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
                3 => '<change-password entry="entry" entity="entity" size="sm" label="Change Password" ></change-password>',
            ) ,
            'batchActions' => array (
                0 => 'delete',
                1 => '<batch-active type="active" action="users" selection="selection"></batch-active>',
                2 => '<batch-in-active type="inactive" action="users" selection="selection"></batch-in-active>',
                3 => '<change-delete-status type="users" action="user_profiles" selection="selection"></change-delete-status>',
                4 => '<change-email-verified-status type="users" action="user_profiles" selection="selection"></change-email-verified-status>',
                5 => '<change-email-not-verified-status type="users" action="user_profiles" selection="selection"></change-email-not-verified-status>',
                6 => '<change-mobile-verified-status type="users" action="user_profiles" selection="selection"></change-mobile-verified-status>',
                7 => '<change-mobile-not-verified-status type="users" action="user_profiles" selection="selection"></change-mobile-not-verified-status>',
                8 => '<change-pro-user-mark-status type="users" action="user_profiles" selection="selection"></change-pro-user-mark-status>',
                9 => '<change-pro-user-unmark-status type="users" action="user_profiles" selection="selection"></change-pro-user-unmark-status>',
            ),
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
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                ),
                3 => array (
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                )
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
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'first_name',
                    'label' => 'Firstname',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'last_name',
                    'label' => 'LastName',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'phone',
                    'label' => 'Phone'
                ) ,
                7 => array (
                    'name' => 'is_agree_terms_conditions',
                    'label' => 'Agree Terms Condition?',
                    'type' => 'choice',
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
                8 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
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
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string'
                ) ,
                1 => array (
                    'name' => 'user_profile.first_name',
                    'label' => 'First Name',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'user_profile.last_name',
                    'label' => 'Last Name',
                    'type' => 'string'
                ) ,
                7 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                9 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'choice',
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
                ),
                10 => array (
                    'name' => 'mobile_code_country_id',
                    'label' => 'Mobile Code',
                    'targetEntity' => 'countries',
                    'targetField' => 'phone',
                    'map' => 
                    array (
                        0 => 'truncate',
                    ),
                    'validation' => 
                    array (
                        'required' => true,
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true
                ),          
                11 => 
                    array (
                    'name' => 'phone_number',
                    'label' => 'Mobile',
                    'type' => 'string',
                    'validation' => 
                    array (
                        'required' => true,
                    ),
                ),
                12 => array (
                    'name' => 'secondary_mobile_code_country_id',
                    'label' => 'Secondary Mobile Code',
                    'targetEntity' => 'countries',
                    'targetField' => 'phone',
                    'map' => 
                    array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true
                ),          
                13 => 
                    array (
                    'name' => 'secondary_phone_number',
                    'label' => 'Secondary Mobile',
                    'type' => 'string'                   
                ) ,
                14 => 
                    array (
                    'name' => 'user_profile.verification_note_by_site',
                    'label' => 'Background Verification Note',
                    'type' => 'text'                   
                ) ,
                15 => 
                    array (
                    'name' => 'user_profile.cv',
                    'label' => 'CV',
                    'type' => 'text'                   
                ) ,
                16 => 
                    array (
                    'name' => 'user_profile.driving_license_information',
                    'label' => 'Driving license number',
                    'type' => 'string'                   
                ) ,
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
                    'name' => 'role.name',
                        'label' => 'Role'
                ) ,
                2 => array (
                    'name' => 'user_profile.first_name',
                    'label' => 'First Name',
                ) ,
                3 => array (
                    'name' => 'user_profile.last_name',
                    'label' => 'Last Name',
                ) ,
                4 => array (
                    'name' => 'email',
                    'label' => 'Email'
                ) ,
                5 => array (
                    'label' => 'Mobile Number',
                    'template' => '<show-mobile-number entry="entry" entity="entity" size="sm" label="Change Name" ></show-mobile-number>'
                ) ,
                9 => array (
                    'name' => 'address',
                    'label' => 'Address'
                ) ,
                10 => array (
                    'name' => 'address1',
                    'label' => 'Address Line 2'
                ) ,
                11 => array (
                    'name' => 'city.name',
                    'label' => 'City'
                ) ,
                12 => array (
                    'name' => 'state.name',
                    'label' => 'State'
                ) ,
                13 => array (
                    'name' => 'country.name',
                    'label' => 'Country'
                ) ,
                14 => array (
                    'name' => 'postal_code',
                    'label' => 'Postal Code'
                ) ,
                15 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ) ,
                16 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'boolean'
                ) ,
                17 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'boolean'
                ) ,
                18 => array (
                    'name' => 'is_app_device_available',
                    'label' => 'App Device Used?',
                    'type' => 'boolean'
                ) ,
                19 => array (
                    'name' => 'is_push_notification_enabled',
                    'label' => 'Push Notification Enabled?',
                    'type' => 'boolean'
                )
            ) ,
        ) ,
    ) ,
    'customers' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ) ,
                1 => array (
                    'label' => 'Customer',
                    'template' => '<display-customer-name entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-name>'
                ) ,
                4 => array (
                    'name' => 'appointment_count',
                    'label' => 'Bookings',
                    'type' => 'number'
                ) ,
                10 => array (
                    'name' => 'user_login_count',
                    'label' => 'Logins',
                    'type' => 'number'
                ) ,
                11 => array (
                    'name' => 'user_profile.site_revenue_as_customer',
                    'label' => 'Site Revenue',
                    'type' => 'number'
                )
            ),
            'title' => 'Customers',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
                3 => '<change-password entry="entry" entity="entity" size="sm" label="Change Password" ></change-password>',
            ) ,
            'batchActions' => array (
                0 => 'delete',
                1 => '<batch-active type="active" action="users" selection="selection"></batch-active>',
                2 => '<batch-in-active type="inactive" action="users" selection="selection"></batch-in-active>',
                3 => '<change-delete-status type="users" action="user_profiles" selection="selection"></change-delete-status>',
                4 => '<change-email-verified-status type="users" action="user_profiles" selection="selection"></change-email-verified-status>',
                5 => '<change-email-not-verified-status type="users" action="user_profiles" selection="selection"></change-email-not-verified-status>',
                6 => '<change-mobile-verified-status type="users" action="user_profiles" selection="selection"></change-mobile-verified-status>',
                7 => '<change-mobile-not-verified-status type="users" action="user_profiles" selection="selection"></change-mobile-not-verified-status>',
            ),
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
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                1 => 'filter'
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'first_name',
                    'label' => 'Firstname',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'last_name',
                    'label' => 'LastName',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'phone',
                    'label' => 'Phone'
                ) ,
                7 => array (
                    'name' => 'is_agree_terms_conditions',
                    'label' => 'Agree Terms Condition?',
                    'type' => 'choice',
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
                8 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
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
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string'
                ) ,
                1 => array (
                    'name' => 'user_profile.first_name',
                    'label' => 'First Name',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'user_profile.last_name',
                    'label' => 'Last Name',
                    'type' => 'string'
                ) ,
                7 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                9 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'choice',
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
                ),
                10 => array (
                    'name' => 'mobile_code_country_id',
                    'label' => 'Mobile Code',
                    'targetEntity' => 'countries',
                    'targetField' => 'phone',
                    'map' => 
                    array (
                        0 => 'truncate',
                    ),
                    'validation' => 
                    array (
                        'required' => true,
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true
                ),          
                11 => 
                    array (
                    'name' => 'phone_number',
                    'label' => 'Mobile',
                    'type' => 'string',
                    'validation' => 
                    array (
                        'required' => true,
                    ),
                ),
                12 => array (
                    'name' => 'secondary_mobile_code_country_id',
                    'label' => 'Secondary Mobile Code',
                    'targetEntity' => 'countries',
                    'targetField' => 'phone',
                    'map' => 
                    array (
                        0 => 'truncate',
                    ),
                    'type' => 'reference',
                    'remoteComplete' => true
                ),          
                13 => 
                    array (
                    'name' => 'secondary_phone_number',
                    'label' => 'Secondary Mobile',
                    'type' => 'string'                   
                ),
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
                    'name' => 'role.name',
                        'label' => 'Role'
                ) ,
                2 => array (
                    'name' => 'user_profile.first_name',
                    'label' => 'First Name',
                ) ,
                3 => array (
                    'name' => 'user_profile.last_name',
                    'label' => 'Last Name',
                ) ,
                4 => array (
                    'name' => 'email',
                    'label' => 'Email'
                ) ,
                5 => array (
                    // 'name' => 'mobile_code' . 'phone_number',
                    'label' => 'Mobile Number',
                    'template' => '<show-mobile-number entry="entry" entity="entity" size="sm" label="Change Name" ></show-mobile-number>'
                ) ,
                9 => array (
                    'name' => 'address',
                    'label' => 'Address'
                ) ,
                10 => array (
                    'name' => 'address1',
                    'label' => 'Address Line 2'
                ) ,
                11 => array (
                    'name' => 'city.name',
                    'label' => 'City'
                ) ,
                12 => array (
                    'name' => 'state.name',
                    'label' => 'State'
                ) ,
                13 => array (
                    'name' => 'country.name',
                    'label' => 'Country'
                ) ,
                14 => array (
                    'name' => 'postal_code',
                    'label' => 'Postal Code'
                ) ,
                15 => array (
                    'name' => 'user_profile.site_revenue_as_customer',
                    'label' => 'Site Revenue',
                    'type' => 'number'
                ) ,
                16 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ) ,
                17 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'boolean'
                ) ,
                18 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'boolean'
                ) ,
                19 => array (
                    'name' => 'is_app_device_available',
                    'label' => 'App Device Used?',
                    'type' => 'boolean'
                ) ,
                20 => array (
                    'name' => 'is_push_notification_enabled',
                    'label' => 'Push Notification Enabled?',
                    'type' => 'boolean'
                )
            ) ,
        ) ,
    ) ,
    'administrators' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ) ,
                1 => array (
                    'label' => 'Administrator',
                    'template' => '<display-customer-name entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-name>'
                ) ,
                10 => array (
                    'name' => 'user_login_count',
                    'label' => 'Logins',
                    'type' => 'number'
                )
            ),
            'title' => 'Administrator',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
                3 => '<change-password entry="entry" entity="entity" size="sm" label="Change Password" ></change-password>',
            ) ,
            'batchActions' => array (
                0 => 'delete',
                1 => '<batch-active type="active" action="users" selection="selection"></batch-active>',
                2 => '<batch-in-active type="inactive" action="users" selection="selection"></batch-in-active>',
                3 => '<batch-mobile-verified type="verify" action="users" selection="selection"></batch-mobile-verified>',
                4 => '<batch-mobile-not-verified type="notverify" action="users" selection="selection"></batch-mobile-not-verified>',
            ),
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
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                1 => 'filter'
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'first_name',
                    'label' => 'Firstname',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'last_name',
                    'label' => 'LastName',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'phone',
                    'label' => 'Phone'
                ) ,
                7 => array (
                    'name' => 'is_agree_terms_conditions',
                    'label' => 'Agree Terms Condition?',
                    'type' => 'choice',
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
                8 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
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
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string'
                ) ,
                1 => array (
                    'name' => 'user_profile.first_name',
                    'label' => 'First Name',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'user_profile.last_name',
                    'label' => 'Last Name',
                    'type' => 'string'
                ) ,
                7 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                9 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'choice',
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
                    'name' => 'role.name',
                        'label' => 'Role'
                ) ,
                2 => array (
                    'name' => 'user_profile.first_name',
                    'label' => 'First Name',
                ) ,
                3 => array (
                    'name' => 'user_profile.last_name',
                    'label' => 'Last Name',
                ) ,
                4 => array (
                    'name' => 'email',
                    'label' => 'Email'
                ) ,
                5 => array (
                    'name' => 'mobile_code' . 'phone_number',
                    'label' => 'Mobile Number'
                ) ,
                6 => array (
                    'name' => 'secondary_mobile_code'.'secondary_phone_number',
                    'label' => 'Secondary Mobile Number'
                ) ,
                9 => array (
                    'name' => 'address',
                    'label' => 'Address'
                ) ,
                10 => array (
                    'name' => 'address1',
                    'label' => 'Address Line 2'
                ) ,
                11 => array (
                    'name' => 'city.name',
                    'label' => 'City'
                ) ,
                12 => array (
                    'name' => 'state.name',
                    'label' => 'State'
                ) ,
                13 => array (
                    'name' => 'country.name',
                    'label' => 'Country'
                ) ,
                14 => array (
                    'name' => 'postal_code',
                    'label' => 'Postal Code'
                ) ,
                15 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ) ,
                16 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'boolean'
                ) ,
                17 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'boolean'
                ) ,
                18 => array (
                    'name' => 'is_app_device_available',
                    'label' => 'App Device Used?',
                    'type' => 'boolean'
                ) ,
                19 => array (
                    'name' => 'is_push_notification_enabled',
                    'label' => 'Push Notification Enabled?',
                    'type' => 'boolean'
                )
            ) ,
        ) ,
    ) ,    
    'add_users' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ) ,
                2 => array (
                    'name' => 'username',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'email',
                    'label' => 'Email'
                ) ,
                4 => array (
                    'name' => 'phone_number',
                    'label' => 'Mobile Number'
                ) ,
                1 => array (
                    'name' => 'role.name',
                    'label' => 'Role'
                ) ,
                10 => array (
                    'name' => 'user_login_count',
                    'label' => 'Logins',
                    'type' => 'number'
                )
            ) ,
            'title' => 'Users',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
            
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
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                )
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => 'filter',
                1 => 'create'
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ),
                1 => array (
                    'name' => 'first_name',
                    'label' => 'Firstname',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'last_name',
                    'label' => 'LastName',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                9 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
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
                10 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                ),
                5 => array (
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                )
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string'
                ) ,
                1 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string'
                ) ,
                7 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
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
                9 => array (
                    'name' => 'is_profile_updated',
                    'label' => 'Profile Updated?',
                    'type' => 'choice',
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
                )
            ) ,
        )
    ) ,
    'listings' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ) ,
                1 => array (
                    'name' => 'email',
                    // 'label' => 'Service Provider',
                    'template' => '<display-customer-name entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-name>'
                ) ,
                2 => array (
                    'name' => 'user_profile.listing_title',
                    'label' => 'Listing Title'
                ) ,
                3 => array (
                    'name' => 'user_profile.listing_address',
                    'label' => 'Listing Location'
                ) ,
                4 => array (
                    'name' => 'user_profile.user_view_count',
                    'label' => 'Views',
                    'type' => 'number'
                ) ,  
                5 => array (
                    'name' => 'user_profile.user_favorite_count',
                    'label' => 'Favorites',
                    'type' => 'number'
                ) ,                                
                6 => array (
                    'name' => 'user_profile.completed_appointment_count',
                    'label' => 'Completed Bookings',
                    'type' => 'number'
                ) ,
                7 => array (
                    'name' => 'user_profile.photo_count',
                    'label' => 'Photos',
                    'type' => 'number'
                ),
                8 => array (
                    'name' => 'user_profile.response_rate',
                    'label' => 'Response Rate (%)',
                    'type' => 'number'
                ) ,
                9 => array (
                    'name' => 'user_profile.response_time',
                    'label' => 'Response Time',
                    'type' => 'number'
                ) ,
                10 => array (
                    'name' => 'user_profile.listing_status.name',
                    'label' => 'Status'
                )
            ) ,
            'title' => 'Listing',
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
                1 => '<batch-active type="active" action="users" selection="selection"></batch-active>',
                2 => '<batch-in-active type="inactive" action="users" selection="selection"></batch-in-active>',
                3 => '<change-mobile-verified-status type="users" action="user_profiles" selection="selection"></change-mobile-verified-status>',
                4 => '<change-mobile-not-verified-status type="users" action="user_profiles" selection="selection"></change-mobile-not-verified-status>',
                5 => '<change-address-verified-status type="users" action="user_profiles" selection="selection"></change-address-verified-status>',
                6 => '<change-address-not-verified-status type="users" action="user_profiles" selection="selection"></change-address-not-verified-status>',
                7 => '<change-online-test-completed-status type="users" action="user_profiles" selection="selection"></change-online-test-completed-status>',
                8 => '<change-online-test-not-completed-status type="users" action="user_profiles" selection="selection"></change-online-test-not-completed-status>',
                9 => '<change-listing-draft-status type="users" action="user_profiles" selection="selection"></change-listing-draft-status>',
                10 => '<change-listing-admin-approval-status type="users" action="user_profiles" selection="selection"></change-listing-admin-approval-status>',
                11 => '<change-listing-approved-status type="users" action="user_profiles" selection="selection"></change-listing-approved-status>',
                12 => '<change-listing-invisible-status type="users" action="user_profiles" selection="selection"></change-listing-invisible-status>',
                13 => '<change-listing-rejected-status type="users" action="user_profiles" selection="selection"></change-listing-rejected-status>',
            ),
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
                1 => array (
                    'name' => 'id',
                    'label' => 'Service Provider',
                    'targetEntity' => 'users',
                    'targetField' => 'email',
                    'type' => 'reference',
                    'permanentFilters' => array (
                        'role_id' => 3,
                    ), 
                    'remoteCompleteAdditionalParams' => array (
                        'role_id' => 3,
                    )
                ),
                2 => array (
                    'name' => 'user_profile.listing_status_id',
                    'label' => 'Listing Status',
                    'targetEntity' => 'listing_statuses',
                    'targetField' => 'name',
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
                0 => array (
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'first_name',
                    'label' => 'Firstname',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'last_name',
                    'label' => 'LastName',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'phone',
                    'label' => 'Phone'
                ) ,
                7 => array (
                    'name' => 'is_agree_terms_conditions',
                    'label' => 'Agree Terms Condition?',
                    'type' => 'choice',
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
                8 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
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
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'listing_title',
                    'label' => 'Listing Title',
                    'type' => 'string'
                ) ,
                1 => array (
                    'name' => 'listing_description',
                    'label' => 'Description',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'listing_address',
                    'label' => 'Location',
                    'template' => '<google-places entry="entry" entity="entity"></google-places>',
                ) ,
                3 => array (
                    'name' => 'is_listing_address_verified',
                    'label' => 'Address Verified?',
                    'type' => 'choice',
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
                4 => array (
                    'name' => 'is_online_assessment_test_completed',
                    'label' => 'Online Assessment Test Completed?',
                    'type' => 'choice',
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
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true
                ) ,
                1 => array (
                    'name' => 'username',
                    'label' => 'Service Provider',
                ) ,
                2 => array (
                    'name' => 'user_profile.listing_status.name',
                    'label' => 'Status'
                ) ,
                3 => array (
                    'name' => 'user_profile.listing_title',
                    'label' => 'Listing Title'
                ) ,
                4 => array (
                    'name' => 'user_profile.listing_description',
                    'label' => 'Description'
                ) ,
                5 => array (
                    'name' => 'user_profile.listing_address',
                    'label' => 'Listing Location'
                ) ,
                6 => array (
                    'name' => 'user_profile.user_view_count',
                    'label' => 'Views',
                    'type' => 'number'
                ) ,  
                7 => array (
                    'name' => 'user_profile.user_favorite_count',
                    'label' => 'Favorites',
                    'type' => 'number'
                ) ,                
                8 => array (
                    'name' => 'user_profile.listing_postal_code',
                    'label' => 'Listing Postal Code,'
                ) ,
                9 => array (
                    'name' => 'user_profile.completed_appointment_count',
                    'label' => 'Completed Bookings',
                    'type' => 'number'
                ) ,
                10 => array (
                    'name' => 'user_profile.photo_count',
                    'label' => 'Photos',
                    'type' => 'number'
                ),
                11 => array (
                    'name' => 'user_profile.response_rate',
                    'label' => 'Response Rate (%)',
                    'type' => 'number'
                ) ,
                12 => array (
                    'name' => 'user_profile.response_time',
                    'label' => 'Response Time',
                    'type' => 'number'
                ) ,
                13 => array (
                    'name' => 'user_profile.repeat_client_count',
                    'label' => 'Repeat Clients',
                    'type' => 'number'
                ) ,
                14 => array (
                    'name' => 'user_profile.is_listing_address_verified',
                    'label' => 'Address Verified?',
                    'type' => 'boolean'
                ) ,
                15 => array (
                    'name' => 'user_profile.is_online_assessment_test_completed',
                    'label' => 'Online Assessment Test Completed?',
                    'type' => 'boolean'
                ) ,
                16 => array (
                    'name' => 'created_at',
                    'label' => 'Registered On',
                ) ,
                17 => array (
                'name' => 'Other Information',
                'label' => 'Other Information',
                'targetEntity' => 'form_field_submissions',
                'targetReferenceField' => 'foreign_id',
                'targetFields' => array (
                    0 => 
                    array (
                        'name' => 'id',
                        'label' => 'ID',
                        'isDetailLink' => true,
                    ),
                    2 => 
                    array (
                        'name' => 'form_field.label',
                        'label' => 'Field',
                    ),
                    3 => 
                    array (
                        'name' => 'response',
                        'label' => 'Answer',
                        'map' => 
                        array (
                        0 => 'truncate',
                        ),          
                    ), 
                ) ,
                'map' => array (
                    0 => 'truncate',
                ) ,
                'type' => 'referenced_list',
                'permanentFilters' => array (
                    'class' => 'User'
                ),
                'perPage' => 10
                ) ,
            ) ,
        ) ,
    ) ,     
    'settings' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'modified',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
        array (
            'title' => 'Settings',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
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
                    'name' => 'setting_category_id',
                    'label' => 'Setting Category',
                    'targetEntity' => 'setting_categories',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => '',
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'label',
                    'label' => 'Name',
                    'editable' => false
                ) ,
                1 => array (
                    'name' => 'value',
                    'label' => 'Value',
                    'template' => '<input-type entry="entry" entity="entity"></input-type>',
                ) ,
            ) ,
            'actions' => array (
                'delete'
            )
        ) ,
    ) ,
    'pages' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array (
                    'name' => 'title',
                    'label' => 'Title',
                ) ,
                3 => array (
                    'name' => 'page_content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                ) ,
                4 => array (
                    'name' => 'language_id',
                    'label' => 'Language',
                    'targetEntity' => 'languages',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getLanguages'
                ) ,
                7 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Static Pages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                1 => 'filter',
                2 => '<a href="#/pages/add"> create </a>',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'page_content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'language_id',
                    'label' => 'Language',
                    'targetEntity' => 'languages',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                4 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Add Page',
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'page_content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'language_id',
                    'label' => 'Language',
                    'targetEntity' => 'languages',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'title' => 'Edit Page',
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'title',
                    'label' => 'Title',
                ) ,
                3 => array (
                    'name' => 'page_content',
                    'label' => 'Content',
                    'type' => 'wysiwyg',
                ) ,
                4 => array (
                    'name' => 'language_id',
                    'label' => 'Language',
                    'targetEntity' => 'languages',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getLanguages'
                ) ,
                7 => array (
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
    ) ,
    'email_templates' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'from_name',
                    'label' => 'From',
                ) ,
                4 => array (
                    'name' => 'reply_to',
                    'label' => 'Reply To',
                ) ,
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Email Templates',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'show',
                1 => 'edit'
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
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'from_name',
                    'label' => 'From',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'reply_to',
                    'label' => 'Reply To',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'body_content',
                    'label' => 'Body Content',
                    'type' => 'text',
                ) ,
            ) ,
            'title' => 'Add Email Template',
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'from_name',
                    'label' => 'From',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'reply_to',
                    'label' => 'Reply To',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'body_content',
                    'label' => 'Body Content',
                    'type' => 'wysiwyg',
                ) ,
            ) ,
            'title' => 'Edit Email Template',
            'actions' => array (
                0 => 'list'
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
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                4 => array (
                    'name' => 'from_name',
                    'label' => 'From',
                ) ,
                5 => array (
                    'name' => 'reply_to',
                    'label' => 'Reply To',
                ) ,
                6 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
                7 => array (
                    'name' => 'body_content',
                    'label' => 'Body Content',
                    'type' => 'wysiwyg',
                ) ,
                8 => array (
                    'name' => 'filename',
                    'label' => 'File Name',
                ) ,
                8 => array (
                    'name' => 'info',
                    'label' => 'Info',
                ) ,
                11 => array (
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
            'actions' => array (
                0 => 'list'
            ) ,
        ) ,
    ) ,
    'languages' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ) ,
                4 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ) ,
                5 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                6 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Languages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
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
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Add Language'
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
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Edit Language'
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ) ,
                4 => array (
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ) ,
                6 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                7 => array (
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
    ) ,
    'contacts' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                3 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                ) ,
                4 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ) ,
                5 => array (
                    'name' => 'email',
                    'label' => 'Email',
                ) ,
                6 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
                7 => array (
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
                8 => array (
                    'name' => 'telephone',
                    'label' => 'Telephone',
                ) ,
                9 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Contacts',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'show',
                1 => 'delete',
                2 => '<review-contact entry="entry" entity="entity" size="sm"></review-contact>',
                3 => '<download-file entry="entry" entity="entity" size="sm"></download-file>'
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
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                2 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                6 => array (
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                7 => array (
                    'name' => 'telephone',
                    'label' => 'Telephone',
                    'type' => 'number',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                2 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                6 => array (
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                7 => array (
                    'name' => 'telephone',
                    'label' => 'Telephone',
                    'type' => 'number',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                3 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                ) ,
                4 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ) ,
                5 => array (
                    'name' => 'email',
                    'label' => 'Email',
                ) ,
                6 => array (
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
                7 => array (
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
                8 => array (
                    'name' => 'telephone',
                    'label' => 'Telephone',
                ) ,
            ) ,
        ) ,
    ) ,
    'countries' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array(
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ) ,
                4 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ) ,
                5 => array(
                    'name' => 'phone',
                    'label' => 'Phone Code',
                ) ,                
                6 => array(
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                7 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Countries',
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
                1 => array(
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array(
                        0 => array(
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
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
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'phone',
                    'label' => 'Phone Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,                
                5 => array(
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array(
                        0 => array(
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Add Country'
        ) ,
        'editionview' => array(
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
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'phone',
                    'label' => 'Phone Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,                  
                5 => array(
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array(
                        0 => array(
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Edit Country'
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array(
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ) ,
                4 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ) ,
                5 => array(
                    'name' => 'phone',
                    'label' => 'Phone Code',
                ) ,                
                7 => array(
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                8 => array(
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
    ) ,
    'cities' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array (
                    'name' => 'country.name',
                    'label' => 'Country',
                ) ,
                3 => array (
                    'name' => 'state.name',
                    'label' => 'State',
                ) ,
                4 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                5 => array (
                    'name' => 'latitude',
                    'label' => 'Latitude',
                ) ,
                6 => array (
                    'name' => 'longitude',
                    'label' => 'Longitude',
                ) ,
                7 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                8 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Cities',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array (
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                3 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
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
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'type' => 'string',
                ) ,
                4 => array (
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'type' => 'string',
                ) ,
                5 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Add Citiy'
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'type' => 'string',
                ) ,
                4 => array (
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'type' => 'string',
                ) ,
                5 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Edit Citiy'
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getCountries'
                ) ,
                3 => array (
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getStates'
                ) ,
                4 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                5 => array (
                    'name' => 'latitude',
                    'label' => 'Latitude',
                ) ,
                6 => array (
                    'name' => 'longitude',
                    'label' => 'Longitude',
                ) ,
                7 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                8 => array (
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
    ) ,
    'states' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array (
                    'name' => 'country.name',
                    'label' => 'Country',
                ) ,
                3 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                4 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'States',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
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
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Add State'
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Edit State'
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getCountries'
                ) ,
                3 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                4 => array (
                    'name' => 'is_active',
                    'type' => 'boolean',
                    'label' => 'Active?',
                ) ,
                5 => array (
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
    ) ,
    'roles' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Roles',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'show',
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
                1 => 'filter',
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
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'updated_at',
                    'label' => 'Modified',
                ) ,
            ) ,
            'actions' => array (
                0 => 'list'
            ) ,
        ) ,
    ) ,
    'settings' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created',
                    'label' => 'Created',
                ) ,
                2 => array (
                    'name' => 'modified',
                    'label' => 'Modified',
                ) ,
            ) ,
        ) ,
        array (
            'title' => 'Settings',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
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
                    'name' => 'setting_category_id',
                    'label' => 'Setting Category',
                    'targetEntity' => 'setting_categories',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => '',
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'label',
                    'label' => 'Name',
                    'editable' => false
                ) ,
                2 => array (
                    'name' => 'value',
                    'label' => 'Value',
                    'template' => '<input-type entry="entry" entity="entity"></input-type>',
                ) ,
                1 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'editable' => false
                ) ,
            ) ,
            'actions' => array (
                'list'
            )
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
                    'name' => 'value',
                    'label' => 'Value',
                    'template' => '',
                ) ,
                2 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
    ) ,
    'setting_categories' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                1 => array (
                    'name' => 'description',
                    'label' => 'Description',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                ) ,
            ) ,
            'title' => 'Site Settings',
            'perPage' => '25',
            'sortField' => '',
            'sortDir' => 'ASC',
            'infinitePagination' => false,
            'listActions' => array (
                0 => '<ma-show-button entry="entry" entity="entity" size="sm" label="Config" ></ma-show-button>',
            ) ,
            'filters' => array (
                0 => array (
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="fa fa-search text-primary"></i></span></div>',
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                'setting_category_action_tpl' => '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button><ma-export-to-csv-button entry="entry" entity="entity" size="sm" datastore="::datastore"></ma-export-to-csv-button>',
                'settings_category_edit_template' => '<ma-list-button entry="entry" entity="entity" size="sm"></ma-list-button>',
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
                    'name' => 'description',
                    'label' => 'Description',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string'
                ) ,
                1 => array (
                    'name' => 'description',
                    'label' => 'Description'
                ) ,
                2 => array (
                    'name' => 'setting_category_id',
                    'label' => 'Related Settings',
                    'targetEntity' => 'settings',
                    'targetReferenceField' => 'setting_category_id',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'label',
                            'label' => 'Name',
                        ) ,
                        1 => array (
                            'name' => 'value',
                            'label' => 'Value',
                        ) ,
                    ) ,
                    'listActions' => array (
                        0 => 'edit',
                    ) ,
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'referenced_list',
                ) ,
                3 => array (
                    'name' => 'icon',
                    'label' => '',
                    'template' => '<add-sync entry="entry" entity="entity" size="sm" label="Synchronize" ></add-sync>'
                ) ,
                4 => array (
                    'name' => 'icon',
                    'label' => '',
                    'type' => 'template',
                    'template' => '<mooc-sync entry="entry" entity="entity" size="sm" label="Synchronize" ></mooc-sync>'
                ) ,
            ) ,
            'actions' => array (
                'list'
            )
        ) ,
    ) ,
    'listing_views' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array (
                    // 'name' => 'user.email',
                    'label' => 'User',
                    'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
                ) ,
                3 => array (
                    // 'name' => 'other_user.email',
                    'label' => 'Listing Owner',
                    'template' => '<display-provider-messages entry="entry" entity="entity" size="sm" label="Change Name" ></display-provider-messages>'
                ) ,
                4 => array (
                    'name' => 'other_user.user_profile.listing_title',
                    'label' => 'Listing Title'
                ) ,
                5 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Listing Views',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'delete'
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
                    'name' => 'other_user_id',
                    'label' => 'Listing Owner',
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
                1 => 'filter',
            ) ,
        ) ,
    ) ,
    'services' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
                2 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array (
                    'name' => 'booking_option_id',
                    'label' => 'Booking Option',
                    'template' => '<p ng-if="entry.values.booking_option_id === 1" height="42" width="42">TimeSlot</p><p ng-if="entry.values.booking_option_id === 2" height="42" width="42">SingleDate</p><p ng-if="entry.values.booking_option_id === 3" height="42" width="42">MultipleDate</p><p ng-if="entry.values.booking_option_id === 4" height="42" width="42">Recurring</p><p ng-if="entry.values.booking_option_id === 5" height="42" width="42">Hourly Rate And Booking</p>',
                ) ,
                4 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                ) ,                 
                5 => array (
                    'name' => 'is_enable_multiple_booking',
                    'label' => 'Enable Multiple Booking?',
                    'type' => 'boolean'
                ) ,
                6 => array (
                    'name' => 'is_allow_to_get_number_of_items',
                    'label' => 'Allow choose number of Items',
                    'type' => 'boolean'
                ) ,  
                7 => array (
                    'name' => 'label_for_number_of_item',
                    'label' => 'Label for Number of Item'
                ) ,  
                8 => array (
                    'name' => 'maximum_number_to_allow',
                    'label' => 'Maximum Number To Allow'
                ) ,  
                9 => array (
                    'name' => 'is_multiply_booking_amount_when_choosing_number_of_items',
                    'label' => 'Multiply Booking Amount When Choosing Number Of Items?',
                    'type' => 'boolean'
                ) , 
                10 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class'
                ) , 
                11 => array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'boolean'
                ) ,                                                 
                12 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ) ,
                13 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
                14 => array (
                    'name' => 'is_need_user_location',
                    'label' => 'Required service location when booking?',
                    'type' => 'boolean'
                ) ,                                 
            ) ,
            'title' => 'Services',
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
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array (
                    'name' => 'booking_option_id',
                    'label' => 'Booking Option',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'TimeSlot',
                            'value' => 1,
                        ) ,
                        1 => array (
                            'label' => 'SingleDate',
                            'value' => 2,
                        ) ,
                        2 => array (
                            'label' => 'MultipleDate',
                            'value' => 3,
                        ) ,
                        3 => array (
                            'label' => 'Recurring',
                            'value' => 4,
                        ) ,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'parent_id',
                    'label' => 'Parent',
                    'targetEntity' => 'services',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                4 => array (
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                5 => array (
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
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'booking_option_id',
                    'label' => 'Booking Option',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'TimeSlot',
                            'value' => 1,
                        ) ,
                        1 => array (
                            'label' => 'SingleDate',
                            'value' => 2,
                        ) ,
                        2 => array (
                            'label' => 'MultipleDate',
                            'value' => 3,
                        ) ,
                        3 => array (
                            'label' => 'Hourly Rate And Booking',
                            'value' => 5,
                        ) ,
                    ) ,
                    'validation' => array (
                        'required' => false,
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
                10 => array (
                    'name' => 'is_allow_to_get_number_of_items',
                    'label' => 'Allow choose number of Items (like number of dogs/child)?',
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
                11 => array (
                    'name' => 'label_for_number_of_item',
                    'label' => 'Label for Number of Item (e.g.., if we give \'dog\', then system will display in booking form like \'1 dog\', \'2 dogs\' like that)',
                    'type' => 'string'
                ) , 
                12 => array (
                    'name' => 'maximum_number_to_allow',
                    'label' => 'Maximum Number To Allow (e.g.., If we enter 10, \'1 dog\' to \'10 dogs\' will display in booking form)',
                    'type' => 'number',
                ) , 
                13 => array (
                    'name' => 'is_multiply_booking_amount_when_choosing_number_of_items',
                    'label' => 'Multiply Booking Amount When Choosing Number Of Items (dogs/child)?',
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
                6 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
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
               9 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) , 
                13 => array (
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
                14 => array (
                    'name' => 'is_need_user_location',
                    'label' => 'Required service location when booking?',
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
                19 => array (
                    'name' => 'form_field_groups',
                    'label' => 'Form Fields',
                    'type' => 'embedded_list',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'name',
                            'label' => 'Group Name',
                            'type' => 'string',
                            'validation' => 
                            array (
                                'required' => true,
                            ),
                        ) ,
                        1 => array (
                            'name' => 'form_fields',
                            'label' => '',
                            'type' => 'embedded_list',
                            'targetFields' => array (
                                0 => array (
                                    'name' => 'name',
                                    'label' => 'Field Name',
                                    'type' => 'string',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
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
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    ),
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                ) ,
                                3 => array (
                                    'name' => 'label',
                                    'label' => 'Label',
                                    'type' => 'string',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
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
                                    'label' => 'Display Order',
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                ) ,
                                8 => array (
                                    'name' => 'is_enable_this_field_in_search_form',
                                    'type' => 'choice',
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
                                    ),
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),
                                ) ,                                
                                9 => array (
                                    'name' => 'is_active',
                                    'type' => 'choice',
                                    'label' => 'Active?',
                                    'choices' => array (
                                        0 => array (
                                            'label' => 'Yes',
                                            'value' => true,
                                        ) ,
                                        1 => array (
                                            'label' => 'No',
                                            'value' => false,
                                        ) ,
                                    ),
                                    'validation' => 
                                    array (
                                        'required' => true,
                                    ),                                    
                                )
                            )
                        ),
                        2 => array (
                            'name' => 'is_belongs_to_service_provider',
                            'type' => 'choice',
                            'label' => 'Belongs To Service Provider?',
                            'choices' => array (
                                0 => array (
                                    'label' => 'Yes',
                                    'value' => true,
                                ) ,
                                1 => array (
                                    'label' => 'No',
                                    'value' => false,
                                ) ,
                            ),
                            'validation' => 
                            array (
                                'required' => true,
                            ), 
                        )                        
                    )
                )
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string'
                ) ,
                2 => array (
                    'name' => 'booking_option_id',
                    'label' => 'Booking Option',
                    'type' => 'choice',
                    'choices' => array (
                        0 => array (
                            'label' => 'TimeSlot',
                            'value' => 1,
                        ) ,
                        1 => array (
                            'label' => 'SingleDate',
                            'value' => 2,
                        ) ,
                        2 => array (
                            'label' => 'MultipleDate',
                            'value' => 3,
                        ) ,
                        3 => array (
                            'label' => 'Hourly Rate And Booking',
                            'value' => 5,
                        ) ,
                    ) ,
                    'validation' => array (
                        'required' => false,
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
                10 => array (
                    'name' => 'is_allow_to_get_number_of_items',
                    'label' => 'Allow choose number of Items (like number of dogs/child)?',
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
                11 => array (
                    'name' => 'label_for_number_of_item',
                    'label' => 'Label for Number of Item (e.g.., if we give \'dog\', then system will display in booking form like \'1 dog\', \'2 dogs\' like that)',
                    'type' => 'string'
                ) , 
                12 => array (
                    'name' => 'maximum_number_to_allow',
                    'label' => 'Maximum Number To Allow (e.g.., If we enter 10, \'1 dog\' to \'10 dogs\' will display in booking form)',
                    'type' => 'number',
                ) , 
                13 => array (
                    'name' => 'is_multiply_booking_amount_when_choosing_number_of_items',
                    'label' => 'Multiply Booking Amount When Choosing Number Of Items (dogs/child)?',
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
               6 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,                                
                8 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'choices' => array (
                        0 => array (
                            'label' => 'Active',
                            'value' => true,
                        ) ,
                        1 => array (
                            'label' => 'Inactive',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                9 => array (
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
                14 => array (
                    'name' => 'is_need_user_location',
                    'label' => 'Required service location when booking?',
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
                19 => array (
                    'name' => 'form_field_groups',
                    'label' => 'Form Fields',
                    'type' => 'embedded_list',
                    'targetFields' => array (
                        0 => array (
                            'name' => 'name',
                            'label' => 'Group Name',
                            'type' => 'string',
                            'validation' => 
                            array (
                                'required' => true,
                            ),
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
                                    'required' => true,
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
        'showview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getCategories'
                ) ,
                2 => array (
                    'name' => 'booking_option_id',
                    'label' => 'Booking Option',
                    'template' => '<p ng-if="entry.values.booking_option_id === 1" height="42" width="42">TimeSlot</p><p ng-if="entry.values.booking_option_id === 2" height="42" width="42">SingleDate</p><p ng-if="entry.values.booking_option_id === 3" height="42" width="42">MultipleDate</p><p ng-if="entry.values.booking_option_id === 4" height="42" width="42">Recurring</p>',
                ) ,
                4 => array (
                    'name' => 'is_enable_multiple_booking',
                    'label' => 'Enable Multiple Booking?',
                    'type' => 'boolean'
                ) ,
                5 => array (
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                11 => array (
                    'name' => 'is_allow_to_get_number_of_items',
                    'label' => 'Allow choose number of Items (like number of dogs/child)?',
                    'type' => 'boolean'
                ) ,  
                12 => array (
                    'name' => 'label_for_number_of_item',
                    'label' => 'Label for Number of Item (e.g.., if we give \'dog\', then system will display in booking form like \'1 dog\', \'2 dogs\' like that)'
                ) ,  
                13 => array (
                    'name' => 'maximum_number_to_allow',
                    'label' => 'Maximum Number To Allow (e.g.., If we enter 10, \'1 dog\' to \'10 dogs\' will display in booking form)'
                ) ,  
                14 => array (
                    'name' => 'is_multiply_booking_amount_when_choosing_number_of_items',
                    'label' => 'Multiply Booking Amount When Choosing Number Of Items (dogs/child)?',
                    'type' => 'boolean'
                ) ,                 
                7 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean'
                ),
                19 => array (
                    'name' => 'icon_class',
                    'label' => 'Icon Class'
                ) , 
                20 => array (
                    'name' => 'is_featured',
                    'label' => 'Featured?',
                    'type' => 'boolean'
                ) ,                                
                29 => array (
                'name' => 'Booking Form Additional Information',
                'label' => 'Booking Form Additional Information',
                'targetEntity' => 'form_field_submissions',
                'targetReferenceField' => 'foreign_id',
                'targetFields' => array (
                    0 => 
                    array (
                        'name' => 'id',
                        'label' => 'ID',
                        'isDetailLink' => true,
                    ),
                    1 => 
                    array (
                    'name' => 'created_at',
                    'label' => 'Created',
                    ),
                    2 => 
                    array (
                        'name' => 'form_field.name',
                        'label' => 'Form Field',
                    ),
                    3 => 
                    array (
                        'name' => 'response',
                        'label' => 'Response',
                        'map' => 
                        array (
                        0 => 'truncate',
                        ),          
                    ), 
                    4 => 
                    array (
                        'name' => 'class',
                        'label' => 'Class',
                    ),
                    5 => 
                    array (
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
                    'class' => 'Appointment'
                ),
                'perPage' => 10
                ) , 
            ) ,
        ) ,
    ) ,
    'user_logins' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    // 'name' => 'user.username',
                    'label' => 'User',
                    'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
                ) ,
                3 => array (
                    'name' => 'user_agent',
                    'label' => 'User Agent',
                ) ,
                4 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'User Logins',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
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
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
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
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'user_agent',
                    'label' => 'User Agent',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'user_agent',
                    'label' => 'User Agent',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
    ) ,
    'appointment_statuses' => array (
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
                2 => array (
                    'name' => 'appointment_count',
                    'label' => 'Appointments',
                    'type' => 'number'
                ) ,
                3 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Appointment Statuses',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array () ,
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
                1 => 'filter',
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
                    'name' => 'appointment_count',
                    'label' => 'Appointments',
                    'type' => 'number'
                ) ,
            ) ,
        ) ,
    ) ,
    'appointment_settings' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                2 => array (
                    'name' => 'is_today_first_day',
                    'label' => 'Today First Days?',
                    'type' => 'boolean'
                ) ,
                3 => array (
                    'name' => 'calendar_slot_id',
                    'label' => 'Calendar Slot',
                ) ,
                4 => array (
                    'name' => 'is_two_session',
                    'label' => 'Two Sessions?',
                    'type' => 'boolean'
                ) ,
                5 => array (
                    'name' => 'practice_open',
                    'label' => 'Practice Open',
                ) ,
                6 => array (
                    'name' => 'lunch_at',
                    'label' => 'Lunch At',
                ) ,
                7 => array (
                    'name' => 'resume_at',
                    'label' => 'Resume At',
                ) ,
                8 => array (
                    'name' => 'practice_close',
                    'label' => 'Practice Close',
                ) ,
                9 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'template' => '<p ng-if="entry.values.type === 0" height="42" width="42">Same for All</p><p ng-if="entry.values.type === 1" height="42" width="42">Individual Days</p>',
                ) ,
                10 => array (
                    'name' => 'is_sunday_open',
                    'label' => 'Sunday Opens?',
                    'type' => 'boolean'
                ) ,
                11 => array (
                    'name' => 'sunday_two_session',
                    'label' => 'Sunday Two Session',
                    'type' => 'boolean'
                ) ,
                12 => array (
                    'name' => 'sunday_practice_open',
                    'label' => 'Sunday Practice Open',
                ) ,
                13 => array (
                    'name' => 'sunday_lunch_at',
                    'label' => 'Sunday Lunch At',
                ) ,
                14 => array (
                    'name' => 'sunday_resume_at',
                    'label' => 'Sunday Resume At',
                ) ,
                15 => array (
                    'name' => 'sunday_practice_close',
                    'label' => 'Sunday Practice Close',
                ) ,
                16 => array (
                    'name' => 'is_monday_open',
                    'label' => 'Monday Opens?',
                    'type' => 'boolean'
                ) ,
                17 => array (
                    'name' => 'monday_two_session',
                    'label' => 'Monday Two Session',
                    'type' => 'boolean'
                ) ,
                18 => array (
                    'name' => 'monday_practice_open',
                    'label' => 'Monday Practice Open',
                ) ,
                19 => array (
                    'name' => 'monday_lunch_at',
                    'label' => 'Monday Lunch At',
                ) ,
                20 => array (
                    'name' => 'monday_resume_at',
                    'label' => 'Monday Resume At',
                ) ,
                21 => array (
                    'name' => 'monday_practice_close',
                    'label' => 'Monday Practice Close',
                ) ,
                22 => array (
                    'name' => 'is_tuesday_open',
                    'label' => 'Tuesday Opens?',
                    'type' => 'boolean'
                ) ,
                23 => array (
                    'name' => 'tuesday_two_session',
                    'label' => 'Tuesday Two Session',
                    'type' => 'boolean'
                ) ,
                24 => array (
                    'name' => 'tuesday_practice_open',
                    'label' => 'Tuesday Practice Open',
                ) ,
                25 => array (
                    'name' => 'tuesday_lunch_at',
                    'label' => 'Tuesday Lunch At',
                ) ,
                26 => array (
                    'name' => 'tuesday_resume_at',
                    'label' => 'Tuesday Resume At',
                ) ,
                27 => array (
                    'name' => 'tuesday_practice_close',
                    'label' => 'Tuesday Practice Close',
                ) ,
                28 => array (
                    'name' => 'is_wednesday_open',
                    'label' => 'Wednesday Opens?',
                    'type' => 'boolean'
                ) ,
                29 => array (
                    'name' => 'wednesday_two_session',
                    'label' => 'Wednesday Two Session',
                    'type' => 'boolean'
                ) ,
                30 => array (
                    'name' => 'wednesday_practice_open',
                    'label' => 'Wednesday Practice Open',
                ) ,
                31 => array (
                    'name' => 'wednesday_lunch_at',
                    'label' => 'Wednesday Lunch At',
                ) ,
                32 => array (
                    'name' => 'wednesday_resume_at',
                    'label' => 'Wednesday Resume At',
                ) ,
                33 => array (
                    'name' => 'wednesday_practice_close',
                    'label' => 'Wednesday Practice Close',
                ) ,
                34 => array (
                    'name' => 'is_thursday_open',
                    'label' => 'Thrusday Opens?',
                    'type' => 'boolean'
                ) ,
                35 => array (
                    'name' => 'thrusday_two_session',
                    'label' => 'Thrusday Two Session',
                    'type' => 'boolean'
                ) ,
                36 => array (
                    'name' => 'thursday_practice_open',
                    'label' => 'Thrusday Practice Open',
                ) ,
                37 => array (
                    'name' => 'thrusday_lunch_at',
                    'label' => 'Thrusday Lunch At',
                ) ,
                38 => array (
                    'name' => 'thrusday_resume_at',
                    'label' => 'Thrusday Resume At',
                ) ,
                39 => array (
                    'name' => 'thursday_practice_close',
                    'label' => 'Thrusday Practice Close',
                ) ,
                40 => array (
                    'name' => 'is_friday_open',
                    'label' => 'Friday Opens?',
                    'type' => 'boolean'
                ) ,
                41 => array (
                    'name' => 'friday_two_session',
                    'label' => 'Friday Two Session',
                    'type' => 'boolean'
                ) ,
                42 => array (
                    'name' => 'friday_practice_open',
                    'label' => 'Friday Practice Open',
                ) ,
                43 => array (
                    'name' => 'friday_lunch_at',
                    'label' => 'Friday Lunch At',
                ) ,
                44 => array (
                    'name' => 'friday_resume_at',
                    'label' => 'Friday Resume At',
                ) ,
                45 => array (
                    'name' => 'friday_practice_close',
                    'label' => 'Friday Practice Close',
                ) ,
                46 => array (
                    'name' => 'is_saturday_open',
                    'label' => 'Saturday Opens?',
                    'type' => 'boolean'
                ) ,
                47 => array (
                    'name' => 'saturday_two_session',
                    'label' => 'Saturday Two Session',
                    'type' => 'boolean'
                ) ,
                48 => array (
                    'name' => 'saturday_practice_open',
                    'label' => 'Saturday Practice Open',
                ) ,
                49 => array (
                    'name' => 'saturday_lunch_at',
                    'label' => 'Saturday Lunch At',
                ) ,
                50 => array (
                    'name' => 'saturday_resume_at',
                    'label' => 'Saturday Resume At',
                ) ,
                51 => array (
                    'name' => 'saturday_practice_close',
                    'label' => 'Saturday Practice Close',
                ) ,
                52 => array (
                    'name' => 'is_active',
                    'label' => 'Actives?',
                    'type' => 'boolean'
                ) ,
                53 => array (
                    'name' => 'is_suspend',
                    'label' => 'Suspends?',
                    'type' => 'boolean'
                ) ,
                54 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Appointment Settings',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                //0 => 'edit',
                0 => 'show',
                //2 => 'delete',
                
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
                    'targetField' => 'username',
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
                1 => 'filter',
            ) ,
        ) ,
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
                    ) ,
                ) ,
                1 => array (
                    'name' => 'is_today_first_day',
                    'label' => 'Today First Days?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'calendar_slot_id',
                    'label' => 'Calendar Slot',
                    'targetEntity' => 'calendar_slots',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'is_two_session',
                    'label' => 'Two Sessions?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'practice_open',
                    'label' => 'Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'lunch_at',
                    'label' => 'Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                6 => array (
                    'name' => 'resume_at',
                    'label' => 'Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                7 => array (
                    'name' => 'practice_close',
                    'label' => 'Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                9 => array (
                    'name' => 'is_sunday_open',
                    'label' => 'Sunday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                10 => array (
                    'name' => 'sunday_two_session',
                    'label' => 'Sunday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                11 => array (
                    'name' => 'sunday_practice_open',
                    'label' => 'Sunday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                12 => array (
                    'name' => 'sunday_lunch_at',
                    'label' => 'Sunday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                13 => array (
                    'name' => 'sunday_resume_at',
                    'label' => 'Sunday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                14 => array (
                    'name' => 'sunday_practice_close',
                    'label' => 'Sunday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                15 => array (
                    'name' => 'is_monday_open',
                    'label' => 'Monday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                16 => array (
                    'name' => 'monday_two_session',
                    'label' => 'Monday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                17 => array (
                    'name' => 'monday_practice_open',
                    'label' => 'Monday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                18 => array (
                    'name' => 'monday_lunch_at',
                    'label' => 'Monday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                19 => array (
                    'name' => 'monday_resume_at',
                    'label' => 'Monday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                20 => array (
                    'name' => 'monday_practice_close',
                    'label' => 'Monday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                21 => array (
                    'name' => 'is_tuesday_open',
                    'label' => 'Tuesday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                22 => array (
                    'name' => 'tuesday_two_session',
                    'label' => 'Tuesday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                23 => array (
                    'name' => 'tuesday_practice_open',
                    'label' => 'Tuesday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                24 => array (
                    'name' => 'tuesday_lunch_at',
                    'label' => 'Tuesday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                25 => array (
                    'name' => 'tuesday_resume_at',
                    'label' => 'Tuesday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                26 => array (
                    'name' => 'tuesday_practice_close',
                    'label' => 'Tuesday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                27 => array (
                    'name' => 'is_wednesday_open',
                    'label' => 'Wednesday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                28 => array (
                    'name' => 'wednesday_two_session',
                    'label' => 'Wednesday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                29 => array (
                    'name' => 'wednesday_practice_open',
                    'label' => 'Wednesday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                30 => array (
                    'name' => 'wednesday_lunch_at',
                    'label' => 'Wednesday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                31 => array (
                    'name' => 'wednesday_resume_at',
                    'label' => 'Wednesday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                32 => array (
                    'name' => 'wednesday_practice_close',
                    'label' => 'Wednesday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                33 => array (
                    'name' => 'is_thursday_open',
                    'label' => 'Thrusday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                34 => array (
                    'name' => 'thrusday_two_session',
                    'label' => 'Thrusday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                35 => array (
                    'name' => 'thursday_practice_open',
                    'label' => 'Thrusday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                36 => array (
                    'name' => 'thrusday_lunch_at',
                    'label' => 'Thrusday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                37 => array (
                    'name' => 'thrusday_resume_at',
                    'label' => 'Thrusday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                38 => array (
                    'name' => 'thursday_practice_close',
                    'label' => 'Thrusday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                39 => array (
                    'name' => 'is_friday_open',
                    'label' => 'Friday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                40 => array (
                    'name' => 'friday_two_session',
                    'label' => 'Friday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                41 => array (
                    'name' => 'friday_practice_open',
                    'label' => 'Friday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                42 => array (
                    'name' => 'friday_lunch_at',
                    'label' => 'Friday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                43 => array (
                    'name' => 'friday_resume_at',
                    'label' => 'Friday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                44 => array (
                    'name' => 'friday_practice_close',
                    'label' => 'Friday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                45 => array (
                    'name' => 'is_saturday_open',
                    'label' => 'Saturday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                46 => array (
                    'name' => 'saturday_two_session',
                    'label' => 'Saturday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                47 => array (
                    'name' => 'saturday_practice_open',
                    'label' => 'Saturday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                48 => array (
                    'name' => 'saturday_lunch_at',
                    'label' => 'Saturday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                49 => array (
                    'name' => 'saturday_resume_at',
                    'label' => 'Saturday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                50 => array (
                    'name' => 'saturday_practice_close',
                    'label' => 'Saturday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                51 => array (
                    'name' => 'is_active',
                    'label' => 'Actives?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                52 => array (
                    'name' => 'is_suspend',
                    'label' => 'Suspends?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'is_today_first_day',
                    'label' => 'Today First Days?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'calendar_slot_id',
                    'label' => 'Calendar Slot',
                    'targetEntity' => 'calendar_slots',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'is_two_session',
                    'label' => 'Two Sessions?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'practice_open',
                    'label' => 'Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'lunch_at',
                    'label' => 'Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                6 => array (
                    'name' => 'resume_at',
                    'label' => 'Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                7 => array (
                    'name' => 'practice_close',
                    'label' => 'Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                9 => array (
                    'name' => 'is_sunday_open',
                    'label' => 'Sunday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                10 => array (
                    'name' => 'sunday_two_session',
                    'label' => 'Sunday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                11 => array (
                    'name' => 'sunday_practice_open',
                    'label' => 'Sunday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                12 => array (
                    'name' => 'sunday_lunch_at',
                    'label' => 'Sunday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                13 => array (
                    'name' => 'sunday_resume_at',
                    'label' => 'Sunday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                14 => array (
                    'name' => 'sunday_practice_close',
                    'label' => 'Sunday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                15 => array (
                    'name' => 'is_monday_open',
                    'label' => 'Monday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                16 => array (
                    'name' => 'monday_two_session',
                    'label' => 'Monday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                17 => array (
                    'name' => 'monday_practice_open',
                    'label' => 'Monday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                18 => array (
                    'name' => 'monday_lunch_at',
                    'label' => 'Monday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                19 => array (
                    'name' => 'monday_resume_at',
                    'label' => 'Monday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                20 => array (
                    'name' => 'monday_practice_close',
                    'label' => 'Monday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                21 => array (
                    'name' => 'is_tuesday_open',
                    'label' => 'Tuesday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                22 => array (
                    'name' => 'tuesday_two_session',
                    'label' => 'Tuesday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                23 => array (
                    'name' => 'tuesday_practice_open',
                    'label' => 'Tuesday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                24 => array (
                    'name' => 'tuesday_lunch_at',
                    'label' => 'Tuesday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                25 => array (
                    'name' => 'tuesday_resume_at',
                    'label' => 'Tuesday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                26 => array (
                    'name' => 'tuesday_practice_close',
                    'label' => 'Tuesday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                27 => array (
                    'name' => 'is_wednesday_open',
                    'label' => 'Wednesday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                28 => array (
                    'name' => 'wednesday_two_session',
                    'label' => 'Wednesday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                29 => array (
                    'name' => 'wednesday_practice_open',
                    'label' => 'Wednesday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                30 => array (
                    'name' => 'wednesday_lunch_at',
                    'label' => 'Wednesday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                31 => array (
                    'name' => 'wednesday_resume_at',
                    'label' => 'Wednesday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                32 => array (
                    'name' => 'wednesday_practice_close',
                    'label' => 'Wednesday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                33 => array (
                    'name' => 'is_thursday_open',
                    'label' => 'Thrusday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                34 => array (
                    'name' => 'thrusday_two_session',
                    'label' => 'Thrusday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                35 => array (
                    'name' => 'thursday_practice_open',
                    'label' => 'Thrusday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                36 => array (
                    'name' => 'thrusday_lunch_at',
                    'label' => 'Thrusday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                37 => array (
                    'name' => 'thrusday_resume_at',
                    'label' => 'Thrusday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                38 => array (
                    'name' => 'thursday_practice_close',
                    'label' => 'Thrusday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                39 => array (
                    'name' => 'is_friday_open',
                    'label' => 'Friday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                40 => array (
                    'name' => 'friday_two_session',
                    'label' => 'Friday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                41 => array (
                    'name' => 'friday_practice_open',
                    'label' => 'Friday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                42 => array (
                    'name' => 'friday_lunch_at',
                    'label' => 'Friday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                43 => array (
                    'name' => 'friday_resume_at',
                    'label' => 'Friday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                44 => array (
                    'name' => 'friday_practice_close',
                    'label' => 'Friday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                45 => array (
                    'name' => 'is_saturday_open',
                    'label' => 'Saturday Opens?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                46 => array (
                    'name' => 'saturday_two_session',
                    'label' => 'Saturday Two Session',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                47 => array (
                    'name' => 'saturday_practice_open',
                    'label' => 'Saturday Practice Open',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                48 => array (
                    'name' => 'saturday_lunch_at',
                    'label' => 'Saturday Lunch At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                49 => array (
                    'name' => 'saturday_resume_at',
                    'label' => 'Saturday Resume At',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                50 => array (
                    'name' => 'saturday_practice_close',
                    'label' => 'Saturday Practice Close',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                51 => array (
                    'name' => 'is_active',
                    'label' => 'Actives?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                52 => array (
                    'name' => 'is_suspend',
                    'label' => 'Suspends?',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
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
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'singleApiCall' => 'getUsers'
                ) ,
                2 => array (
                    'name' => 'is_today_first_day',
                    'label' => 'Today First Days?',
                    'type' => 'boolean'
                ) ,
                3 => array (
                    'name' => 'calendar_slot_id',
                    'label' => 'Calendar Slot',
                ) ,
                4 => array (
                    'name' => 'is_two_session',
                    'label' => 'Two Sessions?',
                    'type' => 'boolean'
                ) ,
                5 => array (
                    'name' => 'practice_open',
                    'label' => 'Practice Open',
                ) ,
                6 => array (
                    'name' => 'lunch_at',
                    'label' => 'Lunch At',
                ) ,
                7 => array (
                    'name' => 'resume_at',
                    'label' => 'Resume At',
                ) ,
                8 => array (
                    'name' => 'practice_close',
                    'label' => 'Practice Close',
                ) ,
                9 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'template' => '<p ng-if="entry.values.type === 0" height="42" width="42">Same for All</p><p ng-if="entry.values.type === 1" height="42" width="42">Individual Days</p>',
                ) ,
                10 => array (
                    'name' => 'is_sunday_open',
                    'label' => 'Sunday Opens?',
                    'type' => 'boolean'
                ) ,
                11 => array (
                    'name' => 'sunday_two_session',
                    'label' => 'Sunday Two Session',
                    'type' => 'boolean'
                ) ,
                12 => array (
                    'name' => 'sunday_practice_open',
                    'label' => 'Sunday Practice Open',
                ) ,
                13 => array (
                    'name' => 'sunday_lunch_at',
                    'label' => 'Sunday Lunch At',
                ) ,
                14 => array (
                    'name' => 'sunday_resume_at',
                    'label' => 'Sunday Resume At',
                ) ,
                15 => array (
                    'name' => 'sunday_practice_close',
                    'label' => 'Sunday Practice Close',
                ) ,
                16 => array (
                    'name' => 'is_monday_open',
                    'label' => 'Monday Opens?',
                    'type' => 'boolean'
                ) ,
                17 => array (
                    'name' => 'monday_two_session',
                    'label' => 'Monday Two Session',
                    'type' => 'boolean'
                ) ,
                18 => array (
                    'name' => 'monday_practice_open',
                    'label' => 'Monday Practice Open',
                ) ,
                19 => array (
                    'name' => 'monday_lunch_at',
                    'label' => 'Monday Lunch At',
                ) ,
                20 => array (
                    'name' => 'monday_resume_at',
                    'label' => 'Monday Resume At',
                ) ,
                21 => array (
                    'name' => 'monday_practice_close',
                    'label' => 'Monday Practice Close',
                ) ,
                22 => array (
                    'name' => 'is_tuesday_open',
                    'label' => 'Tuesday Opens?',
                    'type' => 'boolean'
                ) ,
                23 => array (
                    'name' => 'tuesday_two_session',
                    'label' => 'Tuesday Two Session',
                    'type' => 'boolean'
                ) ,
                24 => array (
                    'name' => 'tuesday_practice_open',
                    'label' => 'Tuesday Practice Open',
                ) ,
                25 => array (
                    'name' => 'tuesday_lunch_at',
                    'label' => 'Tuesday Lunch At',
                ) ,
                26 => array (
                    'name' => 'tuesday_resume_at',
                    'label' => 'Tuesday Resume At',
                ) ,
                27 => array (
                    'name' => 'tuesday_practice_close',
                    'label' => 'Tuesday Practice Close',
                ) ,
                28 => array (
                    'name' => 'is_wednesday_open',
                    'label' => 'Wednesday Opens?',
                    'type' => 'boolean'
                ) ,
                29 => array (
                    'name' => 'wednesday_two_session',
                    'label' => 'Wednesday Two Session',
                    'type' => 'boolean'
                ) ,
                30 => array (
                    'name' => 'wednesday_practice_open',
                    'label' => 'Wednesday Practice Open',
                ) ,
                31 => array (
                    'name' => 'wednesday_lunch_at',
                    'label' => 'Wednesday Lunch At',
                ) ,
                32 => array (
                    'name' => 'wednesday_resume_at',
                    'label' => 'Wednesday Resume At',
                ) ,
                33 => array (
                    'name' => 'wednesday_practice_close',
                    'label' => 'Wednesday Practice Close',
                ) ,
                34 => array (
                    'name' => 'is_thursday_open',
                    'label' => 'Thrusday Opens?',
                    'type' => 'boolean'
                ) ,
                35 => array (
                    'name' => 'thrusday_two_session',
                    'label' => 'Thrusday Two Session',
                    'type' => 'boolean'
                ) ,
                36 => array (
                    'name' => 'thursday_practice_open',
                    'label' => 'Thrusday Practice Open',
                ) ,
                37 => array (
                    'name' => 'thrusday_lunch_at',
                    'label' => 'Thrusday Lunch At',
                ) ,
                38 => array (
                    'name' => 'thrusday_resume_at',
                    'label' => 'Thrusday Resume At',
                ) ,
                39 => array (
                    'name' => 'thursday_practice_close',
                    'label' => 'Thrusday Practice Close',
                ) ,
                40 => array (
                    'name' => 'is_friday_open',
                    'label' => 'Friday Opens?',
                    'type' => 'boolean'
                ) ,
                41 => array (
                    'name' => 'friday_two_session',
                    'label' => 'Friday Two Session',
                    'type' => 'boolean'
                ) ,
                42 => array (
                    'name' => 'friday_practice_open',
                    'label' => 'Friday Practice Open',
                ) ,
                43 => array (
                    'name' => 'friday_lunch_at',
                    'label' => 'Friday Lunch At',
                ) ,
                44 => array (
                    'name' => 'friday_resume_at',
                    'label' => 'Friday Resume At',
                ) ,
                45 => array (
                    'name' => 'friday_practice_close',
                    'label' => 'Friday Practice Close',
                ) ,
                46 => array (
                    'name' => 'is_saturday_open',
                    'label' => 'Saturday Opens?',
                    'type' => 'boolean'
                ) ,
                47 => array (
                    'name' => 'saturday_two_session',
                    'label' => 'Saturday Two Session',
                    'type' => 'boolean'
                ) ,
                48 => array (
                    'name' => 'saturday_practice_open',
                    'label' => 'Saturday Practice Open',
                ) ,
                49 => array (
                    'name' => 'saturday_lunch_at',
                    'label' => 'Saturday Lunch At',
                ) ,
                50 => array (
                    'name' => 'saturday_resume_at',
                    'label' => 'Saturday Resume At',
                ) ,
                51 => array (
                    'name' => 'saturday_practice_close',
                    'label' => 'Saturday Practice Close',
                ) ,
                52 => array (
                    'name' => 'is_active',
                    'label' => 'Actives?',
                    'type' => 'boolean'
                ) ,
                53 => array (
                    'name' => 'is_suspend',
                    'label' => 'Suspends?',
                    'type' => 'boolean'
                ) ,
            ) ,
        ) ,
    ) ,
    'appointment_modifications' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                2 => array (
                    'name' => 'unavailable_date',
                    'label' => 'Unavailable Date',
                ) ,
                3 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'template' => '<p ng-if="entry.values.type === 0" height="42" width="42">Schedule Change</p><p ng-if="entry.values.type === 1" height="42" width="42">Make day off</p>',
                ) ,
                4 => array (
                    'name' => 'unavailable_from_time',
                    'label' => 'Unavailable From Time',
                ) ,
                5 => array (
                    'name' => 'unavailable_to_time',
                    'label' => 'Unavailable To Time',
                ) ,
                6 => array (
                    'name' => 'day',
                    'label' => 'Day',
                ) ,
                7 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Appointment Modifications',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                    'targetField' => 'username',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                3 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'choice',
                    'defaultValue' => 0,
                    'choices' => array (
                        0 => array (
                            'label' => 'Schedule Change',
                            'value' => 0,
                        ) ,
                        1 => array (
                            'label' => 'Make day off',
                            'value' => 1,
                        ) ,
                    ) ,
                    'validation' => array (
                        'required' => true,
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
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'unavailable_date',
                    'label' => 'Unavailable Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'choice',
                    'defaultValue' => 0,
                    'choices' => array (
                        0 => array (
                            'label' => 'Schedule Change',
                            'value' => 0,
                        ) ,
                        1 => array (
                            'label' => 'Make day off',
                            'value' => 1,
                        ) ,
                    ) ,
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'unavailable_from_time',
                    'label' => 'Unavailable From Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'unavailable_to_time',
                    'label' => 'Unavailable To Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'day',
                    'label' => 'Day',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
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
                    ) ,
                ) ,
                1 => array (
                    'name' => 'unavailable_date',
                    'label' => 'Unavailable Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                2 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'choice',
                    'defaultValue' => 0,
                    'choices' => array (
                        0 => array (
                            'label' => 'Schedule Change',
                            'value' => 0,
                        ) ,
                        1 => array (
                            'label' => 'Make day off',
                            'value' => 1,
                        ) ,
                    ) ,
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                3 => array (
                    'name' => 'unavailable_from_time',
                    'label' => 'Unavailable From Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                4 => array (
                    'name' => 'unavailable_to_time',
                    'label' => 'Unavailable To Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                5 => array (
                    'name' => 'day',
                    'label' => 'Day',
                    'type' => 'string',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
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
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'singleApiCall' => 'getUsers'
                ) ,
                2 => array (
                    'name' => 'unavailable_date',
                    'label' => 'Unavailable Date',
                ) ,
                3 => array (
                    'name' => 'type',
                    'label' => 'Type',
                    'template' => '<p ng-if="entry.values.type === 0" height="42" width="42">Schedule Change</p><p ng-if="entry.values.type === 1" height="42" width="42">Make day off</p>',
                ) ,
                4 => array (
                    'name' => 'unavailable_from_time',
                    'label' => 'Unavailable From Time',
                ) ,
                5 => array (
                    'name' => 'unavailable_to_time',
                    'label' => 'Unavailable To Time',
                ) ,
                6 => array (
                    'name' => 'day',
                    'label' => 'Day',
                ) ,
            ) ,
        ) ,
    ) ,
    'bookings' => array (
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
                    // 'name' => 'provider_user.email',
                    'label' => 'Service Provider',
                    'template' => '<display-provider-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-provider-favourite>'
                ) ,
                3 => array (
                    'name' => 'provider_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                ) ,
                4 => array (
                    'name' => 'service.name',
                    'label' => 'Service Type',
                ) ,
                5 => array (
                    'name' => 'appointment_status.name',
                    'label' => 'Status',
                ) ,
                6 => array (
                    'name' => 'appointment_from_date',
                    'label' => 'Booking Date',
                ) ,
                7 => array (
                    'name' => 'total_booking_amount',
                    'label' => 'Booking Amount',
                ) ,
                8 => array (
                    'name' => 'site_commission_from_freelancer',
                    'label' => 'Commission From Service Provider',
                    'type' => 'float'
                ) ,  
                9 => array (
                    'name' => 'site_commission_from_customer',
                    'label' => 'Commission From Customer',
                    'type' => 'float'
                ) ,             
            ) ,
            'title' => 'Bookings',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
                3 => '<re-assign entry="entry" entity="entity" size="sm"></re-assign>'
                
            ) ,
            'batchActions' => array (
                0 => 'delete',
                1 => '<change-pending-approval-status type="appointments" action="change_status" selection="selection"></change-pending-approval-status>',
                2 => '<change-approved-status type="appointments" action="change_status" selection="selection"></change-approved-status>',
                3 => '<change-closed-status type="appointments" action="change_status" selection="selection"></change-closed-status>',
                4 => '<change-cancelled-status type="appointments" action="change_status" selection="selection"></change-cancelled-status>',
                5 => '<change-rejected-status type="appointments" action="change_status" selection="selection"></change-rejected-status>',
                6 => '<change-expired-status type="appointments" action="change_status" selection="selection"></change-expired-status>',
                7 => '<change-present-status type="appointments" action="change_status" selection="selection"></change-present-status>',
                8 => '<change-enquiry-status type="appointments" action="change_status" selection="selection"></change-enquiry-status>',
                9 => '<change-pre-approved-status type="appointments" action="change_status" selection="selection"></change-pre-approved-status>',
                10 => '<change-payment-pending-status type="appointments" action="change_status" selection="selection"></change-payment-pending-status>',
                11=> '<change-canceled-by-admin-status type="appointments" action="change_status" selection="selection"></change-canceled-by-admin-status>',
                12 => '<change-reassigned-service-provider-status type="appointments" action="change_status" selection="selection"></change-reassigned-service-provider-status>',
                13 => '<change-completed-status type="appointments" action="change_status" selection="selection"></change-completed-status>',
            ),
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
                    'permanentFilters' => array (
                        'role_id' => 2,
                    ), 
                    'remoteCompleteAdditionalParams' => array (
                        'role_id' => 2,
                    )
                ) ,
                2 => array (
                    'name' => 'provider_user_id',
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
                3 => array (
                    'name' => 'appointment_status_id',
                    'label' => 'Appointment Status',
                    'targetEntity' => 'appointment_statuses',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                4 => array (
                    'name' => 'service_id',
                    'label' => 'Services',
                    'targetEntity' => 'services',
                    'targetField' => 'name',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'permanentFilters' => array (
                        'is_active' => 1
                    )
                )/*,
                5 => array (
                    'name' => 'provider_user_id',
                    'label' => 'Listing Title',
                    'targetEntity' => 'user_profiles',
                    'targetField' => 'listing_title',
                    'map' => array (
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true
                )*/
            ) ,
            'permanentFilters' => '',
            'actions' => array (
                0 => 'batch',
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'editable' => false
                ), 
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User',
                    'editable' => false
                ) ,
                2 => array (
                    'name' => 'provider_user.username',
                    'label' => 'Service Provider',
                    'editable' => false
                ) ,
                3 => array (
                    'name' => 'service_id',
                    'label' => 'Service Type',
                    'targetEntity' => 'services',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'editable' => false
                ) ,
                4 => array (
                    'name' => 'provider_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                    'editable' => false
                ) ,
                5 => array (
                    'name' => 'appointment_from_date',
                    'label' => 'Appointment From Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                6 => array (
                    'name' => 'appointment_from_time',
                    'label' => 'Appointment From Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                7 => array (
                    'name' => 'appointment_to_date',
                    'label' => 'Appointment To Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
                8 => array (
                    'name' => 'appointment_to_time',
                    'label' => 'Appointment To Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                9 => array (
                    'name' => 'appointment_status_id',
                    'label' => 'Status',
                    'targetEntity' => 'appointment_statuses',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                10 => array (
                    'name' => 'total_booking_amount',
                    'label' => 'Booking Amount',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                11 => array (
                    'name' => 'site_commission_from_freelancer',
                    'label' => 'Site Commission',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'editable' => false
                ), 
                1 => array (
                    'name' => 'user.username',
                    'label' => 'User',
                    'editable' => false
                ) ,
                2 => array (
                    'name' => 'provider_user.username',
                    'label' => 'Service Provider',
                    'editable' => false
                ) ,
                3 => array (
                    'name' => 'service_id',
                    'label' => 'Service Type',
                    'targetEntity' => 'services',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'editable' => false
                ) ,
                4 => array (
                    'name' => 'provider_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                    'editable' => false
                ) ,
                5 => array (
                    'name' => 'appointment_from_date',
                    'label' => 'Appointment From Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,
            /*    6 => array (
                    'name' => 'appointment_from_time',
                    'label' => 'Appointment From Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) , */
                7 => array (
                    'name' => 'appointment_to_date',
                    'label' => 'Appointment To Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array (
                        'required' => false,
                    ) ,
                ) ,/*
                8 => array (
                    'name' => 'appointment_to_time',
                    'label' => 'Appointment To Time',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,*/
                9 => array (
                    'name' => 'appointment_status_id',
                    'label' => 'Status',
                    'targetEntity' => 'appointment_statuses',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                10 => array (
                    'name' => 'total_booking_amount',
                    'label' => 'Booking Amount',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                11 => array (
                    'name' => 'site_commission_from_freelancer',
                    'label' => 'Site Commission',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
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
                    'name' => 'user.username',
                    'label' => 'Customer'
                ) ,
                2 => array (
                    'name' => 'provider_user.username',
                    'label' => 'Service Provider'
                ) ,
                3 => array (
                    'name' => 'provider_user.user_profile.listing_title',
                    'label' => 'Listing Title',
                ) ,
                4 => array (
                    'name' => 'service_id',
                    'label' => 'Service Type',
                    'targetEntity' => 'services',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getServices',
                    'isDetailLink' => false,
                ) ,
                5 => array (
                    'name' => 'appointment_status_id',
                    'label' => 'Status',
                    'targetEntity' => 'appointment_statuses',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getStatus',
                    'isDetailLink' => false,
                ) ,
                6 => array (
                    'name' => 'appointment_from_date',
                    'label' => 'Booking Date',
                ) ,
                7 => array (
                    'name' => 'created_at',
                    'label' => 'Booked On',
                ) ,
                8 => array (
                    'name' => 'total_booking_amount',
                    'label' => 'Booking Amount',
                ) ,
                9 => array (
                    'name' => 'site_commission_from_freelancer',
                    'label' => 'Commission From Service Provider',
                    'type' => 'float'
                ) ,
                10 => array (
                    'name' => 'site_commission_from_customer',
                    'label' => 'Commission From Customer',
                    'type' => 'float'
                ) ,
                11 => array (
                    'name' => 'paid_escrow_amount_at',
                    'label' => 'Paid Amount At'
                ) , 
                12 => array (
                    'name' => 'payment_gateway_id',
                    'label' => 'Payment Gateway Name',
                    'targetEntity' => 'payment_gateways',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getPaymentGateway',
                    'isDetailLink' => false,
                ),
                25 => 
                array (
                'name' => 'work_location_address',
                'label' => 'Work Location Address',
                ),
                26 => 
                array (
                'name' => 'work_location_address1',
                'label' => 'Work Location Address1',
                ),
                27 => 
                array (
                'name' => 'work_location_city.name',
                'label' => 'Work Location City',                
                ),
                28 => 
                array (
                'name' => 'work_location_state.name',
                'label' => 'Work Location State',               
                ),
                29 => 
                array (
                'name' => 'work_location_country.name',
                'label' => 'Work Location Country',                
                ),
                30 => 
                array (
                'name' => 'work_location_postal_code',
                'label' => 'Work Location Postal Code',               
                ),                              
            ) ,
        ) ,
    ) ,
    'faqs' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'faq_question',
                    'label' => 'Faq Question',
                ) ,
                2 => array (
                    'name' => 'faq_answer',
                    'label' => 'Faq Answer',
                ) ,
                3 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'FAQs',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'faq_question',
                    'label' => 'Faq Question',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'faq_answer',
                    'label' => 'Faq Answer',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'faq_question',
                    'label' => 'Faq Question',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'faq_answer',
                    'label' => 'Faq Answer',
                    'type' => 'wysiwyg',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
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
                    'name' => 'faq_question',
                    'label' => 'Faq Question',
                ) ,
                2 => array (
                    'name' => 'faq_answer',
                    'label' => 'Faq Answer',
                ) ,
            ) ,
        ) ,
    ) ,
    'account_close_reasons' => array (
        'listview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array (
                    'name' => 'reasons',
                    'label' => 'Reasons',
                ) ,
                2 => array (
                    'name' => 'display_order',
                    'label' => 'Display Order',
                ) ,
                2 => array (
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,             
            ) ,
            'title' => 'Account Close Reasons',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array (
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'reasons',
                    'label' => 'Reasons',
                    'type' => 'text',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'display_order',
                    'label' => 'Display Order',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array (
            'fields' => array (
                0 => array (
                    'name' => 'reasons',
                    'label' => 'Reasons',
                    'type' => 'text',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
                1 => array (
                    'name' => 'display_order',
                    'label' => 'Display Order',
                    'type' => 'string',
                    'validation' => array (
                        'required' => true,
                    ) ,
                ) ,
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
                    'name' => 'reasons',
                    'label' => 'Reasons',
                ) ,
                2 => array (
                    'name' => 'display_order',
                    'label' => 'Display Order',
                ) ,
                2 => array (
                    'name' => 'created_at',
                    'label' => 'Created At',
                ) ,
            ) ,
        ) ,
    ) ,
  'form_field_submissions' => 
  array (
    'listview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink' => true,
        ),
        1 => 
        array (
          'name' => 'form_field.name',
          'label' => 'Form Field'
        ),
        3 => 
        array (
          'name' => 'class',
          'label' => 'Class',
        ),
        4 => 
        array (
          'name' => 'response',
          'label' => 'Response',
        ),
        5 => 
        array (
          'name' => 'is_custom_form_field',
          'label' => 'Custom Form Field?',
        ),
        6 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On'
        )
      )
    )
  ),
  'providers' => 
  array (
    'listview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink' => true,
        ),
        1 => 
        array (
          'name' => 'name',
          'label' => 'Name',
        ),
        2 => 
        array (
          'name' => 'secret_key',
          'label' => 'Secret Key',
        ),
        3 => 
        array (
          'name' => 'api_key',
          'label' => 'Api Key',
        ),
        6 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean'
        ),
        7 => 
        array (
          'name' => 'display_order',
          'label' => 'Display Order',
        ),
        8 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        ),
      ),
      'title' => 'Providers',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'edit',
        1 => 'show'
      ),
      'filters' => 
      array (
        0 => 
        array (
          'name' => 'q',
          'pinned' => true,
          'label' => 'Search',
          'type' => 'template',
          'template' => '',
        ),
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter',
      ),
    ),
    'creationview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
        array (
          'name' => 'secret_key',
          'label' => 'Secret Key',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        2 => 
        array (
          'name' => 'api_key',
          'label' => 'Api Key',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        5 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'choices' => array (
                0 => array (
                    'label' => 'Yes',
                    'value' => true,
                ) ,
                1 => array (
                    'label' => 'No',
                    'value' => false,
                )
            )
        ),
        6 => 
        array (
          'name' => 'display_order',
          'label' => 'Display Order',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        )
      )
    ),    
    'editionview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'name',
          'label' => 'Name',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        1 => 
        array (
          'name' => 'secret_key',
          'label' => 'Secret Key',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        2 => 
        array (
          'name' => 'api_key',
          'label' => 'Api Key',
          'type' => 'string',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        5 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'choice',
          'choices' => array (
                0 => array (
                    'label' => 'Yes',
                    'value' => true,
                ) ,
                1 => array (
                    'label' => 'No',
                    'value' => false,
                )
            )
        ),
        6 => 
        array (
          'name' => 'display_order',
          'label' => 'Display Order',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        )
      )
    )
  ),  
);
$dashboard = array (
    /*'users' => array (
        'addCollection' => array (
            'fields' => array (
                0 => array (
                    'name' => 'first_name',
                    'label' => 'First Name',
                ) , 
                1 => array (
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ) ,
                2 => 
                array (
                'name' => 'user_profile.phone',
                'label' => 'Mobile'       
                ),                
                3 => array (
                    'name' => 'email',
                    'label' => 'Email'
                ) ,
                4 => array (
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'boolean'
                ),                                                                                                
                5 => array (
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,                
            ) ,
            'title' => 'Recent Users',
            'name' => 'recent_users',
            'perPage' => 5,
            'order' => 1,
            'template' => '<div class="col-lg-12"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_users" entries="dashboardController.entries.recent_users" datastore="dashboardController.datastore"> </ma-dashboard-panel></div></div>'
        )
    )*/
);
// Plugin enabled Condtions for Fields

if (isPluginEnabled('PaymentBooking/PaymentBooking')) {
    $user_table = array (
        'service_providers' => array (
            'listview' => array (
                'fields' => array (
                    5 => array (
                        'name' => 'available_wallet_amount',
                        'label' => 'Available Revenue Amount',
                        'type' => 'float'
                    ),
                    8 => array (
                        'name' => 'user_profile.earned_amount_as_service_provider',
                        'label' => 'Total Earned Amount',
                        'type' => 'float'
                    ) ,
                    9 => array (
                        'name' => 'user_profile.site_revenue_as_service_provider',
                        'label' => 'Site Revenue',
                        'type' => 'float'
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    7 => array (
                        'name' => 'available_wallet_amount',
                        'label' => 'Available Revenue Amount',
                        'type' => 'float'
                    ),
                    8 => array (
                        'name' => 'user_profile.earned_amount_as_service_provider',
                        'label' => 'Total Earned Amount',
                        'type' => 'float'
                    ) ,
                    20 => array (
                        'name' => 'user_profile.site_revenue_as_service_provider',
                        'label' => 'Site Revenue',
                        'type' => 'float'
                    ),
                    21 => array (
                        'name' => 'user_profile.total_spent_amount_as_customer',
                        'label' => 'Spent Amount',
                        'type' => 'float'
                    )
                )
            ),
            'editionview' => array (
                'fields' => array (
                    7 => array (
                        'name' => 'available_wallet_amount',
                        'label' => 'Available Revenue Amount',
                        'type' => 'float'
                    )
                )
            )
        ),
        'customers' => array (
            'listview' => array (
                'fields' => array (
                    5 => array (
                        'name' => 'user_profile.total_spent_amount_as_customer',
                        'label' => 'Spent Amount',
                        'type' => 'float'
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    7 => array (
                        'name' => 'available_wallet_amount',
                        'label' => 'Available Revenue Amount',
                        'type' => 'float'
                    ),
                    8 => array (
                        'name' => 'user_profile.earned_amount_as_service_provider',
                        'label' => 'Total Earned Amount',
                        'type' => 'float'
                    ) ,
                    20 => array (
                        'name' => 'user_profile.site_revenue_as_service_provider',
                        'label' => 'Site Revenue',
                        'type' => 'float'
                    ),
                    21 => array (
                        'name' => 'user_profile.total_spent_amount_as_customer',
                        'label' => 'Spent Amount',
                        'type' => 'float'
                    )
                )
            ),
            'editionview' => array (
                'fields' => array (
                    7 => array (
                        'name' => 'available_wallet_amount',
                        'label' => 'Available Revenue Amount',
                        'type' => 'float'
                    )
                )
            )
        ),
        'listings' => array (
            'listview' => array (
                'fields' => array (
                    16 => array (
                        'name' => 'user_profile.earned_amount_as_service_provider',
                        'label' => 'Total Earned Amount',
                        'type' => 'float'
                    ) ,
                    17 => array (
                        'name' => 'user_profile.site_revenue_as_service_provider',
                        'label' => 'Site Revenue',
                        'type' => 'float'
                    ) ,
                )
            ),
            'showview' => array (
                'fields' => array (
                    6 => array (
                        'name' => 'user_profile.earned_amount_as_service_provider',
                        'label' => 'Total Earned Amount',
                        'type' => 'float'
                    ) ,
                    7 => array (
                        'name' => 'user_profile.site_revenue_as_service_provider',
                        'label' => 'Site Revenue',
                        'type' => 'float'
                    ) ,
                )
            )
        ),
    );
    $tables = merge_details($tables, $user_table);
}
if (isPluginEnabled('Review/Review')) {
    $user_table = array (
        'service_providers' => array (
            'listview' => array (
                'fields' => array (
                    7 => array (
                        'name' => 'average_rating_as_service_provider',
                        'label' => 'Avg Rating',
                        //'template' => '<star-rating stars="{{entry.values.average_rating_as_service_provider}}"></star-rating>'
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    25 => array (
                        'name' => 'total_rating_as_service_provider',
                        'label' => 'Rating as Service Provider',
                        'template' => '<star-rating stars="{{entry.values.total_rating_as_service_provider}}"></star-rating>'
                    )
                )
            )
        ),
        'customers' => array (
            'showview' => array (
                'fields' => array (
                    25 => array (
                        'name' => 'total_rating_as_employer',
                        'label' => 'Rating as Customer',
                        'template' => '<star-rating stars="{{entry.values.total_rating_as_employer}}"></star-rating>'
                    )
                )
            )
        ),
        'listings' => array (
            'listview' => array (
                'fields' => array (
                    18 => array (
                        'name' => 'average_rating_as_service_provider',
                        'label' => 'Avg Rating',
                        'type' => 'float'
                    ) ,
                )
            ),
            'showview' => array (
                'fields' => array (
                    18 => array (
                        'name' => 'average_rating_as_service_provider',
                        'label' => 'Avg Rating',
                        'type' => 'float'
                    ) ,
                )
            ),
        ),
    );
    $tables = merge_details($tables, $user_table);
}
if (isPluginEnabled('Referral/Referral')) {
    $user_table = array (
        'service_providers' => array (
            'listview' => array (
                'fields' => array (
                    6 => array (
                        'name' => 'affiliate_pending_amount',
                        'label' => 'Available Referral Amount',
                        'type' => 'float'
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    22 => array (
                        'name' => 'referred_by_user.username',
                        'label' => 'Referred By'
                    ),
                    23 => array (
                        'name' => 'affiliate_pending_amount',
                        'label' => 'Available Referral Amount',
                        'type' => 'float'
                    ),
                    24 => array (
                        'name' => 'affiliate_paid_amount',
                        'label' => 'Referral Used Amount',
                        'type' => 'float'
                    )
                )
            ),
            'editionview' => array (
                'fields' => array (
                    23 => array (
                        'name' => 'affiliate_pending_amount',
                        'label' => 'Available Referral Amount',
                        'type' => 'float'
                    ),
                    24 => array (
                        'name' => 'affiliate_paid_amount',
                        'label' => 'Referral Used Amount',
                        'type' => 'float'
                    )
                )
            ),
        ),
        'customers' => array (
            'listview' => array (
                'fields' => array (
                    5 => array (
                        'name' => 'affiliate_pending_amount',
                        'label' => 'Available Referral Amount',
                        'type' => 'float'
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    22 => array (
                        'name' => 'referred_by_user.username',
                        'label' => 'Referred By'
                    ),
                    23 => array (
                        'name' => 'affiliate_pending_amount',
                        'label' => 'Available Referral Amount',
                        'type' => 'float'
                    ),
                    24 => array (
                        'name' => 'affiliate_paid_amount',
                        'label' => 'Referral Used Amount',
                        'type' => 'float'
                    )
                )
            ),
            'editionview' => array (
                'fields' => array (
                    23 => array (
                        'name' => 'affiliate_pending_amount',
                        'label' => 'Available Referral Amount',
                        'type' => 'float'
                    ),
                    24 => array (
                        'name' => 'affiliate_paid_amount',
                        'label' => 'Referral Used Amount',
                        'type' => 'float'
                    )
                )
            ),
        ),
        'bookings' => array (
            'showview' => array (
                'fields' => array (
                    13 => array (
                        'name' => 'used_affiliate_amount',
                        'label' => 'Used Referral Amount For Booking',
                        'type' => 'float'
                    )
                )
            )
        )
    );
    $tables = merge_details($tables, $user_table);
}

if (isPluginEnabled('SMS/SMS')) {
    $user_table = array (
        'service_providers' => array (
            'listview' => array (
                'filters' => array (
                    3 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Yes',
                                'value' => true,
                            ) ,
                            1 => array (
                                'label' => 'No',
                                'value' => false,
                            )
                        )
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    26 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'boolean'
                    ),
                    27 => array (
                        'name' => 'is_sms_notification',
                        'label' => 'SMS Notification Enabled?',
                        'type' => 'boolean'
                    )
                ),
            ),
            'editionview' => array (
                'fields' => array (
                    26 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Yes',
                                'value' => true,
                            ),
                            1 => array (
                                'label' => 'No',
                                'value' => false,
                            )
                        )
                    ),
                    27 => array (
                        'name' => 'is_sms_notification',
                        'label' => 'SMS Notification Enabled?',
                        'type' => 'choice',
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
        ),
        'customers' => array (
            'listview' => array (
                'filters' => array (
                    3 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Yes',
                                'value' => true,
                            ) ,
                            1 => array (
                                'label' => 'No',
                                'value' => false,
                            )
                        )
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    26 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'boolean'
                    ),
                    27 => array (
                        'name' => 'is_sms_notification',
                        'label' => 'SMS Notification Enabled?',
                        'type' => 'boolean'
                    )
                ),
                'editionview' => array (
                    'fields' => array (
                        26 => array (
                            'name' => 'is_mobile_number_verified',
                            'label' => 'Mobile Number Confirmed?',
                            'type' => 'choice',
                            'choices' => array (
                                0 => array (
                                    'label' => 'Yes',
                                    'value' => true,
                                ) ,
                                1 => array (
                                    'label' => 'No',
                                    'value' => false,
                                )
                            )
                        ),
                        27 => array (
                            'name' => 'is_sms_notification',
                            'label' => 'SMS Notification Enabled?',
                            'type' => 'choice',
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
            )
        ),
        'add_users' => array (
            'listview' => array (
                'filters' => array (
                    3 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Yes',
                                'value' => true,
                            ) ,
                            1 => array (
                                'label' => 'No',
                                'value' => false,
                            )
                        )
                    )
                )
            ),
            'creationview' => array (
                'fields' => array (
                    11 => array (
                        'name' => 'is_mobile_number_verified',
                        'label' => 'Mobile Number Confirmed?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Yes',
                                'value' => true,
                            ) ,
                            1 => array (
                                'label' => 'No',
                                'value' => false,
                            )
                        )
                    ),
                )
            )
        )
    );
    $tables = merge_details($tables, $user_table);
}

if (isPluginEnabled('Withdrawal/Withdrawal')) {
    $user_table = array (
        'service_providers' => array (
            'showview' => array (
                'fields' => array (
                    25 => array (
                        'name' => 'blocked_amount',
                        'label' => 'Blocked Amount',
                        'type' => 'float'
                    )
                )
            )
        ),
        'customers' => array (
            'showview' => array (
                'fields' => array (
                    25 => array (
                        'name' => 'blocked_amount',
                        'label' => 'Blocked Amount',
                        'type' => 'float'
                    )
                )
            )
        )
    );
    $tables = merge_details($tables, $user_table);
}
if (isPluginEnabled('TopUser/TopUser')) {
    $service_provider_table = array (
        'service_providers' => array (
            'listview' => array (
                'fields' => array (
                    11 => array (
                        'name' => 'user_profile.is_top_listed',
                        'label' => 'Top Listed User?',
                        'type' => 'boolean',
                    )
                ),
                'filters' => array (
                    4 => array (
                        'name' => 'is_top_listed',
                        'label' => 'Top Listed User?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Yes',
                                'value' => 1,
                            ),
                            1 => array (
                                'label' => 'No',
                                'value' => 0,
                            )
                        )
                    )
                )
            ),
            'showview' => array (
                'fields' => array (
                    11 => array (
                        'name' => 'user_profile.is_top_listed',
                        'label' => 'Top Listed User?',
                        'type' => 'boolean',
                    )
                )
            )
        )
    );
    $tables = merge_details($tables, $service_provider_table);
}
if (isPluginEnabled('ProUser/ProUser')) {
    $user_table = array (
        'service_providers' => array (
            'listview' => array (
                'fields' => array (
                    25 => array (
                        'name' => 'user_profile.pro_account_status_id',
                        'label' => 'Pro User?',
                        'template' => '<show-prouser-status entry="entry" entity="entity" size="sm" label="Change Name" ></show-prouser-status>'
                    )
                ),
                'filters' => array (
                    5 => array (
                        'name' => 'pro_account_status_id',
                        'label' => 'Pro User?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Not Paid',
                                'value' => 1,
                            ),
                            1 => array (
                                'label' => 'Paid and Pending Approval',
                                'value' => 2,
                            ),
                            2 => array (
                                'label' => 'Apporved',
                                'value' => 3,
                            )
                        )
                    )
                )
            ),
            
            'showview' => array (
                'fields' => array (
                    25 => array (
                        'name' => 'user_profile.pro_account_status_id',
                        'label' => 'Pro User?',
                         'template' => '<show-prouser-status entry="entry" entity="entity" size="sm" label="Change Name" ></show-prouser-status>'
                    )
                )
            ),
            'editionview' => array (
                'fields' => array (
                    10 => array (
                        'name' => 'user_profile.pro_account_status_id',
                        'label' => 'PRO User?',
                        'type' => 'choice',
                        'choices' => array (
                            0 => array (
                                'label' => 'Not Paid',
                                'value' => 1,
                            ),
                            1 => array (
                                'label' => 'Paid and Pending Approval',
                                'value' => 2,
                            ),
                            2 => array (
                                'label' => 'Approved',
                                'value' => 3,
                            )
                        ) ,
                    )
                )
            )
        )
    );
    $tables = merge_details($tables, $user_table);
}

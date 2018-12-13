<?php
$menus = array (
    'Bookings' => array (
        'title' => 'Bookings',
        'icon_template' => '<span class="fa fa-ticket fa-fw"></span>',
        'child_sub_menu' => array (
            'messages' => 
            array (
                'title' => 'Messages',
                'icon_template' => '<span class="fa fa-comment"></span>',
                'suborder' => 2
            ),
        ),
        'order' => 3
    )
);
$tables = array (
      'messages' => 
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
          'name' => 'foreign_id',
          'label' => 'Booking ID'
        ),
        2 => 
        array (
          // 'name' => 'user.email',
          'label' => 'User',
          'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
        ),
        3 => 
        array (
          // 'name' => 'other_user.email',
          'label' => 'Other User',
          'template' => '<display-provider-messages entry="entry" entity="entity" size="sm" label="Change Name" ></display-provider-messages>'
        ),
        4 => 
        array (
          'name' => 'message_content.message',
          'label' => 'Message',
          'type' => 'wysiwyg',
          'map' => 
          array (
            0 => 'truncate',
          ),
        ),
        5 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On'
        ),
      ),
      'title' => 'Messages',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => 'delete',
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
        1 => 
        array (
          'name' => 'user_id',
          'label' => 'User',
          'targetEntity' => 'users',
          'targetField' => 'email',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        2 => 
        array (
          'name' => 'other_user_id',
          'label' => 'Other User',
          'targetEntity' => 'users',
          'targetField' => 'email',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'foreign_id',
          'label' => 'Booking ID',
          'targetEntity' => 'appointments',
          'targetField' => 'id',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter',
      ),
    ),
    'showview' => array (
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
          'name' => 'foreign_id',
          'label' => 'Booking ID'
        ),
        2 => 
        array (
          'label' => 'User',
          'template' => '<display-customer-favourite entry="entry" entity="entity" size="sm" label="Change Name" ></display-customer-favourite>'
        ),
        3 => 
        array (
          'label' => 'Other User',
          'template' => '<display-provider-messages entry="entry" entity="entity" size="sm" label="Change Name" ></display-provider-messages>'
        ),
        4 => 
        array (
          'name' => 'message_content.message',
          'label' => 'Message',
          'type' => 'wysiwyg'
        ),
        5 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On'
        ),
      ),
    ),
    'creationview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'user_id',
          'label' => 'User',
          'targetEntity' => 'users',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        1 => 
        array (
          'name' => 'to_user_id',
          'label' => 'To User',
          'targetEntity' => 'to_users',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        2 => 
        array (
          'name' => 'message_content_id',
          'label' => 'Message Content',
          'targetEntity' => 'message_contents',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => false,
          ),
        ),
        3 => 
        array (
          'name' => 'message_folder_id',
          'label' => 'Message Folder',
          'targetEntity' => 'message_folders',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        4 => 
        array (
          'name' => 'messageable_id',
          'label' => 'Messageable',
          'targetEntity' => 'messageables',
          'targetField' => 'name',
          'type' => 'reference',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
        5 => 
        array (
          'name' => 'messageable_type',
          'label' => 'Messageable Type',
          'type' => 'string',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
      ),
    )
  ),
);
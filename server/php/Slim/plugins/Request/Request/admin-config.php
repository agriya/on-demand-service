<?php
$menus = array (
    'Requests' => array (
        'title' => 'Requests',
        'icon_template' => '<span class="fa fa-paper-plane fa-fw"></span>',
        'child_sub_menu' => array (
            'requests' => array (
                'title' => 'Requests',
                'icon_template' => '<span class="fa fa-paper-plane"></span>',
                'suborder' => 1
            ),
            'requests_users' => 
            array (
              'title' => 'Responses',
              'icon_template' => '<span class="fa fa-reply"></span>',
              'suborder' => 2
            ),
        ) ,
        'order' => 4
    ) ,
);
$tables = array (
  'requests' => 
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
          'name' => 'user.username',
          'label' => 'Customer'
        ),
        2 => 
        array (
          'name' => 'service.name',
          'label' => 'Service'
        ),
        3 => 
        array (
          'name' => 'work_location_address',
          'label' => 'Location'
        ),
        4 => 
        array (
          'name' => 'job_type_id',
          'label' => 'Job Type',
          'template' => '<p ng-if="entry.values.job_type_id === 1" height="42" width="42">One time Job</p><p ng-if="entry.values.job_type_id === 2" height="42" width="42">Part Time</p><p ng-if="entry.values.job_type_id === 3" height="42" width="42">Full Time</p>'
        ),
        5 => 
        array (
          'name' => 'appointment_timing_type_id',
          'label' => 'Timing Type',
          'template' => '<p ng-if="entry.values.appointment_timing_type_id === 1" height="42" width="42">Specify Times</p><p ng-if="entry.values.appointment_timing_type_id === 2" height="42" width="42">During The Day</p><p ng-if="entry.values.appointment_timing_type_id === 3" height="42" width="42">During The Night</p>'
        ),
        6 => 
        array (
          'name' => 'request_status_id',
          'label' => 'Status',
          'template' => '<p ng-if="entry.values.request_status_id === 1" height="42" width="42">Open</p><p ng-if="entry.values.request_status_id === 2" height="42" width="42">Closed</p>'
        ),
        7 => 
        array (
          'name' => 'requests_user_count',
          'label' => 'Requests Users',
          'type' => 'number'
        ),
        8 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        )
      ),
      'title' => 'Requests',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'show',
        1 => 'delete',
      ),
      'batchActions' => 
      array (
        0 => 'delete'
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
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        2 => 
        array (
          'name' => 'service_id',
          'label' => 'Service',
          'targetEntity' => 'services',
          'targetField' => 'name',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'request_status_id',
          'label' => 'Status',
          'type' => 'choice',
          'choices' => array (
                0 => array (
                    'label' => 'Open',
                    'value' => 1
                ),
                1 => array (
                    'label' => 'Closed',
                    'value' => 2
                )
            )
        )
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    ),
    'showview' => 
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
            'name' => 'user.username',
            'label' => 'Customer'
        ),        
        2 => 
        array (
            'name' => 'service_id',
            'label' => 'Service',
            'targetEntity' => 'services',
            'targetField' => 'name',
            'type' => 'reference',
            'singleApiCall' => 'getServices'
        ),
        3 => 
        array (
          'name' => 'work_location_address',
          'label' => 'Location'
        ),
        4 => 
        array (
          'name' => 'request_status_id',
          'label' => 'Status',
          'template' => '<p ng-if="entry.values.request_status_id === 1" height="42" width="42">Open</p><p ng-if="entry.values.request_status_id === 2" height="42" width="42">Closed</p>'
        ),
        5 => 
        array (
          'name' => 'job_type_id',
          'label' => 'Job Type',
          'template' => '<p ng-if="entry.values.job_type_id === 1" height="42" width="42">One time Job</p><p ng-if="entry.values.job_type_id === 2" height="42" width="42">Part Time</p><p ng-if="entry.values.job_type_id === 3" height="42" width="42">Full Time</p>'
        ),
        6 => 
        array (
          'name' => 'appointment_timing_type_id',
          'label' => 'Timing Type',
          'template' => '<p ng-if="entry.values.appointment_timing_type_id === 1" height="42" width="42">Specify Times</p><p ng-if="entry.values.appointment_timing_type_id === 2" height="42" width="42">During The Day</p><p ng-if="entry.values.appointment_timing_type_id === 3" height="42" width="42">During The Night</p>'
        ),
        7 => 
        array (
          'name' => 'appointment_from_date',
          'label' => 'Appointment From Date',
        ),
        8 => 
        array (
          'name' => 'appointment_to_date',
          'label' => 'Appointment To Date',
        ),
        9 => 
        array (
          'name' => 'appointment_from_time',
          'label' => 'Appointment From Time',
        ),
        10 => 
        array (
          'name' => 'appointment_to_time',
          'label' => 'Appointment To Time',
        ),
        11 => 
        array (
          'name' => 'is_sunday_needed',
          'label' => 'Sunday Needed?',
          'type' => 'boolean'
        ),
        12 => 
        array (
          'name' => 'is_monday_needed',
          'label' => 'Monday Needed?',
          'type' => 'boolean'
        ),
        13 => 
        array (
          'name' => 'is_tuesday_needed',
          'label' => 'Tuesday Needed?',
          'type' => 'boolean'
        ),
        14 => 
        array (
          'name' => 'is_wednesday_needed',
          'label' => 'Wednesday Needed?',
          'type' => 'boolean'
        ),
        15 => 
        array (
          'name' => 'is_thursday_needed',
          'label' => 'Thursday Needed?',
          'type' => 'boolean'
        ),
        16 => 
        array (
          'name' => 'is_friday_needed',
          'label' => 'Friday Needed?',
          'type' => 'boolean'
        ),
        17 => 
        array (
          'name' => 'is_saturday_needed',
          'label' => 'Saturday Needed?',
          'type' => 'boolean'
        ),
        18 => 
        array (
          'name' => 'requests_user_count',
          'label' => 'Requests Users',
          'type' => 'number'
        ),
        19 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On',
        ),
        20 => array (
          'name' => 'Form field submissions',
          'label' => 'Form field submissions',
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
              'class' => 'Request'
          ),
          'perPage' => 10
          )
      ),
    ),
  ),
  'requests_users' => 
  array (
    'listview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink' => false,
        ),
        1 => array (
          'name' => 'request.user.username',
          'label' => 'Customer'
        ) ,
        2 => array (
          'name' => 'user.username',
          'label' => 'Service Provider'
        ) ,
        3 => 
        array (
          'name' => 'request.work_location_address',
          'label' => 'Location'
        ),
        4 => 
        array (
          'label' => 'Job Type',
          'template' => '<p ng-if="entry.values.request.job_type_id === 1" height="42" width="42">One time Job</p><p ng-if="entry.values.job_type_id === 2" height="42" width="42">Part Time</p><p ng-if="entry.values.job_type_id === 3" height="42" width="42">Full Time</p>'
        ),
        5 => 
        array (
          'name' => 'appointment_timing_type_id',
          'label' => 'Timing Type',
          'template' => '<p ng-if="entry.values.request.appointment_timing_type_id === 1" height="42" width="42">Specify Times</p><p ng-if="entry.values.appointment_timing_type_id === 2" height="42" width="42">During The Day</p><p ng-if="entry.values.appointment_timing_type_id === 3" height="42" width="42">During The Night</p>'
        ),
        6 => 
        array (
          'name' => 'request_status_id',
          'label' => 'Request Status',
          'template' => '<p ng-if="entry.values.request.request_status_id === 1" height="42" width="42">Open</p><p ng-if="entry.values.request_status_id === 2" height="42" width="42">Closed</p>'
        ),
        7 => 
        array (
          'name' => 'created_at',
          'label' => 'Created On'
        ),
      ),
      'title' => 'Requests Users',
      'perPage' => '10',
      'sortField' => '',
      'sortDir' => '',
      'infinitePagination' => false,
      'listActions' => 
      array (
        0 => 'delete',
      ),
      'batchActions' => 
      array (
        0 => 'delete',
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
          'name' => 'customer_id',
          'label' => 'Customer',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
            'role_id' => 2 
          )
        ),
        2 => 
        array (
          'name' => 'user_id',
          'label' => 'Service Provider',
          'targetEntity' => 'users',
          'targetField' => 'username',
          'map' => 
          array (
            0 => 'truncate',
          ),
          'type' => 'reference',
          'remoteComplete' => true,
          'permanentFilters' => array (
            'role_id' => 3
          )
        ),
      ),
      'permanentFilters' => '',
      'actions' => 
      array (
        0 => 'batch',
        1 => 'filter'
      ),
    )
  )  
);

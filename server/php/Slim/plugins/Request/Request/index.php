<?php

/**
 * GET requestsGet
 * Summary: Fetch all requests
 * Notes: Returns all requests from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/requests', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$requests = Models\Request::Filter($queryParams)->paginate()->toArray();
		$data = $requests['data'];
		unset($requests['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $requests
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
});


$app->GET('/api/v1/me/requests', function($request, $response, $args) {
	global $authUser;
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$requests = Models\Request::Filter($queryParams)->where('user_id', $authUser->id)->paginate()->toArray();
		$data = $requests['data'];
		unset($requests['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $requests
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
})->add(new ACL('canGetMeRequest'));

/**
 * POST requestsPost
 * Summary: Creates a new request
 * Notes: Creates a new request
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/requests', function($request, $response, $args) {
	global $authUser;
	$args = $request->getParsedBody();
    if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
        $args['user_id'] = $authUser->id;
    }
	$request = new Models\Request($args);
	$result = array();
	try {
		$validationErrorFields = $request->validate($args);
		if(is_object($validationErrorFields)){
			$validationErrorFields = $validationErrorFields->toArray();
		}
		if(empty($validationErrorFields)){
			$validationErrorFields = array();
		}
		if(!empty($args['job_type_id']) && $args['job_type_id'] == \Constants\ConstJobType::OneTimeJob){
			if(!isset($args['appointment_from_date'])){
                 $validation['error']['appointment_from_date'] = ['Appointment From Date is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!isset($args['appointment_from_time'])){
                 $validation['error']['appointment_from_time'] = ['Appointment From Time is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!isset($args['appointment_to_date'])){
                 $validation['error']['appointment_to_date'] = ['Appointment To Date is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!isset($args['appointment_to_time'])){
                 $validation['error']['appointment_to_time'] = ['Appointment To Date is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}									
		}
		if(!empty($args['job_type_id']) && ($args['job_type_id'] == \Constants\ConstJobType::PartTime || $args['job_type_id'] == \Constants\ConstJobType::FullTime)){
			if(!isset($args['appointment_timing_type_id'])){
                 $validation['error']['appointment_timing_type_id'] = ['Appointment Timing Type is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(empty($args['is_sunday_needed']) && empty($args['is_monday_needed']) && empty($args['is_tuesday_needed']) && empty($args['is_wednesday_needed']) && empty($args['is_thursday_needed']) && empty($args['is_friday_needed']) && empty($args['is_saturday_needed'])){
                 $validation['error']['specific_day_needed'] = ['Specific day is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!empty($args['appointment_timing_type_id']) && $args['appointment_timing_type_id'] == \Constants\ConstAppointmentTimingType::SpecificTime){
				$validationErrorFields = array_merge($validationErrorFields, Models\Request::specificArrayValidation($args));					
			}
		}
		if (!empty($args['work_location_country']['iso2'])) {
			$country_id = findCountryIdFromIso2($args['work_location_country']['iso2']);
			$request->work_location_country_id = $country_id;
		} elseif (isset($args['work_location_country']['iso2'])) {
			$request->work_location_country_id = '';
		}
		if (!empty($args['work_location_state']['name']) && !empty($request->work_location_country_id)) {
			$state_id = findOrSaveAndGetStateId($args['work_location_state']['name'], $request->work_location_country_id);
			$request->work_location_state_id = $state_id;
		} elseif (isset($args['work_location_state']['name'])) {
			$request->work_location_state_id = '';
		}
		if (!empty($args['work_location_city']['name']) && !empty($request->work_location_country_id) && !empty($request->work_location_state_id)) {
			$city_id = findOrSaveAndGetCityId($args['work_location_city']['name'], $request->work_location_country_id, $request->work_location_state_id);
			$request->work_location_city_id = $city_id;
		} elseif (isset($args['work_location_city']['name'])) {
			$request->work_location_city_id = '';
		}	
		if (empty($validationErrorFields)) {
			$request->save();
			if (!empty($args['form_field_submissions'])) {
				foreach ($args['form_field_submissions'] as $formFieldSubmissions) {
					foreach ($formFieldSubmissions as $form_field_id => $value) {
						$formField = Models\FormField::where('id', $form_field_id)->select('id', 'name')->first();
						if (!empty($formField)) {
							$formFieldSubmission = new Models\FormFieldSubmission;
							$formFieldSubmission->response = $value;
							$formFieldSubmission->form_field_id = $formField->id;
							$formFieldSubmission->foreign_id = $request->id;
							$formFieldSubmission->class = 'Request';
							$formFieldSubmission->save();
						}
					}
				}
			}			
			$result = $request->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'Request could not be added. Please, try again.', $validationErrorFields, 1, 422);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canCreateRequest'));


/**
 * DELETE requestsRequestIdDelete
 * Summary: Delete request
 * Notes: Deletes a single request based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/requests/{requestId}', function($request, $response, $args) {
	global $authUser;
	$request = Models\Request::find($request->getAttribute('requestId'));
	$result = array();
    if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
        return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
    }	
	try {
		if (!empty($request)) {
			$request->delete();
			$result = array(
				'status' => 'success',
			);
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'No record found', '', 1, 404);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canDeleteRequest'));


/**
 * GET requestsRequestIdGet
 * Summary: Fetch request
 * Notes: Returns a request based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/requests/{requestId}', function($request, $response, $args) {
	$result = array();
    $queryParams = $request->getQueryParams();
    $request = Models\Request::Filter($queryParams)->find($request->getAttribute('requestId'));
    if (!empty($request)) {
        $result['data'] = $request;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
});


/**
 * PUT requestsRequestIdPut
 * Summary: Update request by its id
 * Notes: Update request by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/requests/{requestId}', function($request, $response, $args) {
	global $authUser;
	$result = array();
	$args = $request->getParsedBody();
	$request = Models\Request::find($request->getAttribute('requestId'));
	if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $request->user_id != $authUser->id) {
        return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
    }
	$request->fill($args);
	try {
		$validationErrorFields = $request->validate($args);
		if(is_object($validationErrorFields)){
			$validationErrorFields = $validationErrorFields->toArray();
		}
		if(empty($validationErrorFields)){
			$validationErrorFields = array();
		}
		if(!empty($args['job_type_id']) && $args['job_type_id'] == \Constants\ConstJobType::OneTimeJob){
			if(!isset($args['appointment_from_date'])){
                 $validation['error']['appointment_from_date'] = ['Appointment From Date is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!isset($args['appointment_from_time'])){
                 $validation['error']['appointment_from_time'] = ['Appointment From Time is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!isset($args['appointment_to_date'])){
                 $validation['error']['appointment_to_date'] = ['Appointment To Date is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!isset($args['appointment_to_time'])){
                 $validation['error']['appointment_to_time'] = ['Appointment To Date is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}									
		}
		if(!empty($args['job_type_id']) && ($args['job_type_id'] == \Constants\ConstJobType::PartTime || $args['job_type_id'] == \Constants\ConstJobType::FullTime)){
			if(!isset($args['appointment_timing_type_id'])){
                 $validation['error']['appointment_timing_type_id'] = ['Appointment Timing Type is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(empty($args['is_sunday_needed']) && empty($args['is_monday_needed']) && empty($args['is_tuesday_needed']) && empty($args['is_wednesday_needed']) && empty($args['is_thursday_needed']) && empty($args['is_friday_needed']) && empty($args['is_saturday_needed'])){
                 $validation['error']['specific_day_needed'] = ['Specific day is required'];
                 $validationErrorFields = array_merge($validationErrorFields, $validation);
			}
			if(!empty($args['appointment_timing_type_id']) && $args['appointment_timing_type_id'] == \Constants\ConstAppointmentTimingType::SpecificTime){
				$validationErrorFields = array_merge($validationErrorFields, Models\Request::specificArrayValidation($args));					
			}
		}	
		if (!empty($args['work_location_country']['iso2'])) {
			$country_id = findCountryIdFromIso2($args['work_location_country']['iso2']);
			$request->work_location_country_id = $country_id;
		} elseif (isset($args['work_location_country']['iso2'])) {
			$request->work_location_country_id = '';
		}
		if (!empty($args['work_location_state']['name']) && !empty($request->work_location_country_id)) {
			$state_id = findOrSaveAndGetStateId($args['work_location_state']['name'], $request->work_location_country_id);
			$request->work_location_state_id = $state_id;
		} elseif (isset($args['work_location_state']['name'])) {
			$request->work_location_state_id = '';
		}
		if (!empty($args['work_location_city']['name']) && !empty($request->work_location_country_id) && !empty($request->work_location_state_id)) {
			$city_id = findOrSaveAndGetCityId($args['work_location_city']['name'], $request->work_location_country_id, $request->work_location_state_id);
			$request->work_location_city_id = $city_id;
		} elseif (isset($args['work_location_city']['name'])) {
			$request->work_location_city_id = '';
		}	
		if (empty($validationErrorFields)) {
			$request->save();
                if (!empty($args['form_field_submissions'])) {
                    foreach ($args['form_field_submissions'] as $formFieldSubmissions) {
                        foreach ($formFieldSubmissions as $form_field_id => $value) {
                            $formField = Models\FormField::where('id', $value)->select('id', 'name')->first();
                            if (!empty($formField)) {
                                $FormFieldSubmission = Models\FormFieldSubmission::where('form_field_id', $formField->id)->where('foreign_id', $request->id)->where('class', 'Request')->first();
                                if (empty($FormFieldSubmission)) {
                                    $formFieldSubmission = new Models\FormFieldSubmission;
                                    $formFieldSubmission->response = $value;
                                    $formFieldSubmission->form_field_id = $formField->id;
                                    $formFieldSubmission->foreign_id = $request->id;
                                    $formFieldSubmission->class = 'Request';
                                    $formFieldSubmission->save();
                                } else {
                                    $FormFieldSubmission->response = $value;
                                    $FormFieldSubmission->save();
                                }
                            }
                        }
                    }
                }			
			$result = $request->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'Request could not be updated. Please, try again.', $validationErrorFields, 1, 422);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canUpdateRequest'));

/**
 * GET requestsUsersGet
 * Summary: Fetch all requests users
 * Notes: Returns all requests users from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/requests_users', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$requestsUsers = Models\RequestsUser::Filter($queryParams)->paginate()->toArray();
		$data = $requestsUsers['data'];
		unset($requestsUsers['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $requestsUsers
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
})->add(new ACL('canListRequestsUser'));


/**
 * POST requestsUsersPost
 * Summary: Creates a new requests user
 * Notes: Creates a new requests user
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/requests_users', function($request, $response, $args) {
	global $authUser, $_server_domain_url;
	$result = array();	
	$args = $request->getParsedBody();
    if ($authUser->role_id != \Constants\ConstUserTypes::Admin) {
        $args['user_id'] = $authUser->id;
    }	
	$request_user = Models\RequestsUser::where('request_id', $args['request_id'])->where('user_id', $args['user_id'])->first();
	if(!empty($request_user)){
		return renderWithJson($result, 'Requests user already exist. Please, try again.', '', 1, 422);		
	}
	$requestsUser = new Models\RequestsUser($args);
	try {
		$validationErrorFields = $requestsUser->validate($args);
		if (empty($validationErrorFields)) {
			$service_provider = Models\User::with('user_profile')->find($args['user_id']);
			$request = Models\Request::with('service','user')->find($args['request_id']);
			$requestsUser->save();
			$emailFindReplace = array(
				'##USERNAME##' => $request['user']['username'],
				'##SERVICE_PROVIDER_FIRSTNAME##' => $service_provider['user_profile']['first_name'],
				'##SERVICE_PROVIDER_LASTNAME##' => $service_provider['user_profile']['last_name'],
				'##SERVICENAME##' => $request['service']['name'],
				'##CALENDAR_URL##' => $_server_domain_url.'/users/'.$args['user_id'].'/'.$service_provider['user_profile']['first_name'].'+'.$service_provider['user_profile']['last_name']
			);
			sendMail('New Interest Received', $emailFindReplace, $request['user']['email']);

			if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
					$followMessage = array(
						'message_type' => 'PUSH_NOTIFICATION_FOR_NEW_INTEREST_RECEIVED',
						'request_user_id' => $requestsUser->id
					);
					addPushNotification($request->user_id, $followMessage);
			}
			if (isPluginEnabled('SMS/SMS')) {
				$message = array(
					'request_user_id' => $requestsUser->id,
					'message_type' => 'SMS_FOR_NEW_INTEREST_RECEIVED'
				);
				Models\Sms::sendSMS($message, $request->user_id);
			}

			$result = $requestsUser->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'Requests user could not be added. Please, try again.', $validationErrorFields, 1, 422);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canCreateRequestsUser'));


/**
 * DELETE requestsUsersRequestsUserIdDelete
 * Summary: Delete requests user
 * Notes: Deletes a single requests user based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/requests_users/{requestsUserId}', function($request, $response, $args) {
	global $authUser;
	$requestsUser = Models\RequestsUser::find($request->getAttribute('requestsUserId'));
	$result = array();
	if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $requestsUser->user_id != $authUser->id) {
        return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
    }	
	try {
		if (!empty($requestsUser)) {
			$requestsUser->delete();
			$result = array(
				'status' => 'success',
			);
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'No record found', '', 1, 404);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canDeleteRequestsUser'));


/**
 * GET requestsUsersRequestsUserIdGet
 * Summary: Fetch requests user
 * Notes: Returns a requests user based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/requests_users/{requestsUserId}', function($request, $response, $args) {
	$result = array();
	$queryParams = $request->getQueryParams();
	try {
		$requestsUser = Models\RequestsUser::Filter($queryParams)->find($request->getAttribute('requestsUserId'));
		if (!empty($requestsUser)) {
			$result['data'] = $requestsUser;
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'Response Not Found', '', 1, 404);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'Response Not Found,Please try again.', $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canViewRequestsUser'));

$app->GET('/api/v1/requests/{requestId}/requests_users', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$requestsUsers = Models\RequestsUser::Filter($queryParams)->where('request_id', $request->getAttribute('requestId'))->paginate()->toArray();
		$data = $requestsUsers['data'];
		unset($requestsUsers['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $requestsUsers
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
})->add(new ACL('canListRequestRequestsUser'));

?>

<?php
/**
 * GET userSearchesGet
 * Summary: Fetch all user searches
 * Notes: Returns all user searches from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_searches', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$userSearches = Models\UserSearch::Filter($queryParams)->paginate()->toArray();
		$data = $userSearches['data'];
		unset($userSearches['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $userSearches
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
})->add(new ACL('canListUserSearch'));

$app->GET('/api/v1/me/user_searches', function($request, $response, $args) {
    global $authUser;
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$userSearches = Models\UserSearch::Filter($queryParams)->where('user_id', $authUser->id)->paginate()->toArray();
		$data = $userSearches['data'];
		unset($userSearches['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $userSearches
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
})->add(new ACL('canListOwnUserSearch'));

/**
 * POST userSearchesPost
 * Summary: Creates a new user search
 * Notes: Creates a new user search
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/user_searches', function($request, $response, $args) {
    global $authUser;
	$args = $request->getParsedBody();
    $args['user_id'] = $authUser->id;	    
    if (!empty($args['form_field_submissions'])) {
        $args['form_field_submissions'] = $args['form_field_submissions'];
    }
	$userSearch = new Models\UserSearch($args);
	$result = array();
	try {
		$validationErrorFields = $userSearch->validate($args);
		if (empty($validationErrorFields)) {
			$userSearch->save();
			$result = $userSearch->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'User search could not be added. Please, try again.', $validationErrorFields, 1, 422);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canCreateUserSearch'));


/**
 * DELETE userSearchesUserSearchIdDelete
 * Summary: Delete user search
 * Notes: Deletes a single user search based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/user_searches/{userSearchId}', function($request, $response, $args) {
    global $authUser;
	$userSearch = Models\UserSearch::find($request->getAttribute('userSearchId'));
	$result = array();
	try {
		if (!empty($userSearch)) {  
            if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $userSearch->user_id != $authUser->id) {
                return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
            }
			$userSearch->delete();
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
})->add(new ACL('canDeleteUserSearch'));


/**
 * GET userSearchesUserSearchIdGet
 * Summary: Fetch user search
 * Notes: Returns a user search based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_searches/{userSearchId}', function($request, $response, $args) {
    global $authUser;
	$result = array();
	$queryParams = $request->getQueryParams();
	try {
		$userSearch = Models\UserSearch::Filter($queryParams)->find($request->getAttribute('userSearchId'));    
		if (!empty($userSearch)) {
			if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $userSearch->user_id != $authUser->id) {
				return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
			}	        
			$result['data'] = $userSearch;
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'User Search not found', '', 1, 404);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'User Search not found.Please try again.',  $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canViewUserSearch'));


/**
 * PUT userSearchesUserSearchIdPut
 * Summary: Update user search by its id
 * Notes: Update user search by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/user_searches/{userSearchId}', function($request, $response, $args) {
	global $authUser;
	$args = $request->getParsedBody();
	$userSearch = Models\UserSearch::find($request->getAttribute('userSearchId'));
    if (!empty($userSearch)) {    
        if ($authUser->role_id != \Constants\ConstUserTypes::Admin && $userSearch->user_id != $authUser->id) {
            return renderWithJson($result, 'Authentication failed! Please try again!', '', '', 1, 401);
        }	
        if (!empty($args['form_field_submissions'])) {
            $args['form_field_submissions'] = $args['form_field_submissions'];
        }
        $userSearch->fill($args);
        $result = array();
        try {
            $validationErrorFields = $userSearch->validate($args);
            if (empty($validationErrorFields)) {
                $userSearch->save();
                $result = $userSearch->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'User search could not be updated. Please, try again.', $validationErrorFields, 1, 422);
            }
        } catch(Exception $e) {
            return renderWithJson($result, $e->getMessage(), '', 1, 422);
        }
    }else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
})->add(new ACL('canUpdateUserSearch'));


/**
 * GET userSearchNotificationLogsGet
 * Summary: Fetch all user search notification logs
 * Notes: Returns all user search notification logs from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_search_notification_logs', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$userSearchNotificationLogs = Models\UserSearchNotificationLog::Filter($queryParams)->paginate()->toArray();
		$data = $userSearchNotificationLogs['data'];
		unset($userSearchNotificationLogs['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $userSearchNotificationLogs
		);
		return renderWithJson($results);
	} catch(Exception $e) {
		return renderWithJson($results, $e->getMessage(), $fields = '', $isError = 1, 422);
	}
})->add(new ACL('canListUserSearchNotificationLog'));


/**
 * POST userSearchNotificationLogsPost
 * Summary: Creates a new user search notification log
 * Notes: Creates a new user search notification log
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/user_search_notification_logs', function($request, $response, $args) {
	$args = $request->getParsedBody();
	$userSearchNotificationLog = new Models\UserSearchNotificationLog($args);
	$result = array();
	try {
		$validationErrorFields = $userSearchNotificationLog->validate($args);
		if (empty($validationErrorFields)) {
			$userSearchNotificationLog->save();
			$result = $userSearchNotificationLog->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'User search notification log could not be added. Please, try again.', $validationErrorFields, 1, 422);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canCreateUserSearchNotificationLog'));


/**
 * DELETE userSearchNotificationLogsUserSearchNotificationLogIdDelete
 * Summary: Delete user search notification log
 * Notes: Deletes a single user search notification log based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/user_search_notification_logs/{userSearchNotificationLogId}', function($request, $response, $args) {
	$userSearchNotificationLog = Models\UserSearchNotificationLog::find($request->getAttribute('userSearchNotificationLogId'));
	$result = array();
	try {
		if (!empty($userSearchNotificationLog)) {
			$userSearchNotificationLog->delete();
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
})->add(new ACL('canDeleteUserSearchNotificationLog'));


/**
 * GET userSearchNotificationLogsUserSearchNotificationLogIdGet
 * Summary: Fetch user search notification log
 * Notes: Returns a user search notification log based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/user_search_notification_logs/{userSearchNotificationLogId}', function($request, $response, $args) {
	$result = array();
	$queryParams = $request->getQueryParams();
	try {
		$userSearchNotificationLog = Models\UserSearchNotificationLog::Filter($queryParams)->find($request->getAttribute('userSearchNotificationLogId'));
		if (!empty($userSearchNotificationLog)) {
			$result['data'] = $userSearchNotificationLog;
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'User Search Notification Log not found', '', 1, 404);
		}
	} catch(Exception $e) {
		return renderWithJson($result, 'User Search Notification Log not found.Please try again.', $e->getMessage(), '', 1, 422);
	}		
})->add(new ACL('canViewUserSearchNotificationLog'));


/**
 * PUT userSearchNotificationLogsUserSearchNotificationLogIdPut
 * Summary: Update user search notification log by its id
 * Notes: Update user search notification log by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/user_search_notification_logs/{userSearchNotificationLogId}', function($request, $response, $args) {
	$args = $request->getParsedBody();
	$userSearchNotificationLog = Models\UserSearchNotificationLog::find($request->getAttribute('userSearchNotificationLogId'));
	$userSearchNotificationLog->fill($args);
	$result = array();
	try {
		$validationErrorFields = $userSearchNotificationLog->validate($args);
		if (empty($validationErrorFields)) {
			$userSearchNotificationLog->save();
			$result = $userSearchNotificationLog->toArray();
			return renderWithJson($result);
		} else {
			return renderWithJson($result, 'User search notification log could not be updated. Please, try again.', $validationErrorFields, 1, 422);
		}
	} catch(Exception $e) {
		return renderWithJson($result, $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canUpdateUserSearchNotificationLog'));

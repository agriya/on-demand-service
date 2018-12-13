<?php
/**
 *
 * @package     Homeassistant
 * @copyright   Copyright (c) 2012 {@link http://www.agriya.com/ Agriya Infoway}
 * @license     http://www.agriya.com/ Agriya Infoway Licence
 * @since       2017-01-02
 *
 */
/**
 * DELETE blockedUsersBlockedUserIdDelete
 * Summary: Delete blocked user
 * Notes: Deletes a single blocked user based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/blocked_users/{blockedUserId}', function($request, $response, $args) {
	global $authUser;
    $blockedUser = Models\BlockedUser::find($request->getAttribute('blockedUserId'));
    $result = array();
    if(!empty($blockedUser) && ($blockedUser->user_id == $authUser->id || $authUser->role_id == \constants\ConstUserTypes::Admin)) {
        try {
            if($blockedUser->delete()){
                //Models\User::find($blockedUser->blocked_user_id)->decrement('blocked_user_count', 1); 
            }
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        }
        catch(Exception $e) {
            return renderWithJson($result, 'Blocked user could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Blocked user already deleted. Please, try again.', '', '', 1, 422);
    }
})->add(new ACL('canDeleteBlockedUser'));

/**
 * GET blockedUsersBlockedUserIdGet
 * Summary: Fetch blocked user
 * Notes: Returns a blocked user based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/blocked_users/{blockedUserId}', function($request, $response, $args) {
	$result = array();
    $queryParams = $request->getQueryParams();
    try {
        $blockedUser = Models\BlockedUser::Filter($queryParams)->find($request->getAttribute('blockedUserId'));
        if(!empty($blockedUser)) {
            $result['data'] = $blockedUser->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch(Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewBlockedUser'));

/**
 * GET blockedUsersGet
 * Summary: Fetch all blocked users
 * Notes: Returns all blocked users from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/blocked_users', function($request, $response, $args) {
	$queryParams = $request->getQueryParams();
	$results = array();
	try {
		$blockedUsers = Models\BlockedUser::Filter($queryParams)->paginate()->toArray();
		$data = $blockedUsers['data'];
		unset($blockedUsers['data']);
		$results = array(
			'data' => $data,
			'_metadata' => $blockedUsers
		);
		return renderWithJson($results);
	}
	catch(Exception $e) {
		return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
	}
})->add(new ACL('canListBlockedUser'));


/**
 * POST blockedUsersPost
 * Summary: Creates a new blocked user
 * Notes: Creates a new blocked user
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/blocked_users', function($request, $response, $args) {
    global $authUser;
	$args = $request->getParsedBody();
	$blockedUser = new Models\BlockedUser($args);
    $blockedUser->user_id =  $authUser->id;
    $result = array();
    if(!Models\BlockedUser::checkAlreadyUserBlocked($blockedUser->blocked_user_id, $blockedUser->user_id)) {
        try {
            $validationErrorFields = $blockedUser->validate($args);
            if (empty($validationErrorFields)) {
                if($blockedUser->save()){
	                //Models\User::find($blockedUser->blocked_user_id)->increment('blocked_user_count', 1); 
                }
                $result['data'] = $blockedUser->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Blocked user could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
            }
        }
        catch(Exception $e) {
             return renderWithJson($result, 'Blocked user could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Blocked user already added. Please, try again.', '', '', 1, 422);
    }
})->add(new ACL('canCreateBlockedUser'));
/**
 * GET usersUserIdBlockedUsersGet
 * Summary: Fetch all blocked users
 * Notes: Returns all blocked users from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/users/{userId}/blocked_users', function($request, $response, $args) {
	global $authUser;
    $queryParams = $request->getQueryParams();
	$results = array();
	if($request->getAttribute('userId') != $authUser->id) {
        return renderWithJson($results, 'Invalid user', '', '', 1, 422);
    } else {
        try {           
            $blockedUsers = Models\BlockedUser::where('user_id', $authUser->id)->Filter($queryParams)->paginate()->toArray();
            $data = $blockedUsers['data'];
            unset($blockedUsers['data']);
            $results = array(
                'data' => $data,
                '_metadata' => $blockedUsers
            );
            return renderWithJson($results);
        }
        catch(Exception $e) {
            return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
        }
    }
})->add(new ACL('canUserListBlockedUser'));

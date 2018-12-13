<?php
//user_educations
//post
$app->POST('/api/v1/user_favorites', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    if (!empty($args['username'])) {
        try {
            $getUser = Models\User::where('username', $args['username'])->first();
            if (!empty($getUser)) {
                $checkUser = Models\UserFavorite::where('user_id', $args['user_id'])->where('provider_user_id', $getUser->id)->first();
                if (empty($checkUser)) {
                    $args['provider_user_id'] = $getUser->id;
                    $user_favorites = new Models\UserFavorite($args);
                    $validationErrorFields = $user_favorites->validate($args);
                    if (empty($validationErrorFields)) {
                        if ($user_favorites->save()) {
                            $result['data'] = $user_favorites->toArray();
                            return renderWithJson($result);
                        } else {
                            return renderWithJson($result, 'UserFavorite could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
                        }
                    } else {
                        return renderWithJson($result, 'UserFavorite could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
                    }
                } else {
                    return renderWithJson($result, 'This user already added.', '', '', 1, 422);
                }
            } else {
                return renderWithJson($result, 'User not found.', '', 1, 404);
            }
        } catch (Exception $e) {
            return renderWithJson($result, 'User could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'User not found.', '', '', 1, 404);
    }
})->add(new ACL('canCreateUserFavorite'));
//get all
$app->GET('/api/v1/user_favorites', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $user_favorites = Models\UserFavorite::Filter($queryParams)->paginate()->toArray();
        $data = $user_favorites['data'];
        unset($user_favorites['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $user_favorites
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canListUserFavorite'));
//get single
$app->GET('/api/v1/user_favorites/{userFavoriteId}', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        $user_favorites = Models\UserFavorite::Filter($queryParams)->find($request->getAttribute('userFavoriteId'));
        if (!empty($user_favorites)) {
            $result['data'] = $user_favorites->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'UserFavorite could not found.', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'UserFavorite not found. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canViewUserFavorite'));
//delete
$app->DELETE('/api/v1/user_favorites/{userFavoriteId}', function ($request, $response, $args) {
    global $authUser;
    $user_favorites = Models\UserFavorite::find($request->getAttribute('userFavoriteId'));
    if ($authUser->role_id != Constants\ConstUserTypes::Admin) {
        $user_favorites = Models\UserFavorite::where('id', $request->getAttribute('userFavoriteId'))->where('user_id', $authUser->id)->first();
    }
    $result = array();
    try {
        if (!empty($user_favorites)) {
            if ($user_favorites->delete()) {
                $result = array(
                    'status' => 'success',
                );
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'UserFavorite could not be deleted. Please, try again', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Sorry! No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'UserFavorite could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteUserFavorite'));

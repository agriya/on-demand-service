<?php
/**
 *
 * @package         Base
 * @copyright   Copyright (c) 2012 {@link http://www.agriya.com/ Agriya Infoway}
 * @license         http://www.agriya.com/ Agriya Infoway Licence
 * @since       2017-01-02
 *
 */
/**
 * GET Review Get
 * Summary: all Review
 * Notes: all Review
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/reviews', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    try {
        global $capsule;
        $reviews = Models\Review::Filter($queryParams)->paginate()->toArray();
        $data = $reviews['data'];
        unset($reviews['data']);
        $result = array(
            'data' => $data,
            '_metadata' => $reviews
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * POST Review POST
 * Summary:Post Review
 * Notes:  Post Review
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/reviews', function ($request, $response, $args) {
    global $authUser;
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    $modelName = 'Models' . '\\' . $args['class'];
    $appointment = $modelName::find($args['foreign_id']);
    if (!empty($appointment) && !empty($authUser['id']) && $args['class'] == 'Appointment') {
        if ($authUser['id'] == $appointment['user_id']) {
            $args['user_id'] = $appointment['user_id'];
            $args['to_user_id'] = $appointment['provider_user_id'];
        } elseif ($authUser['id'] == $appointment['provider_user_id']) {
            $args['user_id'] = $appointment['provider_user_id'];
            $args['to_user_id'] = $appointment['user_id'];
            $args['is_reviewed_as_service_provider'] = 1;
        } elseif ($authUser['role_id'] != \Constants\ConstUserTypes::Admin) {
            return renderWithJson($result, 'Not permission to review. ', '', '', 1, 422);
        }
    }
    try {
        $reviews = new Models\Review($args);
        $validationErrorFields = $reviews->validate($args);
        if (empty($validationErrorFields)) {
            /*$reviews->model_id = $args['foreign_id'];
            if (in_array($reviews->class, ['Appointment'])) {
                $reviews->model_class = 'Appointment';
            }*/
            $checkReviewCount = Models\Review::where('user_id', $reviews->user_id)->where('to_user_id', $reviews->to_user_id)->where('class', $reviews->class)->where('foreign_id', $reviews->foreign_id)->count();
            if (!$checkReviewCount) {
                $reviews->save();
                if ($args['class'] == 'Appointment' && empty($reviews->is_reviewed_as_service_provider)) {
                    if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                        $followMessage = array(
                            'message_type' => 'PUSH_NOTIFICATION_FOR_REVIEW_POSTED_BY_REQUESTOR',
                            'appointment_id' => $appointment->id
                        );
                        addPushNotification($appointment->provider_user_id, $followMessage);
                    }
                    if (isPluginEnabled('SMS/SMS')) {
                        $message = array(
                            'appointment_id' => $appointment->id,
                            'message_type' => 'SMS_FOR_REVIEW_POSTED_BY_REQUESTOR'
                        );
                        Models\Sms::sendSMS($message, $appointment->provider_user_id);
                    }                    
                }
                if ($args['class'] == 'Appointment' && !empty($reviews->is_reviewed_as_service_provider)) {
                    Models\Appointment::where('id', $args['foreign_id'])->update(array('is_review_posted' => 1));
                }
                $result['data'] = $reviews->toArray();
                return renderWithJson($result);
            } else {
                return renderWithJson($result, 'Already reviewed', '', '', 1, 422);
            }
        } else {
            return renderWithJson($result, 'Reviews could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Reviews could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateReview'));
/**
 * DELETE ReviewIdDelete
 * Summary: Delete Review
 * Notes: Deletes a single Review based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/reviews/{reviewId}', function ($request, $response, $args) {
    $reviews = Models\Review::find($request->getAttribute('reviewId'));
    try {
        if ($reviews->delete()) {
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Review could not be deleted. Please, try again.', '', '', 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Review could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteReview'));
/**
 * GET ReviewId get
 * Summary: Fetch a Review based on Review Id
 * Notes: Returns a Review from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/reviews/{reviewId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $reviews = Models\Review::Filter($queryParams)->find($request->getAttribute('reviewId'));
    $result['data'] = $reviews->toArray();
    return renderWithJson($result);
});
/**
 * PUT Review Review IdPut
 * Summary: UpdateReview details
 * Notes: Update Review
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/reviews/{reviewId}', function ($request, $response, $args) {
    global $authUser;
    $args = $request->getParsedBody();
    $result = array();
    $reviews = Models\Review::find($request->getAttribute('reviewId'));
    if ($authUser['id'] != $reviews['user_id'] && $authUser['role_id'] != \Constants\ConstUserTypes::Admin) {
        return renderWithJson($result, 'Not permission to review. ', '', '', 1, 422);
    }
    $validationErrorFields = $reviews->validate($args);
    if (empty($validationErrorFields)) {
        $reviews->fill($args);
        try {
            $reviews->save();
            $result['data'] = $reviews->toArray();
            return renderWithJson($result);
        } catch (Exception $e) {
            return renderWithJson($result, 'Review could not be updated. Please, try again', $e->getMessage(), '', 1, 422);
        }
    } else {
        return renderWithJson($result, 'Review could not be updated. Please, try again', '', $validationErrorFields, 1, 422);
    }
})->add(new ACL('canUpdateReview'));

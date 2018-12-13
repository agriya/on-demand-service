<?php
/**
 *
 * @package         Getlancer
 * @copyright   Copyright (c) 2012 {@link http://www.agriya.com/ Agriya Infoway}
 * @license         http://www.agriya.com/ Agriya Infoway Licence
 * @since       2017-01-02
 *
 */
/**
 * GET messagesGet
 * Summary: Fetch all messages
 * Notes: Returns all messages from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/messages', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $messages = Models\Message::where('parent_id', 0)->Filter($queryParams)->paginate()->toArray();
        $data = $messages['data'];
        unset($messages['data']);
        if ($data) {
            foreach ($data as $key => $record) {
                if ((empty($authUser) && !empty($record['is_private'])) || (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin && $authUser['id'] != $record['user_id'] && $authUser['id'] != $record['other_user_id'] && !empty($record['is_private']))) {
                    $data[$key]['message_content']['subject'] = '[Private Message]';
                    $data[$key]['message_content']['message'] = '[Private Message]';
                }
                $childrenMessage = Models\Message::setChildPrivateMessage($authUser, $record['children']);
                $data[$key]['children'] = $childrenMessage;
            }
        }
        $results = array(
            'data' => $data,
            '_metadata' => $messages
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'No record found', $e->getMessage(), '', 1, 422);
    }
});
/**
 * GET messagesMessageIdGet
 * Summary: Fetch message
 * Notes: Returns a message based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/messages/{messageId}', function ($request, $response, $args) {
    global $authUser;
    $queryParams = $request->getQueryParams();
    $result = array();
    $message = Models\Message::Filter($queryParams)->find($request->getAttribute('messageId'));
    if (!empty($message)) {
        $message = $message->toArray();
        if ((empty($authUser) && !empty($message['is_private'])) || (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin && $authUser['id'] != $message['user_id'] && $authUser['id'] != $message['other_user_id'] && !empty($message['is_private']))) {
            $message['message_content']['subject'] = '[Private Message]';
            $message['message_content']['message'] = '[Private Message]';
        }
        $childrenMessage = Models\Message::setChildPrivateMessage($authUser, $message['children']);
        $message['children'] = $childrenMessage;
        $result['data'] = $message;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', '', 1, 404);
    }
});
/**
 * POST messagesPost
 * Summary: Creates a new message
 * Notes: Creates a new message
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/messages', function ($request, $response, $args) {
    global $authUser;
    global $_server_domain_url;
    $args = $request->getParsedBody();
    $message = new Models\Message($args);
    $result = array();
    try {
        if (!empty($args['class']) && ($args['class'] == 'Appointment')) {
            $Appointment = Models\Appointment::with('service')->where('id', $args['foreign_id'])->first();
            if (!empty($Appointment)) {
                $now = strtotime(date('Y-m-d H:i:s'));
                $created = strtotime($Appointment->created_at);
                $interval = abs($now - $created);
                $minutes = round($interval / 60);
                if ($authUser->id == $Appointment->provider_user_id && $Appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Enquiry && $Appointment->first_response_time == null) {
                    $Appointment->first_response_time = $minutes;
                } elseif (!empty(isPluginEnabled('PaymentBooking/PaymentBooking')) && $authUser->id == $Appointment->provider_user_id && $Appointment->first_response_time == null) {
                    $now = strtotime(date('Y-m-d H:i:s'));
                    $created = strtotime($Appointment->paid_escrow_amount_at);
                    $interval = abs($now - $created);
                    $escrow_minutes = round($interval / 60);
                    $Appointment->first_response_time = $escrow_minutes;
                } elseif ($authUser->id == $Appointment->provider_user_id && $Appointment->first_response_time == null) {
                    $Appointment->first_response_time = $minutes;
                }
            }
            $Appointment->save();
            /*$result = $Appointment->toArray();
             return renderWithJson($result);*/
        }
        if (!empty($args['parent_id'])) {
            $message = Models\Message::where('id', $args['parent_id'])->select('depth')->first();
            if (!empty($message) && (empty(MESSAGE_THREAD_MAX_DEPTH) || $message->depth < MESSAGE_THREAD_MAX_DEPTH)) {
                $depthFlag = 1;
            }
            if (empty($depthFlag)) {
                return renderWithJson($result, 'Your not eligible to reply this message.', '', 1);
            }
        }
        $message->user_id = $authUser['id'];
        $validationErrorFields = $message->validate($args);
        if (empty($validationErrorFields)) {
            $messageContent = new Models\MessageContent;
            $messageContent->message = $args['message'];
            $messageContent->subject = $args['subject'];
            $depth = 0;
            $path = '';
            $messageContent->save();
            $parentId = 0;
            if (!empty($args['parent_id'])) {
                $parentId = $args['parent_id'];
                $parentMessage = Models\Message::where('id', $args['parent_id'])->select('id', 'depth', 'materialized_path', 'is_private', 'message_content_id')->first();
                if (!empty($parentMessage)) {
                    $depth = $parentMessage->id;
                    $path = $parentMessage->materialized_path;
                }
            }
            if (!empty($args['image']['attachment'])) {
                saveImage('MessageContent', $args['image']['attachment'], $messageContent->id);
            }
            $privateStatus = $otherUserId = $parentPrivateStatus = 0;
            $modelId = $args['foreign_id'];
            if (!empty($args['class']) && ($args['class'] == 'Appointment')) {
                $privateStatus = 1;
                if ($authUser->id == $Appointment->user_id) {
                    $otherUserId = $Appointment->provider_user_id;
                } elseif ($authUser->id == $Appointment->provider_user_id) {
                    $otherUserId = $Appointment->user_id;
                }
                if (!empty($args['parent_id']) && !empty($parentMessage)) {
                    $privateStatus = $parentMessage->is_private;
                }
            }
            $senderMessageId = saveMessage($depth, $path, $authUser->id, $otherUserId, $messageContent->id, $parentId, $args['class'], $args['foreign_id'], 1, $modelId, $privateStatus);
            if (!empty($privateStatus)) {
                if (!empty($parentId)) {
                    $otherMessageId = Models\Message::where('id', '!=', $parentId)->where('message_content_id', $parentMessage->message_content_id)->select('id')->first();
                    if (!empty($otherMessageId)) {
                        $parentId = $otherMessageId->id;
                        $receiverMessageId = saveMessage($depth, $path, $authUser->id, $otherUserId, $messageContent->id, $parentId, $args['class'], $args['foreign_id'], 1, $modelId, $privateStatus);
                    }
                } else {
                    $receiverMessageId = saveMessage($depth, $path, $otherUserId, $authUser->id, $messageContent->id, $parentId, $args['class'], $args['foreign_id'], 0, $modelId, $privateStatus);
                }
                if (!empty($otherUserId)) {
                    $otherUserDetails = Models\User::with('user_profile')->find($otherUserId);
                    $user = Models\User::with('user_profile')->find($authUser->id);
                    if (!empty($otherUserDetails->user_profile->first_name) || !empty($otherUserDetails->user_profile->last_name)) {
                        $other_username = $otherUserDetails->user_profile->first_name .' '.$otherUserDetails->user_profile->last_name;
                    } else {
                        $other_username = $otherUserDetails->email; 
                    }  
                    if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
                        $username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
                    } else {
                        $username = $user->email; 
                    }                                         
                    if (!empty($args['class']) && ($args['class'] == 'Appointment')) {
                        $emailFindReplace = array(
                            '##OTHERUSERNAME##' => $other_username,
                            '##USERNAME##' => $username,
                            '##SERVICENAME##' => $Appointment['service']['name'],
                            '##MESSAGE##' => $args['message'],
                            '##APPOINTMENT_DATE##' => $Appointment->appointment_from_date,
                            '##MESSAGE_LINK##' => $_server_domain_url . '/booking/' . $Appointment->id
                        );
                        sendMail('New Message', $emailFindReplace, $otherUserDetails->email);
                    }
                }
            }
            $allowed_class = array(
                'Appointment'
            );
            $enabledIncludes = array(
                'user',
                'foreign_message',
                'children',
                'attachment',
                'message_content'
            );
            if (!empty($args['class']) && ($args['class'] == 'Appointment')) {
                if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                    $followMessage = array(
                        'message_type' => 'PUSH_NOTIFICATION_FOR_NEW_MESSAGE_RECEIVED',
                        'appointment_id' => $args['foreign_id']
                    );
                    addPushNotification($message->other_user_id, $followMessage);
                }
                $appointment = Models\Appointment::where('id', $args['foreign_id'])->first();
                if (!empty($appointment) && $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::PendingApproval) {
                    if(isPluginEnabled('SMS/SMS'))
                    {
                        $message = array(
                            'appointment_id' => $args['foreign_id'],
                            'message_type' => 'SMS_FOR_NEW_MESSAGE_RECEIVED'
                        );
                        Models\Sms::sendSMS($message, $message->other_user_id);
                    }
                    if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                            $followMessage = array(
                            'message_type' => 'PUSH_NOTIFICATION_FOR_NEW_MESSAGE_RECEIVED',
                            'appointment_id' => $args['foreign_id']
                        );
                        addPushNotification($message->other_user_id, $followMessage);
                    }
                }
            }
            $result = Models\Message::with($enabledIncludes)->find($senderMessageId)->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Message could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Message could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateMessage'));

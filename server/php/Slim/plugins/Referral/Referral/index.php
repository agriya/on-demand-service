<?php
$app->POST('/api/v1/invite_friends', function ($request, $response, $args) {
    global $authUser, $_server_domain_url;
    $args = $request->getParsedBody();
    $result = array();
    $user = Models\User::with('user_profile')->find($authUser->id);
    if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
        $username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
    } else {
       $username = $user->email; 
    }
    if (!empty($args['contacts'])) {
        foreach ($args['contacts'] as $contact) {
            $user_count = Models\User::where('email', $contact['email'])->count();
            if ($user_count == 0) {
                $emailFindReplace = array(
                    '##NAME##' => $contact['name'],
                    '##MESSAGE##' => $args['message'],
                    '##INVITED_USER_NAME##' => $username,
                    '##INVITE_URL##' => $_server_domain_url . '/referrals/' . $user->reference_code
                );
                sendMail('Invite Friends', $emailFindReplace, $contact['email']);
            }
        }
        return renderWithJson($result, 'Invitation sent successfully');
    }
})->add(new ACL('canInviteFriends'));

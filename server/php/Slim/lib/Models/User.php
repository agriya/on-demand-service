<?php
/**
 * User
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class User extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $casts = [
        'role_id' => 'integer',
        'available_wallet_amount' => 'double',
        'blocked_amount' => 'double',
        'user_login_count' => 'integer',
        'is_agree_terms_conditions' => 'integer',
        'is_active' => 'integer',
        'is_email_confirmed' => 'integer',
        'activate_hash' => 'integer',
        'city_id' => 'integer',
        'state_id' => 'integer',
        'country_id' => 'integer',
        'referred_by_user_id' => 'integer',
        'affiliate_pending_amount' => 'double',
        'affiliate_paid_amount' => 'double',
        'is_profile_updated' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'review_count_as_service_provider' => 'integer',
        'total_rating_as_service_provider' => 'integer',
        'review_count_as_employer' => 'integer',
        'total_rating_as_employer' => 'integer',
        'average_rating_as_service_provider' => 'double', 
        'average_rating_as_employer' => 'double',
        'is_deleted' => 'integer',
        'account_close_reason_id' => 'integer',
        'is_email_subscribed' => 'integer',
        'is_sms_notification' => 'integer',
        'mobile_number_verification_otp' => 'integer',
        'is_mobile_number_verified' => 'integer',
        'is_app_device_available' => 'integer',
        'appointment_count' => 'integer',
        'category_id' => 'integer',        
        'is_push_notification_enabled' => 'integer'                                                     
    ];     
    // Admin scope
    protected $scopes_1 = array();
    // User scope
    protected $scopes_2 = array(
        'canCreateSubscription',
        'canCreateUserEducation',
        'canListUserEducation',
        'canUpdateUserEducation',
        'canViewUserEducation',
        'canDeleteUserEducation',
        'canCreateUserFavorite',
        'canListUserFavorite',
        'canViewUserFavorite',
        'canDeleteUserFavorite',
        'canCreateAnswer',
        'canListAnswer',
        'canUpdateAnswer',
        'canViewAnswer',
        'canDeleteAnswer',
        'canCreateQuestion',
        'canListQuestion',
        'canUpdateQuestion',
        'canViewQuestion',
        'canDeleteQuestion',
        'canUpdateUserProfile',
        'canViewUserProfile',
        'canChangePassword',
        'canBookAppointments',
        'canGetOwnAppointments',
        'canUpdateAppointments',
        'canViewUser',
        'canCreateOrder',
        'canUpdateAppointmentStatus',
        'canListMeServicesUser',
        'canCreateReview',
        'canUpdateReview',
        'canViewQuiz',
        'canListQuiz',
        'canListAccountCloseReason',
        'canCreateMessage',
        'canInviteFriends',
        'canListQuizQuestion',
        'canViewQuizQuestion',
        'canListQuizQuestionAnswerOption',
        'canViewQuizQuestionAnswerOption',
        'canListAllTransactions',
        'canListUserTransactions',
        'canGetOtherUsers',
        'canUpdateAppointmentSetting',
        'canViewAppointmentSetting',
        'canViewAppointments',
        'canUserListMoneyTransferAccount',
        'canCreateMoneyTransferAccount',
        'canUserCreateMoneyTransferAccount',
        'canViewMoneyTransferAccount',
        'canUserViewMoneyTransferAccount',
        'canUpdateMoneyTransferAccount',
        'canUserUpdateMoneyTransferAccount',
        'canDeleteMoneyTransferAccount',
        'canUserDeleteMoneyTransferAccount',
        'canCreateApnsDevice',
        'canCreateResendOtp',
        'canVerifyOtp',
        'canResendActvationLink',
        'canUserCreateUserCashWithdrawals',
        'canUserViewUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canDeleteAttachment',
        'canViewUserCreditCard',
        'canDeleteUserCreditCard',
        'canUpdateUserCreditCard',
        'canCreateUserCreditCard' ,
        'canListMeUserCreditCard',
        'canCreateRequest',
        'canGetMeRequest',
        'canUpdateRequest',
        'canCreateRequestsUser',
        'canListRequestRequestsUser' ,
        'canListOwnUserSearch',
        'canCreateUserSearch',
        'canDeleteUserSearch',
        'canUpdateUserSearch',
        'canViewUserSearch',
        'canDeleteBlockedUser',
        'canViewBlockedUser',
        'canListBlockedUser',
        'canCreateBlockedUser',
        'canUserListBlockedUser',
        'canDeleteRequestsUser'
    );
    //Service provider scope
    protected $scopes_3 = array(
        'canCreateAppointmentModifications',
        'canUpdateAppointmentModifications',
        'canViewAppointmentModifications',
        'canDeleteAppointmentModifications',
        'canGetMeAppointmentModifications',        
        'canCreateSubscription',
        'canCreateUserEducation',
        'canListUserEducation',
        'canUpdateUserEducation',
        'canViewUserEducation',
        'canDeleteUserEducation',
        'canCreateUserFavorite',
        'canListUserFavorite',
        'canViewUserFavorite',
        'canDeleteUserFavorite',
        'canCreateAnswer',
        'canListAnswer',
        'canUpdateAnswer',
        'canViewAnswer',
        'canDeleteAnswer',
        'canCreateQuestion',
        'canListQuestion',
        'canUpdateQuestion',
        'canViewQuestion',
        'canDeleteQuestion',
        'canUpdateUserProfile',
        'canViewUserProfile',
        'canChangePassword',
        'canBookAppointments',
        'canGetOwnAppointments',
        'canUpdateAppointments',
        'canViewUser',
        'canCreateFormField',
        'canDeleteFormField',
        'canViewFormField',
        'canUpdateFormField',
        'canCreateFormFieldGroup',
        'canUpdateFormFieldGroup',
        'canCreateOrder',
        'canUpdateAppointmentStatus',
        'canListMeServicesUser',
        'canCreateReview',
        'canUpdateReview',
        'canViewQuiz',
        'canListQuiz',
        'canListAccountCloseReason',
        'canCreateMessage',
        'canInviteFriends',
        'canListQuizQuestion',
        'canViewQuizQuestion',
        'canListQuizQuestionAnswerOption',
        'canViewQuizQuestionAnswerOption',
        'canListAllTransactions',
        'canListUserTransactions',
        'canGetOtherUsers',
        'canUpdateAppointmentSetting',
        'canViewAppointmentSetting',
        'canViewAppointments',
        'canUserListMoneyTransferAccount',
        'canCreateMoneyTransferAccount',
        'canUserCreateMoneyTransferAccount',
        'canViewMoneyTransferAccount',
        'canUserViewMoneyTransferAccount',
        'canUpdateMoneyTransferAccount',
        'canUserUpdateMoneyTransferAccount',
        'canDeleteMoneyTransferAccount',
        'canUserDeleteMoneyTransferAccount',
        'canCreateApnsDevice',
        'canCreateResendOtp',
        'canVerifyOtp',
        'canResendActvationLink',
        'canUserCreateUserCashWithdrawals',
        'canUserViewUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canDeleteAttachment',
        'canCreateAppointmentModificationsMultiple',
        'canDeleteAppointmentModificationsMultiple',
        'canViewUserCreditCard',
        'canDeleteUserCreditCard',
        'canUpdateUserCreditCard',
        'canCreateUserCreditCard',
        'canListMeUserCreditCard',
        'canCreateRequest',
        'canGetMeRequest',
        'canUpdateRequest',
        'canCreateRequestsUser',
        'canListRequestRequestsUser',
        'canListOwnUserSearch' ,
        'canCreateUserSearch',
        'canDeleteUserSearch',
        'canUpdateUserSearch',
        'canViewUserSearch',
        'canDeleteBlockedUser',
        'canViewBlockedUser',
        'canListBlockedUser',
        'canCreateBlockedUser',
        'canUserListBlockedUser',
        'canDeleteRequestsUser'     
    );
    //User scope
    protected $scopes_4 = array(
        'canCreateSubscription',
        'canCreateUserEducation',
        'canListUserEducation',
        'canUpdateUserEducation',
        'canViewUserEducation',
        'canDeleteUserEducation',
        'canCreateUserFavorite',
        'canListUserFavorite',
        'canViewUserFavorite',
        'canDeleteUserFavorite',
        'canCreateAnswer',
        'canListAnswer',
        'canUpdateAnswer',
        'canViewAnswer',
        'canDeleteAnswer',
        'canCreateQuestion',
        'canListQuestion',
        'canUpdateQuestion',
        'canViewQuestion',
        'canDeleteQuestion',
        'canUpdateUserProfile',
        'canViewUserProfile',
        'canChangePassword',
        'canBookAppointments',
        'canGetOwnAppointments',
        'canUpdateAppointments',
        'canViewUser',
        'canCreateOrder',
        'canUpdateAppointmentStatus',
        'canListMeServicesUser',
        'canCreateReview',
        'canUpdateReview',
        'canViewQuiz',
        'canListQuiz',
        'canListAccountCloseReason',
        'canCreateMessage',
        'canInviteFriends',
        'canListQuizQuestion',
        'canViewQuizQuestion',
        'canListQuizQuestionAnswerOption',
        'canViewQuizQuestionAnswerOption',
        'canListAllTransactions',
        'canListUserTransactions',
        'canGetOtherUsers',
        'canUpdateAppointmentSetting',
        'canViewAppointmentSetting',
        'canViewAppointments',
        'canUserListMoneyTransferAccount',
        'canCreateMoneyTransferAccount',
        'canUserCreateMoneyTransferAccount',
        'canViewMoneyTransferAccount',
        'canUserViewMoneyTransferAccount',
        'canUpdateMoneyTransferAccount',
        'canUserUpdateMoneyTransferAccount',
        'canDeleteMoneyTransferAccount',
        'canUserDeleteMoneyTransferAccount',
        'canCreateApnsDevice',
        'canCreateResendOtp',
        'canVerifyOtp',
        'canResendActvationLink',
        'canUserCreateUserCashWithdrawals',
        'canUserViewUserCashWithdrawals',
        'canUserListUserCashWithdrawals',
        'canDeleteAttachment',
        'canViewUserCreditCard',
        'canDeleteUserCreditCard',
        'canUpdateUserCreditCard',
        'canCreateUserCreditCard',
        'canListMeUserCreditCard',
        'canCreateAppointmentModifications',
        'canUpdateAppointmentModifications',
        'canViewAppointmentModifications',
        'canDeleteAppointmentModifications',
        'canGetMeAppointmentModifications',
        'canCreateFormField',
        'canDeleteFormField',
        'canViewFormField',
        'canUpdateFormField',
        'canCreateFormFieldGroup',
        'canUpdateFormFieldGroup',
        'canCreateAppointmentModificationsMultiple',
        'canDeleteAppointmentModificationsMultiple',
        'canCreateRequest',
        'canGetMeRequest',
        'canUpdateRequest',
        'canCreateRequestsUser',
        'canListRequestRequestsUser',
        'canListOwnUserSearch',
        'canCreateUserSearch',
        'canDeleteUserSearch',
        'canUpdateUserSearch',
        'canViewUserSearch',
        'canDeleteBlockedUser',
        'canViewBlockedUser',
        'canListBlockedUser',
        'canCreateBlockedUser',
        'canUserListBlockedUser',
        'canDeleteRequestsUser' 
    );
    protected $fillable = array(
        'role_id',
        'username',
        'email',
        'password',
        'available_wallet_amount',
        'blocked_amount',
        'item_count',
        'item_user_count',
        'user_login_count',
        'user_view_count',
        'is_agree_terms_conditions',
        'is_active',
        'is_email_confirmed',
        'is_expiry',
        'mobile',
        'is_featured',
        'category_id',
        'referred_by_user_id',
        'address',
        'address1',
        'city_id',
        'state_id',
        'country_id',
        'postal_code',
        'is_email_subscribed',
        'is_sms_notification',
        'remember_token',
        'latitude',
        'longitude',
        'phone_number',
        'secondary_phone_number',
        'is_deleted',
        'account_close_reason_id',
        'account_close_reason',
        'reference_code',
        'mobile_code',
        'secondary_mobile_code',
        'mobile_number_verification_otp',
        'is_push_notification_enabled',
        'appointment_count',
        'is_mobile_number_verified',
        'request_count'
    );
    public $rules = array(
        'username' => 'sometimes|required',
        'email' => 'sometimes|required|email',
        'password' => 'sometimes|required',
        'confirm_password' => 'sometimes|required',
        'is_agree_terms_conditions' => 'sometimes|required',
        'is_active' => 'sometimes|integer',
        'is_email_confirmed' => 'sometimes|integer',
        'role_id' => 'sometimes|required|integer',
        'city_id' => 'sometimes|required|integer',
        'state_id' => 'sometimes|required|integer',
        'country_id' => 'sometimes|required|integer'
    );
    public $hidden = array(
        'password',
        'mobile_number_verification_otp'
    );
    public function role()
    {
        return $this->belongsTo('Models\Role', 'role_id', 'id');
    }
    public function user_profile()
    {
        return $this->hasOne('Models\UserProfile', 'user_id', 'id');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('class', 'UserAvatar');
    }
    public function category()
    {
        return $this->belongsTo('Models\Category', 'category_id', 'id');
    }
    public function user_favorite()
    {
        return $this->hasMany('Models\UserFavorite', 'user_id', 'id');
    }
    public function user_favorite_for_single_user()
    {
        $user_id = 0;
        global $authUser;
        if (!empty($authUser)) {
            $user_id = $authUser['id'];
        }
        return $this->hasOne('Models\UserFavorite', 'provider_user_id', 'id')->where('user_id', $user_id);
    }
    public function referred_by_user()
    {
        return $this->belongsTo('Models\User', 'referred_by_user_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo('Models\City', 'city_id', 'id');
    }
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
    public function listing_photo()
    {
        return $this->hasMany('Models\Attachment', 'foreign_id', 'id')->where('Class', 'ListingPhoto');
    }
    public function service_users()
    {
        return $this->hasMany('Models\ServiceUser', 'user_id', 'id');
    }
    public function appointment_settings()
    {
        return $this->hasOne('Models\AppointmentSetting', 'user_id', 'id');
    }
    public function appointment_modification()
    {
        return $this->hasMany('Models\AppointmentModification', 'user_id', 'id');
    }
    public function foreigns()
    {
        return $this->morphMany('Models\Attachment', 'foreign');
    }
    public function attachments()
    {
        return $this->belongsTo('Models\User', 'id', 'id')->select('id','id as user_id');
    }
    public function form_field_submission()
    {
        return $this->hasMany('Models\FormFieldSubmission', 'foreign_id', 'id')->where('class','User');
    }    
    public function foreign_transactions()
    {
        return $this->morphMany('Models\Transaction', 'foreign_transaction');
    }  
    public function activity()
    {
        return $this->belongsTo('Models\User', 'id', 'id');
    }  
    public function blocker()
    {
        global $authUser;       
        return $this->hasOne('Models\BlockedUser', 'user_id', 'id')->where('blocked_user_id',$authUser['id']);
    }
    public function blocking()
    {
        global $authUser;
        return $this->hasOne('Models\BlockedUser', 'blocked_user_id', 'id')->where('user_id',$authUser['id']);
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if ((!empty($params['latitude']) && !empty($params['longitude'])) || (!empty($params['listing_ne_latitude']) && !empty($params['listing_ne_longitude']) && !empty($params['listing_sw_latitude']) && !empty($params['listing_sw_longitude']))) {
            $query->leftJoin('user_profiles as user_profiles','user_profiles.user_id','=','users.id');
        }
        if (!empty($params['latitude']) && !empty($params['longitude'])) {
            $radius = 500;
            if (!empty($params['radius'])) {
                $radius = $params['radius'];
            }
             $distance = 'ROUND(( 6371 * acos( cos( radians(' . $params['latitude'] . ') ) * cos( radians( user_profiles.listing_latitude ) ) * cos( radians( user_profiles.listing_longitude ) - radians(' . $params['longitude'] . ')) + sin( radians(' . $params['latitude'] . ') ) * sin( radians( user_profiles.listing_latitude ) ) )))';
            $query->select('*')->selectRaw($distance . ' AS distance')->whereRaw('(' . $distance . ')<=' . $radius);
            if (!empty($params['sort_by_type']) && $params['sort_by_type'] == 'distance') {
                $query->orderBy("distance", "asc");
            }
        }
        if (!empty($params['listing_ne_latitude']) && !empty($params['listing_ne_longitude']) && !empty($params['listing_sw_latitude']) && !empty($params['listing_sw_longitude'])) {
            $lon1 = round($params['listing_sw_longitude'], 6);
            $lon2 = round($params['listing_ne_longitude'], 6);
            $lat1 = round($params['listing_sw_latitude'], 6);
            $lat2 = round($params['listing_ne_latitude'], 6);
            $query->whereBetween('user_profiles.listing_latitude', [$lat1, $lat2])->whereBetween('user_profiles.listing_longitude', [$lon1, $lon2]);
        }
        $provider_user_ids = array();
        if (!empty($params['appointment_from_date']) && !empty($params['appointment_to_date'])) {
            $appoinentment_from_date = date('Y-m-d', strtotime($params['appointment_from_date']));
            $appoinentment_to_date = date('Y-m-d', strtotime($params['appointment_to_date']));
            $appointments = Appointment::whereBetween('appointment_from_date', [$appoinentment_from_date, $appoinentment_to_date])->orWhereBetween('appointment_to_date', [$appoinentment_from_date, $appoinentment_to_date])->get();
            if (!empty($appointments)) {
                foreach ($appointments as $appoinment) {
                    $provider_user_ids[] = $appoinment['provider_user_id'];
                }
                if (!empty($provider_user_ids)) {
                    $query->whereNotIn('id', $provider_user_ids);
                }
            }
        }
        if (!empty($params['request_id'])) {
            $requestUsers = RequestsUser::select('user_id')->where('request_id', $params['request_id'])->get()->toArray();
            $query->whereIn('users.id', $requestUsers);
        }
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('users.email', 'like', "%$search%");
                $q1->orWhereHas('role', function ($q) use ($search) {
                    $q->where('roles.name', 'like', "%$search%");
                });
                $q1->orWhereHas('user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                    $q->orWhere('listing_title', 'like', "%$search%");

                });
                $q1->orWhere('users.phone_number', 'like', "%$search%");
            });
        }
    }
    public function email_conditions($user, $type, $request = array())
    {
        global $_server_domain_url;
        $result = array();
        if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
            $username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
        } else {
            $username = $user->email; 
        }        
        try {
            if (USER_IS_ADMIN_MAIL_AFTER_REGISTER && $type != 'activate') {
                $emailFindReplace = array(
                    '##USERNAME##' => $username,
                    '##EMAIL##' => $user->email
                );
                sendMail('New User Join', $emailFindReplace, SITE_FROM_EMAIL, 'Admin');
            }
            if (USER_IS_EMAIL_VERIFICATION_FOR_REGISTER && ($user->is_email_confirmed == 0)) {
                $activation_link = $_server_domain_url . '/#/users/' . $user->id . '/activate/' . md5($user->id . '-' . \Constants\Security::salt);
                $emailFindReplace = array(
                    '##USERNAME##' => $username,
                    '##ACTIVATION_URL##' => $activation_link,
                    '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,
                    '##FROM_EMAIL##' => SITE_FROM_EMAIL
                );
                sendMail('Activation Request', $emailFindReplace, $user->email);
                return renderWithJson($result, 'You have successfully registered with our site and your activation mail has been sent to your mail inbox.');
            } elseif (USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER && ($user->is_active == 0)) {
                return renderWithJson($result, 'You have successfully registered with our site. After administrator approval you can login to site');
            } elseif (!USER_IS_EMAIL_VERIFICATION_FOR_REGISTER && !USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER && USER_IS_WELCOME_MAIL_AFTER_REGISTER && !USER_IS_AUTO_LOGIN_AFTER_REGISTER) {
                $emailFindReplace = array(
                    '##USERNAME##' => $username,
                    '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,
                    '##FROM_EMAIL##' => SITE_FROM_EMAIL
                );
                sendMail('Welcome Email', $emailFindReplace, $user->email);
                return renderWithJson($result, 'You have successfully registered with our site.');
            } elseif (!USER_IS_EMAIL_VERIFICATION_FOR_REGISTER && !USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER && USER_IS_AUTO_LOGIN_AFTER_REGISTER && USER_IS_WELCOME_MAIL_AFTER_REGISTER) {
                $emailFindReplace = array(
                    '##USERNAME##' => $username,
                    '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,
                    '##FROM_EMAIL##' => SITE_FROM_EMAIL
                );
                sendMail('Welcome Email', $emailFindReplace, $user->email);
                $token = array(
                    'token' => getToken($user->id)
                );
                insertUserToken($user->id, $token['token'], $request);
                User::save_user_login($request);
                $token = $token + $user->toArray();
                return renderWithJson($token, 'You have successfully registered with our site.');
            } elseif (USER_IS_AUTO_LOGIN_AFTER_REGISTER && USER_IS_WELCOME_MAIL_AFTER_REGISTER) {
                $emailFindReplace = array(
                    '##USERNAME##' => $username,
                    '##CONTACT_MAIL##' => SITE_CONTACT_EMAIL,
                    '##FROM_EMAIL##' => SITE_FROM_EMAIL
                );
                sendMail('Welcome Email', $emailFindReplace, $user->email);
                $token = array(
                    'token' => getToken($user->id)
                );
                insertUserToken($user->id, $token['token'], $request);
                User::save_user_login($request);
                $token = $token + $user->toArray();
                return renderWithJson($token, 'You have successfully registered with our site.');
            } else {
                $token = array(
                    'token' => getToken($user->id)
                );
                insertUserToken($user->id, $token['token'], $request);
                User::save_user_login($request);
                $token = $token + $user->toArray();
                return renderWithJson($token, 'You have successfully registered with our site.');
            }
        } catch (Exception $e) {
            return renderWithJson($result, $e->getMessage());
        }
    }
    public function save_user_login($request)
    {
        $user_details = User::where('email', '=', $request->getParsedBody() ['email'])->first();
        $user_details->save();
        $user_login = new UserLogin();
        $user_login->user_id = $user_details->id;
        $user_login->role_id = $user_details->role_id;
        $user_login->user_agent = $request->getheader('User-Agent') [0];
        $user_login->save();
    }
    /*public function scopeStarRating($doctorUserId)
    {
        $userData = User::select(['overall_avg_rating'])->where(['id' => $doctorUserId])->first();
        return $userData['overall_avg_rating'];
    }*/
    public function checkUserName($username)
    {
        $userExist = User::where('username', $username)->first();
        if (count($userExist) > 0) {
            $org_username = $username;
            $i = 1;
            do {
                $username = $org_username . $i;
                $userExist = User::where('username', $username)->first();
                if (count($userExist) < 0) {
                    break;
                }
                $i++;
            } while ($i < 1000);
        }
        return $username;
    }
    public function processCaptured($respose, $id, $class = '')
    {
        global $_server_domain_url;        
        $user = User::with('user_profile')->find($id);
        if (!empty($user)) {
            $admin = User::where('role_id', \Constants\ConstUserTypes::Admin)->first();
            $user->pay_key = !empty($respose['paykey']) ? $respose['paykey'] : '';
            $user_profile = UserProfile::where('user_id', $id)->first();
            if ($class == 'ProUser' && (isPluginEnabled('ProUser/ProUser'))) {                    
                if (empty(PRO_ACCOUNT_AUTO_APPROVAL)) {
                    $user_profile->pro_account_status_id = \Constants\ConstProUser::PaidAndPendingApproval;
                } else {
                    $user_profile->pro_account_status_id = \Constants\ConstProUser::Approved;
                }
                $user_profile->paid_pro_amount = PRO_ACCOUNT_FEE;
                $transaction_type = \Constants\TransactionType::PROPayment;
                insertTransaction($id, $admin->id, $id, 'PROUser', $transaction_type, $user->payment_gateway_id, $user_profile->paid_pro_amount, 0);
                User::updateAmountInProviderProfile($id);
            } elseif ($class == 'TopUser' && (isPluginEnabled('TopUser/TopUser'))) {
                $transaction_type = \Constants\TransactionType::TopListed;
                insertTransaction($id, $admin->id, $id, 'TopUser', $transaction_type, $user->payment_gateway_id, TOP_LISTED_ACCOUNT_FEE, 0);
                $topListingPaymentLog = new TopListingPaymentLog;
                $topListingPaymentLog->user_id = $user->id;
                $topListingPaymentLog->paid_top_listing_amount = TOP_LISTED_ACCOUNT_FEE;
                $topListingPaymentLog->top_listing_paid_on = date('Y-m-d H:i:s');
                $topListingPaymentLog->expiry_on = date('Y-m-d', strtotime("+".TOP_LISTING_EXPIRY_DAYS." days"));
                $topListingPaymentLog->is_active = 1;
                $topListingPaymentLog->save();
                User::updateAmountInProviderProfile($id);   
                $user_profile->is_top_listed = 1; 
                if($user_profile->top_user_expiry == null || (!empty($user_profile->top_user_expiry) && date('Y-m-d', strtotime($user_profile->top_user_expiry)) <= date('Y-m-d'))){
                    $user_profile->top_user_expiry = date('Y-m-d', strtotime("+".TOP_LISTING_EXPIRY_DAYS." days"));
                }    
                elseif(date('Y-m-d', strtotime($user_profile->top_user_expiry)) > date('Y-m-d')){
                    $now = time();
                    $datediff = strtotime($user_profile->top_user_expiry) - $now;
                    $total_days = TOP_LISTING_EXPIRY_DAYS + (floor($datediff / (60 * 60 * 24)));
                    $user_profile->top_user_expiry = date('Y-m-d', strtotime("+".$total_days." days"));
                }
            }
            $user_profile->update();
            User::updateDisplayOrder($user->id); 
            $user->update();
        }
        $response = array(
            'data' => $respose,
            'error' => array(
                'code' => 0,
                'message' => 'Payment successfully completed'
            )
        );
        return $response;
    }
    public function processOrder($args)
    {
        global $authUser, $_server_domain_url;
        $user = User::with('user_profile')->find($args['foreign_id']);
        $result = array();
        if (!empty($user)) {
            $amount = 0;
            if ($args['class'] == 'ProUser' && (isPluginEnabled('ProUser/ProUser'))) {
                $amount = PRO_ACCOUNT_FEE;
                $args['name'] = $args['description'] = 'PROUser Payment to '.SITE_NAME;
            }   
            if ($args['class'] == 'TopUser' && (isPluginEnabled('TopUser/TopUser'))) {
                $amount = TOP_LISTED_ACCOUNT_FEE;
                $args['name'] = $args['description'] = 'TopUser Payment to '.SITE_NAME;
            }   
            $user->payment_gateway_id = $args['payment_gateway_id'];
            $user->update();
            if ($amount > 0) {                        
                if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
                    $username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
                } else {
                    $username = $user->username; 
                }              
                $args['amount'] = $amount; 
                $args['payment_type'] = 'sale';                
                $args['id'] = $user->id;
                $args['success_url'] = $_server_domain_url . '/user_dashboard/success/' . $user->id . '?error_code=0';
                $args['cancel_url'] = $_server_domain_url . '/user_dashboard/cancelled/' . $user->id . '/tests?error_code=512';
                $result = Payment::processPayment($user->id, $args, 'User', $args['class']);
            } else {
                $user_profile = UserProfile::where('user_id', $args['foreign_id'])->first();
                if ($args['class'] == 'ProUser' && (isPluginEnabled('ProUser/ProUser'))) {                    
                    if (empty(PRO_ACCOUNT_AUTO_APPROVAL)) {
                        $user_profile->pro_account_status_id = \Constants\ConstProUser::PaidAndPendingApproval;
                    } else {
                        $user_profile->pro_account_status_id = \Constants\ConstProUser::Approved;
                        User::updateDisplayOrder($user->id);                        
                    }
                    $user_profile->paid_pro_amount = 0;
                    $user_profile->update();
                } elseif ($args['class'] == 'TopUser' && (isPluginEnabled('TopUser/TopUser'))) {           
                    User::updateDisplayOrder($user->id);              
                }
                $result = $user->toArray();                
            }
        }
        return $result;
    }
    public function updateDisplayOrder($user_id)
    {
        $user = User::with('user_profile')->where('id', $user_id)->first();
        if(!empty($user['user_profile']) && (($user['user_profile']['pro_account_status_id'] == \Constants\ConstProUser::Approved) && (!empty($user['user_profile']['is_top_listed'])))){
            $user->display_order = min([PRO_ACCOUNT_DISPLAY_ORDER, TOP_LISTING_ACCOUNT_DISPLAY_ORDER]);
        }elseif(!empty($user['user_profile']) && $user['user_profile']['pro_account_status_id'] == \Constants\ConstProUser::Approved){
            $user->display_order = PRO_ACCOUNT_DISPLAY_ORDER;
        }elseif(!empty($user['user_profile']) && !empty($user['user_profile']['is_top_listed'])){
            $user->display_order = TOP_LISTING_ACCOUNT_DISPLAY_ORDER;
        }else{
            $user->display_order = 3;
        }
        $user->update();
    }
    public function updateMailchimpListID($user_id)
    {
        $user = User::with('user_profile')->where('id', $user_id)->first();
        if (!empty($user)) {
            $data = array();
            if (!empty($user->mailchimp_list_id)) {
                $id = $user->mailchimp_list_id;
                $hash = md5($user->email);
                $url = getMailChimpUrl('lists/' . $id . '/members/' . $hash);  
                $members = json_decode(mailchimpCurlConnect($url, 'DELETE', $data));
                if (empty($members)) {
                    $user->mailchimp_list_id = '';
                }
            }  
            if ($user->role_id == \Constants\ConstUserTypes::ServiceProvider) {
                $user->mailchimp_list_id = SERVICE_PROVIDER_LISTING_ID;
            } elseif ($user->role_id == \Constants\ConstUserTypes::Customer) {
                $user->mailchimp_list_id = CUSTOMER_LISTING_ID;
            } elseif ($user->role_id == \Constants\ConstUserTypes::User) {
                $user->mailchimp_list_id = USER_LISTING_ID;
            }
            $data['email_address'] = $user->email;
            $data['status'] = 'subscribed';
            $data['merge_fields']['FNAME'] = $user->user_profile->first_name;
            $data['merge_fields']['LNAME'] = $user->user_profile->last_name;
            $id = $user->mailchimp_list_id;            
            $url = getMailChimpUrl('lists/' . $id . '/members');     
            $members = json_decode(mailchimpCurlConnect( $url, 'POST', $data));
            if (!is_int( $members->status)) {
                 $user->update();     
            }                     
        }
    }
    public function unsubscribeMailchimpListID($user_id)
    {
        $user = User::with('user_profile')->where('id', $user_id)->first();        
        if (!empty($user) && $user->mailchimp_list_id) {     
            $data = array();      
            $id = $user->mailchimp_list_id;
            $hash = md5($user->email);
            $url = getMailChimpUrl('lists/' . $id . '/members/' . $hash);  
            $members = json_decode(mailchimpCurlConnect($url, 'DELETE', $data));
            if (empty($members)) {
                $user->mailchimp_list_id = '';
                $user->update();
            }            
        }
    }
    public function updateAmountInProviderProfile($provider_user_id) {
        $earned_amount_as_service_provider = Appointment::where('provider_user_id', $provider_user_id)->where('appointment_status_id', \Constants\ConstAppointmentStatus::Closed)->selectRaw('sum(total_booking_amount - site_commission_from_freelancer) as amount')->first()->toArray();

        $site_commission_from_freelancer = Appointment::where('provider_user_id', $provider_user_id)->where('appointment_status_id', \Constants\ConstAppointmentStatus::Closed)->selectRaw('sum(site_commission_from_freelancer) as amount')->first()->toArray();
        $top_user_paid_amount = TopListingPaymentLog::where('user_id', $provider_user_id)->selectRaw('sum(paid_top_listing_amount) as amount')->first()->toArray();

        $paid_pro_amount = UserProfile::select('paid_pro_amount')->where('user_id', $provider_user_id)->first(); 

        $site_revenue_as_service_provider = $site_commission_from_freelancer['amount'] + $top_user_paid_amount['amount'] +  $paid_pro_amount->paid_pro_amount;

        UserProfile::where('user_id', $provider_user_id)->update(array(
            'earned_amount_as_service_provider' => !empty($earned_amount_as_service_provider['amount']) ?$earned_amount_as_service_provider['amount'] : 0.00,
            'site_revenue_as_service_provider' => $site_revenue_as_service_provider,
        ));
    }
}

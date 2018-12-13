<?php
namespace Models;

/**
 * Class State
 * @package App
 */
use Carbon\Carbon as Carbon;

class Appointment extends AppModel
{
    protected $table = "appointments";
    protected $casts = [
        'user_id' => 'integer',
        'provider_user_id' => 'integer',
        'appointment_status_id' => 'integer',
        'no_of_days' => 'integer',
        'total_booking_amount' => 'double',
        'site_commission_from_freelancer' => 'double',
        'used_affiliate_amount' => 'double',
        'payment_gateway_id' => 'integer',
        'service_id' => 'integer',
        'cancellation_policy_id' => 'integer',
        'refunded_amount' => 'double',
        'first_response_time' => 'integer',
        'booked_minutes' => 'integer',
        'work_location_city_id' => 'integer',
        'work_location_state_id' => 'integer',
        'work_location_country_id' => 'integer'                                      
    ];      
    protected $fillable = ['user_id', 'provider_user_id', 'appointment_from_date', 'appointment_from_time', 'customer_note', 'provider_note', 'appointment_status_id', 'category_id', 'booking_option_id', 'appointment_end_date', 'recurring_start_date', 'recurring_end_date', 'recurring_days', 'recurring_time', 'total_booking_amount', 'appointment_to_date', 'no_of_days', 'site_commission_from_freelancer', 'paid_escrow_amount_at', 'used_affiliate_amount', 'payment_gateway_id', 'paypal_status', 'authorization_id', 'capture_id', 'payment_type', 'service_id', 'cancellation_policy_id', 'refunded_amount','booked_minutes', 'work_location_address', 'work_location_address1', 'work_location_city_id', 'work_location_state_id', 'work_location_country_id', 'work_location_postal_code','number_of_item', 'user_credit_card_id', 'request_id', 'is_appointment_for_interview', 'bonus_amount', 'bonus_amount_note'];
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function appointment_status()
    {
        return $this->belongsTo('Models\AppointmentStatus', 'appointment_status_id', 'id');
    }
    public function provider_user()
    {
        return $this->belongsTo('Models\User', 'provider_user_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function foreign_review_models()
    {
        return $this->morphMany('Models\Review', 'foreign_model');
    }
    public function service()
    {
        return $this->belongsTo('Models\Service', 'service_id', 'id');
    }
    public function cancellation_policy()
    {
        return $this->belongsTo('Models\CancellationPolicy', 'cancellation_policy_id', 'id');
    }
    public function foreign_transactions()
    {
        return $this->morphMany('Models\Transaction', 'foreign_transaction');
    }   
    public function activity()
    {
        return $this->belongsTo('Models\Appointment', 'id', 'id')->with('user','provider_user','service');
    }  
    public function work_location_city()
    {
        return $this->belongsTo('Models\City', 'work_location_city_id', 'id');
    }
    public function work_location_state()
    {
        return $this->belongsTo('Models\State', 'work_location_state_id', 'id');
    }
    public function request()
    {
        return $this->belongsTo('Models\Request', 'request_id', 'id');
    }    
    public function work_location_country()
    {
        return $this->belongsTo('Models\Country', 'work_location_country_id', 'id');
    } 
    public function user_credit_card()
    {
        return $this->belongsTo('Models\UserCreditCard', 'user_credit_card_id', 'id');
    } 
    public function form_field_submission()
    {
        return $this->hasMany('Models\FormFieldSubmission', 'foreign_id', 'id')->where('class','Appointment');
    }           
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->whereHas('user.user_profile', function ($q) use ($params, $search) {
                $q->where('first_name', 'LIKE', "%$search%");
                $q->orWhere('last_name', 'LIKE', "%$search%");
                $q->orWhere('listing_title', 'LIKE', "%$search%");
            });
            $query->orWhereHas('provider_user.user_profile', function ($q) use ($params, $search) {
                $q->where('first_name', 'LIKE', "%$search%");
                $q->orWhere('last_name', 'LIKE', "%$search%");
                $q->orWhere('listing_title', 'LIKE', "%$search%");
            });

            $query->orWhereHas('appointment_status', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
            $query->orWhereHas('payment_gateway', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
            $query->orWhereHas('service', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
            $query->orWhereHas('cancellation_policy', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
        }
        return $query;
    }
    
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::saving(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Customer)) {
                $data['user_id'] = $authUser->id;
            } elseif (($authUser['role_id'] == \Constants\ConstUserTypes::ServiceProvider)) {
                $data['provider_user_id'] = $authUser->id;
            }
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin)) {
                return true;
            }
            return false;
        });
        self::deleted(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin)) {
                $appointmentResponses = Appointment::whereNotNull('first_response_time')->select('first_response_time')->where('provider_user_id', $data->provider_user_id)->get();
                $response_time = $avg_response_time = $response_rate = 0;
                if (!empty($appointmentResponses)) {
                    foreach ($appointmentResponses as $appointmentResponse) {
                        $avg_response_time+= $appointmentResponse->first_response_time;
                    }
                    $response_time = $avg_response_time / count($appointmentResponses);
                }
                $status = array(
                    \Constants\ConstAppointmentStatus::PaymentPending,
                    \Constants\ConstAppointmentStatus::CanceledByAdmin,
                    \Constants\ConstAppointmentStatus::Cancelled
                );
                $appointmentFullResponses = Appointment::whereNotIn('appointment_status_id', $status)->where('provider_user_id', $data->provider_user_id)->count();
                $appointmentResponseRates = Appointment::whereNotIn('appointment_status_id', $status)->whereNotNull('first_response_time')->where('provider_user_id', $data->provider_user_id)->count();
                if (!empty($appointmentFullResponses)) {
                    $response_rate = ($appointmentResponseRates * 100) / $appointmentFullResponses;
                }
                UserProfile::where('user_id', $data->provider_user_id)->update(array(
                    'response_time' => $response_time,
                    'response_rate' => $response_rate
                ));
                $notStatus = array(
                    \Constants\ConstAppointmentStatus::PaymentPending,
                    \Constants\ConstAppointmentStatus::CanceledByAdmin,
                    \Constants\ConstAppointmentStatus::Cancelled,
                    \Constants\ConstAppointmentStatus::Expired,
                    \Constants\ConstAppointmentStatus::Rejected
                );                
                $appointment_count = Appointment::whereNotIn('appointment_status_id', $notStatus)->where('user_id', $data->user_id)->count();
                User::where('id', $data->user_id)->update(array(
                    'appointment_count' => $appointment_count
                ));  
                $listing_appointment_count = Appointment::whereNotIn('appointment_status_id', $notStatus)->where('provider_user_id', $data->provider_user_id)->count();
                UserProfile::where('user_id', $data->provider_user_id)->update(array(
                    'listing_appointment_count' => $listing_appointment_count
                ));                               
                return true;
            }
            return false;
        });
        self::saved(function ($data) use ($authUser) {
            if ($data->appointment_status_id == \Constants\ConstAppointmentStatus::Closed) {
                $completed_count = Appointment::where('appointment_status_id', $data->appointment_status_id)->where('provider_user_id', $data->provider_user_id)->count();
                UserProfile::where('user_id', $data->provider_user_id)->update(['completed_appointment_count' => $completed_count]);
            }
            if ($data->appointment_status_id == \Constants\ConstAppointmentStatus::Approved) {
                $status = array(
                    \Constants\ConstAppointmentStatus::Closed,
                    \Constants\ConstAppointmentStatus::Completed,
                    \Constants\ConstAppointmentStatus::Present,
                    \Constants\ConstAppointmentStatus::Approved
                );
                global $capsule;                
                $repeat_count = Appointment::whereIn('appointment_status_id', $status)->select($capsule::raw('count(*) as count'))->where('provider_user_id', $data->provider_user_id)->groupBy('user_id')->having('count', '>', 1)->get();                
                $repeat_client_count = 0;
                if (!empty($repeat_count->count())) {
                    $repeat_client_count = $repeat_count->count();
                }
                UserProfile::where('user_id', $data->provider_user_id)->update(['repeat_client_count' => $repeat_client_count]);
            }
            $appointmentResponses = Appointment::whereNotNull('first_response_time')->select('first_response_time')->where('provider_user_id', $data->provider_user_id)->get();
            $response_time = $avg_response_time = $response_rate = 0;
            if (!empty($appointmentResponses) && count($appointmentResponses) > 0) {
                foreach ($appointmentResponses as $appointmentResponse) {
                    $avg_response_time+= $appointmentResponse->first_response_time;
                }
                $response_time = $avg_response_time / count($appointmentResponses);
            }
            $status = array(
                \Constants\ConstAppointmentStatus::PaymentPending,
                \Constants\ConstAppointmentStatus::CanceledByAdmin,
                \Constants\ConstAppointmentStatus::Cancelled
            );
            $appointmentFullResponses = Appointment::whereNotIn('appointment_status_id', $status)->where('provider_user_id', $data->provider_user_id)->count();
            $appointmentResponseRates = Appointment::whereNotIn('appointment_status_id', $status)->whereNotNull('first_response_time')->where('provider_user_id', $data->provider_user_id)->count();
            if (!empty($appointmentFullResponses)) {
                $response_rate = ($appointmentResponseRates * 100) / $appointmentFullResponses;
            }
            UserProfile::where('user_id', $data->provider_user_id)->update(array(
                'response_time' => $response_time,
                'response_rate' => $response_rate
            ));
            $notStatus = array(
                \Constants\ConstAppointmentStatus::PaymentPending,
                \Constants\ConstAppointmentStatus::CanceledByAdmin,
                \Constants\ConstAppointmentStatus::Cancelled,
                \Constants\ConstAppointmentStatus::Expired,
                \Constants\ConstAppointmentStatus::Rejected
            );                
            $appointment_count = Appointment::whereNotIn('appointment_status_id', $notStatus)->where('user_id', $data->user_id)->count();
            User::where('id', $data->user_id)->update(array(
                'appointment_count' => $appointment_count
            ));  
            $listing_appointment_count = Appointment::whereNotIn('appointment_status_id', $notStatus)->where('provider_user_id', $data->provider_user_id)->count();
            UserProfile::where('user_id', $data->provider_user_id)->update(array(
                'listing_appointment_count' => $listing_appointment_count
            ));              
        });
    }
    public $rules = array(
        'user_id' => 'sometimes|required',
        'provider_user_id' => 'sometimes|required',
        'appointment_from_date' => 'sometimes|required',
        'appointment_from_time' => 'sometimes|required',
        'customer_note' => 'sometimes|required',
        'provider_note' => 'sometimes|required',
        'appointment_status_id' => 'sometimes|required',
        'category_id' => 'sometimes|required',
        'booking_option_id' => 'sometimes|required',
        'appointment_end_date' => 'sometimes|required',
        'recurring_start_date' => 'sometimes|required',
        'recurring_end_date' => 'sometimes|required',
        'recurring_days' => 'sometimes|required',
        'recurring_time' => 'sometimes|required',
        'total_booking_amount' => 'sometimes|required',
        'no_of_days' => 'sometimes|required',
        'site_commission_from_freelancer' => 'sometimes|required',
        'paid_escrow_amount_at' => 'sometimes|required',
        'used_affiliate_amount' => 'sometimes|required',
        'payment_gateway_id' => 'sometimes|required',
        'paypal_status' => 'sometimes|required',
        'authorization_id' => 'sometimes|required',
        'capture_id' => 'sometimes|required',
        'payment_type' => 'sometimes|required',
        'service_id' => 'sometimes|required',
        'cancellation_policy_id' => 'sometimes|required'
    );
    public function processCaptured($respose, $id)
    {
        global $_server_domain_url;
        $dispatcher = Appointment::getEventDispatcher();
        Appointment::unsetEventDispatcher();
        $admin = User::where('role_id', \Constants\ConstUserTypes::Admin)->first();
        $appointment_details = Appointment::where('id', $id)->first();
        if (!empty($appointment_details) && $appointment_details->bonus_amount > 0) {
            $appointment = Appointment::where(function ($q) {            
                                        $q->where('appointment_status_id', \Constants\ConstAppointmentStatus::Closed);
                                        $q->orWhere('appointment_status_id', \Constants\ConstAppointmentStatus::Present);
                                        $q->orWhere('appointment_status_id', \Constants\ConstAppointmentStatus::Completed);
                                    })->where('is_paid_bonus_amount', 0)->find($id);
            if (!empty($appointment)) {
                $transaction_type = \Constants\TransactionType::BonusAmount;
                insertTransaction($appointment->user_id, $appointment->provider_user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->bonus_amount, $appointment->site_commission_from_freelancer_for_bonus_amount, 0, null, $appointment->site_commission_from_customer_for_bonus_amount); 
                $notStatus = array(
                    \Constants\ConstAppointmentStatus::PaymentPending,
                    \Constants\ConstAppointmentStatus::CanceledByAdmin,
                    \Constants\ConstAppointmentStatus::Cancelled,
                    \Constants\ConstAppointmentStatus::Expired,
                    \Constants\ConstAppointmentStatus::Rejected,
                    \Constants\ConstAppointmentStatus::Enquiry,
                    \Constants\ConstAppointmentStatus::PendingApproval
                );  
                $appointment->total_booking_amount = $appointment->total_booking_amount + $appointment->bonus_amount;
                $appointment->site_commission_from_customer = $appointment->site_commission_from_customer + $appointment->site_commission_from_customer_for_bonus_amount;  
                $appointment->site_commission_from_freelancer = $appointment->site_commission_from_freelancer + $appointment->site_commission_from_freelancer_for_bonus_amount;  
                $appointment->is_paid_bonus_amount = 1;
                $appointment->update();
                User::updateAmountInProviderProfile($appointment->provider_user_id);
                Appointment::updateAmountInCustomerProfile($appointment->user_id);
            }
        } else {        
            $appointment = Appointment::where(function ($q) {            
                                        $q->where('appointment_status_id', \Constants\ConstAppointmentStatus::PaymentPending);
                                        $q->orWhere('appointment_status_id', \Constants\ConstAppointmentStatus::PreApproved);
                                    })->find($id);
            if (!empty($appointment)) {
                $request_user = User::where('id', $appointment->user_id)->with('user_profile')->first();
                $service_provider = User::where('id', $appointment->provider_user_id)->with('user_profile')->first();
                if (!empty($service_provider->user_profile->first_name) || !empty($service_provider->user_profile->last_name)) {
                    $service_username = $service_provider->user_profile->first_name .' '.$service_provider->user_profile->last_name;
                } else {
                    $service_username = $service_provider->email; 
                }  
                if (!empty($request_user->user_profile->first_name) || !empty($request_user->user_profile->last_name)) {
                    $request_username = $request_user->user_profile->first_name .' '.$request_user->user_profile->last_name;
                } else {
                    $request_username = $request_user->email; 
                }                       
                $emailFindReplace = array(
                    '##SERVICE_PROVIDER##' => $service_username,
                    '##REQUESTOR_NAME##' => $request_username,
                    '##LINK##' => $_server_domain_url . '/#/appointments',
                    '##DATE##' => $appointment->appointment_from_date,
                );
                /*send mail to patient*/
                sendMail('New Service Request', $emailFindReplace, $service_provider->email);
                if (!empty($respose['authorization_id'])) {
                    $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PendingApproval;
                } elseif (!empty($respose['capture_id'])) {
                    $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Approved;
                }
                $appointment->authorization_id = !empty($respose['authorization_id']) ? $respose['authorization_id'] : '';
                $appointment->capture_id = !empty($respose['capture_id']) ? $respose['capture_id'] : '';
                $appointment->paypal_status = $respose['paypal_status'];
                $appointment->paid_escrow_amount_at = date('Y-m-d H:i:s');
                if ($appointment->update()) {
                    if (isPluginEnabled('Referral/Referral')) {
                        $affiliate_paid_amount = $request_user->affiliate_paid_amount + $appointment->used_affiliate_amount;
                        $affiliate_pending_amount = $request_user->affiliate_pending_amount - $appointment->used_affiliate_amount;
                        User::where('id', $request_user->id)->update(array(
                            'affiliate_paid_amount' => $affiliate_paid_amount,
                            'affiliate_pending_amount' => $affiliate_pending_amount
                        ));
                    }
                    if ($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Approved && !empty($appointment->capture_id)) {
                        $transaction_type = \Constants\TransactionType::BookingAcceptedAndAmountMovedToEscrow;
                        insertTransaction($appointment->user_id, $appointment->provider_user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->total_booking_amount, 0, 0, null, $appointment->site_commission_from_customer); 
                        $notStatus = array(
                            \Constants\ConstAppointmentStatus::PaymentPending,
                            \Constants\ConstAppointmentStatus::CanceledByAdmin,
                            \Constants\ConstAppointmentStatus::Cancelled,
                            \Constants\ConstAppointmentStatus::Expired,
                            \Constants\ConstAppointmentStatus::Rejected,
                            \Constants\ConstAppointmentStatus::Enquiry,
                            \Constants\ConstAppointmentStatus::PendingApproval
                        );                                 
                    } elseif($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::PendingApproval && !empty($appointment->authorization_id)) {
                        $transaction_type = \Constants\TransactionType::BookedAndWaitingForApproval;
                        insertTransaction($appointment->user_id, $appointment->provider_user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $appointment->total_booking_amount, 0, 0, null, $appointment->site_commission_from_customer);                     
                    }              
                }
                Appointment::setEventDispatcher($dispatcher);
            }
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
    /*public function get_doctor_booking_appointments($doctorId, $startDate = null, $endDate = null)
    {
        $whereInIds = [\Constants\ConstAppointmentStatus::Cancelled, \Constants\ConstAppointmentStatus::Rejected];
        if ($startDate == null && $endDate == null) {
            $appointmentBookings = Appointment::where('appointment_from_date', '>=', date('Y-m-d'))->where(['provider_user_id' => $doctorId])->whereNotIn('appointment_status_id', $whereInIds)->select(['appointment_from_date', 'appointment_from_time', 'provider_user_id'])->get();
        } else {
            $appointmentBookings = Appointment::whereBetween('appointment_from_date', [$startDate, $endDate])->where('provider_user_id', $doctorId)->whereNotIn('appointment_status_id', $whereInIds)->select(['appointment_from_date', 'appointment_from_time', 'provider_user_id'])->get();
        }
        return $appointmentBookings->toArray();
    }*/
    public function processOrder($args)
    {
        global $authUser, $_server_domain_url;
        $appointment = Appointment::with('provider_user.user_profile', 'service')->find($args['foreign_id']);
        $result = array();
        if (!empty($args['is_bonus'])) {
            if (!empty($appointment) && $appointment->user_id == $authUser->id && $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Present || $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Completed || $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Closed && $appointment->is_paid_bonus_amount == 0) {
                $total_booking_amount = $appointment->bonus_amount;            
                if (isPluginEnabled('Interview/Interview') && !empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER)) {
                    $site_commission_from_customer_for_bonus_amount = (SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER / 100) * $appointment->bonus_amount;
                    $appointment->site_commission_from_customer_for_bonus_amount = $site_commission_from_customer_for_bonus_amount;
                    $total_booking_amount = $appointment->bonus_amount + $site_commission_from_customer_for_bonus_amount;
                } elseif (empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER)) {
                    $site_commission_from_customer_for_bonus_amount = (SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER / 100) * $appointment->bonus_amount;
                    $appointment->site_commission_from_customer_for_bonus_amount = $site_commission_from_customer_for_bonus_amount;
                    $total_booking_amount = $appointment->bonus_amount + $site_commission_from_customer_for_bonus_amount;
                }
                if (isPluginEnabled('Interview/Interview') && !empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_INTERVIEW_FROM_SERVICE_PROVIDER)) {
                    $appointment->site_commission_from_freelancer_for_bonus_amount = ((SITE_COMMISSION_FOR_INTERVIEW_FROM_SERVICE_PROVIDER / 100) * $appointment->bonus_amount);
                } elseif (empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER)) {
                    $appointment->site_commission_from_freelancer_for_bonus_amount = !empty(SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER) ? ((SITE_COMMISSION_FOR_BOOKING_FROM_SERVICE_PROVIDER / 100) * $appointment->bonus_amount) : 0;
                }
                if (!empty($args['user_credit_card_id'])) {
                    $appointment->user_credit_card_id = $args['user_credit_card_id'];
                }
                $appointment->update();
                if ($total_booking_amount > 0) {                        
                    if (!empty($appointment->provider_user->user_profile->first_name) || !empty($appointment->provider_user->user_profile->last_name)) {
                        $username = $appointment->provider_user->user_profile->first_name .' '.$appointment->provider_user->user_profile->last_name;
                    } else {
                        $username = $appointment->provider_user->username; 
                    }                
                    $args['name'] = 'Bonus Booking Payment to '.SITE_NAME;
                    $args['description'] = 'You booked  '.$username.' for '.$appointment->service->name.' for '.$appointment->appointment_from_date;
                    $args['amount'] = $total_booking_amount; 
                    $args['payment_type'] = 'sale';
                    $args['appointment_status_id'] = $appointment->appointment_status_id;
                    $args['id'] = $appointment->id;
                    $args['success_url'] = $_server_domain_url . '/appointments/success/' . $appointment->id . '?error_code=0';
                    $args['cancel_url'] = $_server_domain_url . '/appointments/cancelled/' . $appointment->id . '/tests?error_code=512';
                    $result = Payment::processPayment($appointment->id, $args, 'Appointment');
                } else {
                    $appointment->is_paid_bonus_amount = 1;
                    $appointment->update();
                    $result = $appointment->toArray();
                }
            }
        } else {
            if (!empty($appointment) && $appointment->user_id == $authUser->id && $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::PaymentPending || $appointment->appointment_status_id == \Constants\ConstAppointmentStatus::PreApproved) {
                $total_booking_amount = $appointment->total_booking_amount;            
                if (isPluginEnabled('Interview/Interview') && !empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER)) {
                    $site_commission_from_customer = (SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER / 100) * $appointment->total_booking_amount;
                    $appointment->site_commission_from_customer = $site_commission_from_customer;
                    $total_booking_amount = $appointment->total_booking_amount + $site_commission_from_customer;
                } elseif (empty($appointment->is_appointment_for_interview) && !empty(SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER)) {
                    $site_commission_from_customer = (SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER / 100) * $appointment->total_booking_amount;
                    $appointment->site_commission_from_customer = $site_commission_from_customer;
                    $total_booking_amount = $appointment->total_booking_amount + $site_commission_from_customer;
                }
                if (!empty($args['user_credit_card_id'])) {
                    $appointment->user_credit_card_id = $args['user_credit_card_id'];
                }
                $appointment->update();
                if ($total_booking_amount > 0) {                        
                    if (!empty($appointment->provider_user->user_profile->first_name) || !empty($appointment->provider_user->user_profile->last_name)) {
                        $username = $appointment->provider_user->user_profile->first_name .' '.$appointment->provider_user->user_profile->last_name;
                    } else {
                        $username = $appointment->provider_user->username; 
                    }                
                    $args['name'] = 'Booking Payment to '.SITE_NAME;
                    $args['description'] = 'You booked  '.$username.' for '.$appointment->service->name.' for '.$appointment->appointment_from_date;
                    $args['amount'] = $total_booking_amount; 
                    $args['payment_type'] = $appointment->payment_type;
                    $args['appointment_status_id'] = $appointment->appointment_status_id;
                    $args['id'] = $appointment->id;
                    $args['success_url'] = $_server_domain_url . '/appointments/success/' . $appointment->id . '?error_code=0';
                    $args['cancel_url'] = $_server_domain_url . '/appointments/cancelled/' . $appointment->id . '/tests?error_code=512';
                    $result = Payment::processPayment($appointment->id, $args, 'Appointment');
                } else {
                    $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::PendingApproval;
                    $appointment->update();
                    $result = $appointment->toArray();
                }
            }
        }
        return $result;
    }

    public function closeAppointment($appintment_id)
    {
        $appointment = Appointment::with('user.user_profile')->find($appintment_id);
        $admin = User::where('role_id', \Constants\ConstUserTypes::Admin)->first();
        $provider_user = User::with('user_profile')->where('id', $appointment->provider_user_id)->first();
        $amount = ($appointment->total_booking_amount + $appointment->used_affiliate_amount) - $appointment->site_commission_from_freelancer;
        $provider_user->available_wallet_amount = $provider_user->available_wallet_amount + $amount;
        $provider_user->update();
        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Closed;
        if ($appointment->update()) {
            if ($appointment->appointment_status_id == \Constants\ConstAppointmentStatus::Closed) {
                $transaction_type = \Constants\TransactionType::CompletedAndAmountMovedToWallet;
                insertTransaction($appointment->user_id, $appointment->provider_user_id, $appointment->id, 'Appointment', $transaction_type, $appointment->payment_gateway_id, $amount, $appointment->site_commission_from_freelancer);         
                   
                User::updateAmountInProviderProfile($appointment->provider_user_id);
                Appointment::updateAmountInCustomerProfile($appointment->user_id);
            }
        }
        if (!empty($provider_user->user_profile->first_name) || !empty($provider_user->user_profile->last_name)) {
            $service_username = $provider_user->user_profile->first_name .' '.$provider_user->user_profile->last_name;
        } else {
            $service_username = $provider_user->email; 
        }  
        if (!empty($appointment['user']['user_profile']['first_name']) || !empty($appointment['user']['user_profile']['last_name'])) {
            $request_username = $appointment['user']['user_profile']['first_name'].' '.$appointment['user']['user_profile']['last_name'];
        } else {
            $request_username = $appointment['user']['email']; 
        }         
        $emailFindReplace = array(
            '##SERVICE_PROVIDER##' => $service_username,
            '##REQUESTOR_NAME##' => $request_username
        );
        sendMail('Service Request Closed', $emailFindReplace, $provider_user->email);
        if (IS_SITE_ENABLED_PUSH_NOTIFICATION) {
                $followMessage = array(
                    'message_type' => 'PUSH_NOTIFICATION_FOR_TASK_MARKED_AS_CLOSED_BY_REQUESTOR',
                    'appointment_id' => $appointment->id
                );
                addPushNotification($appointment->provider_user_id, $followMessage);
                $followMessage = array(
                    'message_type' => 'PUSH_NOTIFICATION_FOR_AMOUNT_RECEIVED_TO_SERVICE_PROVIDER',
                    'appointment_id' => $appointment->id
                );
                addPushNotification($appointment->provider_user_id, $followMessage);
        }
        if (isPluginEnabled('SMS/SMS')) {
            $message = array(
                'appointment_id' => $appointment->id,
                'message_type' => 'SMS_FOR_TASK_MARKED_AS_CLOSED_BY_REQUESTOR'
            );
            Sms::sendSMS($message, $appointment->provider_user_id);
            $message = array(
                'appointment_id' => $appointment->id,
                'message_type' => 'SMS_FOR_AMOUNT_RECEIVED_TO_SERVICE_PROVIDER'
            );
            Sms::sendSMS($message, $appointment->provider_user_id);
        }
    }
    public function updateAmountInCustomerProfile($customer_id)
    {
        $notStatus = array(
            \Constants\ConstAppointmentStatus::PaymentPending,
            \Constants\ConstAppointmentStatus::CanceledByAdmin,
            \Constants\ConstAppointmentStatus::Cancelled,
            \Constants\ConstAppointmentStatus::Expired,
            \Constants\ConstAppointmentStatus::Rejected,
            \Constants\ConstAppointmentStatus::Enquiry,
            \Constants\ConstAppointmentStatus::PendingApproval
        );         
        $site_revenue_as_customer = Appointment::whereNotIn('appointment_status_id', $notStatus)->selectRaw('sum(site_commission_from_customer) as site_commission_from_customer')->where('user_id', $customer_id)->first()->toArray();

        $total_booking_amount = Appointment::whereNotIn('appointment_status_id', $notStatus)->where('user_id', $customer_id)->selectRaw('sum(total_booking_amount) as amount')->first()->toArray();

        $total_spent_amount = $site_revenue_as_customer['site_commission_from_customer'] + $total_booking_amount['amount'];
        UserProfile::where('user_id', $customer_id)->update(array(
            'site_revenue_as_customer' => $site_revenue_as_customer['site_commission_from_customer'],
            'total_spent_amount_as_customer' => $total_spent_amount
        ));
    }
    public function appointmentValidation($data)
    {
        $validation = array();
        $serviceUser = ServiceUser::with('service.category', 'user.user_profile')->find($data['services_user_id']);
        if (!empty($serviceUser)) {
            if (!empty($serviceUser->service)) {
                $validation['data']['provider_user_id'] = $serviceUser->user_id;
                $validation['data']['service_id'] = $serviceUser->service_id;
                $validation['data']['cancellation_policy_id'] = $serviceUser->cancellation_policy_id;
                if (!in_array($serviceUser->service->booking_option_id, [\Constants\ConstBookingOption::SingleDate, \Constants\ConstBookingOption::MultipleDate, \Constants\ConstBookingOption::MultiHours, \Constants\ConstBookingOption::TimeSlot])) {
                    $validation['error']['booking_option_id'] = ['Invalid Booking Option Id'];
                }
                $now = date('Y-m-d');
                if ($data['appointment_from_date'] >= $now) {
                    switch($serviceUser->service->booking_option_id)
                    {
                        case \Constants\ConstBookingOption::SingleDate: 
                            $is_open_day_array = getIsDayName($data['appointment_from_date']);
                            $is_open_day = $is_open_day_array['is_open_day'];
                            $appointmentSetting = AppointmentSetting::where('user_id', $serviceUser['user_id'])->where(function ($q) use ($is_open_day) {
                                $q->where('type', \Constants\ConstAppointmentSettingType::SameForAll)->orWhere(function ($q1) use ($is_open_day) {
                                    $q1->where('type', \Constants\ConstAppointmentSettingType::IndividualDays);
                                    $q1->where($is_open_day, 1);
                                });
                            })->first();
                            $appoinment_modification_fully_on = AppointmentModification::where('user_id',$serviceUser->user_id)->where('unavailable_date', $data['appointment_from_date'])->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOn)->first();
                            if (empty($appointmentSetting) && empty($appoinment_modification_fully_on)) {
                                $validation['error']['appointment_from_date'] = ['This provider not available on this date'];
                            } else {
                                $appoinment_modification = AppointmentModification::where('user_id',$serviceUser->user_id)->where('unavailable_date', $data['appointment_from_date'])->whereIn('type',[\Constants\ConstAppointmentModificationType::UnavailableParticularDateAndTime, \Constants\ConstAppointmentModificationType::MakeADayFullyOff])->first();
                                $exitsAppointment = Appointment::where('appointment_from_date', $data['appointment_from_date'])->where('provider_user_id', $serviceUser['user_id'])->where('appointment_status_id', \Constants\ConstAppointmentStatus::Approved)->first();
                                if (!empty($exitsAppointment) && empty($serviceUser->service->is_enable_multiple_booking) || !empty($appoinment_modification)) {
                                    $validation['error']['appointment_from_date'] = ['This provider not available on this date'];
                                } else {
                                    $validation['data']['appointment_from_date'] = $data['appointment_from_date'];
                                    $validation['data']['appointment_to_date'] = $data['appointment_from_date'];
                                    $validation['data']['no_of_days'] = 1;
                                    $validation['data']['total_booking_amount'] = round($serviceUser->rate, 2);
                                }
                            }
                            break;
                        case \Constants\ConstBookingOption::MultipleDate:
                            $from_date = strtotime($data['appointment_from_date']);
                            $to_date = strtotime($data['appointment_to_date']);
                            $datediff = $to_date - $from_date;
                            $days = floor($datediff / (60 * 60 * 24)) + 1;
                            $error_status = 0;
                            if ($days > 7) {
                                $days = 7;
                            }
                            for ($i = 0; $i < $days; $i++) {
                                $from_date = date('Y-m-d', strtotime($data['appointment_from_date'] . ' +' . $i . ' day'));
                                $is_open_day_array = getIsDayName($from_date);
                                $is_open_day = $is_open_day_array['is_open_day'];
                                $appointmentSetting = AppointmentSetting::where('user_id', $serviceUser['user_id'])->where(function ($q) use ($is_open_day) {
                                    $q->where('type', \Constants\ConstAppointmentSettingType::SameForAll)->orWhere(function ($q1) use ($is_open_day) {
                                        $q1->Where('type', \Constants\ConstAppointmentSettingType::IndividualDays);
                                        $q1->where($is_open_day, 1);
                                    });
                                })->first();
                                $appoinment_modification_fully_on = AppointmentModification::where('user_id',$serviceUser->user_id)->where('unavailable_date', $from_date)->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOn)->first();
                                if (empty($appointmentSetting) && empty($appoinment_modification_fully_on)) {
                                    $error_status = 1;
                                } else {
                                    $appoinment_modification = AppointmentModification::where('user_id',$serviceUser->user_id)->where('unavailable_date', $from_date)->whereIn('type',[\Constants\ConstAppointmentModificationType::UnavailableParticularDateAndTime, \Constants\ConstAppointmentModificationType::MakeADayFullyOff])->first();
                                    $exitsAppointment = Appointment::where(function($q) use($data){
                                        $q->whereBetween('appointment_from_date', [$data['appointment_from_date'], $data['appointment_to_date']]);
                                        $q->orWhereBetween('appointment_to_date', [$data['appointment_from_date'], $data['appointment_to_date']]);
                                    })->where('provider_user_id', $serviceUser['user_id'])->whereIn('appointment_status_id',[\Constants\ConstAppointmentStatus::Approved, \Constants\ConstAppointmentStatus::PendingApproval])->first();
                                    if (!empty($exitsAppointment) && empty($serviceUser->service->is_enable_multiple_booking) || !empty($appoinment_modification)) {
                                        $error_status = 1;
                                    }
                                }
                            }
                            if (empty($error_status)) {
                                $appointment_from_date = date_create($data['appointment_from_date']);
                                $appointment_to_date = date_create($data['appointment_to_date']);
                                $diff = date_diff($appointment_from_date, $appointment_to_date);
                                $no_of_days = $diff->format("%a");
                                $validation['data']['appointment_from_date'] = $data['appointment_from_date'];
                                $validation['data']['appointment_to_date'] = $data['appointment_to_date'];
                                $validation['data']['no_of_days'] = $no_of_days + 1;
                                $validation['data']['total_booking_amount'] = round($serviceUser->rate * $validation['data']['no_of_days'], 2);                              
                            } else {
                                $validation['error']['appointment_from_date'] = ['This provider not available on this date'];
                            }
                            break;
                        case \Constants\ConstBookingOption::MultiHours: 
                            if (empty($data['appointment_from_date']) || empty($data['appointment_to_date']) || empty($data['appointment_from_time']) || empty($data['appointment_to_time'])) {
                                $validation['error']['required'] = ['Required fields are appointment_from_date,appointment_to_date,appointment_from_time,appointment_to_time '];
                                return $validation;
                            }
                            $from_date = strtotime($data['appointment_from_date']);
                            $to_date = strtotime($data['appointment_to_date']);
                            $datediff = $to_date - $from_date;
                            $days = floor($datediff / (60 * 60 * 24)) + 1;
                            $error_status = 0;
                            if ($days > 7) {
                                $days = 7;
                            }
                            for ($i = 0; $i < $days; $i++) {
                                $from_date = date('Y-m-d', strtotime($data['appointment_from_date'] . ' +' . $i . ' day'));
                                $is_open_day_array = getIsDayName($from_date);
                                $is_open_day = $is_open_day_array['is_open_day'];
                                $practice_open = $is_open_day_array['practice_open'];
                                $practice_close = $is_open_day_array['practice_close'];
                                $appointmentSetting = AppointmentSetting::where('user_id', $serviceUser['user_id'])->where(function ($q) use ($is_open_day, $data, $practice_open, $practice_close) {
                                    $q->where(function ($q1) use ($is_open_day, $data) {
                                        $q1->where('type', \Constants\ConstAppointmentSettingType::SameForAll);
                                        $q1->where(function($q2) use($data) {
                                            $q2->where(function($q3)use($data){
                                                $q3->where('practice_open','<=', $data['appointment_from_time']);
                                                $q3->where('practice_close','>=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->where('practice_open','>=', $data['appointment_from_time']);
                                                $q3->where('practice_close','<=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->whereBetween('practice_open', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                $q3->orWhereBetween('practice_close', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                            });
                                        });
                                    })->orWhere(function ($q1) use ($is_open_day, $data, $practice_open, $practice_close) {
                                        $q1->where('type', \Constants\ConstAppointmentSettingType::IndividualDays);
                                        $q1->where($is_open_day, 1);
                                        $q1->where(function($q2) use($is_open_day, $data, $practice_open, $practice_close) {
                                            $q2->where(function($q3)use($is_open_day, $data, $practice_open, $practice_close) {
                                                $q3->where($practice_open,'<=', $data['appointment_from_time']);
                                                $q3->where($practice_close,'>=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($is_open_day, $data, $practice_open, $practice_close)
                                            {
                                                $q3->where($practice_open,'>=', $data['appointment_from_time']);
                                                $q3->where($practice_close,'<=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($is_open_day, $data, $practice_open, $practice_close)
                                            {
                                                $q3->whereBetween($practice_open, [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                $q3->orWhereBetween($practice_close, [$data['appointment_from_time'], $data['appointment_to_time']]);
                                            });
                                        });
                                    });
                                })->first();
                                $appoinment_modification_fully_on = AppointmentModification::where('user_id',$serviceUser->user_id)->where('unavailable_date', $from_date)->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOn)->first();   
                                if (empty($appointmentSetting) && empty($appoinment_modification_fully_on)) {
                                    $error_status = 1;
                                } else {
                                    $appoinment_modification = AppointmentModification::where('user_id', $serviceUser['user_id'])->where('unavailable_date', $from_date)->where(function ($q) use ($data) {
                                    $q->where(function ($q1) use ($data) {
                                        $q1->whereIn('type', [\Constants\ConstAppointmentModificationType::UnavailableParticularDateAndTime, \Constants\ConstAppointmentModificationType::UnavailableInEveryParticularDayAndTime]);
                                        $q1->where(function($q2) use($data) {
                                            $q2->where(function($q3)use($data){
                                                $q3->where('day', ucfirst(date('l', strtotime($data['appointment_from_date']))));
                                                $q3->orWhere('day', 'AllDay');
                                            });                                            
                                            $q2->where(function($q3)use($data){
                                                $q3->where('unavailable_from_time','<=', $data['appointment_from_time']);
                                                $q3->where('unavailable_to_time','>=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->where('unavailable_from_time','>=', $data['appointment_from_time']);
                                                $q3->where('unavailable_to_time','<=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->whereBetween('unavailable_from_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                $q3->orWhereBetween('unavailable_to_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                            });
                                        });
                                    })->orWhere(function ($q1) {
                                        $q1->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOff);
                                    });
                                })->first();
                                    $exitsAppointment = Appointment::where(function($q) use($data){
                                        $q->whereBetween('appointment_from_date', [$data['appointment_from_date'], $data['appointment_to_date']]);
                                        $q->orWhereBetween('appointment_to_date', [$data['appointment_from_date'], $data['appointment_to_date']]);
                                    })->where('provider_user_id', $serviceUser['user_id'])->where('appointment_status_id', \Constants\ConstAppointmentStatus::Approved)
                                    ->where(function($q2) use($data) {
                                            $q2->where(function($q3)use($data){
                                                $q3->where('appointment_from_time','<=', $data['appointment_from_time']);
                                                $q3->where('appointment_to_time','>=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->where('appointment_from_time','>=', $data['appointment_from_time']);
                                                $q3->where('appointment_to_time','<=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->whereBetween('appointment_from_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                $q3->orWhereBetween('appointment_from_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                            });
                                        })->first(); 
                                    if (!empty($exitsAppointment) && empty($serviceUser->service->is_enable_multiple_booking) || !empty($appoinment_modification)) {
                                        $error_status = 1;
                                    }
                                }
                            }
                            if (empty($error_status)) {
                                $appointment_from_date = date_create($data['appointment_from_date']);
                                $appointment_to_date = date_create($data['appointment_to_date']);
                                $diff = date_diff($appointment_from_date, $appointment_to_date);
                                $no_of_days = $diff->format("%a");
                                $validation['data']['appointment_from_date'] = $data['appointment_from_date'];
                                $validation['data']['appointment_to_date'] = $data['appointment_to_date'];
                                $validation['data']['appointment_from_time'] = $data['appointment_from_time'];
                                $validation['data']['appointment_to_time'] = $data['appointment_to_time'];
                                $validation['data']['no_of_days'] = $no_of_days + 1;                                
                                $validation['data']['total_booking_amount'] = round($serviceUser->rate * $validation['data']['no_of_days'], 2);
                                $rate = $serviceUser['rate'];                               
                                $number_of_item = 1;
                                if(!empty($data['number_of_item']) && $data['number_of_item'] > 1){
                                    $number_of_item = $data['number_of_item'];
                                }
                                $rate = $rate * $number_of_item;
                                $minutes = (strtotime($validation['data']['appointment_to_date'].' '.$validation['data']['appointment_to_time']) - strtotime($validation['data']['appointment_from_date'].' '.$validation['data']['appointment_from_time']))/60;
                                $hours = ceil($minutes/60);
                                $validation['data']['booked_minutes'] = $minutes;
                                $validation['data']['total_booking_amount'] = $rate * $hours;                                                    
                            } else {
                                $validation['error']['appointment_from_date'] = ['This provider not available on this date'];
                            }    
                            break;                        
                        case \Constants\ConstBookingOption::TimeSlot: 
                            if (empty($data['appointment_from_date']) || empty($data['appointment_from_time'])) {
                                $validation['error']['required'] = ['Required fields are appointment_from_date,appointment_from_time '];
                                return $validation;
                            }
                            $is_open_day_array = getIsDayName($data['appointment_from_date']);
                            $is_open_day = $is_open_day_array['is_open_day'];
                            $practice_open = $is_open_day_array['practice_open'];
                            $practice_close = $is_open_day_array['practice_close'];
                            $appointmentSetting = AppointmentSetting::where('user_id', $serviceUser['user_id'])->where(function ($q) use ($is_open_day, $data, $practice_open, $practice_close) {
                                $q->orWhere(function ($q1) use ($is_open_day, $data) {
                                    $q1->where('type', \Constants\ConstAppointmentSettingType::SameForAll);
                                    $q1->where('practice_open', '<=', $data['appointment_from_time']);
                                    $q1->where('practice_close','>=',  $data['appointment_from_time']);
                                })->orWhere(function ($q1) use ($is_open_day, $data, $practice_open, $practice_close) {
                                    $q1->Where('type', \Constants\ConstAppointmentSettingType::IndividualDays);
                                    $q1->where($is_open_day, 1);
                                    $q1->where($practice_open,'<=', $data['appointment_from_time']);
                                    $q1->where($practice_close, '>=', $data['appointment_from_time']);
                                });
                            })->first();
                            $appoinment_modification_fully_on = AppointmentModification::where('user_id',$serviceUser->user_id)->where('unavailable_date', $data['appointment_from_date'])->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOn)->first();        
                            if (empty($appointmentSetting) && empty($appoinment_modification_fully_on)) {
                                $validation['error']['appointment_from_date'] = ['This provider not available on this date'];
                            } else {       
                                    $data['appointment_to_time'] =  date('H:i:s', strtotime("+" . $appointmentSetting->calendar_slot_id . " minutes", strtotime($data['appointment_from_time'])));                   
                                    $appoinment_modification = AppointmentModification::where('user_id', $serviceUser['user_id'])->where('unavailable_date', $data['appointment_from_date'])->where(function ($q) use ($data) {
                                        $q->where(function ($q1) use ($data) {
                                            $q1->whereIn('type', [\Constants\ConstAppointmentModificationType::UnavailableParticularDateAndTime, \Constants\ConstAppointmentModificationType::UnavailableInEveryParticularDayAndTime]); 
                                            $q1->where(function($q2) use($data) {
                                                $q2->where(function($q3)use($data){
                                                    $q3->where('day', ucfirst(date('l', strtotime($data['appointment_from_date']))));
                                                    $q3->orWhere('day', 'AllDay');
                                                });
                                                $q2->where(function($q3)use($data){
                                                    $q3->where('unavailable_from_time','<=', $data['appointment_from_time']);
                                                    $q3->where('unavailable_to_time','>=', $data['appointment_to_time']);
                                                });
                                                $q2->orWhere(function($q3) use($data)
                                                {
                                                    $q3->where('unavailable_from_time','>=', $data['appointment_from_time']);
                                                    $q3->where('unavailable_to_time','<=', $data['appointment_to_time']);
                                                });
                                                $q2->orWhere(function($q3) use($data)
                                                {
                                                    $q3->whereBetween('unavailable_from_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                    $q3->orWhereBetween('unavailable_to_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                });
                                            });
                                        })->orWhere(function ($q1) use ($data) {
                                            $q1->where('type', \Constants\ConstAppointmentModificationType::MakeADayFullyOff);
                                        });
                                    })->first();
                                $exitsAppointment = Appointment::where('provider_user_id', $serviceUser['user_id'])
                                            ->where('appointment_status_id', \Constants\ConstAppointmentStatus::Approved)->where(function($q2) use($data) {
                                            $q2->where(function($q3)use($data){
                                                $q3->where('appointment_from_time','<=', $data['appointment_from_time']);
                                                $q3->where('appointment_to_time','>=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->where('appointment_from_time','>=', $data['appointment_from_time']);
                                                $q3->where('appointment_to_time','<=', $data['appointment_to_time']);
                                            });
                                            $q2->orWhere(function($q3) use($data)
                                            {
                                                $q3->whereBetween('appointment_from_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                                $q3->orWhereBetween('appointment_from_time', [$data['appointment_from_time'], $data['appointment_to_time']]);
                                            });
                                        })->first();                                       
                                if ((!empty($exitsAppointment) && empty($serviceUser->service->is_enable_multiple_booking) || !empty($appoinment_modification)) && empty($data['is_appointment_for_interview'])) {
                                    $validation['error']['appointment_from_date'] = ['This provider not available on this date'];
                                } else {
                                    $validation['data']['appointment_from_date'] = $data['appointment_from_date'];
                                    $validation['data']['appointment_from_time'] = $data['appointment_from_time'];
                                    $validation['data']['appointment_to_time'] = date('H:i:s', strtotime("+" . $appointmentSetting->calendar_slot_id . " minutes", strtotime($data['appointment_from_time'])));
                                    $validation['data']['no_of_days'] = 1;                                    
                                    $validation['data']['total_booking_amount'] = round($serviceUser->rate, 2);
                                    $rate = $serviceUser['rate']; 
                                    $number_of_item = 1;
                                    if(!empty($data['number_of_item']) && $data['number_of_item'] > 1){
                                        $number_of_item = $data['number_of_item'];
                                    }  
                                    $rate = $rate * $number_of_item;                                 
                                    $minutes = (strtotime($validation['data']['appointment_to_time']) - strtotime($validation['data']['appointment_from_time']))/60;
                                    $hours = ceil($minutes/60);
                                    $validation['data']['booked_minutes'] = $minutes;
                                    $validation['data']['total_booking_amount'] = $rate * $hours;             
                                }
                            }  
                            break;                          
                        default:
                            $validation['error']['required'] = ['Invalid booking option id'];
                    }
                } else {
                    $validation['error']['appointment_from_date'] = ['From date must be greater than or equal to current date.'];
                }
            } else {
                $validation['error']['services_user'] = ['Service Not Found'];
            }
        } else {
            $validation['error']['services_user'] = ['Invalid Service User'];
        }
        return $validation;
    }
}

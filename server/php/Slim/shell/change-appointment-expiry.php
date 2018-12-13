<?php
/**
 * Sample cron file
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
require_once '../lib/bootstrap.php';
use Carbon\Carbon;
$date = Carbon::parse(Carbon::now()->subHours(BOOKING_EXPIRING_HOURS))->format('Y-m-d H:i:s');
$appointments = Models\Appointment::where('created_at', '<=', $date)->where('appointment_status_id', '=', \Constants\ConstAppointmentStatus::PendingApproval)->get();
if (!empty($appointments)) {
    foreach ($appointments as $appointment) {
        if (AFFILIATE_AMOUNT_REFUND_WHEN_APPOINTMENT_EXPIRE && isPluginEnabled('Referral/Referral')) {
            if (!empty($appointment['used_affiliate_amount'])) {
                $user = Models\User::find($appointment['user_id']);
                if (!empty($user)) {
                    $user->affiliate_pending_amount = $user->affiliate_pending_amount + $appointment['used_affiliate_amount'];
                    $user->affiliate_paid_amount = $user->affiliate_paid_amount - $appointment['used_affiliate_amount'];
                    $user->update();
                }
            }
        }
        if (isPluginEnabled('PaymentBooking/PaymentBooking') && $appointment->payment_gateway_id == \Constants\PaymentGateways::PayPal && $appointment->payment_type == 'authorize' && !empty($appointment->authorization_id)) {
            voidPayment($appointment->authorization_id);
        }
        $appointment->appointment_status_id = \Constants\ConstAppointmentStatus::Expired;
        $appointment->update();
        $service_username = $appointment->provider_user->email;
        $requestor_username = $appointment->user->email;
        if (!empty($appointment->provider_user->user_profile->first_name) || !empty($appointment->provider_user->user_profile->last_name)) {
          $service_username =  $appointment->provider_user->user_profile->first_name . ' ' .$appointment->provider_user->user_profile->last_name;
        }
        if (!empty($appointment->user->user_profile->first_name) || !empty($appointment->user->user_profile->last_name)) {
          $requestor_username =  $appointment->user->user_profile->first_name . ' ' .$appointment->user->user_profile->last_name;
        }
        $emailFindReplace = array(
            '##SERVICE_PROVIDER##' => $service_username,
            '##REQUESTOR_NAME##' => $requestor_username,
            '##APPOINTMENT_FROM_DATE##' => $appointment->appointment_from_date,
        );
        sendMail('Service Request Expired', $emailFindReplace,  $appointment->user->email);
    }
}

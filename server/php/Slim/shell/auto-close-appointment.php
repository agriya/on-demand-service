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
if(!empty(AUTO_CLOSE_DAYS))
{
    $date = Carbon::parse(Carbon::now()->subDays(AUTO_CLOSE_DAYS))->format('Y-m-d');
    $appointments = Models\Appointment::where('appointment_to_date', '<', $date)->where('appointment_status_id', '=', \Constants\ConstAppointmentStatus::Completed)->get();
    if (!empty($appointments)) {
        foreach ($appointments as $appointment) {
            Models\Appointment::closeAppointment($appointment->id);
        }
    }
}


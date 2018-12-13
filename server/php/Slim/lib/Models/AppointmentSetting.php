<?php
namespace Models;

/**
 * Class State
 * @package App
 */
class AppointmentSetting extends AppModel
{
    /**
     * @var string
     */
    protected $table = "appointment_settings";
    protected $casts = [
        'user_id' => 'integer',
        'is_today_first_day' => 'integer',
        'calendar_slot_id' => 'integer',
        'type' => 'integer',
        'is_sunday_open' => 'integer',
        'is_monday_open' => 'integer',
        'is_tuesday_open' => 'integer',
        'is_wednesday_open' => 'integer',
        'is_thursday_open' => 'integer',
        'is_friday_open' => 'integer',
        'is_saturday_open' => 'integer'                             
    ];    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'is_today_first_day', 'calendar_slot_id', 'practice_open', 'practice_close', 'type', 'is_sunday_open', 'sunday_practice_open', 'sunday_practice_close', 'is_monday_open', 'monday_practice_open', 'monday_practice_close', 'is_tuesday_open', 'tuesday_practice_open', 'tuesday_practice_close', 'is_wednesday_open', 'wednesday_practice_open', 'wednesday_practice_close', 'is_thursday_open', 'thursday_practice_open', 'thursday_practice_close', 'is_friday_open', 'friday_practice_open', 'friday_practice_close', 'is_saturday_open', 'saturday_practice_open', 'saturday_practice_close', 'is_enable_multiple_booking', 'recurring_calendar_slot_id'];
    public $rules = array(
        'user_id' => 'sometimes|required'
    );
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function saveAppointment($request_data)
    {
        $appointement_settings = new AppointmentSetting;
        $appointement_settings->user_id = $request_data['user_id'];
        $appointement_settings->calendar_slot_id = 60;
        $appointement_settings->practice_open = date('Y-m-d') . ' 00:00:00';
        $appointement_settings->practice_close = date('Y-m-d') . ' 23:59:59';
        return $appointement_settings;
    }
    public function validateAppointmentSettings($args)
    {
        $validationStatus = true;
        if (!empty($args['is_sunday_open']) && (empty($args['sunday_practice_open']) || empty($args['sunday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Sunday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_sunday_open'])) {
            $args['sunday_practice_open'] = null;
            $args['sunday_practice_close'] = null;
        }
        if (!empty($args['is_monday_open']) && (empty($args['monday_practice_open']) || empty($args['monday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Monday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_monday_open'])) {
            $args['monday_practice_open'] = null;
            $args['monday_practice_close'] = null;
        }
        if (!empty($args['is_tuesday_open']) && (empty($args['tuesday_practice_open']) || empty($args['tuesday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Tuesday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_tuesday_open'])) {
            $args['tuesday_practice_open'] = null;
            $args['tuesday_practice_close'] = null;
        }
        if (!empty($args['is_wednesday_open']) && (empty($args['wednesday_practice_open']) || empty($args['wednesday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Wednesday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_wednesday_open'])) {
            $args['wednesday_practice_open'] = null;
            $args['wednesday_practice_close'] = null;
        }
        if (!empty($args['is_thursday_open']) && (empty($args['thursday_practice_open']) || empty($args['thursday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Thrusday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_thursday_open'])) {
            $args['thursday_practice_open'] = null;
            $args['thursday_practice_close'] = null;
        }
        if (!empty($args['is_friday_open']) && (empty($args['friday_practice_open']) || empty($args['friday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Friday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_friday_open'])) {
            $args['friday_practice_open'] = null;
            $args['friday_practice_close'] = null;
        }
        if (!empty($args['is_saturday_open']) && (empty($args['saturday_practice_open']) || empty($args['saturday_practice_close']))) {
            $validationStatus = false;
            $type_validation_error['required'] = array(
                'Saturday practice open and practice close is required'
            );
            $validationError[] = $type_validation_error;
        } elseif (empty($args['is_saturday_open'])) {
            $args['saturday_practice_open'] = null;
            $args['saturday_practice_close'] = null;
        }
        $result['validationStatus'] = $validationStatus;
        $result['required'] = $validationError;
        return $result;
    }
    /*public function get_doctors_appointment_details($userIds, $viewSlot = 1, $isUserProfileNeeded = 1, $isWeb, $reivew, $category_id)
    {
        if (!empty($userIds)) {
            // For get the appointment setting details 
            $appoinmentSettings = AppointmentSetting::whereIn('user_id', $userIds)->get()->toArray();
            if (!empty($appoinmentSettings)) {
                // addDate() is based on today date process if we need current date and day just pass empty function 
                $daysArray = [0 => AppointmentSetting::addDate(0, $viewSlot) , 1 => AppointmentSetting::addDate(1, $viewSlot) , 2 => AppointmentSetting::addDate(2, $viewSlot) , 3 => AppointmentSetting::addDate(3, $viewSlot) , 4 => AppointmentSetting::addDate(4, $viewSlot) , 5 => AppointmentSetting::addDate(5, $viewSlot) , 6 => AppointmentSetting::addDate(6, $viewSlot) , ];
                //$multiple_slot_enable = 0;
                if ($category_id != '') {
                    $category = Category::where('id', '=', $category_id)->first()->toArray();
                    if ($category['is_enable_multiple_booking'] == 1) {
                        $multiple_slot_enable = 1;
                    } else {
                        $multiple_slot_enable = 0;
                    }
                } else {
                    $multiple_slot_enable = 0;
                }
                $settingFrom = $daysArray[0]['date'];
                $settingTo = $daysArray[6]['date'];
                foreach ($appoinmentSettings as $appointmentSetting) {
                    $modifiedAppointmentDetails = AppointmentModification::get_doctor_appointment_modificaiton_details($appointmentSetting['user_id'], $settingFrom, $settingTo);
                    // For build the modified setting process 
                    if (!empty($modifiedAppointmentDetails)) {
                        $buildWeek = '';
                        foreach ($modifiedAppointmentDetails as $modifiedAppointmentDetail) {
                            $calenderBuildDay[] = AppointmentSetting::getDatebyDay($modifiedAppointmentDetail['unavailable_date']);
                            if (in_array($modifiedAppointmentDetail['unavailable_date'], array_column($daysArray, 'date'))) {
                                $day['day'] = AppointmentSetting::getDatebyDay($modifiedAppointmentDetail['unavailable_date']);
                                if ($modifiedAppointmentDetail['type'] == 1) {
                                    $buildWeek[$day['day']] = [0 => '--'];
                                } else {
                                    $buildWeek[$day['day']] = '';
                                    if (!empty($modifiedAppointmentDetail['unavailable_from_time'])) {
                                        foreach (explode(',', $modifiedAppointmentDetail['unavailable_from_time']) as $timeValue) {
                                            $buildWeek[$day['day']][] = $timeValue;
                                        }
                                    } else {
                                        $buildWeek[$day['day']] = ['0' => '--'];
                                    }
                                }
                            }
                        }
                        if (count($buildWeek) != 7) {
                            $otherBuildDays = array_diff(array_column($daysArray, 'day'), $calenderBuildDay);
                            $otherDaysbuildWeek = AppointmentSetting::appointmentsettings_calender_build($otherBuildDays, $appointmentSetting);
                            $buildWeek = array_merge($buildWeek, $otherDaysbuildWeek);
                        }
                    } else {
                        $buildWeek = AppointmentSetting::appointmentsettings_calender_build(array_column($daysArray, 'day'), $appointmentSetting);
                    }
                    if ($isUserProfileNeeded) {
                        // For get the Doctor Profile Details 
                        $doctorDetailValue = UserProfile::with('user', 'listing_city', 'listing_state', 'listing_country', 'gender')->where('user_id', $appointmentSetting['user_id'])->first()->toArray();
                        $doctorDetailValue['starRating'] = User::scopeStarRating($appointmentSetting['user_id']); // here need to add the start rating calculation 
                        $calenderDetails[$appointmentSetting['user_id']] = array_merge($doctorDetailValue, $buildWeek);
                    } else {
                        $calenderDetails = $buildWeek;
                    }
                    // For Removed Booked Appointment Details Here 
                    $appointmentDetails = Appointment::get_doctor_booking_appointments($appointmentSetting['user_id'], $settingFrom, $settingTo);
                    if (!empty($appointmentDetails)) {
                        foreach ($appointmentDetails as $appointmentDetail) {
                            $checkDay = date('D', strtotime($appointmentDetail['appointment_from_date']));
                            if ($isUserProfileNeeded) {
                                if (!empty($calenderDetails[$appointmentSetting['user_id']][$checkDay])) {
                                    if (($key = array_search($appointmentDetail['appointment_from_time'], $calenderDetails[$appointmentSetting['user_id']][$checkDay])) !== false) {
                                        if ($multiple_slot_enable == 0) {
                                            unset($calenderDetails[$appointmentSetting['user_id']][$checkDay][$key]);
                                        }
                                        if (!empty($calenderDetails[$appointmentSetting['user_id']][$checkDay])) {
                                            $calenderDetails[$appointmentSetting['user_id']][$checkDay] = array_values($calenderDetails[$appointmentSetting['user_id']][$checkDay]);
                                        } else {
                                            $calenderDetails[$appointmentSetting['user_id']][$checkDay] = [0 => '--'];
                                        }
                                    }
                                }
                            } else {
                                if (!empty($calenderDetails[$checkDay])) {
                                    if (($key = array_search($appointmentDetail['appointment_from_time'], $calenderDetails[$checkDay])) !== false) {
                                        if ($multiple_slot_enable == 0) {
                                            unset($calenderDetails[$checkDay][$key]);
                                        }
                                        if (!empty($calenderDetails[$checkDay])) {
                                            $calenderDetails[$checkDay] = array_values($calenderDetails[$checkDay]);
                                        } else {
                                            $calenderDetails[$checkDay] = [0 => '--'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // Here Removed For Current Date & Time Before Appointment Time 
                    if ($viewSlot == 1) {
                        $todayDay = $daysArray[0]['day'];
                        $format = SITE_TIMEFORMAT;
                        if ($format == 12) {
                            $currentTime = date('h:i A');
                        } else {
                            $currentTime = date('H:i');
                        }
                        if ($isUserProfileNeeded) {
                            $todayCond = $calenderDetails[$appointmentSetting['user_id']][$todayDay];
                        } else {
                            $todayCond = $calenderDetails[$todayDay];
                        }
                        //echo '<pre>'; print_r($todayCond); die;
                        if (!empty($todayCond)) {
                            if ($format == 12) {
                                $filterValue = array_filter($todayCond, function ($timeValue) use ($currentTime) {
                                    return date('H:i', strtotime($timeValue)) > date('H:i', strtotime($currentTime));
                                });
                            } else {
                                $filterValue = array_filter($todayCond, function ($timeValue) use ($currentTime) {
                                    return $timeValue > $currentTime;
                                });
                            }
                            if ($isUserProfileNeeded) {
                                unset($calenderDetails[$appointmentSetting['user_id']][$todayDay]);
                                if (!empty($filterValue)) {
                                    $calenderDetails[$appointmentSetting['user_id']][$todayDay] = array_values($filterValue);
                                } else {
                                    $calenderDetails[$appointmentSetting['user_id']][$todayDay] = [0 => '--'];
                                }
                            } else {
                                unset($calenderDetails[$todayDay]);
                                if (empty(!$filterValue)) {
                                    $calenderDetails[$todayDay] = array_values($filterValue);
                                } else {
                                    $calenderDetails[$todayDay] = [0 => '--'];
                                }
                            }
                        }
                    }
                    if ($isWeb == null) {
                        // for find and fill the empty block 
                        $maxCountDay = '';
                        foreach ($daysArray as $day) {
                            if ($isUserProfileNeeded) {
                                $maxCountDay[] = count($calenderDetails[$appointmentSetting['user_id']][$day['day']]);
                            } else {
                                $maxCountDay[] = count($calenderDetails[$day['day']]);
                            }
                        }
                        $maxCount = max($maxCountDay);
                        foreach ($daysArray as $day) {
                            if ($isUserProfileNeeded) {
                                $objCountVal = count($calenderDetails[$appointmentSetting['user_id']][$day['day']]);
                                if ($maxCount != $objCountVal) {
                                    $fillVal = $maxCount - $objCountVal;
                                    $calenderDetails[$appointmentSetting['user_id']][$day['day']] = array_merge($calenderDetails[$appointmentSetting['user_id']][$day['day']], array_fill($objCountVal, $fillVal, '--'));
                                }
                            } else {
                                $objCountVal = count($calenderDetails[$day['day']]);
                                if ($maxCount != $objCountVal) {
                                    $fillVal = $maxCount - $objCountVal;
                                    $calenderDetails[$day['day']] = array_merge($calenderDetails[$day['day']], array_fill($objCountVal, $fillVal, '--'));
                                }
                            }
                        }
                    }
                }
                return $calenderDetails;
            }
        } else {
            return $calenderDetails = '';
        }
    }*/
    /*public function getDatebyDay($date)
    {
        return date('D', strtotime($date));
    }*/
    /*public function appointmentsettings_calender_build($daysArray = array(), $appointmentSetting)
    {
        if (!empty($daysArray)) {
            foreach ($daysArray as $otherDay) {
                if ($appointmentSetting['type'] == 0) {
                    if ($appointmentSetting['is_two_session'] == 1) {
                        // Before Break Here 
                        $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['practice_open'], $appointmentSetting['lunch_at'], $appointmentSetting['calendar_slot_id']);
                        // After the Break Here 
                        $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['resume_at'], $appointmentSetting['practice_close'], $appointmentSetting['calendar_slot_id']);
                        $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                    } else {
                        $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['practice_open'], $appointmentSetting['practice_close'], $appointmentSetting['calendar_slot_id']);
                    }
                } else {
                    // For Check individual and set the time for all days 
                    if ($otherDay == 'Mon') {
                        if ($appointmentSetting['is_monday_open'] == 1) {
                            if ($appointmentSetting['monday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['monday_practice_open'], $appointmentSetting['monday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['monday_resume_at'], $appointmentSetting['monday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['monday_practice_open'], $appointmentSetting['monday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    } elseif ($otherDay == 'Tue') {
                        if ($appointmentSetting['is_tuesday_open'] == 1) {
                            if ($appointmentSetting['tuesday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['tuesday_practice_open'], $appointmentSetting['tuesday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['tuesday_resume_at'], $appointmentSetting['tuesday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['tuesday_practice_open'], $appointmentSetting['tuesday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    } elseif ($otherDay == 'Wed') {
                        if ($appointmentSetting['is_wednesday_open'] == 1) {
                            if ($appointmentSetting['wednesday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['wednesday_practice_open'], $appointmentSetting['wednesday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['wednesday_resume_at'], $appointmentSetting['wednesday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['wednesday_practice_open'], $appointmentSetting['wednesday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    } elseif ($otherDay == 'Thu') {
                        if ($appointmentSetting['is_thursday_open'] == 1) {
                            if ($appointmentSetting['thrusday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['thursday_practice_open'], $appointmentSetting['thrusday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['thrusday_resume_at'], $appointmentSetting['thursday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['thursday_practice_open'], $appointmentSetting['thursday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    } elseif ($otherDay == 'Fri') {
                        if ($appointmentSetting['is_friday_open'] == 1) {
                            if ($appointmentSetting['friday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['friday_practice_open'], $appointmentSetting['friday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['friday_resume_at'], $appointmentSetting['friday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['friday_practice_open'], $appointmentSetting['friday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    } elseif ($otherDay == 'Sat') {
                        if ($appointmentSetting['is_saturday_open'] == 1) {
                            if ($appointmentSetting['saturday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['saturday_practice_open'], $appointmentSetting['saturday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['saturday_resume_at'], $appointmentSetting['saturday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['saturday_practice_open'], $appointmentSetting['saturday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    } elseif ($otherDay == 'Sun') {
                        if ($appointmentSetting['is_sunday_open'] == 1) {
                            if ($appointmentSetting['sunday_two_session'] == 1) {
                                // Before Break Here 
                                $beforeBreak = AppointmentSetting::getTimeSlot($appointmentSetting['sunday_practice_open'], $appointmentSetting['sunday_lunch_at'], $appointmentSetting['calendar_slot_id']);
                                // After the Break Here 
                                $afterBreak = AppointmentSetting::getTimeSlot($appointmentSetting['sunday_resume_at'], $appointmentSetting['sunday_practice_close'], $appointmentSetting['calendar_slot_id']);
                                $buildWeek[$otherDay] = array_merge($beforeBreak, $afterBreak);
                            } else {
                                $buildWeek[$otherDay] = AppointmentSetting::getTimeSlot($appointmentSetting['sunday_practice_open'], $appointmentSetting['sunday_practice_close'], $appointmentSetting['calendar_slot_id']);
                            }
                        } else {
                            $buildWeek[$otherDay] = [0 => '--'];
                        }
                    }
                }
            }
        } else {
            $buildWeek = '';
        }
        return $buildWeek;
    }*/
    /*public function getTimeSlot($startTime, $endTime, $interval)
    {
        // For Split the time slot here 
        $format = SITE_TIMEFORMAT;
        $start = new \DateTime(date('Y-m-d') . $startTime);
        $end = new \DateTime(date('Y-m-d') . $endTime);
        if ($format == 12) {
            $timeSlotList[] = $start->modify('+0 minutes')->format('h:i A'); // For add the start time has current time 
        } else {
            $timeSlotList[] = $start->modify('+0 minutes')->format('H:i'); // For add the start time has current time 
        }
        while ($start < $end) {
            if ($format == 12) {
                $timeSlotList[] = $start->modify('+' . $interval . 'minutes')->format('h:i A');
            } else {
                $timeSlotList[] = $start->modify('+' . $interval . 'minutes')->format('H:i');
            }
        }
        return $timeSlotList;
    }*/
/*    public function addDate($days = 0, $multipleCount = 1)
    {
        // Here calculate the added day or days 
        if ($multipleCount > 1) {
            $addDays = ($multipleCount - 1) * 7 + $days;
        } else {
            $addDays = $days;
        }
        // Here start date added process 
        if (($days == 0) && ($multipleCount == 1)) {
            return ['day' => date('D') , 'date' => date('Y-m-d') ];
        } elseif (($days == 0) && ($multipleCount > 1)) {
            $days = ($multipleCount - 1) * 7;
            $todayDate = date('Y-m-d');
            $date = ['day' => date('D', strtotime($todayDate . "+" . $days . " days")) , 'date' => date('Y-m-d', strtotime($todayDate . "+" . $days . " days")) , ];
            return $date;
        } else {
            $todayDate = date('Y-m-d');
            $date = ['day' => date('D', strtotime($todayDate . "+" . $addDays . " days")) , 'date' => date('Y-m-d', strtotime($todayDate . "+" . $addDays . " days")) , ];
            return $date;
        }
    }*/
}

<?php
/**
 * Request
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Home Assistant
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;
use Illuminate\Database\Eloquent\Builder;

/*
 * Request
 */
class Request extends AppModel
{
    protected $table = 'requests';
    protected $fillable = array(
        'user_id','service_id','request_status_id','job_type_id','appointment_timing_type_id','description','appointment_from_date','appointment_from_time','appointment_to_date','appointment_to_time','price_per_hour','work_location_address','work_location_address1','work_location_state_id','sunday_appointment_from_time','work_location_city_id','work_location_country_id','payment_mode_id','work_location_postal_code','sunday_appointment_to_time','number_of_item','is_monday_needed','tuesday_appointment_to_time','monday_appointment_from_time','monday_appointment_to_time','tuesday_appointment_from_time','is_tuesday_needed','friday_appointment_from_time','wednesday_appointment_from_time','wednesday_appointment_to_time','is_thursday_needed','thursday_appointment_from_time','thursday_appointment_to_time','is_friday_needed','is_wednesday_needed','requests_user_count','is_saturday_needed','friday_appointment_to_time','saturday_appointment_from_time','saturday_appointment_to_time','is_sunday_needed','work_location_latitude','work_location_longitude', 'work_location_sw_longitude', 'work_location_sw_latitude', 'work_location_ne_longitude', 'work_location_ne_latitude'
    );
    public $rules = array(
        'user_id' => 'sometimes|required','service_id' => 'sometimes|required','request_status_id' => 'sometimes|required','job_type_id' => 'sometimes|required','appointment_timing_type_id' => 'sometimes|required','description' => 'sometimes|required','appointment_from_date' => 'sometimes|required','appointment_from_time' => 'sometimes|required','appointment_to_date' => 'sometimes|required','appointment_to_time' => 'sometimes|required','price_per_hour' => 'sometimes|required','work_location_address' => 'sometimes|required','work_location_address1' => 'sometimes|required','work_location_state_id' => 'sometimes|required','sunday_appointment_from_time' => 'sometimes|required','work_location_city_id' => 'sometimes|required','work_location_country_id' => 'sometimes|required','payment_mode_id' => 'sometimes|required','work_location_postal_code' => 'sometimes|required','sunday_appointment_to_time' => 'sometimes|required','number_of_item' => 'sometimes|required','is_monday_needed' => 'sometimes|required','tuesday_appointment_to_time' => 'sometimes|required','monday_appointment_from_time' => 'sometimes|required','monday_appointment_to_time' => 'sometimes|required','tuesday_appointment_from_time' => 'sometimes|required','is_tuesday_needed' => 'sometimes|required','friday_appointment_from_time' => 'sometimes|required','wednesday_appointment_from_time' => 'sometimes|required','wednesday_appointment_to_time' => 'sometimes|required','is_thursday_needed' => 'sometimes|required','thursday_appointment_from_time' => 'sometimes|required','thursday_appointment_to_time' => 'sometimes|required','is_friday_needed' => 'sometimes|required','is_wednesday_needed' => 'sometimes|required','requests_user_count' => 'sometimes|required','is_saturday_needed' => 'sometimes|required','friday_appointment_to_time' => 'sometimes|required','saturday_appointment_from_time' => 'sometimes|required','saturday_appointment_to_time' => 'sometimes|required','is_sunday_needed' => 'sometimes|required' , 'work_location_latitude' => 'sometimes|required' , 'work_location_longitude' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo('Models\Service', 'service_id', 'id');
    }
    public function work_location_state()
    {
        return $this->belongsTo('Models\State', 'work_location_state_id', 'id');
    }
    public function work_location_city()
    {
        return $this->belongsTo('Models\City', 'work_location_city_id', 'id');
    }
    public function work_location_country()
    {
        return $this->belongsTo('Models\Country', 'work_location_country_id', 'id');
    }
    public function current_user_interest()
    {
        $user_id = 0;
        global $authUser;
        if (!empty($authUser)) {
            $user_id = $authUser['id'];
        }
        return $this->hasOne('Models\RequestsUser', 'request_id', 'id')->where('user_id', $user_id);
    }  
    public function requests_users()
    {
        return $this->hasMany('Models\RequestsUser', 'request_id', 'id');
    }      
    public function form_field_submission()
    {
        return $this->hasMany('Models\FormFieldSubmission', 'foreign_id', 'id')->where('class','Request');
    }      
    public function blocker()
    {
        global $authUser;       
        return $this->hasOne('Models\BlockedUser', 'user_id', 'user_id')->where('blocked_user_id',$authUser['id']);
    }
    public function blocking()
    {
        global $authUser;
        return $this->hasOne('Models\BlockedUser', 'blocked_user_id', 'user_id')->where('user_id',$authUser['id']);
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        if ($authUser && isPluginEnabled('BlockedUser/BlockedUser')) {
            static::addGlobalScope(function(Builder $builder) use($authUser) {
                $builder->doesntHave('blocker')->doesntHave('blocking');
            });
        }  
        self::saved(function ($data) use ($authUser) {           
            $request_count = Request::where('user_id', $data['user_id'])->count();
            User::where('id', $data['user_id'])->update(['request_count' => $request_count]);
        });
        self::deleted(function ($data) use ($authUser) {
            $request_count = Request::where('user_id', $data['user_id'])->count();
            User::where('id', $data['user_id'])->update(['request_count' => $request_count]);   
        });
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['work_location_latitude']) && !empty($params['work_location_longitude'])) {
            $radius = 500;
            if (!empty($params['radius'])) {
                $radius = $params['radius'];
            }
            $distance = 'ROUND(( 6371 * acos( cos( radians(' . $params['work_location_latitude'] . ') ) * cos( radians( work_location_latitude ) ) * cos( radians( work_location_longitude ) - radians(' . $params['work_location_longitude'] . ')) + sin( radians(' . $params['work_location_latitude'] . ') ) * sin( radians( work_location_latitude ) ) )))';
            $query->select('*')->selectRaw($distance . ' AS distance')->whereRaw('(' . $distance . ')<=' . $radius);
        }
        if (!empty($params['listing_ne_latitude']) && !empty($params['listing_ne_longitude']) && !empty($params['listing_sw_latitude']) && !empty($params['listing_sw_longitude'])) {
            $lon1 = round($params['listing_sw_longitude'], 6);
            $lon2 = round($params['listing_ne_longitude'], 6);
            $lat1 = round($params['listing_sw_latitude'], 6);
            $lat2 = round($params['listing_ne_latitude'], 6);
            $query->whereBetween('work_location_latitude', [$lat1, $lat2])->whereBetween('work_location_longitude', [$lon1, $lon2]);
        }        
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->WhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%$search%");
                });
                $q1->orWhereHas('service', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });                
            });
        }
    }
    public function specificArrayValidation($args){
        $validationErrorFields = array();
            $days_array = array(
                'is_sunday_needed' => array(
                    'sunday_appointment_from_time',
                    'sunday_appointment_to_time'
                ),
                'is_monday_needed' => array(
                    'monday_appointment_from_time',
                    'monday_appointment_to_time'
                ),
                'is_tuesday_needed' => array(
                    'tuesday_appointment_from_time',
                    'tuesday_appointment_to_time'
                ),
                'is_wednesday_needed' => array(
                    'wednesday_appointment_from_time',
                    'wednesday_appointment_to_time'
                ),
                'is_thursday_needed' => array(
                    'thursday_appointment_from_time',
                    'thursday_appointment_to_time'
                ),
                'is_friday_needed' => array(
                    'friday_appointment_from_time',
                    'friday_appointment_to_time'
                ),
                'is_saturday_needed' => array(
                    'saturday_appointment_from_time',
                    'saturday_appointment_to_time'
                )
            );
            foreach($days_array as $key => $day){
                if(!empty($args[$key])){
                    if(empty($args[$day[0]])){
                        $validation['error'][$day[0]] = [$day[0].' is required'];
                        $validationErrorFields = array_merge($validationErrorFields, $validation);
                    }
                    if(empty($args[$day[1]])){
                        $validation['error'][$day[1]] = [$day[1].' is required'];
                        $validationErrorFields = array_merge($validationErrorFields, $validation);
                    }                    
                }
            }
        return $validationErrorFields;
    }    
}

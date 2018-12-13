<?php
namespace Models;

/**
 * Class State
 * @package App
 */
class AppointmentModification extends AppModel
{
    /**
     * @var string
     */
    protected $table = "appointment_modifications";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'unavailable_date', 'unavailable_from_time', 'type', 'unavailable_to_time', 'day'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->whereHas('user', function ($q) use ($params, $search) {
                $q->where('username', 'LIKE', "%$search%");
            });
        }
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::saving(function ($data) use ($authUser) {
            if (($authUser['role_id'] != \Constants\ConstUserTypes::Admin)) {
                $data['user_id'] = $authUser->id;
            }
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
    }
    public $rules = array(
        'type' => 'sometimes|required'
    );
/*    public function get_doctor_appointment_modificaiton_details($doctorId, $startDate = null, $endDate = null)
    {
        if ($startDate == null && $endDate == null) {
            $modifiedAppointmentSettings = AppointmentModification::where('unavailable_date', '>=', date('Y-m-d'))->where(['user_id' => $doctorId])->get();
        } else {
            $modifiedAppointmentSettings = AppointmentModification::whereBetween('unavailable_date', [$startDate, $endDate])->where(['user_id' => $doctorId])->get();
        }
        return $modifiedAppointmentSettings->toArray();
    }*/
}

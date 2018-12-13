<?php
namespace Models;

/**
 * Class AppointmentStatus
 * @package App
 */
class AppointmentStatus extends AppModel
{
    /**
     * @var string
     */
    protected $table = "appointment_statuses";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'appointment_count'];
    public function appointment()
    {
        return $this->hasMany('Models\Appointment', 'appointment_status_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'like', "%$search%");
            });
        }
    }
}

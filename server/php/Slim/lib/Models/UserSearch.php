<?php
/**
 * UserSearch
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

/*
 * UserSearch
 */
class UserSearch extends AppModel
{
    public $table = 'user_searches';
    public $fillable = array(
        'user_id',
        'service_id',
        'name',
        'address',
        'address1',
        'latitude',
        'longitude',
        'sw_latitude',
        'sw_longitude',
        'ne_latitude',
        'ne_longitude',
        'radius',
        'form_field_submissions',
        'filter_count',
        'notification_type_id'
    );
    public $rules = array(
        'user_id' => 'sometimes|required',
        'service_id' => 'sometimes|required',
        'name' => 'sometimes|required',
        'address' => 'sometimes|required',
        'address1' => 'sometimes|required',
        'latitude' => 'sometimes|required',
        'longitude' => 'sometimes|required',
        'sw_latitude' => 'sometimes|required',
        'sw_longitude' => 'sometimes|required',
        'ne_latitude' => 'sometimes|required',
        'ne_longitude' => 'sometimes|required',
        'radius' => 'sometimes|required',
        'form_field_submissions' => 'sometimes|required',
        'filter_count' => 'sometimes|required',
        'notification_type_id' => 'sometimes|required'        
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo('Models\Service', 'service_id', 'id');
    }
    public function blocker()
    {            
        return $this->hasOne('Models\BlockedUser', 'user_id', 'user_id');
    }
    public function blocking()
    {        
        return $this->hasOne('Models\BlockedUser', 'blocked_user_id', 'user_id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('name', 'like', "%$search%");
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%$search%");
                });
                $q1->orWhereHas('service', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });                
            });
        }        
    }    
}

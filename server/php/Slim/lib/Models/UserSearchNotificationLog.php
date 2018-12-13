<?php
/**
 * UserSearchNotificationLog
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
 * UserSearchNotificationLog
 */
class UserSearchNotificationLog extends AppModel
{
    public $table = 'user_search_notification_logs';
    public $fillable = array(
        'user_id',
        'service_provider_id',
        'user_search_id'
    );
    public $rules = array(
        'user_id' => 'sometimes|required',
        'service_provider_id' => 'sometimes|required',
        'user_search_id' => 'sometimes|required'        
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function service_provider()
    {
        return $this->belongsTo('Models\ServiceProvider', 'service_provider_id', 'id');
    }
    public function user_search()
    {
        return $this->belongsTo('Models\UserSearch', 'user_search_id', 'id');
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
                $q1->orWhereHas('service_provider', function ($q) use ($search) {
                    $q->where('username', 'like', "%$search%");
                });    
                $q1->orWhereHas('user_search', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });                              
            });
        }        
    }       
}

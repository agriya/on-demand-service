<?php
/**
 * RequestsUser
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
 * RequestsUser
 */
class RequestsUser extends AppModel
{
    public $table = 'requests_users';
    public $fillable = array(
        'request_id','user_id'
    );
    public $rules = array(
        'request_id' => 'sometimes|required','user_id' => 'sometimes|required'
    );
    public function request()
    {
        return $this->belongsTo('Models\Request', 'request_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->WhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%$search%");
                });                
            });
        }
    }  
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::saved(function ($data) use ($authUser) {
            $requests_user_count = RequestsUser::where('request_id', $data['request_id'])->count();
            Request::where('id', $data['request_id'])->update(['requests_user_count' => $requests_user_count]);
        });
        self::deleted(function ($data) use ($authUser) {
            $requests_user_count = RequestsUser::where('request_id', $data['request_id'])->count();
            Request::where('id', $data['request_id'])->update(['requests_user_count' => $requests_user_count]);   
        });
    }      
}

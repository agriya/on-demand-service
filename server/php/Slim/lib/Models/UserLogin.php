<?php
/**
 * UserLogin
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Hitekwonder
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * UserLogin
*/
class UserLogin extends AppModel
{
    protected $table = 'user_logins';
    public $rules = array();
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    protected static function boot()
    {
        parent::boot();
        self::saved(function ($log_user) {
            User::where('id', $log_user->user_id)->increment('user_login_count', 1);
        });
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('user_agent', 'like', "%$search%");
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%$search%");
                });
            });
        }
    }
}

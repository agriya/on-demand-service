<?php
/**
 * BlockedUser
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Lash
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;



/*
 * BlockedUser
 */
class BlockedUser extends AppModel
{
    protected $table = 'blocked_users';
    protected $casts = [
        'user_id' => 'integer',
        'blocked_user_id' => 'integer'
    ];      
    protected $fillable = array(
        'blocked_user_id',
        'user_id'
    );
    public $rules = array(
        'user_id' => 'sometimes|required',
        'blocked_user_id' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function blocked_user()
    {
        return $this->belongsTo('Models\User', 'blocked_user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {       
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {            
            $query->orWhereHas('user', function ($q) use ($params) {            
                $q->orWhere('username', 'like', '%' . $params['q'] . '%');
            });
           $query->orWhereHas('blocked_user', function ($q) use ($params) {            
                $q->orWhere('username', 'like', '%' . $params['q'] . '%');
            });
        }     
    }
    public function checkAlreadyUserBlocked($blocked_user_id, $user_id)
    {
        $blockedUser = false;
        $user = BlockedUser::select('id')->where('user_id', $user_id)->where('blocked_user_id', $blocked_user_id)->first();
        if (!empty($user)) {
            $blockedUser = true;
        }
        return $blockedUser;
    }   
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::saved(function ($data) use ($authUser) {
            $blocked_user_count = BlockedUser::where('user_id', $data['user_id'])->count();
            User::where('id', $data['user_id'])->update(['blocked_user_count' => $blocked_user_count]);
        });
        self::deleted(function ($data) use ($authUser) {
            $blocked_user_count = BlockedUser::where('user_id', $data['user_id'])->count();
            User::where('id', $data['user_id'])->update(['blocked_user_count' => $blocked_user_count]);   
        });
    }     
}

<?php
/**
 * MoneyTransferAccount
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class MoneyTransferAccount extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'money_transfer_accounts';
    protected $fillable = array(
        'user_id',
        'account',
        'is_primary'
    );
    public $rules = array(
        'account' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id')->select('id', 'username', 'email');
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        static ::addGlobalScope('user', function (\Illuminate\Database\Eloquent\Builder $builder) use ($authUser) {
            if ($authUser['role_id'] != \Constants\ConstUserTypes::Admin) {
                $builder->where('user_id', $authUser['id']);
            }
        });
        self::saving(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
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
            $query->orwhere('account', 'LIKE', "%$search%");
        }
    }
}

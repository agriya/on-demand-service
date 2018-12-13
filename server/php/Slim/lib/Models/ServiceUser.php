<?php
/**
 * ServiceUser
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Hirecoworker
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * ServiceUser
*/
class ServiceUser extends AppModel
{
    protected $table = 'services_users';
    protected $casts = [
        'service_id' => 'integer',
        'user_id' => 'integer',
        'cancellation_policy_id' => 'integer',
        'rate' => 'double'                            
    ];    
    protected $fillable = array(
        'service_id',
        'user_id',
        'rate',
        'cancellation_policy_id'
    );
    public $rules = array(
        'service_id' => 'sometimes|required',
        'user_id' => 'sometimes|required',
        'rate' => 'sometimes|required',
        'cancellation_policy_id' => 'sometimes|required'
    );
    public function service()
    {
        return $this->belongsTo('Models\Service', 'service_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function cancellation_policy()
    {
        return $this->belongsTo('Models\CancellationPolicy', 'cancellation_policy_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('users.username', 'like', "%$search%");
                });
                $q1->orWhereHas('service', function ($q) use ($search) {
                    $q->where('services.name', 'like', "%$search%");
                });
                $q1->orWhereHas('cancellation_policy', function ($q) use ($search) {
                    $q->where('cancellation_policy.name', 'like', "%$search%");
                });
            });
        }
    }
}

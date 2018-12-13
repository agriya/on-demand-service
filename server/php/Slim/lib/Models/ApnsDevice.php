<?php
/**
 * ApnsDevice
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Lash API
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * ApnsDevice
*/
class ApnsDevice extends AppModel
{
    protected $table = 'apns_devices';
    protected $fillable = array(
        'user_id',
        'appname',
        'appversion',
        'deviceuid',
        'devicetoken',
        'devicename',
        'devicemodel',
        'deviceversion',
        'pushbadge',
        'pushalert',
        'pushsound',
        'development',
        'status',
        'latitude',
        'longitude',
        'devicetype'
    );
    //Rules
    public $rules = array(
        'devicetoken' => 'sometimes|required',
        'latitude' => 'sometimes|required',
        'longitude' => 'sometimes|required'
    );
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
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%$search%");
                });
                $q1->orWhere('appname', 'like', "%$search%");
                $q1->orWhere('devicename', 'like', "%$search%");
                $q1->orWhere('devicemodel', 'like', "%$search%");
            });
        }
    }
}

<?php
/**
 * City
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

class City extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';
    protected $casts = ['country_id' => 'integer', 'state_id' => 'integer', 'latitude' => 'double', 'longitude' => 'double', 'is_active' => 'integer'];
    protected $fillable = array(
        'country_id',
        'state_id',
        'name',
        'latitude',
        'longitude',
        'is_active'
    );
    public $rules = array(
        'name' => 'sometimes|required',
    );
    public function state()
    {
        return $this->belongsTo('Models\State', 'state_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country', 'country_id', 'id');
    }
    public function user_profile()
    {
        return $this->hasMany('Models\UserProfile', 'city_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->orWhereHas('state', function ($q) use ($search) {
                    $q->where('states.name', 'like', "%$search%");
                });
                $q1->orWhereHas('country', function ($q) use ($search) {
                    $q->where('countries.name', 'like', "%$search%");
                });
                $q1->orWhere('cities.name', 'like', "%$search%");
            });
        }
    }
}

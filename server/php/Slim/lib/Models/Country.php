<?php
/**
 * Country
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

class Country extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';
    protected $casts = ['is_active' => 'integer'];
    protected $fillable = array(
        'iso2',
        'iso3',
        'name',
        'is_active',
        'phone'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'iso2' => 'sometimes|max:2',
        'iso3' => 'sometimes|max:3'
    );
    public function venue()
    {
        return $this->hasMany('Models\Venue', 'country_id');
    }
    public function user_profile()
    {
        return $this->hasMany('Models\UserProfile', 'country_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'like', "%$search%");
                $q1->orWhere('iso2', 'like', "%$search%");
                $q1->orWhere('iso3', 'like', "%$search%");
                $q1->orWhere('phone', 'like', "%$search%");
            });
        }
    }
}

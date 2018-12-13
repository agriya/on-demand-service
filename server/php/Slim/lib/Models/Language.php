<?php
/**
 * Language
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Vooduvibe
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * Language
*/
class Language extends AppModel
{
    protected $table = 'languages';
    protected $casts = ['is_active' => 'integer'];
    protected $fillable = array(
        'name',
        'iso2',
        'iso3',
        'is_active',
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'iso2' => 'sometimes|max:2',
        'iso3' => 'sometimes|max:3'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'like', "%$search%");
                $q1->orWhere('iso2', 'like', "%$search%");
                $q1->orWhere('iso3', 'like', "%$search%");
            });
        }
    }
}

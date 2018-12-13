<?php
/**
 * Role
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
 * Role
*/
class Role extends AppModel
{
    protected $table = 'roles';
    protected $fillable = array(
        'name'
    );
    public $rules = array(
        'name' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'like', "%$search%");
            });
        }
    }
}

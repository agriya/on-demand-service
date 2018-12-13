<?php
/**
 * InputType
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

/*
 * InputType
*/
class InputType extends AppModel
{
    protected $table = 'input_types';
    private $rules = array();
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where('name', 'like', '%' . $params['q'] . '%');
        }
        return $query;
    }
}

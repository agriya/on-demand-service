<?php
/**
 * Provider
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

class Provider extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'providers';
    protected $casts = ['display_order' => 'integer', 'is_active' => 'integer'];
    protected $fillable = array(
        'name',
        'secret_key',
        'api_key',
        'is_active'
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
                $q1->orWhere('secret_key', 'like', "%$search%");
                $q1->orWhere('api_key', 'like', "%$search%");
            });
        }
    }
}

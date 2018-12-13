<?php
/**
 * CancellationPolicy
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
 * CancellationPolicy
*/
class CancellationPolicy extends AppModel
{
    protected $table = 'cancellation_policies';
    protected $fillable = array(
        'name',
        'description',
        'days_before',
        'days_before_refund_percentage',
        'days_after',
        'days_after_refund_percentage'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'description' => 'sometimes|required',
        'days_before' => 'sometimes|required',
        'days_before_refund_percentage' => 'sometimes|required',
        'days_after' => 'sometimes|required',
        'days_after_refund_percentage' => 'sometimes|required',
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->where('name', 'like', "%$search%");
        }
    }
}

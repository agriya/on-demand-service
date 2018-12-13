<?php
/**
 * AccountCloseReason
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
 * AccountCloseReason
*/
class AccountCloseReason extends AppModel
{
    protected $table = 'account_close_reasons';
    protected $fillable = array(
        'reasons',
        'display_order',
    );
    public $rules = array(
        'reasons' => 'sometimes|required',
        'displayOrder' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhere('reasons', 'like', '%' . $params['q'] . '%');
        }
    }
}

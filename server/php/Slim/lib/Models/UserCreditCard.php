<?php
/**
 * UserCreditCard
 *
 * PHP version 5
 *
 * @category   PHP
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * Vault
*/
class UserCreditCard extends AppModel
{
    protected $table = 'user_credit_cards';
    protected $fillable = array(
        'payment_gateway_id',
        'payment_gateway_customer_id',
        'user_id',
        'token',
        'credit_card_type',
        'masked_card_number',
        'name_on_the_card',
        'credit_card_expire',
        'cvv2',
        'is_primary'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['user_id'])) {
            $query->where('user_id', '=', $params['user_id']);
        }
    }
}

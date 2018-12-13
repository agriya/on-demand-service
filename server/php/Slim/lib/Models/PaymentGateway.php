<?php
/**
 * PaymentGateway
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

class PaymentGateway extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateways';
    protected $fillable = array(
        'name',
        'description',
        'gateway_fees',
        'is_test_mode',
        'is_active',
        'display_name'
    );
    public function payment_settings()
    {
        return $this->hasMany('Models\PaymentGatewaySetting');
    }
}

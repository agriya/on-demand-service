<?php
/**
 * PaymentGatewaySetting
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

class PaymentGatewaySetting extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateway_settings';
    protected $fillable = array(
        'payment_gateway_id',
        'name',
        'type',
        'options',
        'test_mode_value',
        'live_mode_value',
        'label',
        'description'
    );
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
}

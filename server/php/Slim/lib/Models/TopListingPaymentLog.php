<?php
/**
 * TopListingPaymentLog
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Home Assistant
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * TopListingPaymentLog
 */
class TopListingPaymentLog extends AppModel
{
    protected $table = 'top_listing_payment_logs';
    protected $fillable = array(
        'user_id'
    );
    public $rules = array(
        
    );
}

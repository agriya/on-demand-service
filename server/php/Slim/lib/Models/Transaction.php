<?php
/**
 * Transaction
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

use Illuminate\Database\Eloquent\Relations\Relation;

class Transaction extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';
    protected $casts = [
        'user_id' => 'integer',
        'to_user_id' => 'integer',
        'foreign_id' => 'integer',
        'payment_gateway_id' => 'integer',
        'coupon_id' => 'integer',
        'amount' => 'double',
        'site_revenue_from_freelancer' => 'double',
        'site_revenue_from_employer' => 'double'                             
    ];    
    protected $fillable = array(
        'user_id',
        'to_user_id',
        'foreign_id',
        'class',
        'transaction_type',
        'amount',
        'site_revenue_from_freelancer',
        'payment_gateway_id',
        'coupon_id',
        'site_revenue_from_employer'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function other_user()
    {
        return $this->belongsTo('Models\User', 'to_user_id', 'id');
    }
    public function payment_gateway()
    {
        return $this->belongsTo('Models\PaymentGateway', 'payment_gateway_id', 'id');
    }
    public function foreign_transaction()
    {
        return $this->morphTo(null,'class','foreign_id')->with('activity');
    }   
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        Relation::morphMap(['Appointment' => Appointment::class, 'PROUser' => User::class, 'TopUser' => User::class]);
    }    
}

<?php
/**
 * ProviderUser
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

class ProviderUser extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'provider_users';
    protected $casts = ['user_id' => 'integer', 'provider_id' => 'integer', 'is_connected' => 'integer'];
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }
}

<?php
/**
 * Models/OauthClients.php
 *
 * This file model for OauthClients
 *
 *
 * @category Models
 * @package RCBeta
 * @author Adam Stern
 */
namespace Models;

class OauthClient extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "oauth_clients";
    protected $casts = ['is_active' => 'integer'];
    protected $fillable = array(
        'name',
        'api_key',
        'api_secret',
        'is_active',
        'oauth_client_id'
    );
}

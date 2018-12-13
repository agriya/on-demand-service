<?php
/**
 * Models/UserTokens.php
 *
 * This file model for UserTokens
 *
 *
 * @category Models
 */
namespace Models;

class UserToken extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "user_tokens";
    protected $fillable = array(
        'user_id',
        'token',
        'expires',
        'oauth_client_id'
    );
    public $rules = array(
        'user_id' => 'sometimes|required|integer',
        'token' => 'sometimes|required',
        'expires' => 'sometimes|required'
    );
}

<?php
/**
 * Contact
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Vooduvibe
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * Contact
*/
class Contact extends AppModel
{
    protected $table = 'contacts';
    protected $casts = ['user_id' => 'integer'];
    protected $fillable = array(
        'user_id',
        'first_name',
        'last_name',
        'email',
        'subject',
        'message',
        'telephone'
    );
    public $rules = array(
        'other_user_id' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function attachment()
    {
        return $this->hasMany('Models\Attachment', 'foreign_id', 'id')->where('class', 'Contact');
    }    
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->orWhereHas('user', function ($q) use ($search) {
                    $q->where('users.username', 'like', "%$search%");
                });
                $q1->orWhere('first_name', 'like', "%$search%");
                $q1->orWhere('last_name', 'like', "%$search%");
                $q1->orWhere('email', 'like', "%$search%");
                $q1->orWhere('subject', 'like', "%$search%");
                $q1->orWhere('message', 'like', "%$search%");
                $q1->orWhere('telephone', 'like', "%$search%");
            });
        }
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
    }
}

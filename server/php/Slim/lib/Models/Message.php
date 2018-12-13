<?php
/**
 * Message
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Getlancer V3
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

use Illuminate\Database\Eloquent\Relations\Relation;

/*
 * Message
*/
class Message extends AppModel
{
    protected $table = 'messages';
    protected $fillable = array(
        'user_id',
        'other_user_id',
        'parent_id',
        'message_content_id',
        'foreign_id',
        'class',
        'root',
        'freshness_ts',
        'depth',
        'materialized_path',
        'path',
        'size',
        'is_sender',
        'is_read',
        'is_deleted',
        'is_private',
        'is_child_replied',
        'model_id'
    );
    public $rules = array();
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function other_user()
    {
        return $this->belongsTo('Models\User', 'other_user_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo('Models\Message', 'parent_id', 'id');
    }
    public function message_content()
    {
        return $this->belongsTo('Models\MessageContent', 'message_content_id', 'id');
    }
    public function foreigns()
    {
        return $this->morphMany('Models\Activity', 'foreign');
    }
    public function foreign_user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function foreign_other_user()
    {
        return $this->belongsTo('Models\User', 'other_user_id', 'id');
    }
    public function foreign_message()
    {
        return $this->morphTo(null, 'class', 'foreign_id');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if($authUser->role_id != \Constants\ConstUserTypes::Admin){
            $query->where('user_id',$authUser->id);
        }
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->orWhere('class', 'like', "%$search%");
                $q1->orWhere('foreign_id', 'like', "%$search%");
                $q1->orWhereHas('user.user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                });
                $q1->orWhereHas('other_user.user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                });
            });
        }
    }
    public function child()
    {
        return $this->hasMany('Models\Message', 'parent_id', 'id');
    }
    public function children()
    {
        return $this->child()->with('children');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'message_content_id')->where('class', 'MessageContent');
    }
    protected static function boot()
    {
        Relation::morphMap(['Appointment' => Appointment::class ]);
    }
    public function setChildPrivateMessage($authUser, $childrens)
    {
        if ($childrens) {
            foreach ($childrens as $ckey => $children) {
                if ((empty($authUser) && !empty($children['is_private'])) || (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin && $authUser['id'] != $children['user_id'] && $authUser['id'] != $children['other_user_id'] && !empty($children['is_private']))) {
                    $childrens[$ckey]['message_content']['subject'] = '[Private Message]';
                    $childrens[$ckey]['message_content']['message'] = '[Private Message]';
                }
                if ($children['children']) {
                    $childrens[$ckey]['children'] = Message::setChildPrivateMessage($authUser, $children['children']);
                }
            }
        }
        return $childrens;
    }
    public function saveEnquiry($user_id, $appointment)
    {
        global $authUser, $_server_domain_url;
        $message_content = new MessageContent;
        $message_content->message = $appointment->customer_note;
        $message_content->subject = 'Enquiry';
        $message_content->save();
        $senderMessageId = saveMessage(0, '', $user_id, $appointment->provider_user_id, $message_content->id, 0, 'Appointment', $appointment->id, 1, $appointment->id, 0);
        $receiverMessageId = saveMessage(0, '', $appointment->provider_user_id, $user_id, $message_content->id, 0, 'Appointment', $appointment->id, 0, $appointment->id, 0);
        $service_provider = User::with('user_profile')->where('id', $appointment->provider_user_id)->first();
        if (!empty($service_provider->user_profile->first_name) || !empty($service_provider->user_profile->last_name)) {
            $service_username = $service_provider->user_profile->first_name .' '.$service_provider->user_profile->last_name;
        } else {
            $service_username = $service_provider->email; 
        }        
        if (!empty($service_provider)) {
            $user = User::with('user_profile')->where('id', $user_id)->first();
            if (!empty($user->user_profile->first_name) || !empty($user->user_profile->last_name)) {
                $request_username = $user->user_profile->first_name .' '.$user->user_profile->last_name;
            } else {
                $request_username = $user->email; 
            }            
            $emailFindReplace = array(
                '##SERVICE_PROVIDER##' => $service_username,
                '##REQUESTOR_NAME##' => $request_username,
                '##MESSAGE##' => $appointment->customer_note,
                '##LINK##' => $_server_domain_url . '/#/appointments'
            );
            sendMail('New Enquiry', $emailFindReplace, $service_provider->email);
        }
    }
}

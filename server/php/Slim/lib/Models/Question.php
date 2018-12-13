<?php
namespace Models;

class Question extends AppModel
{
    /**
     * @var string
     */
    protected $table = "questions";
    protected $fillable = ['question', 'user_id', 'service_id', 'slug', 'answer_count', 'is_active', 'description', 'is_answered'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo('Models\Service', 'service_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->whereHas('user', function ($q) use ($params, $search) {
                $q->where('username', 'LIKE', "%$search%");
            });
            $query->orWhereHas('service', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
            $query->orWhere('question', 'like', "%$search%");
            $query->orWhere('description', 'like', "%$search%");
        }
    }
    protected static function boot()
    {
        global $authUser;
        parent::boot();
        self::saving(function ($data) use ($authUser) {
            if (($authUser['role_id'] != \Constants\ConstUserTypes::Admin)) {
                $data['user_id'] = $authUser->id;
            }
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
    }
    public $rules = array(
        'user_id' => 'sometimes|required',
        'question' => 'sometimes|required',
        'service_id' => 'sometimes|required',
        'answer_count' => 'sometimes|required',
        'description' => 'sometimes|required',
        'is_answered' => 'sometimes|required'
    );
}

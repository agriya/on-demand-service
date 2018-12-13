<?php
namespace Models;

/**
 * Class State
 * @package App
 */
class UserFavorite extends AppModel
{
    /**
     * @var string
     */
    protected $table = "user_favorites";
    protected $casts = [
        'provider_user_id' => 'integer',
        'user_id' => 'integer'                           
    ];    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'provider_user_id'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function provider_user()
    {
        return $this->belongsTo('Models\User', 'provider_user_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->orWhereHas('user.user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                    $q->orWhere('listing_title', 'like', "%$search%");
                });
                $q1->orWhereHas('provider_user.user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                    $q->orWhere('listing_title', 'like', "%$search%");
                });
            });
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
        self::saved(function ($data) use ($authUser) {
            $user_favorite_count = UserFavorite::where('provider_user_id',$data->provider_user_id)->count();
            $user_profile = UserProfile::where('user_id',$data->provider_user_id)->update(['user_favorite_count' => $user_favorite_count]);
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $data->user_id)) {
                return true;
            }
            return false;
        });
        self::deleted(function ($data) use ($authUser) {
            $user_favorite_count = UserFavorite::where('provider_user_id',$data->provider_user_id)->count();
            $user_profile = UserProfile::where('user_id',$data->provider_user_id)->update(['user_favorite_count' => $user_favorite_count]);
        });
    }
    public $rules = array(
        'user_id' => 'sometimes|required',
        'provider_user_id' => 'sometimes|required'
    );
}

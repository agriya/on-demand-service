<?php
/**
 * UserView
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
 * UserView
*/
class UserView extends AppModel
{
    protected $table = 'user_views';
    protected $fillable = array(
        'user_id',
        'viewing_user_id'
    );
    public $rules = array();
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function other_user()
    {
        return $this->belongsTo('Models\User', 'viewing_user_id', 'id');
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
                $q1->orWhereHas('other_user.user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                    $q->orWhere('listing_title', 'like', "%$search%");
                });
            });
        }
    }

    protected static function boot()
    {
        parent::boot();
        self::saved(function ($data) {
            $user_view_count = UserView::where('viewing_user_id', $data->viewing_user_id)->count();
            $user_profile = UserProfile::where('user_id',$data->viewing_user_id)->update(['user_view_count' => $user_view_count]);
        });
        self::deleted(function ($data) {
            $user_view_count = UserView::where('viewing_user_id', $data->viewing_user_id)->count();
            $user_profile = UserProfile::where('user_id',$data->viewing_user_id)->update(['user_view_count' => $user_view_count]);
        });        
    }
}

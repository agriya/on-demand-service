<?php
/**
 * Review
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
 * Review
*/
class Review extends AppModel
{
    protected $table = 'reviews';
    protected $casts = [
        'user_id' => 'integer',
        'to_user_id' => 'integer',
        'foreign_id' => 'integer',
        'rating' => 'integer',   
        'model_id' => 'integer',     
        'is_reviewed_as_service_provider' => 'integer'                            
    ];     
    protected $fillable = array(
        'user_id',
        'to_user_id',
        'foreign_id',
        'class',
        'rating',
        'message',
        'is_reviewed_as_service_provider',
        'model_id',
        'model_class'
    );
    public $rules = array(
        'rating' => 'sometimes|required'
    );
    public function user()
    {
        return $this->belongsTo('Models\User', 'user_id', 'id');
    }
    public function to_user()
    {
        return $this->belongsTo('Models\User', 'to_user_id', 'id');
    }
    public function appointment()
    {
        return $this->belongsTo('Models\Appointment', 'foreign_id', 'id');
    }
    public function foreign_review_model()
    {
        return $this->morphTo(null, 'model_class', 'model_id');
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
                $q1->orWhereHas('to_user.user_profile', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%");
                    $q->orWhere('last_name', 'like', "%$search%");
                    $q->orWhere('listing_title', 'like', "%$search%");
                });
                $q1->orWhere('class', 'like', "%$search%");
            });
        }
    }
    protected static function boot()
    {
        Relation::morphMap(['Appointment' => Appointment::class ]);
        global $authUser;
        parent::boot();
        self::saved(function ($data) use ($authUser) {
            $reviewSum = Review::where('to_user_id', $data->to_user_id)->sum('rating');
            $count = Review::where('to_user_id', $data->to_user_id)->count();
            $avg_rating = round($reviewSum / $count, 2);
            if (!empty($data->is_reviewed_as_service_provider)) {
                User::where('id', $data->to_user_id)->update(array(
                    'review_count_as_employer' => $count,
                    'total_rating_as_employer' => $reviewSum,
                    'average_rating_as_employer' => $avg_rating
                ));
            } else {
                User::where('id', $data->to_user_id)->update(array(
                    'review_count_as_service_provider' => $count,
                    'total_rating_as_service_provider' => $reviewSum,
                    'average_rating_as_service_provider' => $avg_rating
                ));
            }
        });
        self::deleted(function ($data) use ($authUser) {
            $reviewSum = Review::where('to_user_id', $data->to_user_id)->sum('rating');
            $count = Review::where('to_user_id', $data->to_user_id)->count();
            $avg_rating = round($reviewSum / $count, 2);
            if (!empty($data->is_reviewed_as_service_provider)) {
                User::where('id', $data->to_user_id)->update(array(
                    'review_count_as_employer' => $count,
                    'total_rating_as_employer' => $reviewSum,
                    'average_rating_as_employer' => $avg_rating
                ));
            } else {
                User::where('id', $data->to_user_id)->update(array(
                    'review_count_as_service_provider' => $count,
                    'total_rating_as_service_provider' => $reviewSum,
                    'average_rating_as_service_provider' => $avg_rating
                ));
            }
        });
        self::deleting(function ($data) use ($authUser) {
            if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin)) {
                return true;
            }
            return false;
        });
    }
}

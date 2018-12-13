<?php
namespace Models;

/**
 * Class ListingStatus
 * @package App
 */
class ListingStatus extends AppModel
{
    /**
     * @var string
     */
    protected $table = "listing_statuses";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'like', "%$search%");
            });
        }
    }
}

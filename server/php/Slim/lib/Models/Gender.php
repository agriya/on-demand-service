<?php
namespace Models;

/**
 * Class Gender
 * @package App
 */
class Gender extends AppModel
{
    /**
     * @var string
     */
    protected $table = "genders";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }
    public $rules = array(
        'name' => 'sometimes|required'
    );
}

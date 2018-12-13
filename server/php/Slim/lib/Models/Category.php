<?php
/**
 * Category
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
 * Category
*/
class Category extends AppModel
{
    protected $table = 'categories';
    protected $casts = [
        'is_enable_multiple_booking' => 'integer',
        'service_count' => 'integer',
        'is_active' => 'integer',
        'is_enable_common_hourly_rate_for_all_sub_services' => 'integer' 
    ];      
    protected $fillable = array(
        'name',
        'service_count',
        'is_active',
        'is_enable_multiple_booking',
        'is_enable_common_hourly_rate_for_all_sub_services',
        'icon_class',
        'is_featured'
    );
    public function user()
    {
        return $this->hasMany('Models\User', 'category_id', 'id');
    }
    public function service()
    {
        return $this->hasMany('Models\Service', 'category_id', 'id');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('Class', 'Category');
    }    
    public $rules = array(
        'name' => 'sometimes|required',
        'is_active' => 'sometimes|required',
        'is_enable_multiple_booking' => 'sometimes|required'
    );
    public function form_field_groups()
    {
        return $this->hasMany('Models\FormFieldGroup', 'foreign_id', 'id')->where('class', 'Category');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->where('name', 'like', "%$search%");
        }
    }
}

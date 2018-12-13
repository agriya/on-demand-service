<?php
namespace Models;

/**
 * Class Country
 * @package App
 */
class Service extends AppModel
{
    /**
     * @var string
     */
    protected $table = "services";
    protected $casts = [
        'category_id' => 'integer',
        'booking_option_id' => 'integer',
        'is_enable_multiple_booking' => 'integer',
        'is_need_user_location' => 'integer',   
        'is_active' => 'integer',     
        'parent_id' => 'integer'                            
    ];     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'name', 'booking_option_id', 'is_enable_multiple_booking', 'is_need_user_location', 'slug', 'is_active', 'parent_id', 'icon_class','is_allow_to_get_number_of_items','label_for_number_of_item','maximum_number_to_allow','is_multiply_booking_amount_when_choosing_number_of_items','is_featured'];
    public function category()
    {
        return $this->belongsTo('Models\Category', 'category_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo('Models\Service', 'parent_id', 'id');
    }
    public function sub_service()
    {
        return $this->hasMany('Models\Service', 'parent_id', 'id');
    }    
    public function form_field_groups()
    {
        return $this->hasMany('Models\FormFieldGroup', 'foreign_id', 'id')->where('class', 'Service');
    }
    public function service_user()
    {
        return $this->hasMany('Models\ServiceUser', 'service_id', 'id');
    }
    public function attachment()
    {
        return $this->hasOne('Models\Attachment', 'foreign_id', 'id')->where('Class', 'Service');
    }     
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('name', 'like', "%$search%");
                $q1->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            });
        }
    }
    public $rules = array(
        'category_id' => 'sometimes|required',
        'name' => 'sometimes|required',
        'is_active' => 'sometimes|required',
        'booking_option_id' => 'sometimes|required',
        'is_need_user_location' => 'sometimes|required',
        'is_enable_multiple_booking' => 'sometimes|required'
    );
    protected static function boot()
    {
        parent::boot();
        self::saved(function ($service) {
            /* For Update the service count */
            $service_count = Service::where('category_id', $service->category_id)->count();
            $category = Category::find($service->category_id);
            if (!empty($category)) {
                $category->service_count = $service_count + 1;
                $category->update();
            }
        });
    }
}

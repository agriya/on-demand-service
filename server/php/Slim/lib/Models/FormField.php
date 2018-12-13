<?php
/**
 * QuoteFormField
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * FormField
*/
class FormField extends AppModel
{
    protected $table = 'form_fields';
    protected $casts = [
        'length' => 'integer',
        'input_type_id' => 'integer',
        'foreign_id' => 'integer',
        'form_field_group_id' => 'integer',
        'is_required' => 'integer',
        'is_active' => 'integer',
        'display_order' => 'integer',
        'is_enable_this_field_in_search_form' => 'integer'                            
    ];     
    protected $fillable = array(
        'name',
        'input_type_id',
        'foreign_id',
        'form_field_group_id',
        'label',
        'info',
        'length',
        'options',
        'is_required',
        'is_active',
        'display_order',
        'depends_on',
        'depends_value',
        'class',
        'is_enable_this_field_in_search_form'
    );
    public $rules = array(
        'length' => 'sometimes|required',
        'display_order' => 'sometimes|required',
    );
    public function form_field_group()
    {
        return $this->belongsTo('Models\FormFieldGroup', 'form_field_group_id', 'id');
    }
    public function input_types()
    {
        return $this->belongsTo('Models\InputType', 'input_type_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where('name', 'like', '%' . $params['q'] . '%');
        }
    }
   /*protected static function boot()
    {
        parent::boot();
        self::created(function ($FormFields) {
            if (!empty($FormFields->class)) {
                $model = 'Models\\' . $FormFields->class;
                $model::where('id', $FormFields->foreign_id)->increment('form_field_count', 1);
            }
        });
        self::deleted(function ($FormFields) {
            if (!empty($FormFields->class)) {
                $model = 'Models\\' . $FormFields->class;
                $model::where('id', $FormFields->foreign_id)->decrement('form_field_count', 1);
            }
        });
        FormField::updating(function ($formField) {
            $original = $formField->getOriginal();
            self::saved(function ($FormFields) use ($original) {
                if (!empty($FormFields->class) && !empty($original['foreign_id']) && $original['foreign_id'] != $FormFields->foreign_id) {
                    $model = 'Models\\' . $FormFields->class;
                    $model::where('id', $original['foreign_id'])->decrement('form_field_count', 1);
                    $model::where('id', $FormFields->foreign_id)->increment('form_field_count', 1);
                }
            });
        });
    }*/
}

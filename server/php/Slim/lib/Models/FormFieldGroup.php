<?php
/**
 * FormFieldGroup
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
 * QuoteFormField
*/
class FormFieldGroup extends AppModel
{
    protected $table = 'form_field_groups';
    protected $fillable = array(
        'name',
        'foreign_id',
        'info',
        'class',
        'is_deletable',
        'is_editable',
        'is_belongs_to_service_provider'
    );
    public $rules = array(
        'name' => 'sometimes|required',
    );
    public function form_fields()
    {
        return $this->hasMany('Models\FormField', 'form_field_group_id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where('name', 'like', '%' . $params['q'] . '%');
        }
    }
}

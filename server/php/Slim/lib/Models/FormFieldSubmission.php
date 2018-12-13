<?php
/**
 * FormFieldSubmission
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
 * FormFieldSubmission
*/
class FormFieldSubmission extends AppModel
{
    protected $table = 'form_field_submissions';
    protected $fillable = array(
        'foreign_id',
        'form_field_id',
        'class',
        'response'
    );
    public $rules = array();
    public function form_field()
    {
        return $this->belongsTo('Models\FormField', 'form_field_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
    }
}

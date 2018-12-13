<?php
/**
 * Faq
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Hirecoworker
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * Faq
*/
class Faq extends AppModel
{
    protected $table = 'faqs';
    protected $fillable = array(
        'faq_question',
        'faq_answer'
    );
    public $rules = array(
        'faq_question' => 'sometimes|required',
        'faq_answer' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->orWhere('faq_question', 'like', '%' . $params['q'] . '%');
            $query->orWhere('faq_answer', 'like', '%' . $params['q'] . '%');
        }
    }
}

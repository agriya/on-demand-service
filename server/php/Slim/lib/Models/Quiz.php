<?php
/**
 * Quiz
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
 * Quiz
*/
class Quiz extends AppModel
{
    protected $table = 'quizzes';
    protected $fillable = array(
        'name',
        'description',
        'display_order'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'description' => 'sometimes|required',
        'display_order' => 'sometimes|required'
    );
    public function quiz_question()
    {
        return $this->hasMany('Models\QuizQuestion', 'quiz_id', 'id');
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

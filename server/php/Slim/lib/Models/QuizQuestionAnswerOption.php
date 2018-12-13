<?php
/**
 * QuizQuestionAnswerOption
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
 * QuizQuestionAnswerOption
*/
class QuizQuestionAnswerOption extends AppModel
{
    protected $table = 'quiz_question_answer_options';
    protected $casts = [
        'quiz_id' => 'integer',
        'quiz_question_id' => 'integer',
        'is_correct_answer' => 'integer'                            
    ];    
    protected $fillable = array(
        'quiz_id',
        'quiz_question_id',
        'option',
        'is_correct_answer'
    );
    public $rules = array(
        'quiz_id' => 'sometimes|required',
        'quiz_question_id' => 'sometimes|required',
        'option' => 'sometimes|required',
        'is_correct_answer' => 'sometimes|required'
    );
    public function quiz()
    {
        return $this->belongsTo('Models\Quiz', 'quiz_id', 'id');
    }
    public function quiz_question()
    {
        return $this->belongsTo('Models\QuizQuestion', 'quiz_question_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->whereHas('quiz', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
            $query->whereHas('quiz_question', function ($q) use ($params, $search) {
                $q->where('question', 'LIKE', "%$search%");
            });
            $query->orWhere('option', 'like', "%$search%");
        }
    }
}

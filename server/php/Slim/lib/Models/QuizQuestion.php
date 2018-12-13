<?php
/**
 * QuizQuestion
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
 * QuizQuestion
*/
class QuizQuestion extends AppModel
{
    protected $table = 'quiz_questions';
    protected $casts = [
        'quiz_id' => 'integer'                           
    ];     
    protected $fillable = array(
        'quiz_id',
        'question'
    );
    public $rules = array(
        'quiz_id' => 'sometimes|required',
        'question' => 'sometimes|required'
    );
    public function quiz()
    {
        return $this->belongsTo('Models\Quiz', 'quiz_id', 'id');
    }
    public function quiz_question_answer_option()
    {
        return $this->hasMany('Models\QuizQuestionAnswerOption', 'quiz_question_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $search = $params['q'];
            $query->whereHas('quiz', function ($q) use ($params, $search) {
                $q->where('name', 'LIKE', "%$search%");
            });
            $query->orWhere('question', 'like', "%$search%");
        }
    }
}

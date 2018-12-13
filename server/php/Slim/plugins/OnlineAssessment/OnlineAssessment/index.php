<?php
/**
 *
 * @package         Getlancer
 * @copyright   Copyright (c) 2012 {@link http://www.agriya.com/ Agriya Infoway}
 * @license         http://www.agriya.com/ Agriya Infoway Licence
 * @since       2017-01-02
 *
 */
/**
 * GET quizzesGet
 * Summary: Fetch all quizzes
 * Notes: Returns all quizzes from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/quizzes', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $quizzes = Models\Quiz::Filter($queryParams)->paginate()->toArray();
        $data = $quizzes['data'];
        unset($quizzes['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $quizzes
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = $e->getMessage(), $fields = '', $isError = 1, 422);
    }
})->add(new ACL('canListQuiz'));
/**
 * POST quizzesPost
 * Summary: Creates a new quiz
 * Notes: Creates a new quiz
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/quizzes', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $quiz = new Models\Quiz($args);
    $result = array();
    try {
        $validationErrorFields = $quiz->validate($args);
        if (empty($validationErrorFields)) {
            $quiz->save();
            $result['data'] = $quiz->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Quiz could not be added. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Quiz could not be added. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canCreateQuiz'));
/**
 * DELETE quizzesQuizIdDelete
 * Summary: Delete quiz
 * Notes: Deletes a single quiz based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/quizzes/{quizId}', function ($request, $response, $args) {
    $quiz = Models\Quiz::find($request->getAttribute('quizId'));
    $result = array();
    try {
        if (!empty($quiz)) {
            $quiz->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Quiz could not be deleted. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canDeleteQuiz'));
/**
 * GET quizzesQuizIdGet
 * Summary: Fetch quiz
 * Notes: Returns a quiz based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/quizzes/{quizId}', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $result = array();
    $quiz = Models\Quiz::Filter($queryParams)->find($request->getAttribute('quizId'));
    if (!empty($quiz)) {
        $result['data'] = $quiz;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
})->add(new ACL('canViewQuiz'));
/**
 * PUT quizzesQuizIdPut
 * Summary: Update quiz by its id
 * Notes: Update quiz by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/quizzes/{quizId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $quiz = Models\Quiz::find($request->getAttribute('quizId'));
    $quiz->fill($args);
    $result = array();
    try {
        $validationErrorFields = $quiz->validate($args);
        if (empty($validationErrorFields)) {
            $quiz->save();
            $result['data'] = $quiz->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Quiz could not be updated. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateQuiz'));
/**
 * GET quizQuestionsGet
 * Summary: Fetch all quiz questions
 * Notes: Returns all quiz questions from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/quiz_questions', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $quizQuestions = Models\QuizQuestion::Filter($queryParams)->paginate()->toArray();
        $data = $quizQuestions['data'];
        unset($quizQuestions['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $quizQuestions
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = $e->getMessage(), $fields = '', $isError = 1, 422);
    }
})->add(new ACL('canListQuizQuestion'));
/**
 * POST quizQuestionsPost
 * Summary: Creates a new quiz question
 * Notes: Creates a new quiz question
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/quiz_questions', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $quizQuestion = new Models\QuizQuestion($args);
    $result = array();
    try {
        $validationErrorFields = $quizQuestion->validate($args);
        if (empty($validationErrorFields)) {
            $quizQuestion->save();
            $answer_options = explode("\n", $args['answer_options']);
            foreach ($answer_options as $answer_option) {
                $questionAnswerOptions = new Models\QuizQuestionAnswerOption();
                $questionAnswerOptions->quiz_question_id = $quizQuestion->id;
                $questionAnswerOptions->quiz_id = $quizQuestion->quiz_id;
                $questionAnswerOptions->option = trim($answer_option);
                $questionAnswerOptions->is_correct_answer = 0;
                if (!empty($args['current_answer']) && trim($args['current_answer']) == trim($answer_option)) {
                    $questionAnswerOptions->is_correct_answer = 1;
                }
                $questionAnswerOptions->save();
            }
            $result['data'] = $quizQuestion->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Quiz question could not be added. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateQuizQuestion'));
/**
 * DELETE quizQuestionsQuizQuestionIdDelete
 * Summary: Delete quiz question
 * Notes: Deletes a single quiz question based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/quiz_questions/{quizQuestionId}', function ($request, $response, $args) {
    $quizQuestion = Models\QuizQuestion::find($request->getAttribute('quizQuestionId'));
    $result = array();
    try {
        if (!empty($quizQuestion)) {
            $quizQuestion->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteQuizQuestion'));
/**
 * GET quizQuestionsQuizQuestionIdGet
 * Summary: Fetch quiz question
 * Notes: Returns a quiz question based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/quiz_questions/{quizQuestionId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $quizQuestion = Models\QuizQuestion::Filter($queryParams)->find($request->getAttribute('quizQuestionId'));
    if (!empty($quizQuestion)) {
        $quizQuestion->answer_options = '';
        $quizQuestion->current_answer = '';
        if (!empty($quizQuestion) && !empty($quizQuestion->quiz_question_answer_option)) {
            $answer_options = '';
            foreach ($quizQuestion->quiz_question_answer_option as $question_answer_options) {
                $answer_options .= $question_answer_options->option. PHP_EOL;
                if (!empty($question_answer_options->is_correct_answer)) {
                    $quizQuestion->current_answer = $question_answer_options->option;
                }
            }
            $quizQuestion->answer_options = rtrim($answer_options,PHP_EOL);
        }
        $result['data'] = $quizQuestion->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
})->add(new ACL('canViewQuizQuestion'));
/**
 * PUT quizQuestionsQuizQuestionIdPut
 * Summary: Update quiz question by its id
 * Notes: Update quiz question by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/quiz_questions/{quizQuestionId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $quizQuestion = Models\QuizQuestion::find($request->getAttribute('quizQuestionId'));
    $quizQuestion->fill($args);
    $result = array();
    try {
        $validationErrorFields = $quizQuestion->validate($args);
        if (empty($validationErrorFields)) {
            $quizQuestion->save();
            if (!empty($args['answer_options'])) {
                Models\QuizQuestionAnswerOption::where('quiz_question_id', $request->getAttribute('quizQuestionId'))->delete();
                $answer_options = explode("\n", $args['answer_options']);
                foreach ($answer_options as $answer_option) {
                    $questionAnswerOptions = new Models\QuizQuestionAnswerOption();
                    $questionAnswerOptions->quiz_question_id = $quizQuestion->id;
                    $questionAnswerOptions->quiz_id = $quizQuestion->quiz_id;
                    $questionAnswerOptions->option = trim($answer_option);
                    $questionAnswerOptions->is_correct_answer = 0;
                    if (!empty($args['current_answer']) && trim($args['current_answer']) == trim($answer_option)) {
                        $questionAnswerOptions->is_correct_answer = 1;
                    }
                    $questionAnswerOptions->save();
                }
            }
            $result['data'] = $quizQuestion->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Quiz question could not be updated. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateQuizQuestion'));
/**
 * GET quizQuestionAnswerOptionsGet
 * Summary: Fetch all quiz question answer options
 * Notes: Returns all quiz question answer options from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/quiz_question_answer_options', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $quizQuestionAnswerOptions = Models\QuizQuestionAnswerOption::Filter($queryParams)->paginate()->toArray();
        $data = $quizQuestionAnswerOptions['data'];
        unset($quizQuestionAnswerOptions['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $quizQuestionAnswerOptions
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = $e->getMessage(), $fields = '', $isError = 1, 422);
    }
})->add(new ACL('canListQuizQuestionAnswerOption'));
/**
 * POST quizQuestionAnswerOptionsPost
 * Summary: Creates a new quiz question answer option
 * Notes: Creates a new quiz question answer option
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/quiz_question_answer_options', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $quizQuestionAnswerOption = new Models\QuizQuestionAnswerOption($args);
    $result = array();
    try {
        $validationErrorFields = $quizQuestionAnswerOption->validate($args);
        if (empty($validationErrorFields)) {
            $quizQuestionAnswerOption->save();
            $result = $quizQuestionAnswerOption->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Quiz question answer option could not be added. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canCreateQuizQuestionAnswerOption'));
/**
 * DELETE quizQuestionAnswerOptionsQuizQuestionAnswerOptionIdDelete
 * Summary: Delete quiz question answer option
 * Notes: Deletes a single quiz question answer option based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/quiz_question_answer_options/{quizQuestionAnswerOptionId}', function ($request, $response, $args) {
    $quizQuestionAnswerOption = Models\QuizQuestionAnswerOption::find($request->getAttribute('quizQuestionAnswerOptionId'));
    $result = array();
    try {
        if (!empty($quizQuestionAnswerOption)) {
            $quizQuestionAnswerOption->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteQuizQuestionAnswerOption'));
/**
 * GET quizQuestionAnswerOptionsQuizQuestionAnswerOptionIdGet
 * Summary: Fetch quiz question answer option
 * Notes: Returns a quiz question answer option based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/quiz_question_answer_options/{quizQuestionAnswerOptionId}', function ($request, $response, $args) {
    $result = array();
    $quizQuestionAnswerOption = Models\QuizQuestionAnswerOption::find($request->getAttribute('quizQuestionAnswerOptionId'));
    if (!empty($quizQuestionAnswerOption)) {
        $result['data'] = $quizQuestionAnswerOption;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
})->add(new ACL('canViewQuizQuestionAnswerOption'));
/**
 * PUT quizQuestionAnswerOptionsQuizQuestionAnswerOptionIdPut
 * Summary: Update quiz question answer option by its id
 * Notes: Update quiz question answer option by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/quiz_question_answer_options/{quizQuestionAnswerOptionId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $quizQuestionAnswerOption = Models\QuizQuestionAnswerOption::find($request->getAttribute('quizQuestionAnswerOptionId'));
    $quizQuestionAnswerOption->fill($args);
    $result = array();
    try {
        $validationErrorFields = $quizQuestionAnswerOption->validate($args);
        if (empty($validationErrorFields)) {
            $quizQuestionAnswerOption->save();
            $result['data'] = $quizQuestionAnswerOption->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Quiz question answer option could not be updated. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateQuizQuestionAnswerOption'));

'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('QuizesFactory', ['$resource', function($resource) {
        return $resource('/api/v1/quizzes', {}, {}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('QuizesQuestionFactory', ['$resource', function($resource) {
        return $resource('/api/v1/quiz_questions', {}, {}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('QuizQuestionAnswerOptionFactory', ['$resource', function($resource) {
        return $resource('/api/v1/quiz_question_answer_options', {}, {}, {
            get: {
                method: 'GET'
            }
        });
}]);
    


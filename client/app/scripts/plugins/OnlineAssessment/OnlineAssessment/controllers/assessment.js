'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserProfileController
 * @description
 * # UserProfileController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name user.controller:UserProfileController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/

    .controller('AssessmentController', function ($state, $scope,QuizesFactory,QuizesQuestionFactory,QuizQuestionAnswerOptionFactory,$cookies , flash, UserProfilesFactory, $filter, $rootScope, $location, Upload, GENERAL_CONFIG, ConstSocialLogin, ConstThumb, $timeout) {
        

        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:UserProfileController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        
        $scope.setMetaData = function () {
            var fullUrl = $location.absUrl();
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $scope.answerChange = function(parentIndex,index){
            $scope.checked = $scope.que[parentIndex].options[index].selected_code;
            angular.forEach($scope.que, function(value, key){
                angular.forEach(value.options, function(childvalue){
                    if(key === parentIndex){
                        childvalue.selected_code = '';
                    }
                });
                
            });
            $scope.que[parentIndex].options[index].selected_code = $scope.checked;
        };
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Online Assessment");
            $scope.setMetaData();
            $scope.ConstSocialLogin = ConstSocialLogin;
            $scope.thumb = ConstThumb.user;
             $scope.params = {};  
             $scope.click = false;
             $scope.valid = true;
           if(parseInt($state.params.id) === 5){
               $scope.click = true;
               $scope.valid = false;
           } 
            $scope.quizes =[];
            QuizesFactory.get().$promise.then(function (response) {
                  $scope.size = response.data;
                angular.forEach(response.data, function(childvalue){
                        if(parseInt($state.params.id) === parseInt(childvalue.id)){
                            $scope.quizes.push(childvalue);
                        }  
                    });
            });
            $scope.questions =[];
            QuizesQuestionFactory.get().$promise.then(function (response) {
                angular.forEach(response.data, function(childvalue){
                        if(parseInt($state.params.id) === parseInt(childvalue.quiz_id)){
                            $scope.questions.push(childvalue);
                        }  
                    });
            });
            $scope.que =[];
            var params = {};
            params.filter = '{"limit":500,"skip":"0"}';
            QuizQuestionAnswerOptionFactory.get(params).$promise.then(function (response) {
                $timeout(function(){
                    angular.forEach($scope.questions, function(questionvalue,questionkey){
                        $scope.que.push({"quiz_id":questionvalue.id,"question":questionvalue.question});
                        $scope.que[questionkey].options = [];
                        angular.forEach(response.data, function(childvalue){
                            if(parseInt(childvalue.is_correct_answer) === 1){
                                $scope.que[questionkey].answerId = childvalue.id;
                            }
                            if(parseInt(questionvalue.id) === parseInt(childvalue.quiz_question_id)){
                                $scope.que[questionkey].options.push(childvalue);
                                childvalue.selected_code=0;
                            }  
                        });
                    });
                },50)
                  
               
            });
            
        };
        $scope.init();
        
        //Update user details
        /**
         * @ngdoc method
         * @name userProfile
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will upload the file and returns the success message.
         *
         **/
        $scope.userSubmit = function () {
            angular.forEach( $scope.que, function(quevalue,quekey){
                $scope.setcorrect = true;
                $scope.setprovided = true;
                angular.forEach(quevalue.options, function(childvalue){
                    if(childvalue.selected_code !== 0 ){
                        $scope.setprovided = false;
                        if(childvalue.is_correct_answer === 1){
                            if(childvalue.id === childvalue.selected_code){
                                $scope.setcorrect = false;
                            }
                        }
                     }
                });
                if($scope.setprovided === false){
                    $scope.que[quekey].showNotCorrect= $scope.setcorrect;
                }
                $scope.que[quekey].showNotProvided = $scope.setprovided;
            });
            angular.forEach($scope.que, function(value,key){
                if(key === 0){
                     if(value.showNotCorrect === false && value.showNotProvided === false){
                $scope.value = true;
            } } 
            if(key === 1){
                if(value.showNotCorrect === false && value.showNotProvided === false){
                $scope.value_start = true;
            }
            }
            if(key === 2){
                if(value.showNotCorrect === false && value.showNotProvided === false){
                $scope.value_end = true;
             }
            }
                 });
                if($scope.value && $scope.value_start && parseInt($state.params.id) !== 5){
                $scope.adding = parseInt($state.params.id) + 1;
                $state.go('AssessmentDetails', {
                'id': $scope.adding
                  }, {reload: true});
                }
                if($scope.value_end !== undefined){
                if($scope.value && $scope.value_start && $scope.value_end && parseInt($state.params.id) === 5){
                     $scope.user_profile = {};
                      $scope.user_profile.is_online_assessment_test_completed = 1;
                      UserProfilesFactory.update($scope.user_profile).$promise.then(function () {
                        flash.set($filter("translate")("Online Assessment has been completed"), 'success', true);
                        $state.go('user_dashboard');
                    });
                }
                }
        };
    });
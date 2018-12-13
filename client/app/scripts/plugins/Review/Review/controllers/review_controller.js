'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:AppointmentsController
 * @description
 * # AppointmentsController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
.controller('ReviewController', function ($scope, $state, $filter, $rootScope,AppointmentStatus,flash, ConstUserType, ConstAppointmentStatus, ConstService, $window, md5, $cookies,ReviewFactory,ReviewUpdate, AllReviews) {
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        
        $scope.enabled = false;
        $scope.showClose = false;
        $scope.review = [];
        $rootScope.subHeader = "Bookings & messages"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.user_review = [];
        $scope.user_review_count = 0;
        $scope.enable_review = true;
        $scope.showEdit = true;
        $scope.showUpdate = false;
        
        //Review Get
      var params = {};
       params.filter = '{"where":{"foreign_id":'+$state.params.id+'}}';
       AllReviews.get(params, function(response){
           $scope.user_review = response.data;
           $scope.user_review_count = $scope.user_review.length;
       });
         //Update Review
        $scope.UpdateReview = function(){
            params = {};
            params.reviewId = $scope.user_review[0].id;
            params.message = $scope.user_review[0].message;
            ReviewUpdate.update(params).$promise.then(function(response) {
                if (response.error.code === 0) {
                    flash.set($filter("translate")("review updated successfully."), 'success', true);
                } else {
                    flash.set($filter("translate")(response.error.message), 'error', false);
                }
            });
        };
        $scope.reviewSubmit = function(is_valid) {
            
            params ={};
                if (is_valid && $scope.review.rating === 0){
                        is_valid = false;
                        $scope.rating_review = false;
                    } else if( is_valid && $scope.review.rating > 0 ){
                        is_valid = true;
                        $scope.rating_review = true;
                    }
                    if (is_valid) {
                        if (!$scope.reviewid) {
                        params = {};
                        params.foreign_id = $state.params.id;
                        params.class = "Appointment";
                        params.rating = $scope.review.rating;
                        params.message = $scope.review.message;
                        ReviewFactory.add(params, function(response) {
                            if (response.error.code === 0) {
                                flash.set($filter("translate")("review added successfully."), 'success', true);
                                $scope.user_review = [];
                                $scope.user_review.push(response.data);
                                $scope.user_review_count = $scope.user_review.length;
                                //$scope.closefrm();
                            } else {
                                flash.set($filter("translate")(response.error.message), 'error', false);
                            }
                        });
                    } else {
                        
                    }
                }
            };
    });
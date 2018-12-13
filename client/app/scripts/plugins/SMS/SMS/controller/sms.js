'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:PhotosController
 * @description
 * # PhotosController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name user.controller:PhotosController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/

 .controller('SmsController', function ($state, $scope, flash, $filter,UserProfilesFactory, $rootScope,SmsRecend,SmsVerify,$timeout,$interval) {        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:PhotosController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:PhotosController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $scope.init = function () {
            $scope.sms = {};
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Mobile number verification");
            var params = {};
            params.filter = '{"include":{"0":"user"}}';
            UserProfilesFactory.get(params).$promise.then(function (response) {
                $scope.phone_number = response.user.phone_number;
                if(response.user.mobile_code != null){
                    $scope.mobile_code = response.user.mobile_code;
                }else{
                   $scope.mobile_code = ''; 
                }
                if(response.user.is_mobile_number_verified == 0){
                    $scope.mobile_verified = true;
                }else{
                   $scope.mobile_verified = false;
                }
            });
            $scope.show = false;
            $scope.hide = true;
        };
        $scope.init();
            $scope.smsVerify = function($valid){
                if($valid){
                    var param = {};
                    param.otp = $scope.sms.otp;
                    param.id = $rootScope.auth.id;
                    SmsVerify.save(param,function (response) {
                    if(response.error.code === 0){
                        flash.set($filter("translate")(response.error.message), 'success', true);
                    }
                    $state.go('user_profile', {
                            type: 'personal',
                        });
                    },function(response) {
                    flash.set($filter("translate")(response.data.error.message), 'error', false);
                });
                }
                
            };
            $scope.showing = function(){
                $scope.show = true;
                $scope.hide = false;
            };
            $scope.smsResend = function(){
                SmsRecend.post().$promise.then(function (response) {
                    if(response.error.code === 0){
                        flash.set($filter("translate")(response.error.message), 'success', true);
                        $scope.showing = false;
                        $scope.value = true;
                        $scope.countDown = 20;
                        var stop;
                        stop = $interval(function() {
                        if ($scope.countDown > 0) {
                       $scope.countDown = $scope.countDown - 1;
                       }else{
                        $scope.stopFight();
                        $scope.value = false;
                       }
                       }, 1000);
                       $scope.stopFight = function() {
                        $interval.cancel(stop);
                        stop = undefined;
                        };
                        $timeout(function () {
                        $scope.showing = true;
                         }, 20000);
                    }else{
                        flash.set($filter("translate")(response.error.message), 'error', false);
                    }
            });
            };
            
       
    });
    


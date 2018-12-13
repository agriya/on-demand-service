'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserNotificationController
 * @description
 * # UserNotificationController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserNotificationController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/
.controller('UserNotificationController', function ($state, $scope, $cookies, flash, UserProfilesFactory, $filter, $rootScope, $location, Upload, GENERAL_CONFIG, ConstSocialLogin, ConstThumb,ConstListingStatus) {
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Notifications");
            $scope.ConstSocialLogin = ConstSocialLogin;
            $scope.ConstListingStatus = ConstListingStatus;
            $scope.thumb = ConstThumb.user;
            if(parseInt($rootScope.settings.IS_SITE_ENABLED_PUSH_NOTIFICATION) === 1){
                $scope.push = true;
            }else{
                $scope.push = false;
            }
        };
        $scope.init();
        $scope.dateBlockeBefore = $filter('date')(new Date(), "yyyy-MM-ddTHH:mm:ss.sssZ");
        
        // upload on file select or drop
        /**
         * @ngdoc method
         * @name upload
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will save the user profile data
         *
         * @param {!Array.<string>} profileData contains the array of user profile data
         **/
       
        $scope.auth = $cookies.get("auth");
        $scope.authvalue = $scope.auth;
        $scope.user_profile = {};
        var params = {};
             params.filter = '{"include":{"0":"user"}}';
            UserProfilesFactory.get(params).$promise.then(function (response) {
             $scope.first_name = response.first_name;
             $scope.last_name = response.last_name;
             $scope.value = response;
             if (parseInt(response.user.is_app_device_available) === 1){
                  $scope.push_notification = true;
             } else{
                 $scope.push_notification = false;
             }
             if (parseInt(response.user.is_sms_notification) === 1){
                  $scope.user_profile.is_sms_notification = true;
             } 
             if (parseInt(response.user.is_email_subscribed) === 1) {
                  $scope.user_profile.is_email_subscribed = true;
             }
             if (parseInt(response.user.is_push_notification_enabled) === 1) {
                  $scope.user_profile.is_push_notification_enabled = true;
             }
            });

        $scope.userNotifications = function(user){
            params = {};
            params.is_email_subscribed = user.is_email_subscribed === true ? 1 : 0;
            params.is_sms_notification = user.is_sms_notification === true ? 1 : 0;
            params.is_push_notification_enabled = user.is_push_notification_enabled === true ? 1 : 0;
            UserProfilesFactory.update(params).$promise.then(function (response) {
            if(response.error.code === 0){
                    if(parseInt($rootScope.settings.IS_SITE_ENABLED_PUSH_NOTIFICATION) === 1){
                    $scope.push = true;
                }else{
                    $scope.push = false;
                }
                flash.set($filter("translate")("Notifications has been updated."), 'success', true);
            }    
            
            },function(errorResponse){
                    flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
            });
        };
           
    })

    /* For select when search */
    .directive('convertToNumber', function() {
        return {
          require: 'ngModel',
          link: function(scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function(val) {
              return val ? parseInt(val, 10) : null;
            });
            ngModel.$formatters.push(function(val) {
              return val ? '' + val : '';
            });
          }
        };
    });
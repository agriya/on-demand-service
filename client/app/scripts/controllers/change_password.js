'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:ChangePasswordController
 * @description
 * # ChangePasswordController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:ChangePasswordController
     * @description
     *
     * 1.This is change password controller having the methods setmMetaData, init and change_password.
     *
     * 2.It is used to change the password of the user.
     **/
.controller('ChangePasswordController', function ($state, $auth, $scope, flash, $http, $filter, $rootScope, $location,  $window, $cookies, ChangePWd, providerSetting) {
        $scope.user = {};
        $scope.providers = providerSetting.provider_details.data; 
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:ChangePasswordController
         * @description
         * This method is used to set the meta data dynamically by using the angular.element
         *
         */
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("Change Password");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings.SCHEME_NAME + ":/" + $location.url();
            angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]').attr("content", $rootScope.settings.SITE_NAME + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:ChangePasswordController
         * @description
         * This method is used to initialize the meta data that is already set by setmetadata() method.
         *
         */
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Change Password");
        };
        $scope.init();
        /**
         * @ngdoc method
         * @name change_password
         * @methodOf user.controller:ChangePasswordController
         * @description
         * This method used for changing the password by the user.
         * @param {!Array.<string>} changePasswordForm The form contains the array of string that holds the [old_password, new_password]
         */
        $scope.change_password = function (changePasswordForm, user) {
            $scope.disableButton = true;
            if (changePasswordForm) {
                ChangePWd.put({id: $rootScope.auth.id}, user, function (response) {
                   if (response.error.code === 0) {
                            $state.go('user_dashboard');
                            flash.set($filter("translate")("Password changed successfully."), 'success', false);
                        }
                },function(){
                    flash.set($filter("translate")("Your old password is incorrect, please try again."), 'error', false);
                });
            }
        }; 
        $scope.authenticate = function(provider) {
            $auth.authenticate(provider)
                .then(function(response) {
                    $scope.response = response.data;
                    if ($scope.response.error.code === 0 && $scope.response.thrid_party_profile) {
                        $window.localStorage.setItem("twitter_auth", JSON.stringify($scope.response));
                        $state.go('get_email');
                    } else if ($scope.response.access_token) {
                        $cookies.put('auth', JSON.stringify($scope.response), {
                            path: '/'
                        });
                        $cookies.put('token', $scope.response.access_token, {
                            path: '/'
                        });
                        $rootScope.user = $scope.response;
                        $rootScope.$emit('updateParent', {
                            isAuth: true
                        });
                        if ($cookies.get("redirect_url") !== null && $cookies.get("redirect_url") !== undefined) {
                            $location.path($cookies.get("redirect_url"));
                            $cookies.remove('redirect_url');
                        } else {
                            $location.path('/');
                        }
                    }
                })
                .catch(function(error) {
                    console.log("error in login", error);
                });
        };
});
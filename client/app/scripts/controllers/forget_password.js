'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:ForgotPasswordController
 * @description
 * # ForgotPasswordController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:ForgotPasswordController
     * @description
     *
     * This is forgot password controller having the service forgot_password. It is used for reset the password if the users forgot their password.
     **/
.controller('ForgotPasswordController', function ($state, $window, $scope, $rootScope, $location, flash, $filter, ForgotPasswordFactory) {
        $scope.user = {};
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Forgot Password");
        /**
         * @ngdoc method
         * @name forgot_password
         * @methodOf user.controller:ForgotPasswordController
         * @description
         * This method uses the forgot_password factory service to change the password.
         *
         **/
        $scope.forgot_password = function (forgotPassword, user) {
            $scope.user = user;
            $scope.disableButton = true;
            if (forgotPassword) {
                ForgotPasswordFactory.forgot_password($scope.user, function (response) {
                    if (response.error.code === 0) {
                        flash.set($filter("translate")("Password changed Successfully. New password is sent in mail."), 'success', false);
                        $state.go('login');
                    } else {
                        flash.set($filter("translate")("Your email address is not registered in this site."), 'error', false);
                        $scope.disableButton = false;
                    }
                },function(){
                    $scope.disableButton = false;
                    flash.set($filter("translate")("Your email address is not registered in this site."), 'error', false);
                });
            }
        };

    });
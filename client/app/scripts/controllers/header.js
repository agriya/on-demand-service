'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserLoginController
 * @description
 * # UserLoginController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name common.controller:HeaderController
     * @description
     *
     * This is HeaderController having the methods init(), setMetaData() and logout() and it defines the header section of all the pages.
     *
     * The header will change according to the user login. If the user logged in, header conotains the user settings, and logout options.
     *
     * If not logged in, signin and signup options will be available in header.
     **/
   .controller('HeaderController', function ($state, $scope, $rootScope, $auth, $location, $timeout,$cookies, ConstSocialLogin, ConstThumb, ConstUserType, ConstMembershipPlan) {
        /**
         * @ngdoc method
         * @name init
         * @methodOf common.controller:HeaderController
         * @description
         * This method will initialze the page and it initializes the settings for header.
         *
         **/
        $scope.init = function () {
            $rootScope.isAuth = false;
            $scope.ConstSocialLogin = ConstSocialLogin;
            $scope.thumb = ConstThumb.user;
            $scope.ConstUserType = ConstUserType;
            $scope.ConstMembershipPlan = ConstMembershipPlan;
            if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                $rootScope.isAuth = true;
                $rootScope.user = JSON.parse($cookies.get("auth"));
            }
        };
        $scope.init();
    });
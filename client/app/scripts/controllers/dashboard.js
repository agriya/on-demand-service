'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:DashboardController
 * @description
 * # DashboardController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:DashboardController
     * @description
     *
     * This is dashboard controller. It contains all the details about the user. It fetches the data of the user by using AuthFactory.
     **/
.controller('DashboardController', function ($state, $scope, $http, flash, AuthFactory, GENERAL_CONFIG, $filter, $rootScope, ConstThumb, ConstSocialLogin, ConstUserType) {
        var model = this;
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:DashboardController
         * @description
         * This method will initialze the page. It returns the user's details.
         *
         **/
        model.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("User Dashboard");
            //Get user details
            AuthFactory.fetch({}).$promise
                .then(function (response) {
                    $scope.user = response;
                });
            model.ConstSocialLogin = ConstSocialLogin;
            model.thumb = ConstThumb.user;
            model.ConstUserType = ConstUserType;
        };
        model.init();
    });
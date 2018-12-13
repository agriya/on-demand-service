'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:InsuranceController
 * @description
 * # InsuranceController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserPreferenceController
     * @description
     *
     * This is dashboard controller. It contains all the details about the user. It fetches the data of the user by using AuthFactory.
     **/
    .controller('UserPreferenceController', function ($scope, $state,$filter,UserNotification,flash,UserProfilesFactory, $rootScope,YachtTypes,YachtSizeExperiences, $cookies) {   
       $rootScope.subHeader = "Listing"; //for setting active class for subheader
       $cookies.put('subHeader', $rootScope.subHeader);
       $rootScope.gotoHeader();
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Your profile - About me");
            $scope.aside = {
                "title": "Title",
                "content": "Hello Aside<br />This is a multiline message!"
            };
        };

        $scope.init();
        $scope.preference = {};
    });
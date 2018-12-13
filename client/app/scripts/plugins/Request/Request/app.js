'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.Photos
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp.Request', [
    'hirecoworkerApp.Constant',
    'ngResource',
    'ui.router.state',
    'ui.router',
    'ui.bootstrap',
    'ui.calendar',
    'ngSanitize',
    'satellizer',
    'ngAnimate',
    'angular-growl',
    'pascalprecht.translate',
    'ngCookies',
    'ngMap',
    'builder',
    'builder.components',
    'google.places',
    'angular-input-stars',
    'tmh.dynamicLocale',
    'angular-loading-bar',
    'angular-input-stars',
    'mwl.calendar',
    'ngFileUpload',
    'vcRecaptcha',
    'mgcrea.ngStrap',
    'hm.readmore',
    'checklist-model',
    'daterangepicker',
    'oitozero.ngSweetAlert',
    'angular-md5',
    'ngRateIt',
    'angularjs-dropdown-multiselect',
    'slugifier',
    '720kb.socialshare'
])
 .config(function ($stateProvider) {
         var getToken = {
            'TokenServiceData': function(TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
    };
        $stateProvider.state('JobPost', {
                url: '/job-post',
                templateUrl: 'scripts/plugins/Request/Request/views/job_post_home.html',
                controller: 'RequestController',
                resolve: getToken
            }).state('jobs', {
                url: '/jobs?latitude&longitude&sw_latitude&sw_longitude&ne_latitude&ne_longitude&service_id&appointment_from_date&appointment_to_date&address&display_type&page&couple_select&zoom&more&iso2&radius&search_type',
                templateUrl: 'scripts/plugins/Request/Request/views/job_board.html',
                controller: 'JobBoardController',
                resolve: getToken
            })
             .state('JobListing', {
                url: '/my_jobs',
                templateUrl: 'scripts/plugins/Request/Request/views/job_list_home.html',
                controller: 'RequestListController',
                resolve: getToken
            }).state('JobView', {
                url: '/job/{job_id}',
                templateUrl: 'scripts/plugins/Request/Request/views/job_view.html',
                controller: 'JobViewController',
                resolve: getToken
            }).state('JobEdit', {
                url: '/job/{job_id}/edit',
                templateUrl: 'scripts/plugins/Request/Request/views/job_post_home.html',
                controller: 'RequestController',
                resolve: getToken
            });
    });

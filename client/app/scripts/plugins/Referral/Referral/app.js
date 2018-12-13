'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.Photos
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp.Referral', [
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
        $stateProvider.state('referrals', {
            url: '/refer_friends',
            templateUrl: 'scripts/plugins/Referral/Referral/views/referal_index.html',
            controller: 'ReferalController',
            resolve: getToken
        })
        .state('affiliates', {
                url: '/referrals/:username',                
                controller: 'AffiliateReferralController',
                resolve: getToken
            });
    });

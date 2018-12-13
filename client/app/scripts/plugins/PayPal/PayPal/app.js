'use strict';
angular.module('hirecoworkerApp.PayPal', [
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
        $stateProvider.state('CreditCard', {
            url: '/credit_cards',
            templateUrl: 'scripts/plugins/PayPal/PayPal/views/credit_card.html',
            controller: 'CreditCardController',
            resolve: getToken
        });
    });

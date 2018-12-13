'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.Photos
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp.BlockedUser', [])
 .config(function ($stateProvider) {
         var getToken = {
            'TokenServiceData': function(TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
    };
        $stateProvider.state('BlockUser', {
            url: '/blocked_users',
            templateUrl: 'scripts/plugins/BlockedUser/BlockedUser/views/block_user.html',
            controller: 'BlockUserController',
            resolve: getToken
        });
    });

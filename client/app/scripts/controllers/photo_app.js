'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.Photos
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
 .config(function ($stateProvider) {
         var getToken = {
            'TokenServiceData': function(TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
    };
        $stateProvider.state('UserPhotos', {
            url: '/photos',
            templateUrl: 'views/photo_index.html',
            controller: 'PhotosController',
            resolve: getToken
        })
        .state('UserPhotosAdd', {
            url: '/photos/add',
            templateUrl: 'views/photo_add.html',
            controller: 'PhotosController',
            resolve: getToken
        });
    });

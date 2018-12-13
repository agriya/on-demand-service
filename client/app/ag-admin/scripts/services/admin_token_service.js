'use strict';
/**
 * @ngdoc service
 * @name baseApp.sessionService
 * @description
 * # sessionService
 * Factory in the baseApp.
 */
angular.module('base')
    .service('adminTokenService', function($rootScope, $http, $window, $q, $cookies) {
        //jshint unused:false
        var promise;
        var promiseSettings;
        var deferred = $q.defer();
        if (angular.isUndefined($rootScope.settings)) {
            $rootScope.settings = {};
            var params = {};
            params.fields = 'name,value';
            params.sortby = 'asc';
            promiseSettings = $http({
                    method: 'GET',
                    url: '/api/v1/settings',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    params: params
                })
                .success(function(response) {
                    if (angular.isDefined(response.data)) {
                        var settings = {};
                        angular.forEach(response.data, function(value, key) {
                            //jshint unused:false
                            $rootScope.settings[value.name] = value.value;
                            settings[value.name] = value.value;
                        });
                        if ($cookies.get("SETTINGS") === null || $cookies.get("SETTINGS") === undefined) {
                            $cookies.put("SETTINGS", JSON.stringify(settings), {
                                path: '/'
                            });
                        }
                    }
                });
        } else {
            promiseSettings = true;
        }
        return {
        //    promise: promise,
            promiseSettings: promiseSettings
        };
    });
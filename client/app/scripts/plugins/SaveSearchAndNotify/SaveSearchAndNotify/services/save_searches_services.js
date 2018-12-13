'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # SaveSearchAndNotify Plugin
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('GetSavedSearches', ['$resource', function($resource) {
        return $resource('/api/v1/user_searches/:id', {id:'@id'},  {
            get: {
                method: 'GET'
            }
        });
}])
.factory('GetMeSavedSearches', ['$resource', function($resource) {
        return $resource('/api/v1/me/user_searches', {},  {
            get: {
                method: 'GET'
            }
        });
}])
.factory('SavedSearches', ['$resource', function($resource) {
        return $resource('/api/v1/user_searches/:id', {id:'@id'},  {
            put: {
                method: 'PUT'
            },
            delete: {
                method: 'DELETE'
            }
        });
}]).factory('PostSavedSearches', ['$resource', function($resource) {
        return $resource('/api/v1/user_searches', {},  {
            post: {
                method: 'POST'
            }
        });
}]);
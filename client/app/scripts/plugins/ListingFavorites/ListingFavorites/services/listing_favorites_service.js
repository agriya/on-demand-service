'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('UserFavorite', ['$resource', function($resource) {
        return $resource('/api/v1/user_favorites', {}, {
            get: {
                method: 'GET'
            },
            post:{
                method: 'POST'
            }
        });
  }])
  .factory('EditUserFavorite', ['$resource', function($resource) {
        return $resource('/api/v1/user_favorites/:userFavoriteId', {userFavoriteId:'@userFavoriteId'}, {
            get: {
                method: 'GET'
            },
            delete:{
                method: 'DELETE'
            }
        });
  }]);
    


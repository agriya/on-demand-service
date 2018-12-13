'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
  .factory('Home', ['$resource', function($resource) {
        return $resource('/api/v1/languages', {}, {
            get: {
                method: 'GET'
            }
        });
  }])
  .factory('Cities', ['$resource', function($resource) {
        return $resource('/api/v1/cities', {}, {
            get: {
                method: 'GET'
            }
        });
  }])
  .factory('Services', ['$resource', function($resource) {
        return $resource('/api/v1/services', {}, {
            get: {
                method: 'GET'
            }
        });
  }])
  .factory('BestRated', ['$resource', function($resource) {
        return $resource('/api/v1/users/featured', {}, {
            get: {
                method: 'GET'
            }
        });
  }]);
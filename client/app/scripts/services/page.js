'use strict';
/**
 * @ngdoc service
 * @name getlancerApp.page
 * @description
 * # page
 * Factory in the getlancerApp.
 */
angular.module('hirecoworkerApp')
    .factory('page', ['$resource', function($resource) {
        return $resource('/api/v1/pages/:id/:slug', {id: '@id',slug:'@slug'}, {
            get: {
                method: 'GET',
            }
        });
    }]);

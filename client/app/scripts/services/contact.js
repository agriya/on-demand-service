'use strict';
/**
 * @ngdoc service
 * @name getlancerApp.contact
 * @description
 * # contact
 * Factory in the getlancerApp.
 */
angular.module('hirecoworkerApp')
    .factory('contact', ['$resource', function($resource) {
        return $resource('/api/v1/contacts', {}, {
            create: {
                method: 'POST'
            }
        });
       
    }])
    .factory('contactMe', ['$resource', function($resource) {
        return $resource('/api/v1/me/contacts', {}, {
            get: {
                method: 'GET'
            }
        });
       
    }]);
 
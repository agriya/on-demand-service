'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('EmailRecend', ['$resource', function($resource) {
       return $resource('/api/v1/users/resend_activation_link', {}, {
            'post': {
                method: 'POST'
            }
        });
}]);


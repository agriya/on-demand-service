'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.blockeduser
 * @description
 * # appointment
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('BlockUser', ['$resource', function($resource) {
        return $resource('/api/v1/blocked_users', {}, {
            post:{
                    method: 'POST'
                }
        });
}]).factory('GetBlockUser', ['$resource', function($resource) {
        return $resource('/api/v1/blocked_users', {}, {
            get:{
                    method: 'GET'
                }
        });
}]).factory('DeleteBlockUser', ['$resource', function($resource) {
        return $resource('/api/v1/blocked_users/:blockedUserId', {blockedUserId:'@blockedUserId'}, {
            delete:{
                    method: 'DELETE'
                }
        });
}]);
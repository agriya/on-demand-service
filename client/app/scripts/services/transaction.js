'use strict';
/**
 * @ngdoc service
 * @name getlancerApp.cashWithdrawals
 * @description
 * # cashWithdrawals
 * Factory in the getlancerApp.
 */
angular.module('hirecoworkerApp')
    .factory('TransactionsFactory', ['$resource', function($resource) {
        return $resource('/api/v1/transactions', {}, {
            get: {
                method: 'GET'
            }
        });
}]).factory('TransactionsGetFactory', ['$resource', function($resource) {
        return $resource('/api/v1/users/:userId/transactions', {}, {
            get: {
                method: 'GET',
                params : {
                    userId : '@userId'
                }
            }
        });
}]);
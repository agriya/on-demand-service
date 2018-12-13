'use strict';
/**
 * @ngdoc service
 * @name getlancerv3.servicelocation
 * @description
 * # paymentGateway
 * Factory in the getlancerv3.
 */
angular.module('base')
    .factory('TransactionsGetFactory',function($resource) {
        return $resource('/api/v1/transactions', {}, {
            get: {
                method: 'GET'
            }
        });
});
     
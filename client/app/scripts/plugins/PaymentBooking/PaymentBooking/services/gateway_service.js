'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.appointment
 * @description
 * # appointment
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('PaymentGatewaySingle', ['$resource', function($resource) {
        return $resource('/api/v1/payment_gateway_settings/:id', {id:'@id'}, {
            get:{
                    method: 'GET'
                }
        });
}]).factory('PaymentGatewayList', ['$resource', function($resource) {
        return $resource('/api/v1/payment_gateways/list', {}, {
            get:{
                    method: 'GET'
                }
        });
}])
.factory('PaymentOrder', ['$resource', function($resource) {
        return $resource('/api/v1/order', {}, {
            post:{
                    method: 'POST'
                }
        });
}]);

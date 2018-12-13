'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('CreditCardFactory', ['$resource', function($resource) {
        return $resource('/api/v1/user_credit_cards', {},  {
            post: {
                method: 'POST'
            }
        });
}])
.factory('CreditCardGetFactory', ['$resource', function($resource) {
        return $resource('/api/v1/me/user_credit_cards', {},  {
            get: {
                method: 'GET'
            }
        });
}])
.factory('CreditCardRemoveFactory', ['$resource', function($resource) {
        return $resource('/api/v1/user_credit_cards/:userCreditCardId', {userCreditCardId:'@userCreditCardId'},  {
            delete: {
                method: 'DELETE'
            }
        });
}]);
    


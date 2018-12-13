'use strict';
/**
 * @ngdoc service
 * @name getlancerApp.cashWithdrawals
 * @description
 * # cashWithdrawals
 * Factory in the getlancerApp.
 */
angular.module('hirecoworkerApp')
    .factory('cashWithdrawals', ['$resource', function($resource) {
        return $resource('/api/v1/users/:user_id/user_cash_withdrawals', {}, {
            get: {
                method: 'GET',
                params: {
                    user_id: '@user_id'
                }
            },
            save: {
                method: 'POST',
                params: {
                    user_id: '@user_id'
                }
            },
        });
  }])
  .factory('moneyTransferAccount', ['$resource', function($resource) {
          return $resource('/api/v1/users/:user_id/money_transfer_accounts/:account', {}, {
              get: {
                  method: 'GET',
                  params: {
                      user_id: '@user_id'
                  }
              },
              save: {
                  method: 'POST',
                  params: {
                      user_id: '@user_id'
                  }
              },
              delete: {
                  method: 'DELETE',
                  params: {
                      user_id: '@user_id',
                      account: '@account'
                  }
              },
              put: {
                  method: 'PUT',
                  params: {
                      userId: '@userId',
                      account: '@account'
                  }
              }
          });
  }])
  .factory('myUserFactory', ['$resource', function($resource) {
        return $resource('/api/v1/me', {}, {
            get: {
                method: 'GET'
            }
        });
    }])
     .factory('UserMeFactory', ['$resource', function($resource) {
        return $resource('/api/v1/me', {}, {
            get: {
                method: 'GET'
            }
        });
    }]);
'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:MoneyTransferAccountController
 * @description
 * # MoneyTransferAccountController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    .controller('MoneyTransferAccountController', ['$rootScope', '$scope', 'moneyTransferAccount', 'flash', '$filter', '$state', '$cookies','SweetAlert', function($rootScope, $scope, moneyTransferAccount, flash, $filter, $state, $cookies,SweetAlert) {
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Money Transfer Accounts");
         $rootScope.subHeader = "Account"; //for setting active class for subheader
          $cookies.put('subHeader', $rootScope.subHeader);
         $rootScope.gotoHeader();
         if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
       $scope.currentPage = 1;
       $scope.lastPage = 1;
       $scope.index = function() {
            $scope.money_transfer_submit = false;
        };
        $scope.getList = function(){
            var params = {};
            params.user_id = $rootScope.auth.id;
            $scope.loader = true;
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
            params.filter = '{"limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+'}';
            moneyTransferAccount.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    $scope.moneyTransferAccLists = response.data;
                }
                if(response._metadata){
                    $scope.currentPage = response._metadata.current_page;
                    $scope.lastPage = response._metadata.last_page;
                    $scope.itemsPerPage = response._metadata.per_page;
                    $scope.totalRecords = response._metadata.total;
                }
                $scope.loader = false;
            });
        };
        $scope.MoneyTransferAccSubmit = function($valid) {
            if ($valid) {
                $scope.money_transfer_submit = true;
                var params = {};
                params.account = $scope.account;
                // params.is_active = 1;
                moneyTransferAccount.save({
                    'user_id': $rootScope.auth.id
                }, params, function(response) {
                    $scope.response = response;
                    $state.reload();
                    flash.set($filter("translate")("Account added successfully"), 'success', true);
                }, function() {
                    flash.set($filter("translate")("Account could not be added"), 'error', false);
                });
            }
        };
        $scope.MoneyTransferAccDelete = function (id) {
                    SweetAlert.swal({//jshint ignore:line
                        title: $filter("translate")("Are you sure you want to delete?"),
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel",
                        closeOnConfirm: true,
                        animation:false,
                    }, function (isConfirm) {
                        if (isConfirm) {
                            var param = {};
                            param.user_id = $rootScope.auth.id;
                            param.account = id;
                            moneyTransferAccount.delete(param, function(response) {
                                $scope.response = response;
                                if ($scope.response.error.code === 0) {
                                    $state.reload();
                                    flash.set($filter("translate")("Account deleted successfully."), 'success', false);
                                } else {
                                    flash.set($filter("translate")("You have active withdraw request with this money transfer account. So you could not delete this account."), 'error', false);
                                }
                            });
                        }
                    });
        };
        $scope.index();
        $scope.getList();
        $scope.paginate_transaction = function(currentPage) {
            $scope.currentPage = currentPage;
            $scope.getList();
        };
    }]);
'use strict';
/**
 * @ngdoc function
 * @name getlancerApp.controller:CashWithdrawalsController
 * @description
 * # CashWithdrawalsController
 * Controller of the getlancerApp
 */
angular.module('hirecoworkerApp')
    .controller('CashWithdrawalsController', ['$scope', '$cookies','$rootScope', '$state','cashWithdrawals','myUserFactory','$filter','moneyTransferAccount','flash',  function($scope, $cookies, $rootScope, $state,cashWithdrawals,myUserFactory,$filter,moneyTransferAccount,flash) {
        if(parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        $rootScope.subHeader = "Account"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.currentPage = 1;
        $scope.lastPage = 1;
        var params = {};
        // $rootScope.url_split = $location.path().split("/")[2];
        /*jshint -W117 */
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Revenue Withdrawals");
        $scope.minimum_withdraw_amount = $rootScope.settings.USER_MINIMUM_WITHDRAW_AMOUNT;
        $scope.maximum_withdraw_amount = $rootScope.settings.USER_MAXIMUM_WITHDRAW_AMOUNT;
        $scope.user_available_balance = $rootScope.auth.available_wallet_amount;
        $scope.withDrawAmount = 200; 
        $scope.mul = $scope.withDrawAmount * $rootScope.settings.WITHDRAW_REQUEST_FEE;
        $scope.ExampleAmount = $scope.withDrawAmount - $rootScope.settings.WITHDRAW_REQUEST_FEE;
        $scope.total = $scope.mul / 100;
        $scope.account_error = false;
        $scope.index = function() {
            $scope.loader = true;
            myUserFactory.get({}, function(response) {
                $scope.user_available_balance = response.data.available_wallet_amount;
                if (parseInt($scope.user_available_balance) === 0) {
                     $scope.getMyuser();
                    $scope.is_show_wallet_paybtn = false;
                } else {
                    $scope.is_show_wallet_paybtn = true;
                }
            });
        params = {};    
        params.user_id = $rootScope.auth.id;
        moneyTransferAccount.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    $scope.moneyTransferList = response.data;
                }
            });
        };
        $scope.getCashWithdrawlList = function(){
        params = {};
        params.user_id = $rootScope.auth.id;
        $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
        $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
        params.filter = '{"limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+'}';
            cashWithdrawals.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    $scope.cashWithdrawalsList = response.data;
                }
                $scope.loader = false;
            });
        };
        $scope.selectedAcc = function(id) {
            $scope.account_id = id;
            $scope.account_error = false;
        };
        $scope.getMyuser = function() {
            if ($rootScope.isAuth) {
                myUserFactory.get(function(response) {
                    $scope.my_user = response.data;
                });
            }
        };
        $scope.userCashWithdrawSubmit = function($valid) {
            var params = {};
            if ($scope.account_id === undefined) {
                $scope.account_error = true;
            } else {
                $scope.account_error = false;
            }
            if ($valid) {
                $scope.amount = parseFloat($('#amount').val());
                if (parseFloat($scope.user_available_balance) >= $scope.amount) {
                    params.amount = $scope.amount;
                    params.money_transfer_account_id = $scope.account_id;
                    params.remark = "";
                    params.user_id = $rootScope.auth.id;
                    cashWithdrawals.save(params, function(response) {
                        if (response.error.code === 0) {
                            flash.set($filter("translate")("Your request submitted successfully."), 'success', true);
                            $state.reload();
                        }
                    }, function() {
                        flash.set($filter("translate")("Withdraw request could not be added"), 'error', false);
                    });
                } else {
                    flash.set("You Dont have sufficient amount in your wallet.", "error", false);
                }
            }
        };
        $scope.getCashWithdrawlList();
        $scope.index();
        $scope.paginate_cashwithdrawl = function(currentPage) {
            $scope.currentPage = currentPage;
            $scope.getCashWithdrawlList();
        };
    }]);
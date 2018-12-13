'use strict';
/**
 * @ngdoc function
 * @name getlancerApp.controller:TransactionsController
 * @description
 * # TransactionsController
 * Controller of the getlancerApp
 */
/* global angular */
angular.module('hirecoworkerApp')
    .controller('TransactionController', function ($scope,$state,$rootScope,$filter,TransactionsFactory,ConstUserType,ConstTransactionTypes,myUserFactory, $cookies,TransactionsGetFactory) {
        // $rootScope.url_split = $location.path().split("/")[1];
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Transactions History");
        $scope.currentPage = 1;
        $scope.lastPage = 1;
         if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
         $rootScope.subHeader = "Account"; //for setting active class for subheader
          $cookies.put('subHeader', $rootScope.subHeader);
         $rootScope.gotoHeader();
        
        $scope.index = function() {
            $scope.getTransactionsDetails();
            $scope.ConstUserType = ConstUserType;
            $scope.ConstTransactionTypes = ConstTransactionTypes;
        };
         $scope.walletamount = function() { 
            if ($rootScope.isAuth) {
                $scope.loader = true;
                myUserFactory.get(function(response) {
                        $scope.my_user = response.data;
                            $scope.wallet_amount = Number($scope.my_user.available_wallet_amount || 0);
                            $scope.loader = false;
                });
            }
        };
        $scope.walletamount();
        /**
         * @ngdoc method
         * @name JobsAddController.clear
         * @methodOf module.JobsAddController
         * @description
         * This method is used for clear the date
         */
        /**
         * @ngdoc method
         * @name JobsAddController.getDayClass
         * @methodOf module.JobsAddController
         * @description
         * This method is used for datepicker plugin.
         */
        /**
         * @ngdoc method
         * @name JobsAddController.formats
         * @methodOf module.JobsAddController
         * @description
         * This method is used for format the date.
         */
        /**
         * @ngdoc method
         * @name JobsAddController Filter
         * @description
         * This method is used for diplay the custom filter form.
         */
        $scope.getTransactionsDetails = function(){
            var params = {};
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
            params.filter = '{"order":"id desc","limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"include":{"0":"user.user_profile","1":"other_user.user_profile","2":"foreign_transaction"},"where":{"OR":[{"AND":{"transaction_type":' +ConstTransactionTypes.BookingAcceptedAndAmountMovedToEscrow+ ',"user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.CompletedAndAmountMovedToWallet+ ',"to_user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.BookingCanceledAndRefunded+ ',"to_user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.BookingCanceledAndCreditedCancellationAmount+ ',"to_user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.PROPayment+ ',"user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.TopListed+ ',"user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.BonusAmount+ ',"user_id":' +$rootScope.auth.id+ '}},{"AND":{"transaction_type":' +ConstTransactionTypes.BonusAmount+ ',"to_user_id":' +$rootScope.auth.id+ '}}]}}';
            params.userId = $rootScope.auth.id ;
            TransactionsGetFactory.get(params, function(response) {
                if(response._metadata){
                    $scope.currentPage = response._metadata.current_page;
                    $scope.lastPage = response._metadata.last_page;
                    $scope.itemsPerPage = response._metadata.per_page;
                    $scope.totalRecords = response._metadata.total;
                }
                $scope.transactions = response.data;
            });
        };
        /**
         * @ngdoc method
         * @name JobsAddController Filter
         * @description
         * This method is used for submit the custom filter form.
         */
        $scope.paginate_transaction = function(currentPage) {
            $scope.currentPage = currentPage;
            $scope.getTransactionsDetails();
        };
        $scope.index();
    });
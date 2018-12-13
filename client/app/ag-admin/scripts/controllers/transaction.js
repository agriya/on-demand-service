'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:TransactionController
 * @description
 * # TransactionController
TransactionController * Controller of the getlancerv3
 */
angular.module('base')
    .controller('TransactionController', function($scope,$state,$rootScope,$filter, $cookies,TransactionsGetFactory,ConstTransactionTypes) {
        $scope.currentPage = 1;
        $scope.index = function() {
            $scope.getTransactionsDetails();
            $scope.ConstTransactionTypes = {};
            $scope.ConstTransactionTypes = ConstTransactionTypes; 
        };
        /**
         * @ngdoc method
         * @name JobsAddController.clear
         * @methodOf module.JobsAddController
         * @description
         * This method is used for clear the date
         */
        $scope.getTransactionsDetails = function(){
            var params = {};
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = 10;
            params.filter = '{"order":"id desc","limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"include":{"0":"user.user_profile","1":"other_user.user_profile","2":"foreign_transaction"},"where":{"transaction_type":{"inq":{"1":' +ConstTransactionTypes.BookingAcceptedAndAmountMovedToEscrow+',"2":' +ConstTransactionTypes.CompletedAndAmountMovedToWallet+',"3":' +ConstTransactionTypes.BookingCanceledAndRefunded+',"4":' +ConstTransactionTypes.BookingCanceledAndCreditedCancellationAmount+',"5":' +ConstTransactionTypes.PROPayment+',"6":' +ConstTransactionTypes.TopListed+',"7":'+ConstTransactionTypes.Bonus+'}}}}';
            TransactionsGetFactory.get(params,function(response) {
                if(response._metadata){
                    $scope.currentPage = response._metadata.current_page;
                    $scope.lastPage = response._metadata.last_page;
                    //$scope.itemsPerPage = response._metadata.per_page;
                    $scope.totalRecords = response._metadata.total;
                }
                $scope.transactions = response.data;
            });
        };
       
        $scope.paginate_transaction = function(currentPage) {
            $scope.currentPage = currentPage;
            $scope.getTransactionsDetails();
        };
        $scope.index();
    });
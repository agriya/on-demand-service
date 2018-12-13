'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:MoneyTransferAccountController
 * @description
 * # MoneyTransferAccountController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    .controller('CreditCardController', ['$rootScope', '$scope', 'flash', '$filter', '$state', '$cookies','SweetAlert','CreditCardFactory','CreditCardGetFactory','CreditCardRemoveFactory', function($rootScope, $scope,  flash, $filter, $state, $cookies,SweetAlert,CreditCardFactory,CreditCardGetFactory,CreditCardRemoveFactory) {
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Saved Credit Cards");
         $rootScope.subHeader = "Account"; //for setting active class for subheader
          $cookies.put('subHeader', $rootScope.subHeader);
          $scope.user_profile = {"cardType":""};
          $scope.show = false;
          $scope.disable_btn = false;
         $rootScope.gotoHeader();
         if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
       $scope.index = function() {
            CreditCardGetFactory.get(function(response) {
                if(angular.isDefined(response.data)) {
                    $scope.creditCardLists = response.data;
                }
            });
        };
        $scope.showing = function(){
            $scope.show = true;
        };
        $scope.userCreditCard = function($valid,value) {
            if($scope.user_profile.credit_card_number === undefined && $valid === false){
                $scope.showing_error = true;
                $scope.showing_value ='Please enter valid credit card number';
            }
            if ($valid) {
                $scope.disable_btn = true;
                var flashMessage;
                $scope.user_profile.credit_card_type = $scope.user_profile.cardType;
                $scope.user_profile.expire_year = $scope.user_profile.credit_card_expired.year;
                $scope.user_profile.expire_month = $scope.user_profile.credit_card_expired.month;
                CreditCardFactory.post($scope.user_profile, function(response) {
                    $scope.disable_btn = false;
                    $scope.response = response;
                    $state.reload();
                    flash.set($filter("translate")(response.error.message), 'success', true);
                }, function(response) {
                    $scope.errorMessage = $filter("translate")("We are unable to process your request. Please try again.");
                    if(response.data.error.fields){
                        angular.forEach(response.data.error.fields, function(value){
                            $scope.errorMessage = $scope.errorMessage + " "+value.issue;  
                        });
                    }
                    flashMessage = $filter("translate")($scope.errorMessage);
                    flash.set(flashMessage, 'error', false);
                });
            }
        };
        $scope.CreditCaredDelete = function (id) {
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
                            CreditCardRemoveFactory.delete({userCreditCardId:id}, function(response) {
                                $scope.response = response;
                                if ($scope.response.error.code === 0) {
                                    $state.reload();
                                    flash.set($filter("translate")("Credit card deleted successfully."), 'success', false);
                                } else {
                                    flash.set($filter("translate")("Credit card not deleted."), 'error', false);
                                }
                            },function(errorResponse){
                                flash.set($filter("translate")(errorResponse.error.message), 'success', true);
                            });
                        }
                    });
        };
        $scope.index();
    }]);
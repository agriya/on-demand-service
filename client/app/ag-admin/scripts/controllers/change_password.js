'use strict';
/**
 * @ngdoc function
 * @name olxApp.controller:UsersChangePasswordController
 * @description
 * # UsersChangePasswordController
 * Controller of the olxApp
 */
angular.module('base')
    .controller('ChangePasswordController', function($state, $scope, $http, ChangePasswordFactory, $location, notification) {
        var id = $state.params.id;
        $scope.ChangePassword = function() {
            $scope.changePassword.user_id = id;
            delete $scope.changePassword.confirm_password;
            ChangePasswordFactory.update($scope.changePassword, function(response) {
                if (response.error.code === 0) {
                    notification.log('Your password has been changed successfully.', {
                        addnCls: 'humane-flatty-success'
                    });
                    $state.reload('change_password');
                    $scope.user = {};
                } 
            },function(errorMessage){
                    notification.log('Your old password is incorrect, please try again', {
                        addnCls: 'humane-flatty-error'
                    });
                });
        }
    });
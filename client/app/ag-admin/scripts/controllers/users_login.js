'use strict';
/**
 * @ngdoc function
 * @name baseApp.controller:UsersLoginCtrl
 * @description
 * # UsersLoginCtrl
 * Controller of the baseApp
 */
angular.module('base')
    .controller('UsersLoginCtrl', function($rootScope, $scope, $location, $http, $window, $timeout, progression, notification, $cookies) {
        if ($window.sessionStorage.getItem("auth") !== null) {
            $location.path('/dashboard');
        }
        $scope.save_btn = false;
        $scope.loginUser = function() {
            if ($scope.userLogin.$valid && !$scope.save_btn) {
                $scope.save_btn = true;
                //if ($rootScope.settings.USER_USING_TO_LOGIN === 'email') {
                    $scope.user.email = $scope.user.username;
                    delete $scope.user.username;
             //   }
                $http({
                        method: 'POST',
                        url: '/api/v1/users/login',
                        data: $scope.user
                    })
                    .success(function(response) {
                        $scope.response = response;
                        console.log($scope.response,'Loginresponse');
                        if ($scope.response.error.code === 0) {
                        //     $scope.Authuser = {
                        //     id: $scope.response.id,
                        //     username: $scope.response.username,
                        //     role_id: $scope.response.role_id,
                        //     refresh_token: $scope.response.refresh_token,
                        // };
                        // $cookies.put('auth', JSON.stringify($scope.Authuser), {
                        //     path: '/'
                        // });
                        // $cookies.put('token', $scope.response.access_token, {
                        //     path: '/'
                        // });
                        // $rootScope.user = $scope.response;
                        // $rootScope.$emit('updateParent', {
                        //     isAuth: true
                        // });
                            $cookies.put('auth', JSON.stringify($scope.response), {
                                path: '/'
                            });
                            $cookies.put('token', $scope.response.access_token, {
                                path: '/'
                            });
                            $location.path('/dashboard');
                            $timeout(function() {
                                $window.location.reload();
                            });
                        } else {
                            progression.done();
                            notification.log('Your login credentials are invalid.', {
                                addnCls: 'humane-flatty-error'
                            });
                            $scope.user = {};
                            $scope.save_btn = false;
                        }
                    });
            }
        };
    });
	'use strict';
	/**
	 * @ngdoc function
	 * @name getlancerApp.controller:UsersLogutController
	 * @description
	 * # UsersLogutController
	 * Controller of the getlancerApp
	 */
	angular.module('hirecoworkerApp')
	    .controller('UsersLogoutController', ['$rootScope', '$scope', 'usersLogout', '$location', '$window', '$filter', '$cookies', 'flash', function($rootScope, $scope, usersLogout, $location, $window, $filter, $cookies, flash) {
			if($cookies.get("token") !== null || $cookies.get("token") !== undefined){
				usersLogout.logout('', function(response) {
					$scope.response = response;
					flash.set($filter("translate")("You are now logged out of the site."), 'success', false);
					delete $rootScope.user;
					$cookies.remove("auth", {
						path: "/"
					});
					$cookies.remove("token", {
						path: "/"
					});
					$cookies.remove("provider_name", {
						path: "/"
					});
					$cookies.remove("subHeader", {
						path: "/"
					});
					$scope.$emit('updateParent', {
						isAuth: false
					});
					
					$location.path('/');
				});	
			} else {
				$location.path('/');
			}
	        
    }]);
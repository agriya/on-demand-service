'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserProfileController
 * @description
 * # UserProfileController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name user.controller:UserProfileController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/

    .controller('CloseAccountController', function ($state,$cookies,$scope,$location,CloseAccountFactory,flash, UserProfilesFactory, $filter, $rootScope,CloseAccount) {

        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:UserProfileController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        $scope.setMetaData = function () {
            var fullUrl = $location.absUrl();
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Close account");
            $scope.CloseAccount = CloseAccount;
            $scope.showing = false;
            $scope.button = false;
            var params = {};
            params.filter= {'order':'display_order asc'};
            CloseAccountFactory.get(params).$promise.then(function (response) {
                $scope.accounts = response.data;
            }); 
        };
        $scope.init();
        //Update user details
        /**
         * @ngdoc method
         * @name userProfile
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will upload the file and returns the success message.
         *
         **/
        if($rootScope.accountReload === 0){
            $rootScope.accountReload =1;
            $state.reload();
        }        
        $scope.updateDetails = function (id) {
            if(id === null){
                $scope.button = false;
                $scope.showing = false;
            }
            else if(id !== null && parseInt(id) !== $scope.CloseAccount.Others){
                $scope.showing = false;
                $scope.button = true;
            }
            else if(id !== null && parseInt(id) === $scope.CloseAccount.Others){
                $scope.showing = true;
                $scope.button = true;
            }
        };
        $scope.closeAccount = function () {
                        $scope.user_profile.is_deleted = $scope.CloseAccount.Delete;
                      UserProfilesFactory.update($scope.user_profile).$promise.then(function (response) {
                        if (response.error.code === 0) {
						flash.set($filter("translate")("Your account has been closed"), 'success', true);
						delete $rootScope.user;
						$cookies.remove("auth", {
							path: "/"
						});
						$cookies.remove("token", {
							path: "/"
						});
						$scope.$emit('updateParent', {
							isAuth: false
						});
						$location.path('/');
					}else{
                        flash.set($filter("translate")(response.error.message), 'error', false);    
                    }
                    });
                  
        };
        
    });

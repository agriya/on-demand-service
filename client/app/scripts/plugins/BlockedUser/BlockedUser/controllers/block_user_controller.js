'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:HomeController
 * @description
 * # HomeController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name contacts.controller:ContactUsController
     * @description
     *
     * This is contactUs controller having the methods init(), setMetaData(), and contactFormSubmit().
     *
     * It controls the functionality of contact us.
     **/
    .controller('BlockUserController', function ($scope, $rootScope, contact, $filter, flash, $state, $location, $cookies, GetBlockUser ,md5, $window, DeleteBlockUser) {
        /**
         * @ngdoc method
         * @name init
         * @methodOf Contacts.controller:ContactUsController
         * @description
         * This method will initialize the page. It returns the page title.
         **/
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        $scope.currentPage = 1;
        $scope.lastPage = 1;
        $rootScope.subHeader = "Blocked User";
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        var params = {};
        /**
         * @ngdoc method
         * @name contactFormSubmit
         * @methodOf contacts.controller:ContactUsController
         * @description
         * This method handles the form which is used for contact.
         *
         **/
        $scope.getBlockedUsers = function(){
        params = {};
        $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
        $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
         params.filter = '{"limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"where":{"user_id":'+$rootScope.auth.id+'},"include":{"1":"blocked_user.attachment","2":"blocked_user.user_profile"}}';
         GetBlockUser.get(params,function (response) {
            $scope.blockedUsers = response.data;
            if (angular.isDefined(response._metadata)) {
                $scope.totalRecords = response._metadata.total;
                $scope.itemsPerPage = 1;
                $scope.lastPage = response._metadata.last_page;
                $scope.currentPage = response._metadata.current_page;
            }
            angular.forEach($scope.blockedUsers, function(value){
                if (angular.isDefined(value.blocked_user.attachment) && value.blocked_user.attachment !== null) {
                var hash = md5.createHash(value.blocked_user.attachment.class + value.blocked_user.attachment.id + 'png' + 'big_thumb');
                value.user_image = 'images/big_thumb/' + value.blocked_user.attachment.class + '/' + value.blocked_user.attachment.id + '.' + hash + '.png';
                } else {
                    $rootScope.auth.userimage = $window.theme + 'images/default.png';
                 }  
             });    
        });

        };

        $scope.init = function () {
                $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Blocked Users");
                $scope.getBlockedUsers();
        };
        $scope.unblockUser = function(id, index){
            params = {};
            params.blockedUserId = id;
            DeleteBlockUser.delete(params, function(response){
                if(response.error.code === 0){
                    flash.set($filter("translate")("User unblocked successfully. "), 'success', false);
                    $scope.blockedUsers.splice(index, 1);
                }
            });
        };
        $scope.init();
        $scope.paginate = function (currentPage) {
            $scope.currentPage = currentPage;
            $scope.getBlockedUsers();
        };
    });
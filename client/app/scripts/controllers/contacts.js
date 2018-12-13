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
    .controller('ContactsController', function ($scope, $rootScope, contact, $filter, flash, $state, $location, $cookies,contactMe,md5,$window) {
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
        $rootScope.subHeader = "Contacts";
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        /**
         * @ngdoc method
         * @name contactFormSubmit
         * @methodOf contacts.controller:ContactUsController
         * @description
         * This method handles the form which is used for contact.
         *
         **/
        $scope.contactList = function(){
        var params = {};
        $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
        $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
         params.filter = '{"limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"include":{"1":"attachment","2":"user_profile"}}';
         contactMe.get(params,function (response) {
            $scope.contacts = response.data;
            if (angular.isDefined(response._metadata)) {
                $scope.totalRecords = response._metadata.total;
                $scope.itemsPerPage = 1;
                $scope.lastPage = response._metadata.last_page;
                $scope.currentPage = response._metadata.current_page;
            }
            angular.forEach($scope.contacts, function(value){
                if (angular.isDefined(value.attachment) && value.attachment !== null) {
                var hash = md5.createHash(value.attachment.class + value.attachment.id + 'png' + 'big_thumb');
                value.user_image = 'images/big_thumb/' + value.attachment.class + '/' + value.attachment.id + '.' + hash + '.png';
                } else {
                    $rootScope.auth.userimage = $window.theme + 'images/default.png';
                 }  
             });    
        });

        };

        $scope.init = function () {
                $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Contacts");
                $scope.contactList();
        };
        $scope.init();
        $scope.paginate = function (currentPage) {
            $scope.currentPage = currentPage;
            $scope.contactList();
        };
    });
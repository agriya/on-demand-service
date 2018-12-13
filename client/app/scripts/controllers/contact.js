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
    .controller('ContactUsController', function ($scope, $rootScope, contact, $filter, flash, $state, $location, UsersFactory) {
        /**
         * @ngdoc method
         * @name init
         * @methodOf Contacts.controller:ContactUsController
         * @description
         * This method will initialize the page. It returns the page title.
         **/
        $rootScope.isHome = true; // for disabling sub header
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        $scope.contactForm = {};
        var params = {};
        $scope.init = function () {
             $scope.is_photo_required = false;
             if($location.path() === '/contactus') {
                $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Contact Us");
                if($rootScope.isAuth){
                    params.filter = '{"include":{"0":"user_profile"}}';
                    UsersFactory.get(params, { username: $rootScope.auth.id }).$promise.then(function (response) {
                        $scope.contactForm.telephone = parseInt(response.data.phone_number);
                        $scope.contactForm.email = response.data.email;
                        $scope.contactForm.first_name= response.data.user_profile.first_name;
                        $scope.contactForm.last_name= response.data.user_profile.last_name;
                    });
                }
                //$scope.is_photo_required = $state.params.address_verified !== undefined ? true : false;
            }
        };
        $scope.init();
        
        /**
         * @ngdoc method
         * @name contactFormSubmit
         * @methodOf contacts.controller:ContactUsController
         * @description
         * This method handles the form which is used for contact.
         *
         **/
         /*multiple image file upload */
        $scope.$on('MulitpleUploader', function(event, data) {
            $scope.imagedata = data;
            $scope.image = [];
        });

        $scope.contactFormSubmit = function ($valid) {
                $scope.emailErr = '';
                if ($valid) {
                      /* image */
                      $scope.contactForm.image = [];
                    angular.forEach($scope.imagedata, function(img) {
                    angular.forEach(img, function(listPhoto) {
                        if (listPhoto.attachment !== undefined) {
                            $scope.contactForm.image.push({"attachment": listPhoto.attachment });
                        }
                    });
                });
                 if($state.params.address_verified !== undefined){
                    if(!$scope.imagedata){
                       $scope.is_photo_required = true;
                       return; 
                    }else{
                        $scope.is_photo_required = false;
                    }
                    $scope.contactForm.is_contact_for_address_verification = 1;
                }
                contact.save($scope.contactForm).$promise.then(function (response) {
                    $scope.response = response;    
                    if ($scope.response.error.code === 0) {
                        flash.set($filter("translate")("Thank you, we received your message and will get back to you as soon as possible."), 'success', false);
                        if($state.params.address_verified !== undefined){
                            $state.go('user_dashboard');
                        }else{
                            $state.go('home');
                        }
                    } else {
                        flash.set($filter("translate")("Contact could not be submitted. Please try again."), 'error', false);
                    }      
                });
           }
       }; 
    });
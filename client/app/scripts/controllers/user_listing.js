'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserListingController
 * @description
 * # UserListingController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserListingController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/
.controller('UserListingController', function ($state, $scope, flash, UserProfilesFactory,UsersFactory,ConstListingStatus, $filter, $rootScope, $location, Upload, GENERAL_CONFIG, ConstSocialLogin, ConstThumb, City, States, Country, $cookies,ConstStatus,ConstProStatus) {
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $rootScope.subHeader = "Listing"; //for subHeader
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Listing");
            $scope.ConstSocialLogin = ConstSocialLogin;
            $scope.ConstListingStatus = ConstListingStatus;
            $scope.thumb = ConstThumb.user;
            $scope.ConstProStatus = ConstProStatus;
            $scope.ConstStatus = ConstStatus;
            $scope.auto_disable = true;
            //Select Gender List
            $scope.genderArray = [];
            $scope.genderArray.push(
                    {'id': 1, "name": $filter("translate")('Male')},
                    {'id': 2, "name": $filter("translate")('Female')}
            );
            //Select List for notification types
            $scope.notificationArray = [];
            $scope.notificationArray.push(
                    {'id': 1, 'name': $filter("translate")('Both')},
                    {'id': 2, 'name': $filter("translate")('Email')},
                    {'id': 3, 'name': $filter("translate")('SMS')}
            );
            City.cityList({
            }).$promise.then(function (response) {
                $rootScope.cityArray = response.data;
            });
            States.stateList({
            }).$promise.then(function (response) {
                $rootScope.stateArray = response.data;
            });
            Country.countryList({
            }).$promise.then(function (response) {
                $rootScope.countryArray = response.data;
            });
            $scope.auto_approval = parseInt($rootScope.settings.ENABLE_AUTO_APPROVAL_FOR_LISTING) === 1 ? true : false;
          $scope.enabledPlugins=$rootScope.settings.SITE_ENABLED_PLUGINS;
           $scope.enabledPlugins = $scope.enabledPlugins.split(',');
            //checking for enquiry and pre-approval plugin
           angular.forEach($scope.enabledPlugins, function(value){
            if(value === 'SMS'){
                $scope.enabled = true;
            }
           });
            //Get user details
          var params = {};
          params.filter = '{"include":{"0":"attachment","1":"user_profile","2":"category"}}';
          UsersFactory.get(params,{username : $rootScope.auth.id}).$promise.then(function (response) {
              $scope.user = response.data;
              if(response.data.attachment === null){
                    $scope.profile_photo = true;
                } else{
                    $scope.profile_photo = false;
                }
          });
           params.filter = '{"include":{"0":"user.service_users.service"}}';
            UserProfilesFactory.get(params).$promise.then(function (response) {
                $scope.userProfileData = response;
                if(response.photo_count >= 4){
                    $scope.photo = false;
                }else{
                    $scope.photo = true;
                }
                if(response.is_listing_updated === 1){
                    $scope.listing = false;
                } else{
                    $scope.listing = true;
                } 
                if(parseInt(response.listing_address_verified_status_id) === 3){
                    $scope.listing_address = false; 
                }  else if(parseInt(response.listing_address_verified_status_id) === 2){
                    $scope.listing_address = "pending";
                }else if(parseInt(response.listing_address_verified_status_id) === 4){
                    $scope.listing_address = "rejected";
                }else if(parseInt(response.listing_address_verified_status_id) === 1){
                    $scope.listing_address = true;
                }
                if(response.listing_status_id === 2){
                    $scope.listing_status_pending_approval = true;
                }
                else if(response.listing_status_id === 3){
                    $scope.listing_status_approved = true; 
                    $scope.listing_status_pending = false;
                    $scope.listing_status_invisible = false;
                    $scope.update_button = false;
                }else if(response.listing_status_id === 4){
                    $scope.listing_status_invisible = true;
                    $scope.listing_status_approved = false; 
                    $scope.listing_status_pending = false; 
                    $scope.update_button = false;
                }else{
                    $scope.listing_status_pending = true;
                    $scope.listing_status_invisible = false;
                    $scope.listing_status_approved = false;
                }
                  if(response.is_listing_updated === 1 && response.photo_count >= 4 && response.services_user_count > 0 && (response.is_online_assessment_test_completed === 1 ||  $rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('OnlineAssessment/OnlineAssessment') === -1)){
                    $scope.update_button = true;
                    $scope.listing_status_ready = true;
                    $scope.listing_status_pending = false;
                    }
          });
        };
        $scope.init();
        $scope.dateBlockeBefore = $filter('date')(new Date(), "yyyy-MM-ddTHH:mm:ss.sssZ");
        $scope.changeStatus = function(id){
              $scope.submit = {};
              $scope.submit.listing_status_id = id;
              $scope.showLoader=true;
              UserProfilesFactory.update($scope.submit).$promise.then(function () {
                  $scope.reload();
               $scope.showLoader=false;
            });
          };
          var params = {};
          $scope.reload = function(){
              params.filter = '{"include":{"0":"attachment","1":"user_profile","2":"category"}}';
          UsersFactory.get(params,{username : $rootScope.auth.id}).$promise.then(function (response) {
              $scope.user = response.data;
              if(response.data.attachment === null){
                    $scope.profile_photo = true;
                } else{
                    $scope.profile_photo = false;
                }
          });
        params.filter = '{"include":{"0":"user.service_users.service"}}';
            UserProfilesFactory.get(params).$promise.then(function (response) {
                $scope.userProfileData = response;
                if(response.photo_count >= 4){
                    $scope.photo = false;
                }else{
                    $scope.photo = true;
                }
                if(response.is_listing_updated === 1){
                    $scope.listing = false;
                } else{
                    $scope.listing = true;
                } 
                if(parseInt(response.listing_address_verified_status_id) === 3){
                    $scope.listing_address = false; 
                }  else if(parseInt(response.listing_address_verified_status_id) === 2){
                    $scope.listing_address = "pending";
                }else if(parseInt(response.listing_address_verified_status_id) === 4){
                    $scope.listing_address = "rejected";
                }else if(parseInt(response.listing_address_verified_status_id) === 1){
                    $scope.listing_address = true;
                }
                if(response.listing_status_id === 2){
                    $scope.listing_status_pending_approval = true;
                }
                else if(response.listing_status_id === 3){
                    $scope.listing_status_approved = true; 
                    $scope.listing_status_pending = false;
                    $scope.listing_status_invisible = false;
                    $scope.update_button = false;
                }else if(response.listing_status_id === 4){
                    $scope.listing_status_invisible = true;
                    $scope.listing_status_approved = false; 
                    $scope.listing_status_pending = false; 
                    $scope.update_button = false;
                }else{
                    $scope.listing_status_pending = true;
                    $scope.listing_status_invisible = false;
                    $scope.listing_status_approved = false;
                }
                  if(response.is_listing_updated === 1 && response.photo_count >= 4 && response.services_user_count > 0 && (response.is_online_assessment_test_completed === 1 ||  $rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('OnlineAssessment/OnlineAssessment') === -1)){
                    $scope.update_button = true;
                    $scope.listing_status_ready = true;
                    $scope.listing_status_pending = false;
                    } 
          });
          };
        
        // upload on file select or drop
        /**
         * @ngdoc method
         * @name upload
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will save the user profile data
         *
         * @param {!Array.<string>} profileData contains the array of user profile data
         **/
       
        //Update user details
        /**
         * @ngdoc method
         * @name userProfile
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will upload the file and returns the success message.
         *
         **/
        $scope.userProfile = function ($valid) {
            if ($valid) {
                if ($scope.file) {
                    $scope.upload($scope.user_profile, $scope.file);
                } else {
                    UserProfilesFactory.update($scope.user_profile).$promise.then(function (response) {
                         $rootScope.auth.user_profile.address = response.address;
                        flash.set($filter("translate")(response.Success), 'success', true);
                            $state.reload('UserProfile');
                    });
                }
            }
        };
       
        
    })

    /* For select when search */
    .directive('convertToNumber', function() {
        return {
          require: 'ngModel',
          link: function(scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function(val) {
              return val ? parseInt(val, 10) : null;
            });
            ngModel.$formatters.push(function(val) {
              return val ? '' + val : '';
            });
          }
        };
    });
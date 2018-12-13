'use strict';
/**
 * @ngdoc function
 * @name getlancerApp.controller:DashboardController
 * @description
 * # DashboardController
 * Controller of the getlancerApp
 */
angular.module('hirecoworkerApp')
    .controller('UserDashboardController', ['$scope','UserProfilesFactory','UsersFactory','$state','ConstListingStatus','$rootScope','$filter', 'md5', 'ConstAppointmentStatus', 'AppointmentFactory', '$window', 'ConstService', '$cookies','ConstStatus','ConstUserType','$location','providers', 'Services','ConstProStatus', 'flash', function($scope,UserProfilesFactory,UsersFactory,$state,ConstListingStatus,$rootScope,$filter, md5, ConstAppointmentStatus, AppointmentFactory, $window, ConstService, $cookies,ConstStatus,ConstUserType,$location,providers, Service,ConstProStatus, flash) {
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
         $scope.init = function () {
             var temp = {};
            temp.filter='{"where":{"is_active":1}}';
            Service.get(temp, function(response){
                $scope.services = response.data;
            });
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Dashboard");
            $scope.ConstListingStatus = ConstListingStatus;
            $scope.ConstUserType = ConstUserType;
            $scope.ConstStatus = ConstStatus;
            $scope.ConstProStatus = ConstProStatus;
            $scope.referal_description = 'Have you tried ' + $rootScope.settings.SITE_NAME + ' to find a service provider' + '?' + ' Claim '+ $rootScope.settings.CURRENCY_SYMBOL + $rootScope.settings.AFFILIATE_REFERRAL_AMOUNT_NEW_USER + ' free credit with my invitation.';
            providers.get(function(providers) {
                angular.forEach(providers.data, function(res) {
                    if (res.slug === 'facebook') {
                        $scope.facebook_provider = res.api_key;
                    }
                    if (res.slug === 'twitter') {
                        $scope.twitter_provider = res.api_key;
                    }
                });
            });
        };
        $rootScope.subHeader = "Dashboard";
        $scope.translateData= {
            amount1 : $filter('currency')($rootScope.settings.AFFILIATE_REFERRAL_AMOUNT_NEW_USER, $rootScope.currency_type),
            amount2 : $filter('currency')($rootScope.settings.AFFILIATE_REFERRAL_AMOUNT_FOR_AFFILIATE, $rootScope.currency_type)
        };
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
         $scope.init();
          $rootScope.ConstService = ConstService;
          $scope.listingRedirect = function(){
              $state.go('user_profile', {
                            type: 'description_and_details'
                        });
          };
          $scope.changeStatus = function(id){
              $scope.submit = {};
              $scope.submit.listing_status_id = id;
              $scope.showLoader=true;
              UserProfilesFactory.update($scope.submit).$promise.then(function (response) {
                  if(response.error.code === 0){
                      if(parseInt(id) === ConstListingStatus.Approved){
                          flash.set($filter("translate")("Your profile successfully listed."), 'success', false);
                      }else if(parseInt(id) === ConstListingStatus.WaitingForAdminApproval){
                          flash.set($filter("translate")("Your profile successfully sent for approval."),'success', false);
                      }
                  }
                  $scope.reload();
               $scope.showLoader=false;
            });
          };
          $scope.auto_approval = parseInt($rootScope.settings.ENABLE_AUTO_APPROVAL_FOR_LISTING) === 1 ? true : false;
          $scope.enabledPlugins=$rootScope.settings.SITE_ENABLED_PLUGINS;
           $scope.enabledPlugins = $scope.enabledPlugins.split(',');
            //checking for enquiry and pre-approval plugin
           angular.forEach($scope.enabledPlugins, function(value){
            if(value === 'SMS'){
                $scope.enabled = true;
            }
           });
          var params = {};
          params.filter = '{"include":{"0":"attachment","1":"user_profile"}}';
          UsersFactory.get(params,{username : $rootScope.auth.id}).$promise.then(function (response) {
              $scope.user = response.data;
              $scope.referal_link = $location.protocol() + "://"+ $location.host() + "/referrals/" + $scope.user.reference_code;
              if (angular.isDefined($scope.user.attachment) && $scope.user.attachment !== null) {
                        var c = new Date();
                        var hash = md5.createHash($scope.user.attachment.class + $scope.user.attachment.id + 'png' + 'big_thumb');
                        $scope.user.profile_image = 'images/big_thumb/' + $scope.user.attachment.class + '/' + $scope.user.attachment.id + '.' + hash + '.png?' + c.getTime();
                    } else {
                       $scope.user.profile_image = 'images/default.png';
                    }
              if(response.data.attachment === null){
                    $scope.profile_photo = true;
                } else{
                    $scope.profile_photo = false;
                }
                 if(parseInt($scope.user.user_profile.response_time) < 2){
                    $scope.user.user_profile.response_time_value = "within a minute";
                }
                else if(parseInt($scope.user.user_profile.response_time) < 3){
                    $scope.user.user_profile.response_time_value = "within 2 minutes";
                }
                else if(parseInt($scope.user.user_profile.response_time) < 5){
                    $scope.user.user_profile.response_time_value = "within 5 minutes";
                }
                else if(parseInt($scope.user.user_profile.response_time) < 31){
                    $scope.user.user_profile.response_time_value = "within 30 minutes";
                }
                else if(parseInt($scope.user.user_profile.response_time) < 61){
                    $scope.user.user_profile.response_time_value = "within a hour";
                }
                else if(parseInt($scope.user.user_profile.response_time) < 121){
                    $scope.user.user_profile.response_time_value = "within two hours";
                }
                 else if(parseInt($scope.user.user_profile.response_time) < 721){
                    $scope.user.user_profile.response_time_value = "within half a day";
                }
                else if(parseInt($scope.user.user_profile.response_time) < 1441){
                    $scope.user.user_profile.response_time_value = "within a day";
                } 
                else if(parseInt($scope.user.user_profile.response_time) < 10080){
                    $scope.user.user_profile.response_time_value = "within a week";
                }else if(parseInt($scope.user.user_profile.response_time) > 10080){
                    $scope.user.user_profile.response_time_value = "after a week";
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
          $scope.reload = function(){
              params.filter = '{"include":{"0":"attachment","1":"user_profile"}}';
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
          params = {};
          params.filter = '{"where":{"appointment_status_id":'+ConstAppointmentStatus.Confirmed+'},"include":{"0":"user","1":"user.attachment","2":"provider_user","3":"provider_user.attachment","4":"provider_user.user_profile","5":"user.user_profile"}}';
          AppointmentFactory.get(params).$promise.then(function (response) {
              $scope.appointments = response.data;
              var hash = '';
                 angular.forEach($scope.appointments, function(value,key){
                    if (angular.isDefined($scope.appointments[key].user.attachment) && $scope.appointments[key].user.attachment !== null) {
                    hash = md5.createHash($scope.appointments[key].user.attachment.class + $scope.appointments[key].user.attachment.id + 'png' + 'small_thumb');
                    $scope.appointments[key].user_image = 'images/small_thumb/' + $scope.appointments[key].user.attachment.class + '/' + $scope.appointments[key].user.attachment.id + '.' + hash + '.png';
                    }else {
                         $scope.appointments[key].user_image = $window.theme + 'images/default.png';
                    }
                    if(angular.isDefined($scope.appointments[key].provider_user.attachment) && $scope.appointments[key].provider_user.attachment !== null) {
                    hash = md5.createHash($scope.appointments[key].provider_user.attachment.class + $scope.appointments[key].provider_user.attachment.id + 'png' + 'small_thumb');
                    $scope.appointments[key].provider_image = 'images/small_thumb/' + $scope.appointments[key].provider_user.attachment.class + '/' + $scope.appointments[key].provider_user.attachment.id + '.' + hash + '.png';
                    } else {
                     $scope.appointments[key].provider_image = $window.theme + 'images/default.png';
                    }
                });
          });
          $scope.val = [];
          $scope.val.push({'Title':'test'});
    }])
    .directive('selectOnClick', ['$window', function ($window) {
    // Linker function
    return function (scope, element) {
      element.bind('click', function () {
        if (!$window.getSelection().toString()) {
          this.setSelectionRange(0, this.value.length);
        }
      });
    };
  }]);
'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:PhotosController
 * @description
 * # PhotosController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name user.controller:PhotosController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/

 .controller('ReferalController', function ($state, $scope, flash,ReferFriends, $location,$filter,UserProfilesFactory, $rootScope,SmsRecend,SmsVerify,$timeout,$interval,providers) {        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:PhotosController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:PhotosController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Refer Friends");
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
          $scope.data = {};
          $scope.element = {};
        
        var counter = 0;
        $scope.elements = [{
            id: counter
        }];
        $scope.myText = "Hi <First name>,";
        $scope.referal_link = $location.protocol() + "://"+ $location.host() + "/referrals/" + $rootScope.auth.reference_code;
        $scope.referal_description = 'Have you tried ' + $rootScope.settings.SITE_NAME + ' to find a service provider' + '?' + ' Claim '+ $rootScope.settings.CURRENCY_SYMBOL + $rootScope.settings.AFFILIATE_REFERRAL_AMOUNT_NEW_USER + ' free credit with my invitation.';
        $scope.newItem = function () {
            counter++;
            $scope.elements.push({
                id: counter
            });

        };
        $scope.site_name = $rootScope.settings.SITE_NAME;
        $scope.data.message = 'How are you' +'? '+ 'I use ' + $rootScope.settings.SITE_NAME + ' to find local service provider'+', '+ 'its great'+'! '+ 'If you sign up now you get ' + $rootScope.settings.CURRENCY_SYMBOL + $rootScope.settings.AFFILIATE_REFERRAL_AMOUNT_NEW_USER + ' credit to spend on ' + $rootScope.settings.SITE_NAME +' next time you need someone to care for your work.';

        $scope.init();
            $scope.referalSend = function(value,$valid){
                if($valid){
                $scope.referal = {};
                $scope.referal.contacts = [];
               angular.forEach($scope.elements,function(value){
                   $scope.referal.contacts.push({
                       'name' : value.name,
                       'email' : value.email
                   })
                 })
                $scope.referal.message = value.message;
                ReferFriends.save($scope.referal,function (response) {
                    flash.set($filter("translate")(response.error.message), 'success', true);
            },function(errorResponse){
                    flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
            });
            }
            };
    })
    .controller('AffiliateReferralController', ['$rootScope', '$scope', '$stateParams', '$state','$cookies', function($rootScope, $scope, $stateParams, $state,$cookies) {    
         var affiliate_expiry = Number ($rootScope.settings.AFFILIATE_EXPIRE_TIME || 0);
                var expireDate = new Date();
                 expireDate.setHours(expireDate.getHours() + affiliate_expiry);          
                 $cookies.put('referral_username',$state.params.username,{
                     expires: expireDate
                    });
                  $state.go('home', {reload: true});     
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
    


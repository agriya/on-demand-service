'use strict';
/**
 * @ngdoc function
 * @name BookorRent.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the BookorRent
 */
angular.module('hirecoworkerApp')
    .controller('MainController', ['$rootScope', '$scope', '$window', '$cookies', '$state', '$location', 'flash', '$filter','ConstUserType', 'UsersFactory', 'md5', '$timeout', function($rootScope, $scope, $window, $cookies, $state, $location, flash, $filter, ConstUserType, UsersFactory, md5, $timeout) {
        $rootScope.isAuth = false;
        $rootScope.ConstUserType = ConstUserType;
        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
            $rootScope.isAuth = true;
            $rootScope.auth = JSON.parse($cookies.get("auth"));
        }
        var params = {};
        if($rootScope.auth){
            params.username = $rootScope.auth.id;
            params.filter='{"include":{"0":"attachment","1":"user_profile"}}';
            UsersFactory.get(params,function(response){
                $rootScope.auth = response.data;
                if (angular.isDefined($rootScope.auth)){
                if (angular.isDefined($rootScope.auth.attachment) && $rootScope.auth.attachment !== null) {
                    var hash = md5.createHash($rootScope.auth.attachment.class + $rootScope.auth.attachment.id + 'png' + 'small_thumb');
                    $rootScope.auth.userimage = 'images/small_thumb/' + $rootScope.auth.attachment.class + '/' + $rootScope.auth.attachment.id + '.' + hash + '.png';
                } else {
                    $rootScope.auth.userimage = $window.theme + 'images/default.png';
                }
            }
            });
        }
        
        $timeout(function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME;
            $rootScope.currency_type = $rootScope.settings.CURRENCY_SYMBOL !== '' ? $rootScope.settings.CURRENCY_SYMBOL : $rootScope.settings.SITE_CURRENCY_CODE; 
        }, 0);
                $rootScope.gotoAdmin = function(){
                    $scope.site_url = '/ag-admin/#/dashboard';
                    var site_url = $scope.site_url;
                    window.location.href = site_url;
                    $cookies.put('site_name',$rootScope.settings.SITE_NAME);
                };
                  
                
        $rootScope.accountReload = 0;
        //Sub Header Navigation
        $rootScope.gotoHeader = function(){
                       if(!$rootScope.subHeader){
                                $rootScope.subHeader = $cookies.get("subHeader");
                            }
                       var header = document.getElementsByClassName('tempClass');
                        angular.forEach(header, function(value,key){
                            if(value.innerText === $rootScope.subHeader){
                                $scope.headerIndex = key;
                            }
                            $(value).removeClass("active");
                        });
                         $(header[$scope.headerIndex]).addClass("active");
                   };
         
        // event to update authentication status
        $scope.$on('updateParent', function(event, args) {
            if (args.isAuth === true) {
                $rootScope.isAuth = true;
            } else {
                $rootScope.isAuth = false;
            }
        });
        $scope.scrollMoveTop = function () {
            $('html, body').stop(true, true).animate({
                scrollTop: 0
            }, 600);
        };
  }]);
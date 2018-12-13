'use strict';
/**
 * @ngdoc function
 * @name getlancerApp.controller:PageViewController
 * @description
 * # PageViewController
 * Controller of the getlancerApp
 */
angular.module('hirecoworkerApp')
    .controller('PageViewController', ['$scope', '$cookies', '$rootScope', '$stateParams', 'page', 'flash', '$filter','ConstUserType','UserProfilesFactory','$state', function($scope, $cookies, $rootScope, $stateParams, page, flash, $filter,ConstUserType,UserProfilesFactory,$state) {
        $scope.ConstUserType = ConstUserType;
        $rootScope.isHome = true;
        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
            $scope.auth_user_detail = $cookies.getObject("auth");
        }
        if($state.current.name === 'become-a-service-provider'){
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Become a Service Provider");
        }
        $scope.changeStatus = function () {
                var params ={};
                params.role_id = ConstUserType.User;
                UserProfilesFactory.update(params).$promise.then(function (response) {
                    $scope.response = response;    
                    if ($scope.response.error.code === 0) {
                        $scope.Authuser = {
                            id: $scope.auth_user_detail.id,
                            username: $scope.auth_user_detail.username,
                            role_id: $scope.response.data.user.role_id,
                            refresh_token: $scope.auth_user_detail.refresh_token,
                            attachment: response.data.attachment,
                            is_profile_updated: $scope.auth_user_detail.is_profile_updated,
                            affiliate_pending_amount : response.data.affiliate_pending_amount,
                            category_id : $scope.auth_user_detail.category_id,
                            user_profile : $scope.auth_user_detail.user_profile,
                            blocked_user_count : $scope.auth_user_detail.blocked_user_count
                        };
                        $cookies.put('auth', JSON.stringify($scope.Authuser), {
                            path: '/'
                        });
                        $rootScope.auth = JSON.parse($cookies.get('auth'));

                        $state.go('my_services');
                    }     
                });
       };

           if($state.current.name !== 'become-a-service-provider') {
               var params = {};
            params.id = $stateParams.id;
            params.slug = $stateParams.slug;
            params.lang_code = $cookies.get("currentLocale");
            page.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    $scope.page = response.data;
                    $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")($scope.page.title);
                    var _descriptions = ($scope.page.meta_description !== null && $scope.page.meta_description !== '') ? $scope.page.meta_description : $scope.page.title;
                    var _keywords = ($scope.page.meta_keywords !== null && $scope.page.meta_keywords !== '') ? $scope.page.meta_keywords : $scope.page.title;
                    angular.element('html head meta[name=description]')
                        .attr("content", _descriptions);
                    angular.element('html head meta[name=keywords]')
                        .attr("content", _keywords);
                    angular.element('html head meta[property="og:description"], html head meta[name="twitter:description"]')
                        .attr("content", _descriptions);
                    angular.element('html head meta[property="og:title"], html head meta[name="twitter:title"]')
                        .attr("content", $rootScope.header);
                    angular.element('html head meta[property="og:image"], html head meta[name="twitter:image"]')
                        .attr("content", 'img/logo.png');
                    angular.element('meta[property="og:url"], html head meta[name="twitter:url"]')
                        .attr('content', "pages/" + $stateParams.id);
                } else {
                    flash.set($filter("translate")("Invalid request."), 'error', false);
                }
            });
           }
            
    }
]);
'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:HomeController
 * @description
 * # HomeController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
.controller('HomeController', ['$scope', '$rootScope', '$window', '$filter', '$state', '$timeout', '$cookies', '$location','Cities', 'Services', 'Category', 'md5', function($scope, $rootScope, $window, $filter, $state, $timeout, $cookies, $location,Cities, Services, Category, md5) {
        $rootScope.allowedplace = false;
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Home");
        };
        $rootScope.makePlaceTrue = function(){
            $rootScope.allowedplace = false;
        };
        $scope.init();
        $scope.form_services = null;
        $scope.form_city = null;
        $scope.homesearch = function () {
            if($rootScope.cities.length > 0){
                $rootScope.allowedplace = $rootScope.cities.indexOf($rootScope.cityName) === -1 ? true : false;
            }else{
                $rootScope.allowedplace = false;
            }
             if ($rootScope.countryName === $scope.form_address && $scope.form_address !== undefined) {
                $scope.zoom = 4;
            } else if ($rootScope.countryName !== $scope.form_address && $scope.form_address !== undefined) {
                $scope.zoom = 10;
            } else if ($scope.form_address === undefined) {
                $scope.zoom = 1;
            }
            if($rootScope.allowedplace === false){
                    $location.path('/users').search({
                        latitude: $rootScope.form_latitude,
                        longitude: $rootScope.form_longitude,
                        sw_latitude:$rootScope.sw_latitude,
                        sw_longitude:$rootScope.sw_longitude,
                        ne_latitude: $rootScope.sw_latitude,
                        ne_longitude: $rootScope.sw_longitude,
                        address: $rootScope.form_address,
                        service_id: $scope.service_id,
                        page: 1,
                        iso2: $rootScope.country_iso2,
                        zoom: $scope.zoom,
                        is_search : false
                    });
             }
             
        };
        /* To get language list in homepage */
        Cities.get().$promise.then(function (cityResponse) {
            $scope.citiesliLists = cityResponse.data;
        });
        var params = {};
        params.filter = '{"where":{"is_active":1},"order":"is_featured desc","include":{"0":"attachment","service":{"where":{"is_active":1},"0":"attachment"}}}';
        Category.categoryList(params).$promise.then(function (response) {
            $scope.categories = response.data;
            angular.forEach($scope.categories, function(value) {
                if(value.attachment) {
                    value.is_image = true;
                    var hash = md5.createHash(value.attachment.class + value.attachment.id + 'png' + 'small_thumb');
                    value.category_image = 'images/small_thumb/' + value.attachment.class + '/' + value.attachment.id + '.' + hash + '.png';
                } else {
                    value.is_image = false;
                }
                angular.forEach(value.service, function(childvalue){
                    if(childvalue.attachment) {
                        childvalue.is_image = true;
                        var hash = md5.createHash(childvalue.attachment.class + childvalue.attachment.id + 'png' + 'large_thumb');
                        childvalue.service_image = 'images/large_thumb/' + childvalue.attachment.class + '/' + childvalue.attachment.id + '.' + hash + '.png';
                    } else {
                        childvalue.is_image = false;
                    }
                });
            });
        });
        $scope.featured_service = [];
        params = {};
        params.filter = '{"where":{"is_active":1},"include":{"0":"attachment"}}';
        Services.get(params).$promise.then(function (response) {
            $scope.services = response.data;
            var i = 0;
            angular.forEach($scope.services, function(value) {
                if(value.attachment) {
                    value.is_image = true;
                    var hash = '';
                    if(i === 0) {
                        hash = md5.createHash(value.attachment.class + value.attachment.id + 'png' + 'big_thumb');
                        value.service_image = 'images/big_thumb/' + value.attachment.class + '/' + value.attachment.id + '.' + hash + '.png';
                    } else {
                        hash = md5.createHash(value.attachment.class + value.attachment.id + 'png' + 'large_thumb');
                        value.service_image = 'images/large_thumb/' + value.attachment.class + '/' + value.attachment.id + '.' + hash + '.png';
                    }
                } else {
                    value.is_image = false;
                }
                i++;
                if(parseInt(value.is_featured) === 1){
                    $scope.featured_service.push(value);
                }
            });
        });
}]).directive('owlCarouselItem', [function() {
        return {
            restrict: 'A',
            transclude: false,
            link: function(scope, element) {
                if (scope.$last) {
                    scope.initCarousel(element.parent());
                }
            }
        };
    }]).directive("owlCarousel", function() {
        return {
            restrict: 'E',
            transclude: false,
            link: function(scope) {
                scope.initCarousel = function(element) {
                    // provide any default options you want
                    var defaultOptions = {
                        responsiveClass: true,
                        margin: 30,
                        nav: true,
                        navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                        responsive: {
                            0: {
                                items: 1,
                                nav: true,
                                dots: false,
                            },
                            600: {
                                items: 2,
                                nav: true,
                                dots: false,
                            },
                            1000: {
                                items: 4,
                                nav: true,
                                loop: false,
                                dots: false,

                            }
                        }
                    };
                    $(element).owlCarousel(defaultOptions);
                };

            }
        };
    });
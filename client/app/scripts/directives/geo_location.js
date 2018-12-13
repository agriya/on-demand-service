'use strict';
angular.module('hirecoworkerApp')
	.directive('geoLocation', function() {
        return {
            restrict: 'E',
            scope: true,
            controller : function($scope,$rootScope, $filter) {
                $scope.country = [];
                $rootScope.cities =[];
                $scope.placeHolder = $filter("translate")("Search by City");
            if($rootScope.settings.ALLOWED_SERVICE_LOCATIONS){
                $scope.locations =JSON.parse($rootScope.settings.ALLOWED_SERVICE_LOCATIONS);
                angular.forEach($scope.locations.allowed_countries, function(value){
                            $scope.country.push(value.iso2);
                });
                angular.forEach($scope.locations.allowed_cities, function(value){
                            $rootScope.cities.push(value.name);
                });
            }    
            
                var options = {
                    componentRestrictions: {country: $scope.country}
                };
                var autocompleteFrom = new google.maps.places.Autocomplete(document.getElementById('geo-location'), options);
                google.maps.event.addListener(autocompleteFrom, 'place_changed', function() {
                    var place = autocompleteFrom.getPlace();
                    //$rootScope.north_east = place.geometry.viewport.f.f+","+place.geometry.viewport.b.f;
                    $rootScope.ne_latitude = place.geometry.viewport.f.f;
                    $rootScope.ne_longitude = place.geometry.viewport.b.f;
                    $rootScope.sw_latitude = place.geometry.viewport.f.b;
                    $rootScope.sw_longitude = place.geometry.viewport.b.b;
                    //$rootScope.south_west = place.geometry.viewport.f.b+","+place.geometry.viewport.b.b;
                    $scope.profileLatitude = place.geometry.location.lat();
                    $rootScope.form_latitude = place.geometry.location.lat();
                    $scope.profileLongitude = place.geometry.location.lng();
                    $rootScope.form_longitude = place.geometry.location.lng();
                    $scope.profileAddress = place.formatted_address;
                    $rootScope.form_address = place.formatted_address;
                    $rootScope.cityName = undefined;
                    $rootScope.stateName = undefined;
                    $rootScope.country_iso2 = undefined;
                    var k = 0;
                    angular.forEach(place.address_components, function(value) {
                        if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                            if (k === 0) {
                                $scope.cityName = value.long_name;
                            }
                            if (value.types[0] === 'locality') {
                                k = 1;
                            }
                            $rootScope.cityName = value.long_name;
                            
                        }
                        if (value.types[0] === 'administrative_area_level_1') {
                            $rootScope.stateName = value.long_name;
                        }
                        if (value.types[0] === 'country') {
                            $rootScope.countryName = value.long_name;
                            $rootScope.country_iso2 = value.short_name;
                        }
                        if (value.types[0] === 'sublocality_level_1' || value.types[0] === 'sublocality_level_2') {
                            if ($scope.profileAddress !== '') {
                                $scope.profileAddress = $scope.profileAddress + ', ' + value.long_name;
                            } else {
                                $scope.profileAddress = value.long_name;
                            }
                        }        
                        if (value.types[0] === 'postal_code') {
                            $scope.postalCode = parseInt(value.long_name);
                        }
                    });
                     
                });
            },
            template: '<input name="address" class="form-control" id="geo-location" placeholder="{{placeHolder}}"  ng-model="form_address" ng-blur="search_change(form_address)" ng-focus="makePlaceTrue()" ng-change="placeChanged(form_address)">'
        };
   });

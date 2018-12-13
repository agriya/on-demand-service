'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp').controller('SaveSearchHome', function ($scope, GetMeSavedSearches, $rootScope, $cookies, $state, SavedSearches, SweetAlert, $filter, flash, FormField, $uibModal, $uibModalStack, Services, $location) {
    if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
        $state.go('user_profile', { type: 'personal' });
    }
    $scope.cities = [];
    $scope.locations = [];
    $scope.IsAddressPlaceChange = true;
    if ($rootScope.settings.ALLOWED_SERVICE_LOCATIONS) {
        $scope.locations = JSON.parse($rootScope.settings.ALLOWED_SERVICE_LOCATIONS);
        angular.forEach($scope.locations.allowed_countries, function (value) {
            $scope.country.push(value.iso2);
        });
        angular.forEach($scope.locations.allowed_cities, function (value) {
            $rootScope.cities.push(value.name);
        });
    }
    $scope.currentPage = 1;
    $scope.lastPage = 1;
    $rootScope.subHeader = "Saved searches"; //for setting active class for subheader
    $cookies.put('subHeader', $rootScope.subHeader);
    $rootScope.gotoHeader();
    $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Saved Searches");
    var params = {};
    $scope.edit_search = {};
    $scope.getSavedSearches = function () {
        $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
        $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
        params.filter = { "limit": $scope.itemsPerPage, "skip": $scope.skipvalue, "include": { "0": "service" } };
        GetMeSavedSearches.get(params, function (response) {
            $scope.ListSavedSearches = response.data;
            if (response._metadata) {
                $scope.currentPage = response._metadata.current_page;
                $scope.lastPage = response._metadata.last_page;
                $scope.itemsPerPage = response._metadata.per_page;
                $scope.totalRecords = response._metadata.total;
            }
        });
    };
    $scope.getSavedSearches();
    $scope.paginate = function (currentPage) {
        $scope.currentPage = currentPage;
        $scope.getSavedSearches();
    };
    params = {};
    $scope.updateSavedSearches = function (notify_type_id, searchId) {
        params.id = searchId;
        params.notification_type_id = notify_type_id;
        SavedSearches.put(params, function (response) {
            if (response.error.code === 0) {
                flash.set($filter("translate")("Notification setting changed successfully."), 'success', false);
            }
        });
    };
    $scope.editSavedSearch = function (response) {
        $scope.edit_search = response;
        params = {};
        params.filter = ' {"where":{"OR":[{"AND":{"class":"Service","foreign_id":' + $state.params.service_id + '}},{"AND":{"class":"Category","foreign_id":' + $scope.category + '}}],"is_enable_this_field_in_search_form":1},"include":{"0":"input_types"}}';
        FormField.get(params, function (Formresponse) {
            $scope.form_fields = Formresponse.data;
            $scope.form_field_submissions = JSON.parse(response.form_field_submissions);
            var dummy, temp;
            $scope.save_search_form_field_submissions = [];
            angular.forEach($scope.form_field_submissions, function (value) {
                angular.forEach(value, function (child_value, key) {
                    dummy = key.split('_');
                    $scope.form_field_id = dummy[dummy.length - 1];
                    angular.forEach($scope.form_fields, function (form_value) {
                        if (parseInt(form_value.id) === parseInt($scope.form_field_id)) {
                            temp = {};
                            temp.name = $filter("translate")(form_value.label);
                            temp.value = child_value;
                            $scope.save_search_form_field_submissions.push(temp);
                        }
                    });
                });
            });
            $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title',
                ariaDescribedBy: 'modal-body',
                templateUrl: 'editSaveSearch.html',
                size: "lg",
                scope: $scope,
                controller: function () {
                    $scope.modalClose = function () {
                        $uibModalStack.dismissAll();
                    };
                }
            });
        });
    };
    $scope.deleteSavedSearches = function (searchId) {
        SweetAlert.swal({//jshint ignore:line
            title: $filter("translate")("Are you sure you want to delete?"),
            text: "",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            animation: false,
        }, function (isConfirm) {
            if (isConfirm) {
                params = {};
                params.id = searchId;
                SavedSearches.delete(params, function (response) {
                    if (response.error.code === 0) {
                        $state.reload();
                    }
                });
            }
        });

    };
    $scope.updateModalSaveSearch = function () {
        params = {};
        params.id = $scope.edit_search.id;
        params.name = $scope.edit_search.name;
        params.notification_type_id = $scope.edit_search.notification_type_id;
        SavedSearches.put(params, function (response) {
            if (response.error.code === 0) {
                $uibModalStack.dismissAll();
                flash.set($filter("translate")("Search updated successfully."), 'success', false);
            }
        });
    }
    params = {};
    params.filter = '{"where":{"is_active":1},"limit":100,"skip":0}';
    Services.get(params).$promise.then(function (response) {
        $scope.services = response.data;
    });

    $scope.location = function () {
        $scope.IsAddressPlaceChange = false;
        if ($scope.address !== undefined) {
            angular.forEach($scope.address.address_components, function (value) {
                if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                    console.log(value);
                    $scope.cityName = value.long_name;
                }
                if (value.types[0] === 'country') {
                     $scope.countryName = value.long_name;
                     $scope.country_iso2 = value.short_name;   
                }
            });
            if ($scope.address.address_components) {
                $scope.ne_latitude = $scope.address.geometry.viewport.f.f;
                $scope.ne_longitude = $scope.address.geometry.viewport.b.f;
                $scope.sw_latitude = $scope.address.geometry.viewport.f.b;
                $scope.sw_longitude = $scope.address.geometry.viewport.b.b;
                $scope.form_longitude = $scope.address.geometry.location.lng();
                $scope.form_latitude = $scope.address.geometry.location.lat();
                $scope.address = $scope.address.formatted_address;
                $scope.IsAddressPlaceChange = true;
            }
        } else {
            $scope.IsAddressPlaceChange = true;
        }
    };

    $scope.homesearch = function ($valid, formname) {
        if (!formname.$valid) {
            angular.element("[name='" + formname.$name + "']").find('.ng-invalid:visible:first').focus();
            return false;
        }
        if ($valid) {
            if ($scope.cities.length > 0) {
                $scope.allowedplace = $scope.cities.indexOf($scope.cityName) === -1 ? true : false;
            } else {
                $scope.allowedplace = false;
            }
            if ($scope.countryName === $scope.form_address && $scope.form_address !== undefined) {
                $scope.zoom = 4;
            } else if ($scope.countryName !== $scope.form_address && $scope.form_address !== undefined) {
                $scope.zoom = 10;
            } else if ($scope.form_address === undefined) {
                $scope.zoom = 1;
            }
            if($scope.allowedplace === false){
                    $location.path('/users').search({
                        latitude: $scope.form_latitude,
                        longitude: $scope.form_longitude,
                        sw_latitude:$scope.sw_latitude,
                        sw_longitude:$scope.sw_longitude,
                        ne_latitude: $scope.ne_latitude,
                        ne_longitude: $scope.ne_longitude,
                        address: $scope.address,
                        service_id: $scope.service_id,
                        page: 1,
                        iso2: $scope.country_iso2,
                        zoom: $scope.zoom,
                        is_search : false
                    });
             }
        }
    };
});
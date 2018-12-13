'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    .controller('JobBoardController', function ($scope, $location, $window, $rootScope, $filter,NgMap, $state, $timeout, $cookies, UsersFactory, md5, ConstListingStatus, ConstService, JobRequestGet, ConstBookingType, Service, CategoryService, RequestStatusId, JobTypeId, ConstAppintmentTimingType, ConstPaymentType, ExpressInterest, flash, RemoveInterest, SweetAlert) {
        $rootScope.isHome = true;
        $rootScope.JobTypeId = JobTypeId;
        $rootScope.AppointmentTimingType = ConstAppintmentTimingType;
        $rootScope.ConstPaymentType = ConstPaymentType;
        $scope.currentPage = 1;
        $scope.lastPage = 1;
        $scope.enableRadius = false;
        $scope.options = {};
        var params = {};
        $scope.buttonText = "Already applied";
        $scope.submission = [];
        $scope.options.singleDatePicker = true;
        $rootScope.allowedplace = false;
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
            $state.go('user_profile', { type: 'personal' });        }
        $rootScope.subHeader = "Job Board"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader(); 
        $scope.ConstService = ConstService;
        $scope.is_came = false;
        $rootScope.makePlaceTrue = function () {
            $rootScope.allowedplace = false;
        };
        $scope.showLoader = true;
        $rootScope.ListingStatus = ConstListingStatus;
        $scope.page = 1;
        $scope.searchplace = $location.path('/jobs').search().address;
        if (!$location.path('/jobs').search().zoom || !$scope.searchplace) {
            $scope.zoomLevel = 1;
        }

        $scope.data = {};
        if ($cookies.get("map_label") !== null) {
            if ($cookies.get("map_label") === "true") {
                $scope.data.map_label = true;
            }
        }
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Job Board");
        $scope.ConstBookingType = ConstBookingType;
        function pageRedirect(linkvalue) {
            var fulUrl = $location.absUrl();
            var splitUrl = fulUrl.split('?');
            window.location.href = splitUrl[0] + "?" + linkvalue;
        }
        $scope.init = function () {
            $scope.pageRedirect = pageRedirect;
        };
        $scope.mindate = new Date();
        $scope.radiusData = [{"value":0,"text":$filter("translate")("Please select distance")},{"value":10,"text":$filter("translate")("within 10 km")},{"value":20,"text":$filter("translate")("within 20 km")},{"value":50,"text":$filter("translate")("within 50 km")},{"value":100,"text":$filter("translate")("within 100 km")},{"value":200,"text":$filter("translate")("within 200 km")},{"value":500,"text":$filter("translate")("within 500 km")}];
        $scope.category_page = true;
        if ($location.path('/jobs').search().service_id) {
            $scope.category_page = false;
        }
        if($scope.category_page === true){
            //for footer css class
            $rootScope.is_service_class = $('.section').hasClass('service-names') === true ? true : false;
            //Ends -Footer class
        }
       
        //Date Picker
        $scope.$watch("datePicker", function (date) {
            if (date) {
                if (date.endDate) {
                    $scope.form_to_date = $filter('date')(date.endDate._d, "yyyy-MM-dd");
                    $scope.form_date = $filter('date')(date.startDate._d, "yyyy-MM-dd");
                } else {
                    $scope.form_date = $filter('date')(date._d, "yyyy-MM-dd");
                    $scope.form_to_date = $rootScope.start_date;
                }
            }

        });
        $scope.$watch("form_address", function (oldvalue, newvalue) {
            $rootScope.form_address = newvalue;
        });


        //Services 
        var temp = {};
        $scope.category = {};
        temp.filter = '{"where":{"is_active":1},"include":{"category":{"whereHas":{"is_active":1}},"0":"category.form_field_groups.form_fields.input_types","category.form_field_groups.form_fields":{"where":{"is_enable_this_field_in_search_form":1}}}}';
        Service.get(temp, function (response) {
            $scope.services = [];
            $scope.servicing = response.data;
            angular.forEach($scope.servicing, function (value) {
               $scope.services.push(value);
            });
        });
        // });
        $scope.search_change = function (value) {
            if (value === null || value === undefined || value === "") {
                $rootScope.form_address = undefined;
            }
        };
        $scope.placeChanged = function(form_address){
            if(form_address !== '' && form_address !== null && form_address !== undefined){
                $scope.enableRadius = true;
                $scope.data.selectedRadius = 50;
            }else{
                $scope.enableRadius = false;
                $scope.data.selectedRadius = 0;
            }
            
        };
        $scope.homesearch = function () {
            $scope.searchplace = $rootScope.form_address;
            if ($rootScope.cities.length > 1) {
                $rootScope.allowedplace = $rootScope.cities.indexOf($rootScope.cityName) === -1 ? true : false;
            } else {
                $rootScope.allowedplace = false;
            }
            if ($rootScope.allowedplace === false) {

                $scope.form_field_submissions = [];
                angular.forEach($scope.submission, function (submit_value) {
                    angular.forEach($scope.form_fields_all, function (childvalue) {
                        if (submit_value[0]) {
                            if (submit_value[0].value !== "") {
                                if (parseInt(submit_value[0].id) === parseInt(childvalue.id)) {
                                    var temp = {};
                                    childvalue.dummy_name = "f_f_" + childvalue.id;
                                    temp[childvalue.dummy_name] = submit_value[0].value;
                                    $scope.form_field_submissions.push(temp);
                                }
                            }

                        }

                    });
                });
                $scope.temp = JSON.stringify($scope.form_field_submissions);
                if (!$scope.searchplace) {
                    $location.path('/jobs').search({
                        service_id: $scope.service_id,
                        display_type: $scope.display_type,
                        page: $scope.page,
                        zoom: 1,
                        radius: $scope.data.selectedRadius
                    });
                }
                else if ($rootScope.countryName === $scope.searchplace) {
                    $scope.zoom = 4;
                } else if ($rootScope.countryName !== $scope.searchplace) {
                    $scope.zoom = 10;
                }
                if ($scope.searchplace) {
                    $location.path('/jobs').search({
                        latitude: $rootScope.form_latitude,
                        longitude: $rootScope.form_longitude,
                        sw_latitude: $rootScope.sw_latitude,
                        sw_longitude: $rootScope.sw_longitude,
                        ne_latitude: $rootScope.ne_latitude,
                        ne_longitude: $rootScope.ne_longitude,
                        service_id: $scope.service_id,
                        appointment_from_date: $scope.form_date,
                        appointment_to_date: $scope.form_to_date,
                        address: $rootScope.form_address,
                        display_type: $scope.display_type,
                        page: $scope.page,
                        zoom: $scope.zoom,
                        more: $scope.temp,
                        iso2: $rootScope.country_iso2,
                        radius: $scope.data.selectedRadius
                    });
                }

            }

        };


        $scope.loadServices = function (category_id) {
            CategoryService.get({ id: category_id }).$promise.then(function (response) {
                $scope.serviceLists = response.services;
            });
        };
        $scope.init = function () {
        };
         //checkbox select on service
                $scope.service_change = function (index) {
                    $scope.checked = $scope.services[index].checked;
                    if ($scope.checked === true) {
                        angular.forEach($scope.services, function (value) {
                            value.checked = false;
                        });
                        $scope.services[index].checked = $scope.checked;
                        if ($scope.services[index].booking_option_id === ConstBookingType.MultipleDate) {
                            $scope.options.singleDatePicker = false;
                        } else {
                            $scope.options.singleDatePicker = true;
                        }
                    } else {
                        $scope.services[index].checked = true;
                    }
                    $scope.homesearch();
                };
        $scope.searchUsers = function () {
            $scope.loader = true;
            $scope.NoRecords = false;
            params = {};
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 20;
            params.filter = {"limit":$scope.itemsPerPage,"skip":$scope.skipvalue,"order":"id desc"};
            params.filter.include = {};
            params.filter.where = {"request_status_id" : RequestStatusId.Open};
            if($location.search().service_id){
                params.filter.where.service_id = $location.search().service_id; 
            }
            params.filter.include["0"] = "user.attachment";
            params.filter.include["1"] = "user.user_profile";
            params.filter.include["2"] = "form_field_submission.form_field.form_field_group";
            params.filter.include["3"] = "current_user_interest";
            params.filter.include["4"] = "service";
                if(($rootScope.cityName !== null && $rootScope.cityName !== undefined) || ($rootScope.stateName !== null && $rootScope.stateName !== undefined)){
                    params.latitude = $location.search().latitude;
                    params.longitude = $location.search().longitude;
                    params.listing_sw_latitude = $location.search().sw_latitude;
                    params.listing_sw_longitude = $location.search().sw_longitude;
                    params.listing_ne_latitude = $location.search().ne_latitude;
                    params.listing_ne_longitude = $location.search().ne_longitude;
                }else if($location.search().iso2){
                    //while refresh when lat and long is there in params 
                    delete params.latitude;
                    delete params.longitude;
                    delete params.listing_sw_latitude;
                    delete params.listing_sw_longitude;
                    delete params.listing_ne_longitude;
                    delete params.listing_ne_latitude;
                    params.filter.include["user_profile.listing_country"] = {"whereHas":{"iso2" : $location.search().iso2}};
                }
            params.radius = $location.search().radius;
            params.search_type = $location.search().search_type;
            params.filter = JSON.stringify(params.filter);
            JobRequestGet.get(params).$promise.then(function (searchResponse) {
                $scope.loader = false;
                $scope.center_lat = $location.path('/jobs').search().latitude !== undefined ? $location.path('/jobs').search().latitude : 0;
                $scope.center_lon = $location.path('/jobs').search().longitude !== undefined ? $location.path('/jobs').search().longitude : 0;
                $scope.page = searchResponse._metadata.current_page;
                $scope.searchLists = searchResponse.data;
                if(searchResponse._metadata){
                    $scope.currentPage = searchResponse._metadata.current_page;
                    $scope.lastPage = searchResponse._metadata.last_page;
                    $scope.itemsPerPage = searchResponse._metadata.per_page;
                    $scope.totalRecords = searchResponse._metadata.total;
                }
                angular.forEach($scope.searchLists, function(value){
                    value.appointment_from_date = new Date(value.appointment_from_date);
                    value.appointment_to_date = new Date(value.appointment_to_date);
                    if(value.current_user_interest !== (null || undefined)){
                        value.buttonText = $filter("translate")("Already applied");
                    }else{
                        value.buttonText = $filter("translate")("Express Interest");
                    }
                    if(value.user.attachment){
                        var hash = md5.createHash(value.user.attachment.class + value.user.attachment.id + 'png' + 'big_thumb');
                        value.requestor_image = 'images/big_thumb/' + value.user.attachment.class + '/' + value.user.attachment.id + '.' + hash + '.png';
                    } else {
                        value.requestor_image  = $window.theme + 'images/default.png';
                    }
                    value.list_of_days = [{'id':'sun_search',"text" : "Sun","checked":false},{'id':'mon_search',"text" : "Mon","checked":false},{'id':'tue_search',"text" : "Tue","checked":false},{'id':'wed_search',"text" : "Wed","checked":false},{'id':'thu_search',"text" : "Thu","checked":false},{'id':'fri_search',"text" : "Fri","checked":false},{'id':'sat_search',"text" : "Sat","checked":false}];
                });
                
                angular.forEach($scope.searchLists, function(search_value){
                    angular.forEach(search_value.list_of_days, function(value){
                        if(value.text === "Sun"){
                            value.checked = parseInt(search_value.is_sunday_needed) === 1 ? true :false;
                        }else if(value.text === "Mon"){
                            value.checked = parseInt(search_value.is_monday_needed) === 1 ? true :false;
                        }else if(value.text === "Tue"){
                            value.checked = parseInt(search_value.is_tuesday_needed) === 1 ? true :false;
                        }else if(value.text === "Wed"){
                            value.checked = parseInt(search_value.is_wednesday_needed) === 1 ? true :false;
                        }else if(value.text === "Thu"){
                            value.checked = parseInt(search_value.is_thursday_needed) === 1 ? true :false;
                        }else if(value.text === "Fri"){
                            value.checked = parseInt(search_value.is_friday_needed) === 1 ? true :false;
                        }else if(value.text === "Sat"){
                            value.checked = parseInt(search_value.is_saturday_needed) === 1 ? true :false;
                        }    
                    });
                });
                NgMap.getMap().then(function (map) {
                    $scope.map = map;

                });
                $scope.positions = [];
                // code needed
                if ($scope.searchLists !== undefined) {
                    angular.forEach($scope.searchLists, function (value) {
                        if(value.work_location_latitude && value.work_location_longitude){
                            $scope.positions.push({
                                id: value.id,
                                username: value.username,
                                doctor_name: value.first_name + ' ' + value.last_name,
                                address1: value.address,
                                rating: value.starRating,
                                lat: value.work_location_latitude,
                                lon: value.work_location_longitude
                            });
                        }
                        
                    });
                }
                $scope.showLoader = false;
                $scope.mappositions = $scope.positions;
            });
            //}, 200);

        };
        $scope.clearTime = function () {
            clearTimeout($(this).data('timeout'));
        };
        $scope.Goto = function (event) {//jshint ignore:line
                    var id = "#user" + this.id;
                    var top = $(id).offset().top - 80;
                    $('html, body').animate({
                        scrollTop: top
                    }, 500);
                    $scope.userClickedId = this.id;
                };
        $scope.zoomChanged = function () {

                    if ($scope.data.map_label === true) {
                        if ($scope.map) {
                            $scope.zoomLevel = $scope.map.getZoom();
                            $scope.latlng = [$scope.map.getCenter().lat(), $scope.map.getCenter().lng()];
                            $scope.center_lat = $scope.map.getCenter().lat();
                            $scope.center_lon = $scope.map.getCenter().lng();
                        }

                        if ($scope.latlng) {
                            $location.path('/users').search({
                                latitude: $scope.map.getCenter().lat(),
                                longitude: $scope.map.getCenter().lng(),
                                sw_latitude: $scope.map.getBounds().f.b,
                                sw_longitude: $scope.map.getBounds().b.b,
                                ne_latitude: $scope.map.getBounds().f.f,
                                ne_longitude: $scope.map.getBounds().b.f,
                                service_id: $location.search().service_id,
                                address: $rootScope.form_address,
                                display_type: $scope.display_type,
                                page: $location.search().page,
                                zoom: $scope.zoomLevel,
                                iso2: $location.search().iso2,
                                radius: $location.search().radius,
                                search_type : "map" ,
                                user_search_id : $location.search().user_search_id
                            });
                        }
                    }
                };
                $scope.toggleBounce = function (val) {
                    $(this).data('timeout', setTimeout(function () {
                    }, 500));

                };

        $timeout(function () {
            $scope.form_fields_all = [];
            // prefilling data
            $scope.service_id = $location.search().service_id;
            $scope.form_address = $location.path('/jobs').search().address;
            $scope.zoomLevel = $location.path('/jobs').search().zoom;
            if($location.path('/jobs').search().address){
                $scope.data.selectedRadius = $location.path('/jobs').search().radius !== undefined ? $location.path('/jobs').search().radius : 50;
                $scope.enableRadius = true;
            }else{
                $scope.data.selectedRadius = 0;
            }
        }, 10);
        $scope.start_dates = $state.params.start_date;
        $scope.end_dates = $state.params.end_date;

        $scope.map_change = function (val) {
            $cookies.put('map_label', val);
        };
        $scope.getpos = function () {
            $scope.zoomLevel = $location.path('/jobs').search().zoom !== undefined ? $location.path('/jobs').search().zoom : $scope.map.getZoom();
            if ($scope.data.map_label === true) {
                $scope.latlng = [$scope.map.getCenter().lat(), $scope.map.getCenter().lng()];
                $scope.center_lat = $scope.map.getCenter().lat();
                $scope.center_lon = $scope.map.getCenter().lng();
                if ($scope.latlng) {
                    $location.path('/jobs').search({
                        latitude: $scope.map.getCenter().lat(),
                        longitude: $scope.map.getCenter().lng(),
                        sw_latitude: $scope.map.getBounds().f.b,
                        sw_longitude: $scope.map.getBounds().b.b,
                        ne_latitude: $scope.map.getBounds().f.f,
                        ne_longitude: $scope.map.getBounds().b.f,
                        service_id: $location.path('/jobs').search().service_id,
                        address: $rootScope.form_address,
                        display_type: $scope.display_type,
                        page: $location.path('/jobs').search().page,
                        zoom: $scope.zoomLevel,
                        iso2: $location.path('/jobs').search().iso2,
                        radius: $location.path('/jobs').search().radius,
                        search_type : "map"
                    });
                }
            }

        };

        $scope.searchUsers();
        $scope.ExpressInterest = function(requestId, index){
            params = {};
            params.request_id = requestId;
            ExpressInterest.post(params, function(response){
                if(response.error.code === 0){
                    flash.set($filter("translate")("Successfully Applied."), 'success', false);
                    $scope.searchLists[index].current_user_interest =  response;
                    $scope.searchLists[index].buttonText = $filter("translate")("Applied");
                }
            });

        };
        $scope.RemoveInterest = function(requestId, index){
            params = {};
            params.requestsUserId = requestId;
            RemoveInterest.delete(params, function(response){
                if(response.error.code === 0){
                    flash.set($filter("translate")("Interest removed successfully."), 'success', false);
                    $scope.searchLists[index].current_user_interest = null;
                }
            });

        };
        $scope.paginate_search = function(currentPage){
             $scope.currentPage = currentPage;
             $scope.searchUsers($scope.type);
        };
        $scope.ChangeButtonText = function(type, index){
            if(type === "Enter"){
                $scope.searchLists[index].buttonText = $filter("translate")("Remove Interest");
            }else{
                $scope.searchLists[index].buttonText = $filter("translate")("Already applied");
            }
        };
        $scope.closeJob = function(jobId, index){
                SweetAlert.swal({//jshint ignore:line
                        title: $filter("translate")("Are you sure you want to close this job?"),
                        text: "",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel",
                        closeOnConfirm: true,
                        animation:false,
                    }, function (isConfirm) {
                        if (isConfirm) {
                            params = {};
                            params.requestId = jobId;
                            params.request_status_id = RequestStatusId.Closed;
                            JobRequestGet.put(params, function(response){
                                if(response.error.code === 0){
                                    flash.set($filter("translate")("Successfully Closed."), 'success', false);
                                    $scope.searchLists.splice(index,1);
                                }
                            });
                        }
                    });
                
            };
            $scope.editJob = function(id){
                  $state.go('JobEdit', { job_id: id});
            };

    })
    
    /* For select when search */
    .directive('convertToNumber', function () {
        return {
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function (val) {
                    return val ? parseInt(val, 10) : null;
                });
                ngModel.$formatters.push(function (val) {
                    return val ? '' + val : '';
                });
            }
        };
    });
'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    .controller('SearchController', function ($location, $scope, ConstBookingType, $window, $rootScope, $filter, $state, Languages, Cities, Service, Services, SpecialtyDiseas, Gender, Category, SearchList, WeekList, ServiceDetails, CategoryService, appointmentSetting, NgMap, $stateParams, $timeout, $cookies, GetPostions, GetExperience, GetQualification, GetSkill, YachtTypes, UsersFactory, md5, ConstListingStatus, ConstService, UserFavorite, EditUserFavorite, FormField, $builder, ConstInputTypes, ConstUserType,ConstProStatus, $uibModal, $uibModalStack, PostSavedSearches, GetSavedSearches, SavedSearches, JobRequestGet,flash) {
        var dummy = '';
        $scope.currentPage = 1;
        $scope.lastPage = 1;
        $scope.enableRadius = false;
        $scope.ConstProStatus = ConstProStatus;
        $scope.options = {};
        var params = {};
        $scope.submission = [];
        $scope.options = {autoApply:true,singleDatePicker:true,minDate:moment(new Date()).format('YYYY-MM-DD')};
        $rootScope.allowedplace = false;
        if ($rootScope.isAuth) {
            if (parseInt($rootScope.auth.is_profile_updated) === 0) {
                $state.go('user_profile', { type: 'personal' });
            }
        }
        $rootScope.subHeader = "Search";
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.edit_search = {}; 
        $scope.edit_search.notify_type = "instantly";  
        $scope.ConstService = ConstService;
        $scope.is_came = false;
        $rootScope.makePlaceTrue = function () {
            $rootScope.allowedplace = false;
        };
        $scope.showLoader = true;
        $rootScope.ListingStatus = ConstListingStatus;
        $rootScope.isHome = true;// for avoiding sub header in search page
        $scope.page = 1;
        
        $scope.searchplace = $location.search().address;
        if (!$location.search().zoom || !$scope.searchplace) {
            $scope.zoomLevel = 1;
        }

        $scope.data = {};
        if ($cookies.get("map_label") !== null) {
            if ($cookies.get("map_label") === "true") {
                $scope.data.map_label = true;
            }
        }
        $scope.ConstBookingType = ConstBookingType;

        localStorage.setItem("serviceId", $state.params.service_id);
        $scope.mindate = new Date();

        if ($stateParams.specality_id) {
            $scope.specialtyliList.id = $stateParams.specality_id;
        }
        $scope.radiusData = [{"value":0,"text":$filter("translate")("Please select distance")},{"value":"1","text":$filter("translate")("Any")},{"value":10,"text":$filter("translate")("within 10 km")},{"value":20,"text":$filter("translate")("within 20 km")},{"value":50,"text":$filter("translate")("within 50 km")},{"value":100,"text":$filter("translate")("within 100 km")},{"value":200,"text":$filter("translate")("within 200 km")},{"value":500,"text":$filter("translate")("within 500 km")}];
        $scope.category_page = true;
        if ($location.search().service_id || $location.search().user_search_id || $location.search().request_id) {
            $scope.category_page = false;
        }
       //for footer css class
        $rootScope.is_service_class =  $scope.category_page === true ? true : false;
        //Ends -Footer class
       
         //Get categories
        $scope.categories = {};
        $scope.category_values = '';
        params = {};
           params.filter = '{"where":{"is_active":1},"include":{"service":{"whereHas":{"is_active":1}}}}';
           Category.categoryList(params).$promise.then(function (response) {
            $scope.categories = response.data;
            if($scope.categories.length === 1){
                $scope.category_values = $scope.categories[0].id;
                $scope.values_category = true;
                if(!$location.search().service_id){
                    $location.path('/users').search({
                        service_id: $location.search().service_id !== undefined ? $location.search().service_id : $scope.categories[0].service[0].id,
                        zoom: $location.search().zoom !== undefined ? $location.search().zoom :1,
                        latitude: $location.search().latitude,
                        longitude: $location.search().longitude,
                        sw_latitude:$location.search().sw_latitude,
                        sw_longitude:$location.search().sw_longitude,
                        ne_latitude: $location.search().ne_latitude,
                        ne_longitude: $location.search().ne_longitude,
                        address: $location.search().address,
                        page: $location.search().page,
                        more: $location.search().more,
                        iso2: $location.search().iso2,
                        user_search_id : $location.search().user_search_id,
                        search_title : $location.search().search_title,
                        notify_id : $location.search().notify_id,
                        request_id : $location.search().request_id,
                        is_search : $location.search().is_search
                    });
                }    
              }
           });
           $scope.getService = function(service_id){
               $location.path('/users').search({
                     service_id: service_id,
                     zoom: $location.search().zoom !== undefined ? $location.search().zoom :1,
                     latitude: $location.search().latitude,
                     longitude: $location.search().longitude,
                     sw_latitude:$location.search().sw_latitude,
                     sw_longitude:$location.search().sw_longitude,
                     ne_latitude: $location.search().ne_latitude,
                     ne_longitude: $location.search().ne_longitude,
                     address: $location.search().address,
                     page: $location.search().page,
                     more: $location.search().more,
                     iso2: $location.search().iso2,
                     user_search_id : $location.search().user_search_id,
                     search_title : $location.search().search_title,
                     notify_id : $location.search().notify_id,
                     request_id : $location.search().request_id,
                     is_search : $location.search().is_search
                });
           };
        //Date Picker
        $scope.$watch("datePicker", function (date) {
            if(date){
                $scope.date = date;
                if (date.endDate) {
                    if(date.endDate._d){
                        $scope.form_to_date = $filter('date')(date.endDate._d, "yyyy-MM-dd");
                        $scope.form_date = $filter('date')(date.startDate._d, "yyyy-MM-dd");
                    }
                } else if(date._d) {
                    $scope.form_date = $filter('date')(date._d, "yyyy-MM-dd");
                    $scope.form_to_date = $rootScope.start_date;
                }
             }
        });
        //Services 
        var temp = {};
        $scope.category = {};
        temp.filter = '{"where":{"is_active":1},"include":{"category":{"whereHas":{"is_active":1}},"0":"category.form_field_groups.form_fields.input_types","category.form_field_groups.form_fields":{"where":{"is_enable_this_field_in_search_form":1}}}}';
        Service.get(temp, function (response) {
            $scope.services = [];
            $scope.servicing = response.data;
            angular.forEach($scope.servicing, function (value) {
                if (parseInt($state.params.service_id) === parseInt(value.id)) {
                    $scope.category = value.category_id;
                }
            });
            if($state.params.service_id !== undefined){
                angular.forEach($scope.servicing, function (value) {
                if (parseInt($scope.category) === parseInt(value.category_id)) {
                    $scope.services.push(value);
                }
            });
            }else if($state.params.service_id === undefined){
                  angular.forEach($scope.servicing,function(value){
                        $scope.services.push(value);
                   }); 
                }
            //prefilling service
            angular.forEach($scope.services, function (value) {
                if (parseInt(value.id) === parseInt($location.search().service_id)) {
                    value.checked = true;
                    $scope.service_name = value.name; 
                    if(value.booking_option_id === ConstBookingType.MultipleDate) {
                        // $scope.options.singleDatePicker = false;
                          $scope.options = {autoApply:true,singleDatePicker:false,minDate:moment(new Date()).format('YYYY-MM-DD'),
                                    eventHandlers : {'hide.daterangepicker': function() { 
                                                    $scope.get_start_data = $("input[name=daterangepicker_start]");
                                                    if($scope.is_came === false && ($scope.datePicker.startDate === $scope.datePicker.endDate)&& !$scope.options.singleDatePicker){
                                                        $scope.datePicker={"startDate" : $scope.get_start_data[0].value, "endDate" : $scope.get_start_data[0].value};
                                                        $scope.form_date = $scope.get_start_data[0].value;
                                                        $scope.form_to_date = $scope.get_start_data[0].value;
                                                    }
                                                    $scope.is_came = false;
                                                    }
                           }};
                    } else {
                        $scope.option= {singleDatePicker :true,minDate:moment(new Date()).format('YYYY-MM-DD')};
                    }
                }
            });
            //Form Field
            if($location.search().service_id){
                $scope.form_data = [];
                params = {};
                params.filter = ' {"where":{"OR":[{"AND":{"class":"Service","foreign_id":' + $state.params.service_id + '}},{"AND":{"class":"Category","foreign_id":' + $scope.category + '}}],"is_enable_this_field_in_search_form":1},"include":{"0":"input_types"}}';
                FormField.get(params, function (response) {
                    $scope.form_fields = response.data;
                    angular.forEach(response.data, function (value) {
                        $scope.form_data.push(value);
                    });
                    var firstfrm = 1;
                    for (var ival = 1; ival < 10; ival++) {
                        /** to do form empty  */
                        if ($builder.forms['default-' + ival] !== undefined) {
                            $builder.forms['default-' + ival] = [];
                        }
                    }
                    $scope.defaultValue = {};
                    $scope.frmvalues = [];
                    $scope.showfrms = [];
                    angular.forEach($scope.form_data, function (field_type_response) {
                        $scope.prefill_value = '';
                        if (parseInt(field_type_response.input_type_id) === ConstInputTypes.Radio) {
                            $scope.prefill_value = 'null'; // to avoid default selection of radio button(by defalut its taking first value as selected value)        
                        }
                        if (parseInt(field_type_response.input_type_id) === ConstInputTypes.Checkbox || parseInt(field_type_response.input_type_id) === ConstInputTypes.Select || parseInt(field_type_response.input_type_id) === ConstInputTypes.Radio) {
                            if (parseInt(field_type_response.input_type_id) === ConstInputTypes.Checkbox) {
                                field_type_response.input_types.value = "select";
                            }
                            angular.forEach($scope.form_data_answer, function (answer) {
                                angular.forEach(answer, function (child_answer, child_key) {
                                    dummy = child_key.split('_');
                                    if (parseInt(dummy[dummy.length - 1]) === parseInt(field_type_response.id)) {
                                        $scope.prefill_value = child_answer;
                                    }
                                });
                            });
                            var option_values;
                            if (field_type_response.options) {

                                option_values = field_type_response.options.split(",");
                                for(var i=0; i<option_values.length;i++){
                                        option_values[i] = $filter('translate')(option_values[i]); 
                                }
                                if (parseInt(field_type_response.input_type_id) !== ConstInputTypes.Radio) {
                                    option_values.splice(0, 0, "");
                                }

                            }
                            var textbox;
                            textbox = $builder.addFormObject('default-' + firstfrm, { id: field_type_response.id, component: field_type_response.input_types.value, label: $filter('translate')(field_type_response.label), description: "", placeholder: field_type_response.label, options: option_values, editable: true });
                            $scope.defaultValue[textbox.id] = $scope.prefill_value;
                            $scope.showfrms[firstfrm] = false;
                            firstfrm++;
                            $scope.isformfield = true;
                        }
                    });
                    angular.forEach($scope.form_fields, function (value) {
                        $scope.form_fields_all.push(value);
                    });
                });
            }
            
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
                            if (submit_value[0].value !== "" && submit_value[0].value !== "null") {
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
                angular.forEach($scope.services, function (value) {
                    if (value.checked === true) {
                        $scope.service_ids = value.id;
                    }
                });
                if (!$scope.searchplace) {
                    $location.path('/users').search({
                        service_id: $scope.service_ids,
                        appointment_from_date: $scope.form_date,
                        appointment_to_date: $scope.form_to_date,
                        display_type: $scope.display_type,
                        page: $scope.page,
                        zoom: 1,
                        more: $scope.temp,
                        radius: $scope.data.selectedRadius,
                        user_search_id : $location.search().user_search_id,
                        search_title : $location.search().search_title,
                        notify_id : $location.search().notify_id,
                        request_id : $location.search().request_id
                        
                    });
                }
                else if ($rootScope.countryName === $scope.searchplace) {
                    $scope.zoom = 4;
                } else if ($rootScope.countryName !== $scope.searchplace) {
                    $scope.zoom = 10;
                }
                if ($scope.searchplace) {
                    $location.path('/users').search({
                        latitude : $rootScope.form_latitude !== undefined ? $rootScope.form_latitude : $location.search().latitude,
                        longitude :  $rootScope.form_longitude!== undefined ? $rootScope.form_longitude : $location.search().longitude,
                        sw_latitude : $rootScope.sw_latitude!== undefined ? $rootScope.sw_latitude : $location.search().sw_latitude,
                        sw_longitude : $rootScope.sw_longitude!== undefined ? $rootScope.sw_longitude : $location.search().sw_longitude,
                        ne_latitude : $rootScope.ne_latitude!== undefined ? $rootScope.ne_latitude : $location.search().ne_latitude,
                        ne_longitude : $rootScope.ne_longitude!== undefined ? $rootScope.ne_longitude : $location.search().ne_longitude,
                        service_id: $scope.service_ids,
                        appointment_from_date: $scope.form_date,
                        appointment_to_date: $scope.form_to_date,
                        address: $rootScope.form_address,
                        display_type: $scope.display_type,
                        page: $scope.page,
                        zoom: $scope.zoom,
                        more: $scope.temp,
                        iso2: $rootScope.country_iso2,
                        radius: $scope.data.selectedRadius,
                        user_search_id : $location.search().user_search_id,
                        search_title : $location.search().search_title,
                        notify_id : $location.search().notify_id,
                        request_id : $location.search().request_id
                    });
                }

            }

        };


        $scope.getFormattedDates = function (date) {
            $scope.form_appointment_date = $filter('date')(date, "dd-MMM-yyyy");
        };
        $scope.getFormattedEndDates = function (date) {
            $scope.form_appointment_end_date = $filter('date')(date, "dd-MMM-yyyy");
        };
        $scope.getFormattedRecurringDates = function (date) {
            $scope.form_recurring_start_date = $filter('date')(date, "dd-MMM-yyyy");
        };
        $scope.getFormattedRecurringEndDates = function (date) {
            $scope.form_recurring_end_date = $filter('date')(date, "dd-MMM-yyyy");
        };
        $scope.loadServices = function (category_id) {
            CategoryService.get({ id: category_id }).$promise.then(function (response) {
                $scope.serviceLists = response.services;
            });
        };

        $scope.searchUsers = function (type) {
            $scope.type = type; // for displaying purpose
            $scope.searchLists = [];
            $scope.loader = true;
            $scope.NoRecords = false;
            params = {};
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 20;
            params.filter = {"order":"display_order asc","limit":$scope.itemsPerPage,"skip":$scope.skipvalue};
            params.filter.where = {};
            params.filter.include = {};
            params.filter.where.role_id ={inq:{"0" : ConstUserType.ServiceProvider, "1" : ConstUserType.User}};
            // params.filter.where.OR.push({"role_id": ConstUserType.ServiceProvider},{"role_id": ConstUserType.User});
            params.filter.where.is_active = 1;
            //service id
            if ($location.search().service_id) {
                params.filter.include.service_users = { "whereHas": { "service_id": { "inq": { "0": $location.search().service_id } } } };
            }
            params.filter.include["service_users.service"] = { "whereHas": { "is_active": 1 } };
            params.filter.include["1"] = "city";
            params.filter.include["2"] = "state";
            params.filter.include["3"] = "country";
            params.filter.include["4"] = "attachment";
            params.filter.include["5"] = "listing_photo";
            params.filter.include["6"] = "category";
            params.filter.include["7"] = "user_favorite_for_single_user";
            params.filter.include.user_profile = { "whereHas": { "listing_status_id": ConstListingStatus.Approved } };
            if(type === '' || type === undefined){
                if(($rootScope.cityName !== null && $rootScope.cityName !== undefined) || ($rootScope.stateName !== null && $rootScope.stateName !== undefined) || ($location.search().user_search_id) !== undefined || String($location.search().is_search) === "false" && $location.search().request_id === undefined){
                    params.latitude = $location.search().latitude;
                    params.longitude = $location.search().longitude;
                    params.listing_sw_latitude = $location.search().sw_latitude;
                    params.listing_sw_longitude = $location.search().sw_longitude;
                    params.listing_ne_latitude = $location.search().ne_latitude;
                    params.listing_ne_longitude = $location.search().ne_longitude;
                    $scope.zoomLevel = 10;
                }else if($location.search().iso2){
                    //while refresh when lat and long is there in params
                    $scope.zoomLevel = 4; 
                    delete params.latitude;
                    delete params.longitude;
                    delete params.listing_sw_latitude;
                    delete params.listing_sw_longitude;
                    delete params.listing_ne_longitude;
                    delete params.listing_ne_latitude;
                    params.filter.include["user_profile.listing_country"] = {"whereHas":{"iso2" : $location.search().iso2}};
                }    
            }else{
                delete params.filter.include.service_users;
                params.type = "favorite";
                $scope.zoomLevel = 1;
            }
            if($location.search().display_type){
                params.display_type = $location.search().display_type;
                delete params.filter.order;
            }
             if(parseInt($location.search().radius) !== 1 && $location.search().search_type === undefined){
                params.radius = $location.search().radius;
            }
            params.search_type = $location.search().search_type;
            if (!$rootScope.isAuth) {
                delete params.filter.include["7"];
            }
            if ($location.search().more) {
                $scope.inq = JSON.parse($location.search().more);
                if ($scope.inq.length > 0) {
                    params.filter.include.form_field_submission = {};
                    params.filter.include.form_field_submission.whereHas = { "match_operator": ">=", "match_count": $scope.inq.length };
                    params.filter.include.form_field_submission.whereHas.OR = [];
                    angular.forEach($scope.inq, function (value) {
                        angular.forEach(value, function (child_value, key) {
                            dummy = key.split('_');
                            $scope.form_field_id = dummy[dummy.length - 1];
                            params.filter.include.form_field_submission.whereHas.OR.push({ "AND": { "response": { "like": "%" + child_value + "%" }, "form_field_id": $scope.form_field_id } });

                        });
                    });
                }
            }
            if($location.search().request_id){
                params.request_id = $location.search().request_id;
            }
            params.filter = JSON.stringify(params.filter);

            //Search Function here
            UsersFactory.get(params).$promise.then(function (searchResponse) {
                $scope.center_lat = $location.search().latitude !== undefined ? $location.search().latitude : 0;
                $scope.center_lon = $location.search().longitude !== undefined ? $location.search().longitude : 0;
                $scope.page = searchResponse._metadata.current_page;
                $scope.searchLists = searchResponse.data;
                if(searchResponse._metadata){
                    $scope.currentPage = searchResponse._metadata.current_page;
                    $scope.lastPage = searchResponse._metadata.last_page;
                    $scope.itemsPerPage = searchResponse._metadata.per_page;
                    $scope.totalRecords = searchResponse._metadata.total;
                }
                angular.forEach($scope.searchLists, function (searchvalue) {
                    //angular.forEach($scope.UserFavoriteList, function(favoritevalue){
                    if (searchvalue.user_favorite_for_single_user !== null && searchvalue.user_favorite_for_single_user !== undefined) {
                        searchvalue.is_favorite = searchvalue.id === searchvalue.user_favorite_for_single_user.provider_user_id ? true : false;
                        searchvalue.favorite_id = searchvalue.id === searchvalue.user_favorite_for_single_user.provider_user_id ? searchvalue.user_favorite_for_single_user.id : null;
                    } else {
                        searchvalue.is_favorite = false;
                        searchvalue.favorite_id = null;
                    }

                    //});
                });
                $scope.searchLists = searchResponse.data;
                angular.forEach($scope.searchLists, function (value) {
                    if (angular.isDefined(value.listing_photo[0]) && value.listing_photo[0] !== null) {
                        var hash = md5.createHash(value.listing_photo[0].class + value.listing_photo[0].id + 'png' + 'listing_thumb');
                        value.userimage = 'images/listing_thumb/' + value.listing_photo[0].class + '/' + value.listing_photo[0].id + '.' + hash + '.png';
                    } else {
                        $rootScope.auth.userimage = $window.theme + 'images/default.png';
                    }

                });
                $scope.tempArray = [];
                angular.forEach($scope.searchLists,function(user_value){
                    angular.forEach(user_value.service_users, function(value){
                        $scope.tempArray.push(value.rate);
                    });
                    user_value.service_rate = Math.min.apply(Math, $scope.tempArray);
                });
                $scope.loader = false;
                //checkbox select on service
                $scope.service_change = function (index) {
                    $scope.checked = $scope.services[index].checked;
                    if ($scope.checked === true) {
                        angular.forEach($scope.services, function (value) {
                            value.checked = false;
                        });
                        $scope.services[index].checked = $scope.checked;
                        if ($scope.services[index].booking_option_id === ConstBookingType.MultipleDate) {
                            $scope.options = {autoApply:true,singleDatePicker:false,minDate:moment(new Date()).format('YYYY-MM-DD'),
                                    eventHandlers : {'hide.daterangepicker': function() { 
                                                    $scope.get_start_data = $("input[name=daterangepicker_start]");
                                                    if($scope.is_came === false && ($scope.datePicker.startDate === $scope.datePicker.endDate)&& !$scope.options.singleDatePicker){
                                                        $scope.datePicker={"startDate" : $scope.get_start_data[0].value, "endDate" : $scope.get_start_data[0].value};
                                                        $scope.form_date = $scope.get_start_data[0].value;
                                                        $scope.form_to_date = $scope.get_start_data[0].value;
                                                    }
                                                    $scope.is_came = false;
                                                    }
                           }};
                        } else {
                            $scope.options = {autoApply:true,singleDatePicker:false,minDate:moment(new Date()).format('YYYY-MM-DD')};
                        }
                    } else {
                        $scope.services[index].checked = true;
                    }
                    $scope.service_name = $scope.services[index].name; 
                    $scope.homesearch();
                };
                NgMap.getMap().then(function (map) {
                    $scope.map = map;

                });
                
                $scope.positions = [];
                // code needed
                if ($scope.searchLists !== undefined) {
                    angular.forEach($scope.searchLists, function (value) {
                        $scope.positions.push({
                            id: value.id,
                            username: value.username,
                            doctor_name: value.first_name + ' ' + value.last_name,
                            address1: value.address,
                            rating: value.starRating,
                            lat: value.user_profile.listing_latitude,
                            lon: value.user_profile.listing_longitude
                        });
                    });
                }
                $scope.showLoader = false;
                $scope.mappositions = $scope.positions;
            });
            //}, 200);

        };
        $scope.paginate_search = function(currentPage){
             $scope.currentPage = currentPage;
             $scope.searchUsers($scope.type);
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
                                appointment_from_date: $scope.form_date,
                                appointment_to_date: $scope.form_to_date,
                                address: $rootScope.form_address,
                                display_type: $scope.display_type,
                                page: $location.search().page,
                                zoom: $scope.zoomLevel,
                                iso2: $location.search().iso2,
                                radius: $location.search().radius,
                                more: $location.search().more,
                                search_type : "map" ,
                                user_search_id : $location.search().user_search_id,
                                search_title : $location.search().search_title,
                                notify_id : $location.search().notify_id,
                                request_id : $location.search().request_id,
                                is_search : false
                            });
                        }
                    }
                };
                $scope.toggleBounce = function (val) {
                    $(this).data('timeout', setTimeout(function () {
                        if (val) {
                            var marker = $scope.map.markers[val];
                            if (marker.getAnimation() === null) {
                                marker.setAnimation(google.maps.Animation.BOUNCE);
                            } else {
                                marker.setAnimation(null);
                            }
                            $timeout(function () {
                                marker.setAnimation(null);
                                marker.getAnimation(null);
                            }, 2000);
                        }
                    }, 500));

                };        
        $timeout(function () {
            $scope.form_fields_all = [];
            // prefilling data
            $rootScope.form_address = $location.search().address;
            if($location.search().appointment_to_date){
                    $scope.datePicker = { startDate: $location.search().appointment_from_date, endDate: $location.search().appointment_to_date};    
                }else{
                    $scope.datePicker = moment($location.search().appointment_from_date);
                }
            // $rootScope.form_longitude = $location.search().longitude;
            // $rootScope.form_latitude = $location.search().latitude;
            // $rootScope.sw_latitude = $location.search().sw_latitude;
            // $rootScope.sw_longitude = $location.search().sw_longitude;
            // $rootScope.ne_latitude = $location.search().ne_latitude;
            // $rootScope.ne_longitude = $location.search().ne_longitude;
            $scope.service_ids = $location.search().service_id;
            $rootScope.country_iso2 = $location.search().iso2;
            $scope.form_date = $location.search().appointment_from_date;
            $scope.form_to_date = $location.search().appointment_to_date;
            $scope.display_type = $location.search().display_type;
            $scope.page = $location.search().page;
            $scope.zoomLevel = $location.search().zoom;
            if ($location.search().more) {
                $scope.form_data_answer = JSON.parse($location.search().more);
                if($scope.form_data_answer.length > 0){
                    $('#more').addClass("in");
                }
            }
            if($location.search().address){
                $scope.data.selectedRadius = $location.search().radius !== undefined ? $location.search().radius : 50;
                $scope.enableRadius = true;
            }else{
                $scope.data.selectedRadius = 0;
            }
            $scope.user_search_id = $location.search().user_search_id !== undefined ? parseInt($location.search().user_search_id) : undefined;
            $scope.edit_search.title = $location.search().search_title !== undefined ? $location.search().search_title : undefined;
            $scope.edit_search.notify_type = $location.search().notify_id;
            $scope.is_request_job = $location.search().request_id !== undefined ? true : false;
            $scope.request_id = $location.search().request_id !== undefined ? $location.search().request_id  : undefined; 
        }, 10);
        $scope.start_dates = $stateParams.start_date;
        $scope.end_dates = $stateParams.end_date;
        $scope.map_change = function (val) {
            $cookies.put('map_label', val);
        };
        $scope.getpos = function () {
            $scope.zoomLevel = $scope.map.getZoom();
            if ($scope.data.map_label === true) {
                $scope.latlng = [$scope.map.getCenter().lat(), $scope.map.getCenter().lng()];
                $scope.center_lat = $scope.map.getCenter().lat();
                $scope.center_lon = $scope.map.getCenter().lng();
                if ($scope.latlng) {
                    $location.path('/users').search({
                        latitude: $scope.map.getCenter().lat(),
                        longitude: $scope.map.getCenter().lng(),
                        sw_latitude: $scope.map.getBounds().f.b,
                        sw_longitude: $scope.map.getBounds().b.b,
                        ne_latitude: $scope.map.getBounds().f.f,
                        ne_longitude: $scope.map.getBounds().b.f,
                        service_id: $location.search().service_id,
                        appointment_from_date: $scope.form_date,
                        appointment_to_date: $scope.form_to_date,
                        address: $rootScope.form_address,
                        display_type: $scope.display_type,
                        page: $location.search().page,
                        zoom: $scope.zoomLevel,
                        more: $location.search().more,
                        iso2: $location.search().iso2,
                        radius: $location.search().radius,
                        search_type : "map",
                        user_search_id : $location.search().user_search_id,
                        search_title : $location.search().search_title,
                        notify_id : $location.search().notify_id,
                        request_id : $location.search().request_id,
                        is_search : false
                    });
                }
            }

        };

        $scope.displayType = function (type) {
            $scope.display_type = type;
            $location.path('/users').search({
                latitude: $location.search().latitude,
                longitude: $location.search().longitude,
                sw_latitude:$location.search().sw_latitude,
                sw_longitude:$location.search().sw_longitude,
                ne_latitude: $location.search().ne_latitude,
                ne_longitude: $location.search().ne_longitude,
                service_id: $location.search().service_id,
                appointment_from_date: $scope.form_date,
                appointment_to_date: $scope.form_to_date,
                address: $rootScope.form_address,
                display_type: $scope.display_type,
                page: $scope.page,
                zoom: $location.search().zoom,
                more: $location.search().more,
                iso2: $location.search().iso2,
                radius: $location.search().radius,
                user_search_id : $location.search().user_search_id,
                search_title : $location.search().search_title,
                notify_id : $location.search().notify_id,
                request_id : $location.search().request_id,
                is_search : $location.search().is_search
            });
        };
        //User Favorite 

        $scope.UserFavorite = function (user, type) {
            params = {};
            params.user_id = user.id;
            params.username = user.username;
            if (parseInt(type) === 1) {
                user.is_favorite = false;
                EditUserFavorite.delete({ userFavoriteId: user.favorite_id }, function (response) {
                    if (response.error.code !== 0) {
                        user.is_favorite = true;
                    }
                });
            } else if (parseInt(type) === 2) {
                user.is_favorite = true;
                UserFavorite.post(params, function (response) {
                    if (response.error.code !== 0) {
                        user.is_favorite = false;
                    } else {
                        user.favorite_id = response.data.id;
                    }
                });
            }
        };
        $scope.showDetail = function (e, doctorInfo) {
            $scope.doctorInfo = doctorInfo;

        };
        
        //Save Search Function
        $scope.saveSearch = function(){
            params = {};
            params.address = $rootScope.form_address !== undefined ? $rootScope.form_address : $location.search().address;
            params.name = $rootScope.form_address !== undefined ? $rootScope.form_address : $location.search().address; 
            params.from_date = $scope.datePicker.startDate;
            params.to_date = $scope.datePicker.endDate;
            params.latitude= $rootScope.form_latitude !== undefined ? $rootScope.form_latitude : $location.search().latitude;
            params.longitude =  $rootScope.form_longitude!== undefined ? $rootScope.form_longitude : $location.search().longitude;
            params.sw_latitude = $rootScope.sw_latitude!== undefined ? $rootScope.sw_latitude : $location.search().sw_latitude;
            params.sw_longitude = $rootScope.sw_longitude!== undefined ? $rootScope.sw_longitude : $location.search().sw_longitude;
            params.ne_latitude = $rootScope.ne_latitude!== undefined ? $rootScope.ne_latitude : $location.search().ne_latitude;
            params.ne_longitude = $rootScope.ne_longitude!== undefined ? $rootScope.ne_longitude : $location.search().ne_longitude;
            params.radius = $scope.data.selectedRadius;
            params.service_id = $location.search().service_id;
            $scope.form_field_submissions = [];
                angular.forEach($scope.submission, function (submit_value) {
                    angular.forEach($scope.form_fields_all, function (childvalue) {
                        if (submit_value[0]) {
                            if (submit_value[0].value !== "" && submit_value[0].value !== "null") {
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
            params.form_field_submissions = JSON.stringify($scope.form_field_submissions);
            PostSavedSearches.post(params, function(response){
                flash.set($filter("translate")("Your search preferences saved successfully."), 'success', false);
                if(response.error.code === 0){
                    $location.path('/users').search({
                        latitude : $rootScope.form_latitude !== undefined ? $rootScope.form_latitude : $location.search().latitude,
                        longitude :  $rootScope.form_longitude!== undefined ? $rootScope.form_longitude : $location.search().longitude,
                        sw_latitude : $rootScope.sw_latitude!== undefined ? $rootScope.sw_latitude : $location.search().sw_latitude,
                        sw_longitude : $rootScope.sw_longitude!== undefined ? $rootScope.sw_longitude : $location.search().sw_longitude,
                        ne_latitude : $rootScope.ne_latitude!== undefined ? $rootScope.ne_latitude : $location.search().ne_latitude,
                        ne_longitude : $rootScope.ne_longitude!== undefined ? $rootScope.ne_longitude : $location.search().ne_longitude,
                        service_id: $location.search().service_id,
                        appointment_from_date: $scope.form_date,
                        appointment_to_date: $scope.form_to_date,
                        address: $rootScope.form_address !== undefined ? $rootScope.form_address : $location.search().address,
                        display_type: $location.search().display_type,
                        page: $location.search().page,
                        zoom: $location.search().zoom,
                        more: JSON.stringify($scope.form_field_submissions),
                        iso2: $location.search().iso2,
                        radius: $scope.data.selectedRadius,
                        user_search_id : response.id,
                        search_title : response.name,
                        notify_id : response.notification_type_id,
                        request_id : $location.search().request_id,
                        is_search : $location.search().is_search
                    });
                }
            });
            
            
        };
        $scope.getSavedSearch = function(){
            params = {};
            params.id = parseInt($location.search().user_search_id);
            GetSavedSearches.get(params, function(response){
                $scope.save_search_data = response.data;
                $scope.edit_search.title = response.data.name;
                $location.path('/users').search({
                        latitude: response.data.latitude,
                        longitude: response.data.longitude,
                        sw_latitude:response.data.sw_latitude,
                        sw_longitude:response.data.sw_longitude,
                        ne_latitude: response.data.ne_latitude,
                        ne_longitude: response.data.ne_longitude,
                        service_id: response.data.service_id,
                        address: response.data.address,
                        page: 1,
                        zoom: response.data.address !== undefined ? 10 : 1,
                        radius: response.data.radius,
                        user_search_id : response.data.id,
                        search_title : response.data.name,
                        notify_id : response.data.notification_type_id,
                        request_id : $location.search().request_id,
                        is_search : $location.search().is_search,
                        more : response.data.form_field_submissions
                    });
                
            });
        };
        $scope.getRequestJobSearch = function(){
            params = {};
            params.requestId = parseInt($location.search().request_id);
            JobRequestGet.get(params, function(response){
                $scope.save_search_data = response.data;
                $scope.edit_search.title = response.data.name;
                $location.path('/users').search({
                        latitude: response.data.work_location_latitude,
                        longitude: response.data.work_location_longitude,
                        sw_latitude:response.data.work_location_sw_latitude,
                        sw_longitude:response.data.work_location_sw_longitude,
                        ne_latitude: response.data.work_location_ne_latitude,
                        ne_longitude: response.data.work_location_ne_longitude,
                        service_id: response.data.service_id,
                        address: response.data.work_location_address,
                        zoom:  5,
                        radius: 500,
                        request_id : response.data.id,
                        is_search : $location.search().is_search
                    });
                
            });
        };
        if($location.search().service_id){
            $scope.searchUsers("");
        }else if($location.search().user_search_id && $location.search().service_id === undefined){
            $scope.getSavedSearch();
        }else if($location.search().request_id && $location.search().service_id === undefined){
            $scope.getRequestJobSearch();
        }
        $scope.openSaveSearch = function(){
            $scope.save_search_form_field_submissions = [];
                
            params = {};
            params.id = parseInt($location.search().user_search_id);
            GetSavedSearches.get(params, function(response){
                if(response.error.code === 0){
                    $scope.edit_search.notify_type = response.data.notification_type_id;
                    $scope.edit_search.title = $rootScope.form_address !== undefined ? $rootScope.form_address : response.data.title;  
                    angular.forEach($scope.submission, function (submit_value) {
                        angular.forEach($scope.form_fields_all, function (childvalue) {
                            if (submit_value[0]) {
                                if (submit_value[0].value !== "" && submit_value[0].value !== "null") {
                                    if (parseInt(submit_value[0].id) === parseInt(childvalue.id)) {
                                        var temp = {};
                                        temp.name = $filter("translate")(childvalue.label);
                                        temp.value = submit_value[0].value;
                                        $scope.save_search_form_field_submissions.push(temp);
                                    }
                                }

                            }

                        });
                    });
                    $uibModal.open({
                        animation: true,
                        ariaLabelledBy: 'modal-title',
                        ariaDescribedBy: 'modal-body',
                        templateUrl: 'editSearch.html',
                        size: "lg",
                        scope: $scope,
                        controller : function(){
                            $scope.modalClose = function(){
                                    $uibModalStack.dismissAll();
                            };
                        }
                    });
                }
            });            
               
        };
        $scope.updateSaveSearch = function(){
            params = {};
            params.id = $location.search().user_search_id;
            params.name = $scope.edit_search.title;
            params.address = $rootScope.form_address !== undefined ? $rootScope.form_address : undefined;
            params.from_date = $scope.datePicker.startDate;
            params.to_date = $scope.datePicker.endDate;
            params.latitude = $rootScope.form_latitude;
            params.longitude = $rootScope.form_longitude;
            params.sw_latitude = $rootScope.sw_latitude;
            params.sw_longitude = $rootScope.sw_longitude;
            params.ne_latitude = $rootScope.ne_latitude;
            params.ne_longitude = $rootScope.ne_longitude;
            params.radius = $scope.data.selectedRadius;
            params.service_id = $location.search().service_id;
            $scope.form_field_submissions = [];
            angular.forEach($scope.submission, function (submit_value) {
                    angular.forEach($scope.form_fields_all, function (childvalue) {
                        if (submit_value[0]) {
                            if (submit_value[0].value !== "" && submit_value[0].value !== "null") {
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
            params.form_field_submissions =JSON.stringify($scope.form_field_submissions);
            params.notification_type_id = $scope.edit_search.notify_type;
            if($scope.edit_search.search_method === "update"){
                SavedSearches.put(params, function(response){
                    if(response.error.code === 0){
                        $uibModalStack.dismissAll();
                    }
                });
            }else if($scope.edit_search.search_method === "new"){
                PostSavedSearches.post(params, function(response){
                    if(response.error.code === 0){
                        $uibModalStack.dismissAll();
                    }
                });
            }
            
           
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
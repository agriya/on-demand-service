 'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:HomeController
 * @description
 * # HomeController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
.controller('RequestController', ['$scope', '$rootScope', '$window', '$filter', '$state', '$timeout', '$cookies', 'Category', 'UsersFactory', '$builder', '$validator', 'Country', 'ConstBookingType', 'JobRequestPost', 'RequestStatusId', 'JobTypeId', 'ConstAppintmentTimingType', 'flash', 'ConstPaymentType', "JobRequestGet", function($scope, $rootScope, $window, $filter, $state, $timeout, $cookies, Category, UsersFactory, $builder, $validator, Country, ConstBookingType, JobRequestPost, RequestStatusId, JobTypeId, ConstAppintmentTimingType, flash, ConstPaymentType, JobRequestGet) {
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
            $state.go('user_profile', { type: 'personal' });        }
        $rootScope.subHeader = "Post a job"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.BookingType = ConstBookingType;
        $scope.is_job_edit = false;
        $scope.show_submit = false;
        // $scope.oneTime = false;
        // $scope.regular = false;
        // $scope.home = true;
        //$scope.showService = false;
        $scope.oneTime = true;$scope.regular = false;$scope.home = false;$scope.showService = true;//(same code in job view page need to change there tooo)
        $scope.serviceSelected = false;
        $scope.overnight_booking = false;
        $scope.same_day_booking = true;
        $scope.about_user = false;
        $scope.timeError = false;
        $scope.rateError = false;
        $rootScope.JobTypeId = JobTypeId;
        $scope.dayTimeError = false; // for regular time 
        $scope.booking_time = {"start":moment("18:00:00", 'HH:mm:ss'),"end":moment("22:00:00", 'HH:mm:ss')};
        $scope.request_details = {};
        $scope.paymenttype=[{"id":1,"name":"pay via credit card"},{"id":2,"name":"pay cash"}];
        $scope.time_commitment=[{"id":2,"name":"Part Time"},{"id":3,"name":"Full Time"}];
        $scope.time_type=[{"id":0,"name":"Please select an option"},{"id":1,"name":"Specify times"},{"id":2,"name":"During the day"},{"id":3,"name":"During the night"}];
        $scope.request_details.payment_type = ConstPaymentType.ThroughSite;
        // $scope.request_details.job_type_id = JobTypeId.PartTime;
        $scope.request_details.job_type_id = JobTypeId.OneTimeJob;
        $scope.request_details.time_type = 0;
        $scope.fromDate =  $scope.toDate = moment(new Date());
        $scope.request_details.list_of_days = [{'id':'sun_regular',"text" : "Sun","checked":false},{'id':'mon_regular',"text" : "Mon","checked":false},{'id':'tue_regular',"text" : "Tue","checked":false},{'id':'wed_regular',"text" : "Wed","checked":false},{'id':'thu_regular',"text" : "Thu","checked":false},{'id':'fri_regular',"text" : "Fri","checked":false},{'id':'sat_regular',"text" : "Sat","checked":false}];
        $scope.services = [];
        var params = {};
        params.filter = '{"where":{"is_active":1},"include":{"2":"service.form_field_groups.form_fields.input_types","service.form_field_groups":{"where":{"is_belongs_to_service_provider":"0"}},"service":{"whereHas":{"is_active":1}},"4":"form_field_groups.form_fields.input_types","form_field_groups":{"where":{"is_belongs_to_service_provider":"0"}}}}';
        Category.categoryList(params).$promise.then(function (response) {
                $scope.categories = response.data;
                angular.forEach(response.data, function(value){
                        angular.forEach(value.service, function(childvalue){
                                $scope.services.push(childvalue);
                        });
                });
                $scope.request_details.selected_service_id = $scope.services[0].id;
        });
         //country,city restriction
        $scope.country = [];
            $scope.cities = [];
            if ($rootScope.settings.ALLOWED_SERVICE_LOCATIONS) {
                $scope.locations = JSON.parse($rootScope.settings.ALLOWED_SERVICE_LOCATIONS);
                angular.forEach($scope.locations.allowed_countries, function (value) {
                    $scope.country.push(value.iso2);
                });
                angular.forEach($scope.locations.allowed_cities, function (value) {
                    $scope.cities.push(value.name);
                });
            }

            $scope.address_options = {
                types: [], componentRestrictions: { country: $scope.country }
            };
            $rootScope.makePlaceTrue = function () {
            $rootScope.allowedplace = false;
        };
        $scope.basis_options = {autoApply:true};
        params = {};
        params.filter = '{"limit":500,"skip":0}';
        Country.get(params, function (response) {
            $scope.countries = response.data;
        });
        $scope.get_service = function(selectedService){
                $scope.serviceSelected = true;
                $scope.showService = false;
                $scope.form_data = [];
                
                //getting form fields from service and category
                 angular.forEach($scope.services, function(service_value){
                 if(parseInt(service_value.id) === parseInt(selectedService)){
                        $scope.need_location = parseInt(service_value.is_need_user_location) === 1 ? true : false;
                        $scope.get_numberof_items = parseInt(service_value.is_allow_to_get_number_of_items) === 1 ? true : false;
                        $scope.booking = service_value.booking_option_id;
                        $scope.category_id = service_value.category_id;
                        $scope.selectedServiceName = service_value.name;
                        angular.forEach(service_value.form_field_groups, function(value){
                        if(value.form_fields.length > 0){
                                $scope.form_data.push(value);
                        }
                        });
                        if ($scope.get_numberof_items) {
                                $scope.totalCounts = [];
                                $scope.label_value = service_value.label_for_number_of_item;
                                for (var i = 0; i < parseInt(service_value.maximum_number_to_allow); i++) {
                                        $scope.totalCounts.push({ "id": i + 1, "text": (i + 1) + " " + $scope.label_value});
                                }
                                $scope.request_details.count = $scope.totalCounts[0].id;
                                $scope.booking_count = parseInt($scope.selectedCount) + " " + $scope.label_value;
                        }
                }
                });
                if(parseInt($scope.booking) === parseInt($scope.BookingType.TimeSlot)){
                    $scope.placeholder = $filter("translate")("Select date");
                    $scope.timeplaceholder = $filter("translate")("Select time");
                }else{
                    $scope.placeholder = $filter("translate")("Select start date");
                    $scope.timeplaceholder = $filter("translate")("Select start time");
                }
                if(parseInt($scope.booking) === parseInt($scope.BookingType.TimeSlot)){
                    $scope.booking_time = {"start":$scope.booking_time.start === undefined ? moment("18:00:00", 'HH:mm:ss') : moment($scope.booking_time.start, "HH:mm:ss")};
                }else if(parseInt($scope.booking) === parseInt($scope.BookingType.MultiHours)){
                    $scope.booking_time = {"start":$scope.booking_time.start === undefined ? moment("18:00:00", 'HH:mm:ss') : moment($scope.booking_time.start, "HH:mm:ss"), "end":$scope.booking_time.end === undefined ? moment("22:00:00", 'HH:mm:ss') : moment($scope.booking_time.end,"HH:mm:ss")};
                    // $scope.booking_time = {"start":moment("18:00:00", 'HH:mm:ss'),"end":moment("22:00:00", 'HH:mm:ss')};
                }else{
                    $scope.booking_time = {};
                }
                
                angular.forEach($scope.categories, function(category_value){
                        if(parseInt(category_value.id) === parseInt($scope.category_id)){
                                angular.forEach(category_value.form_field_groups, function(value){
                                if(value.form_fields.length > 0){
                                        $scope.form_data.push(value);
                                        }
                                });
                        }
                });
                $timeout(function(){
                    if(!$scope.selected_serviceId){
                        $scope.selected_serviceId = selectedService; 
                    }else{
                        if($scope.selected_serviceId !== selectedService){
                            if(parseInt($scope.booking) === parseInt($scope.BookingType.MultipleDate)){
                                $scope.fromDate = {'startDate' :  moment(new Date()),'endDate' :  moment(new Date())};
                            }else{
                                $scope.fromDate =  $scope.toDate = moment(new Date());
                            }
                            //$scope.booking_time = {"start":moment("18:00:00", 'HH:mm:ss'),"end":moment("22:00:00", 'HH:mm:ss')};
                        }else{
                            if(parseInt($scope.booking) === parseInt($scope.BookingType.MultipleDate)){
                                $scope.fromDate=  {"startDate":$scope.request_details.from_date !== (undefined && null) ? moment(new Date($scope.request_details.from_date)): moment(new Date()),endDate:$scope.request_details.to_date !== (undefined && null) ? moment(new Date($scope.request_details.to_date)): moment(new Date())};    
                            }else{
                                $scope.fromDate =  $scope.request_details.from_date !== (undefined && null) ? moment(new Date($scope.request_details.from_date)): moment(new Date());
                                $scope.toDate = $scope.request_details.to_date !== (undefined && null) ? moment(new Date($scope.request_details.to_date)): moment(new Date());
                            }   

                                
                        }
                        $scope.selected_serviceId = selectedService;
                    }
                },0);
                if($scope.booking === $scope.BookingType.MultipleDate) {
                    $scope.options = {autoApply:true,singleDatePicker:false};
                } else {
                    $scope.options.singleDatePicker = true;
                }
                if($scope.regular === true){
                    if($state.current.name !== "JobEdit"){
                        $scope.toDate = '';
                    }
                    
                }
                $scope.showDateError = false;
        };
        $scope.options = { minDate: moment(new Date()).format('YYYY-MM-DD'),singleDatePicker:true};
        $scope.regular_options = { minDate: moment(new Date()).format('YYYY-MM-DD'),singleDatePicker:true,autoApply:true};
        $scope.get_time_details = function(){
                $scope.showDateError = false;
                if(new Date($scope.end_date) < new Date($scope.start_date)){
                    $scope.showDateError = true;
                    return;
                }
                if(parseInt($scope.booking) === parseInt($scope.BookingType.MultiHours) || parseInt($scope.booking) === parseInt($scope.BookingType.TimeSlot)){            
                    if(parseInt($scope.booking) === parseInt($scope.BookingType.TimeSlot)){
                        $scope.booking_time.end = $scope.booking_time.start; 
                    }
                    if(parseInt($scope.booking) === parseInt($scope.BookingType.MulriHours)){
                        if (moment($scope.booking_time.end).format('HH:mm:ss') <= moment($scope.booking_time.start).format('HH:mm:ss') || !$scope.booking_time.start || !$scope.booking_time.end) {
                                $scope.timeError = true;
                                return;
                        }
                    }
                    
                }
                $scope.request_details.from_date = $scope.start_date;
                $scope.request_details.to_date = $scope.end_date !== undefined ? $scope.end_date : $scope.start_date;
                if(parseInt($scope.booking) === parseInt($scope.BookingType.MultiHours) || parseInt($scope.booking) === parseInt($scope.BookingType.TimeSlot)){
                    $scope.request_details.from_time = $scope.booking_time.start;
                    $scope.request_details.show_from_time = moment($scope.booking_time.start).format('hh:mm a');
                    $scope.request_details.to_time = $scope.booking_time.end;
                    $scope.request_details.show_to_time = moment($scope.booking_time.end).format('hh:mm a');
                }
                if($scope.overnight_booking === true){
                        $scope.request_details.booking_type = 2;
                }else{
                        $scope.request_details.booking_type = 1;
                }
                $scope.about_user = true; 
                $scope.oneTime = false;
                $scope.form_fields_all = [];
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
                angular.forEach($scope.form_data, function(form_value){
                    $scope.form_fields = form_value.form_fields;
                    if ($scope.form_fields !== '') {
                        angular.forEach($scope.form_fields, function (field_type_response) {
                            angular.forEach($scope.request_details.form_field_submission, function(value){
                                $scope.prefillValue = parseInt(value.form_field.id) === parseInt(field_type_response.id) ? value.response : '';
                            });
                            var option_values;
                            if (field_type_response.options) {
                                option_values = field_type_response.options.split(",");
                                for(var i=0; i<option_values.length;i++){
                                    option_values[i] = $filter('translate')(option_values[i]); 
                                }
                            }
                            var textbox;
                            textbox = $builder.addFormObject('default-' + firstfrm, {
                                id: field_type_response.id,
                                component: field_type_response.input_types.value,
                                label: $filter('translate')(field_type_response.label),
                                description: "",
                                placeholder: field_type_response.label,
                                required: field_type_response.is_required,
                                options: option_values,
                                editable: true
                            });
                            $scope.defaultValue[textbox.id] = $scope.prefillValue;
                            $scope.showfrms[firstfrm] = false;
                            firstfrm++;
                            $scope.isformfield = true;
                        });
                        angular.forEach($scope.form_fields, function(value){
                            value.title = form_value.name;
                            $scope.form_fields_all.push(value);
                        });
                        $scope.firstfrm = firstfrm;
                    }

                });
                $scope.is_oneTime = true;
        };
       
        $scope.$watch("fromDate", function (date) {
                if (date) {
                    if (date.endDate) {
                        $scope.end_date = $filter('date')(date.endDate._d, "yyyy-MM-dd");
                        $scope.start_date = $filter('date')(date.startDate._d, "yyyy-MM-dd");
                    } else {
                        $scope.start_date = $filter('date')(date._d, "yyyy-MM-dd");
                    }
                }
        });
        $scope.$watch("toDate", function (date) {
                $scope.end_date = date !== undefined ?  $filter('date')(date._d, "yyyy-MM-dd") : undefined;
        });
       
        $scope.changeTiming = function(){
                if(parseInt($scope.booking) !== parseInt($scope.BookingType.MultipleDate)){
                    $scope.fromDate = moment($scope.request_details.from_date);
                    if($scope.request_details.from_date !== $scope.request_details.to_date){
                            $scope.toDate = moment($scope.request_details.to_date);
                    }
                }else{
                    $scope.fromDate = {'startDate':moment($scope.request_details.from_date),'endDate':moment($scope.request_details.to_date)};
                }    
                $scope.booking_time.start = $scope.request_details.from_time;
                $scope.booking_time.end = $scope.request_details.to_time;
                if($scope.request_details.booking_type === 2){
                     $scope.overnight_booking =true;
                     $scope.same_day_booking = false;
                }
                $scope.about_user = false; 
                $scope.oneTime = true;//for div display
                $scope.is_oneTime =false; // for date_display 
        };
        $scope.get_requestor_info = function($is_valid,type){
            // if(!$scope.request_details.rate || $scope.request_details.rate === ''){
            //     $scope.rateError = true;
            //     return;
            // }
            
            $scope.error = false;
            angular.forEach($scope.form_fields_all, function(value, key){
                var validate_forms = $validator.validate($scope, 'default-' + (key+1));
                validate_forms.error(function(){
                    $scope.error = true;
                });
            });
            $timeout(function(){
                if($scope.error === true){
                  return;  
                }else{
                    if($scope.cities.length > 1){
                        $rootScope.allowedplace = $scope.cities.indexOf($scope.user_profile.listing_city.name) === -1 ? true : false;
                    }else{
                        $rootScope.allowedplace = false;
                    }
                }    
                if($rootScope.allowedplace === false && $is_valid){
                        $scope.payment_mode = parseInt($scope.request_details.payment_type) === 1 ? "credit" : "cash";
                        var params = {};
                        params.service_id = $scope.request_details.selected_service_id;
                        params.request_status_id = RequestStatusId.Open;
                        params.job_type_id = $scope.request_details.job_type_id;
                        params.appointment_from_date = $scope.request_details.from_date;
                        params.appointment_to_date = $scope.request_details.to_date !== (undefined || null) ? $scope.request_details.to_date : $scope.request_details.from_date;
                        if ($scope.BookingType.MultiHours === $scope.booking) {
                                params.appointment_from_time = moment($scope.booking_time.start).format('HH:mm:ss');
                                params.appointment_to_time = moment($scope.booking_time.end).format('HH:mm:ss');
                        } else if ($scope.BookingType.TimeSlot === $scope.booking) {
                                params.appointment_from_time = moment($scope.booking_time.start).format('HH:mm:ss');
                        }else {
                            params.appointment_from_time = moment("00:00:00", "HH:mm:ss").format('HH:mm:ss');
                            params.appointment_to_time = moment("23:59:59", "HH:mm:ss").format('HH:mm:ss');
                        }
                        params.work_location_address = $scope.request_details.address;
                        params.work_location_address1 = $scope.request_details.address1 !== (undefined || null) ? $scope.request_details.address1 : undefined;
                        params.work_location_city = $scope.request_details.city;
                        params.work_location_state = $scope.request_details.state;
                        params.work_location_country = $scope.request_details.country;
                        params.work_location_postal_code = $scope.request_details.postal_code;
                        params.work_location_latitude = $scope.request_details.latitude;
                        params.work_location_longitude = $scope.request_details.longitude;
                        params.work_location_ne_latitude = $scope.request_details.ne_latitude;
                        params.work_location_ne_longitude = $scope.request_details.ne_longitude;
                        params.work_location_sw_latitude = $scope.request_details.sw_latitude; 
                        params.work_location_sw_longitude = $scope.request_details.sw_longitude; 
                        // params.payment_mode_id = $scope.request_details.payment_type;
                        params.payment_mode_id = 1; //pay by card
                        params.price_per_hour = $scope.request_details.rate;
                        if(parseInt($scope.request_details.time_type) !== 0 && $scope.request_details.time_type !== null){
                            params.appointment_timing_type_id = $scope.request_details.time_type;
                        }
                        params.number_of_item = $scope.request_details.count;
                        params.description = $scope.request_details.more_details;
                        if($scope.request_details.time_type === ConstAppintmentTimingType.SpecifyTimes){
                            angular.forEach($scope.request_details.list_of_days, function(day){
                                if(day.checked === true){
                                    if(day.text === "Sun"){
                                        params.is_sunday_needed = 1;
                                        params.sunday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.sunday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }else if(day.text === "Mon"){
                                        params.is_monday_needed = 1;
                                        params.monday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.monday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }else if(day.text === "Tue"){
                                        params.is_tuesday_needed = 1;
                                        params.tuesday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.tuesday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }else if(day.text === "Wed"){
                                        params.is_wednesday_needed = 1;
                                        params.wednesday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.wednesday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }else if(day.text === "Thu"){
                                        params.is_thursday_needed = 1;
                                        params.thursday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.thursday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }else if(day.text === "Fri"){
                                        params.is_friday_needed = 1;
                                        params.friday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.friday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }else if(day.text === "Sat"){
                                        params.is_saturday_needed = 1;
                                        params.saturday_appointment_from_time = moment(day.start).format("HH:mm:ss");
                                        params.saturday_appointment_to_time = moment(day.end).format("HH:mm:ss");
                                    }
                                }

                            });
                        }
                        params.form_field_submissions = [];
                        angular.forEach($scope.request_details.submission, function(submit_value){
                            if(submit_value.value !== ""){
                                var temp = {};
                                    temp[submit_value[0].id] = submit_value[0].value;
                                    params.form_field_submissions.push(temp);
                            }
                        });
                            $scope.errorData = [];
                            $scope.show_submit = true;
                            if(type === "new"){
                                JobRequestPost.post(params, function(response){
                                    $scope.show_submit = false;
                                if(response.error.code === 0){
                                    
                                    flash.set($filter("translate")("Job Posted Successfully."), 'success', false);
                                    $state.go('JobListing');
                                }else{
                                    flash.set($filter("translate")("Please provide valid details"), 'error', true);   
                                }
                                },function errorResponse(response){
                                    flash.set($filter("translate")(response.data.error.message), 'error', true);
                                    if(response.data){
                                        if(response.data.error.raw_message.error){
                                            angular.forEach(response.data.error.raw_message.error, function(raw_value){
                                                angular.forEach(raw_value, function(errordata){
                                                    $scope.errorData.push(errordata);
                                                });
                                            });
                                        }
                                    }
                                });
                            }else{
                                params.requestId = $state.params.job_id;
                                JobRequestGet.put(params, function(response){
                                    $scope.show_submit = false;
                                if(response.error.code === 0){
                                    flash.set($filter("translate")("Job updated successfully."), 'success', false);
                                    $state.go('JobListing');
                                }else{
                                    flash.set($filter("translate")("Please provide valid details"), 'error', true);   
                                }
                                },function errorResponse(response){
                                     $scope.show_submit = false;
                                    flash.set($filter("translate")(response.data.error.message), 'error', true);
                                    if(response.data){
                                        if(response.data.error.raw_message.error || response.data.error.raw_message){
                                            var temp = (response.data.error.raw_message.error || response.data.error.raw_message);
                                            angular.forEach(temp, function(raw_value){
                                                angular.forEach(raw_value, function(errordata){
                                                    $scope.errorData.push(errordata);
                                                });
                                            });
                                        }
                                    }
                                });
                            }  
                            
                }
           });
           
           
        };
        $scope.no_address_available = false;
         $scope.addresslocationChanged = function () {
            $scope.IsAddressPlaceChange = false;
            if ($scope.request_details.address !== undefined) {
                $scope.no_address_available = false;
                angular.forEach($scope.request_details.address.address_components, function (value) {
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        $scope.request_details.city = {};
                        $scope.request_details.city.name = value.long_name;
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        $scope.request_details.state = {};
                        $scope.request_details.state.name = value.long_name;
                    }
                    if (value.types[0] === 'country') {
                        $scope.request_details.country = {};
                        $scope.request_details.country.iso2 = value.short_name;
                        $scope.request_details.country.name = value.long_name;
                        $scope.selectedAddtionalCountry = value.short_name;
                    }
                    if (value.types[0] === 'postal_code') {
                        $scope.request_details.postal_code = parseInt(value.long_name);
                    }

                });
                if ($scope.request_details.address.address_components) {
                    $scope.IsAddressPlaceChange = true;
                    $scope.request_details.latitude = $scope.request_details.address.geometry.location.lat();
                    $scope.request_details.longitude = $scope.request_details.address.geometry.location.lng();
                    $scope.request_details.ne_latitude = $scope.request_details.address.geometry.viewport.f.f;
                    $scope.request_details.ne_longitude = $scope.request_details.address.geometry.viewport.b.f;
                    $scope.request_details.sw_latitude = $scope.request_details.address.geometry.viewport.f.b;
                    $scope.request_details.sw_longitude = $scope.request_details.address.geometry.viewport.b.b;
                    $scope.request_details.full_address = $scope.request_details.address.name + " " + $scope.request_details.address.vicinity;
                    $scope.request_details.address = $scope.request_details.address.formatted_address;
                }else{
                    $scope.no_address_available = true;
                }
            } else {
                $scope.IsAddressPlaceChange = true;
            }
        };
        $scope.countChanged = function (count) {
            //for displaying purpose in screen
            $scope.booking_count = parseInt(count) + " " + $scope.label_value;
        };
        //for "on regular basis"
        $scope.validate_time = function(index){
            if (moment($scope.request_details.list_of_days[index].end).format('HH:mm:ss') <= moment($scope.request_details.list_of_days[index].start).format('HH:mm:ss') || !$scope.booking_time.start || !$scope.booking_time.end) {
                $scope.dayTimeError = true;
                return;
            }else{
                $scope.dayTimeError = false;
            }
        };
        $scope.about_schedule = function(){
            if($scope.request_details.time_type === 0){
                return;
            }
            $scope.is_various = false;
            $scope.is_day_checked = false;
            $scope.request_details.from_date = $scope.start_date;
            $scope.is_null = false;
            $scope.request_details.to_date = $scope.end_date !== undefined ? $scope.end_date : $scope.start_date;
            angular.forEach($scope.request_details.list_of_days, function(value){
                if(value.checked === true && $scope.is_various === false){
                    $scope.regular_start = moment(value.start).format('hh:mm a');
                    $scope.regular_end = moment(value.end).format('hh:mm a');
                    if(value.start === null || value.start === undefined || value.end === undefined || value.end === null){
                        $scope.is_null = true; 
                    }
                    angular.forEach($scope.request_details.list_of_days, function(child_start_value){
                        if(child_start_value.checked){
                            $scope.is_day_checked = true;
                            if(moment(value.start).format('HH:mm:ss') === moment(child_start_value.start).format('HH:mm:ss')){
                                $scope.is_various = false;
                            }else{
                                $scope.is_various = true;
                            }
                        }
                    });
                    if($scope.is_various === false){
                       angular.forEach($scope.request_details.list_of_days, function(child_end_value){
                           if(child_end_value.checked){
                                if(moment(value.end).format('HH:mm:ss') === moment(child_end_value.end).format('HH:mm:ss')){
                                    $scope.is_various = false;
                                }else{
                                    $scope.is_various = true;
                                }
                           }
                        }); 
                    }
                }
            });
            if($scope.is_null === true){
                $scope.dayTimeError = true;
                return;
            }
            $scope.about_user = true; 
            $scope.regular = false;
            $scope.is_regular = true;
        };
        $scope.changeRegularTiming = function(){
            $scope.fromDate = moment($scope.request_details.from_date);
            if($scope.request_details.from_date !== $scope.request_details.to_date){
                $scope.toDate = moment($scope.request_details.to_date);
            }
            $scope.regular = true;
            $scope.about_user = $scope.is_regular = false;
        };
        //job edit 
        if($state.current.name === "JobEdit"){
                $scope.is_job_edit = true;
                params = {};
                params.requestId= $state.params.job_id;
                params.filter='{"include":{"0":"user.attachment","1":"user.user_profile","3":"form_field_submission.form_field.form_field_group", "4" : "current_user_interest","5":"service","6":"work_location_city","7":"work_location_state","8":"work_location_country"}}';
                JobRequestGet.get(params).$promise.then(function (viewResponse) {
                    if(viewResponse.error.code === 0){
                        $timeout(function(){
                            $scope.request_details.selected_service_id = $scope.job.service_id;
                        }, 10);
                        $scope.job = viewResponse.data;
                        $scope.selected_serviceId = $scope.job.service_id;
                        $scope.request_status_id = $scope.job.request_status_id;
                        $scope.request_details.job_type_id = $scope.job.job_type_id;
                        $scope.request_details.from_date = $scope.job.appointment_from_date;
                        $scope.request_details.to_date = $scope.job.appointment_to_date;
                        $scope.booking_time.start = $scope.job.appointment_from_time;
                        $scope.booking_time.end = $scope.job.appointment_to_time; 
                        $scope.request_details.address = $scope.job.work_location_address; 
                        $scope.request_details.address1 = $scope.job.work_location_address1; 
                        $scope.request_details.city = $scope.job.work_location_city; 
                        $scope.request_details.state = $scope.job.work_location_state;
                        $scope.request_details.country = $scope.job.work_location_country;
                        $scope.selectedAddtionalCountry = $scope.job.work_location_country.iso2;
                        $scope.request_details.postal_code = $scope.job.work_location_postal_code; 
                        $scope.request_details.latitude = $scope.job.work_location_latitude;
                        $scope.request_details.longitude = $scope.job.work_location_longitude; 
                        $scope.request_details.ne_longitude = $scope.job.work_location_ne_latitude;
                        $scope.request_details.ne_longitude = $scope.job.work_location_ne_longitude;
                        $scope.request_details.sw_latitude = $scope.job.work_location_sw_latitude; 
                        $scope.request_details.sw_longitude = $scope.job.work_location_sw_longitude;
                        $scope.request_details.payment_type = $scope.job.payment_mode_id;
                        $scope.request_details.time_type = $scope.job.appointment_timing_type_id; 
                        $scope.request_details.rate = $scope.job.price_per_hour; 
                        $scope.request_details.count = $scope.job.number_of_item; 
                        $scope.request_details.more_details = $scope.job.description;
                        $scope.request_details.form_field_submission = $scope.job.form_field_submission;
                    }
                    
                });
                  
            }
            
            
}]);


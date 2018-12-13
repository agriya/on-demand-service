'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserController
 * @description
 * # UserController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserController
     * @description
     *
     * This is user controller having the methods setmMetaData, init, upload and user_profile.
     **/
    //this controller is using for both user_enquiry.html and user_address_location.html
    .controller('UserEnquiryController', function ($scope, $state, $rootScope, BookAppoinment, flash, $filter, UsersFactory, $window, md5, $cookies, Country, $builder, Service, ConstBookingType, $validator, $timeout) {
        $rootScope.subHeader = "Bookings & messages"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        var params = {};
        $scope.userInfo = {};
        $scope.form_data = [];
        $scope.ConstBookingType = ConstBookingType;
        params.filter = '{"include":{"0":"attachment","1":"user_profile","2":"service_users.service.form_field_groups.form_fields.input_types","service_users.service.form_field_groups":{"where":{"is_belongs_to_service_provider":"0"}},"4":"category.form_field_groups.form_fields.input_types","category.form_field_groups":{"where":{"is_belongs_to_service_provider":"0"}}}}';

        UsersFactory.get(params,{username: $state.params.user_id}, function(response){
            $scope.user = response.data;
            if (angular.isDefined($scope.user.attachment) && $scope.user.attachment !== null) {
                var hash = md5.createHash($scope.user.attachment.class + $scope.user.attachment.id + 'png' + 'small_thumb');
                $scope.user.userimage = 'images/small_thumb/' + $scope.user.attachment.class + '/' + $scope.user.attachment.id + '.' + hash + '.png';
            } else {
                $scope.user.userimage = $window.theme + 'images/default.png';
            }
            params= {};
        params.filter = '{"limit":500,"skip":0,"where":{"is_active":1}}';
        Service.get(params, function(response){
            $scope.services = response.data;
            angular.forEach($scope.services, function(value){
                if(parseInt($state.params.service_id) === parseInt(value.id)){
                    $scope.servicename = value.name;
                    $scope.booking = parseInt(value.booking_option_id);
                    $scope.need_location = parseInt(value.is_need_user_location) === 1 ? true : false;
                }
            });
                    angular.forEach($scope.user.category.form_field_groups, function(value){
                    if(value.form_fields.length > 0){
                        $scope.form_data.push(value);
                    }
                });
                angular.forEach($scope.user.service_users, function(value){
                    if(parseInt(value.service_id) === parseInt($state.params.service_id)){
                        angular.forEach(value.service.form_field_groups, function(childvalue){
                            if(childvalue.form_fields.length > 0){
                                $scope.form_data.push(childvalue);
                            }
                        }); 
                    }
                });
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

                            // var obj = {};
                            // obj.id = field_type_response.id;
                            // obj.value = field_type_response.name;
                            // $scope.frmvalues.push(obj);
                            $scope.defaultValue[textbox.id] = '';
                            $scope.showfrms[firstfrm] = false;
                            //$builder.forms['default-' + firstfrm];
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
        });
           
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

            $scope.options = {
                types: [], componentRestrictions: { country: $scope.country }
            };
            $rootScope.makePlaceTrue = function () {
            $rootScope.allowedplace = false;
        };

        
        params = {};
        params.filter = '{"limit":500,"skip":0}';
        Country.get(params, function (response) {
            $scope.countries = response.data;
        });
         var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
         $scope.username = $state.params.slug;
         $scope.from_date = $state.params.from_date;
         $scope.to_date = $state.params.to_date;
         $scope.from_time = $state.params.from_time;
         $scope.to_time = $state.params.to_time;
         $scope.amount = $state.params.amount;
         $scope.type = $state.params.type;
         //for caluculating no of days    
         $scope.firstDate = new Date($state.params.from_date);
         $scope.secondDate = new Date($state.params.to_date);
         $scope.diffDays = Math.round(Math.abs(($scope.firstDate.getTime() - $scope.secondDate.getTime())/(oneDay)));
         $scope.userInfo.enquiry_message = " Hi there, I'm looking for a great home assistant. Looking forward to your response!";
         $scope.sendEnquiry = function(){
             $scope.appoinment = {};
             $scope.appoinment.services_user_id = $state.params.services_user_id;
             $scope.appoinment.appointment_from_date = $state.params.from_date;
             $scope.appoinment.appointment_to_date =   $state.params.to_date;
             $scope.appoinment.appointment_from_time = $state.params.from_time;
             $scope.appoinment.appointment_to_time = $state.params.to_time;
             $scope.appoinment.appointment_status_id = 8; //8 for enquiry
             $scope.appoinment.customer_note = $scope.enquiry_message;
             if($state.params.request_id !== undefined){
                        $scope.appointment.request_id = $state.params.request_id;
                    }
             BookAppoinment.post($scope.appoinment,function(response){
                if(response.error.code === 0){
                    if(parseInt(response.appointment_status_id) === 10 && $rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('PaymentBooking/PaymentBooking') > -1){
                            $state.go('PaymentGateway', {'class':'appointments','id':response.id});
                        }
                        else {
                            flash.set($filter("translate")("Appointment booked successfully."), 'success', false);
                            $scope.showDateError = false;
                            $scope.showServiceError = false;
                            $scope.datePicker = '';
                            $scope.selectedService = '';
                            $state.go('UserView', {"user_id":$state.params.user_id,"slug":$state.params.slug});
                        }
                    }else if(response.error.code === 1){
                        flash.set($filter("translate")(response.error.message), 'error', false);
                    }
            },function(errorMessage){
                     if(errorMessage.data.error){
                            if(errorMessage.data.error.fields){
                                if(errorMessage.data.error.fields.appointment_from_date){
                                    flash.set($filter("translate")(errorMessage.data.error.fields.appointment_from_date[0]), 'error', false);
                                }else{
                                    flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                                }
                            }else{
                                flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                            }
                        }
                });
         };
         $scope.no_address_available = false;
         $scope.addresslocationChanged = function () {
            $scope.IsAddressPlaceChange = false;
            if ($scope.userInfo.address !== undefined) {
                $scope.no_address_available = false;
                angular.forEach($scope.userInfo.address.address_components, function (value) {
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        $scope.userInfo.city = {};
                        $scope.userInfo.city.name = value.long_name;
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        $scope.userInfo.state = {};
                        $scope.userInfo.state.name = value.long_name;
                    }
                    if (value.types[0] === 'country') {
                        $scope.userInfo.country = {};
                        $scope.userInfo.country.iso2 = value.short_name;
                        $scope.userInfo.country.name = value.long_name;
                        $scope.selectedAddtionalCountry = value.short_name;
                    }
                    if (value.types[0] === 'postal_code') {
                        $scope.userInfo.postal_code = parseInt(value.long_name);
                    }

                });
                if ($scope.userInfo.address.address_components) {
                    $scope.IsAddressPlaceChange = true;
                    $scope.userInfo.latitude = $scope.userInfo.address.geometry.location.lat();
                    $scope.userInfo.longitude = $scope.userInfo.address.geometry.location.lng();
                    $scope.userInfo.full_address = $scope.userInfo.address.name + " " + $scope.userInfo.address.vicinity;
                    $scope.userInfo.address = $scope.userInfo.address.formatted_address;
                }else{
                    $scope.no_address_available = true;
                }
            } else {
                $scope.IsAddressPlaceChange = true;
            }
        };
        $scope.submit_form_data = function(){
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
            if($rootScope.allowedplace === false){
                $scope.appointment = {};
                $scope.appointment.form_field_submissions = [];
                angular.forEach($scope.userInfo.submission, function(submit_value){
                    angular.forEach($scope.form_fields_all, function(childvalue){
                        if(parseInt(submit_value[0].id) === parseInt(childvalue.id)){
                            var temp = {};
                            temp[childvalue.id] = submit_value[0].value;
                            $scope.appointment.form_field_submissions.push(temp); 
                        }
                    });
                });
                
                $scope.appointment.services_user_id = $state.params.services_user_id;
                $scope.appointment.appointment_from_date = $state.params.from_date;
                $scope.appointment.appointment_to_date =  $state.params.to_date;
                $scope.appointment.number_of_item = $scope.selectedCount;
                if($scope.need_location){
                    $scope.appointment.work_location_address = $scope.userInfo.address;
                    $scope.appointment.work_location_address1 = $scope.userInfo.address1;
                    $scope.appointment.work_location_city_id = $scope.userInfo.city.id;
                    $scope.appointment.work_location_state_id = $scope.userInfo.state.id;
                    $scope.appointment.work_location_country_id = $scope.userInfo.country.id;
                    $scope.appointment.work_location_postal_code = $scope.userInfo.postal_code;
                    $scope.appointment.work_location_city = $scope.userInfo.city;
                    $scope.appointment.work_location_state = $scope.userInfo.state;
                    $scope.appointment.work_location_country = $scope.userInfo.country;
                }
                
                if(ConstBookingType.MultiHours === $scope.booking){
                    $scope.appointment.appointment_from_time = $state.params.from_time;
                    $scope.appointment.appointment_to_time = $state.params.to_time;
                }else if(ConstBookingType.TimeSlot === $scope.booking){
                    $scope.appointment.appointment_from_time = $state.params.from_time;
                }
                if($state.params.type === 'enquiry'){
                    $scope.appointment.customer_note = $scope.userInfo.enquiry_message;
                    $scope.appointment.appointment_status_id = 8; //8 for enquiry status
                }
                else{
                    $scope.appointment.appointment_status_id = 10; //10 for payment_pending
                }
                if($state.params.request_id !== undefined){
                        $scope.appointment.request_id = $state.params.request_id;
                    }
                BookAppoinment.post($scope.appointment,function(response){
                    if(response.error.code === 0){
                        if(parseInt(response.appointment_status_id) === 10 && $rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('PaymentBooking/PaymentBooking') > -1){
                            $state.go('PaymentGateway', {'class':'appointments','id':response.id});
                        }else{
                            flash.set($filter("translate")("Appointment booked successfully."), 'success', false);
                            $state.go('UserView', {"user_id":$state.params.user_id,"slug":$state.params.slug});
                        }
                    }else {
                        flash.set($filter("translate")(response.error.message), 'error', false);
                    }
                },function(errorMessage){
                     if(errorMessage.data.error){
                            if(errorMessage.data.error.fields){
                                if(errorMessage.data.error.fields.appointment_from_date){
                                    flash.set($filter("translate")(errorMessage.data.error.fields.appointment_from_date[0]), 'error', false);
                                }else{
                                    flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                                }
                            }else{
                                flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                            }
                        }
                });
            }   

                }
            },5);
                 
            
        };
        $scope.addActiveBar = function (id, name) {
            var found = $filter('filter')($scope.activeBar, {
                id: id
            }, true);
            if (found.length && name === null) {
                found[0].class = 'active';
            } else if (found.length && name === 'inactive') {
                found[0].class = '';
            } else if (found.length === 0 && name !== null && name !== 'inactive') {
                $scope.activeBar.push({
                    class: '',
                    id: id,
                    title: name
                });
            }
        };
        //dynamic form process
        
    });
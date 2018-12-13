'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:ServiceController
 * @description
 * # ServiceController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:ServiceController
     * @description
     *
     * This is dashboard controller. It contains all the details about the user. It fetches the data of the user by using AuthFactory.
     **/
    .controller('ServiceController', function ($scope, $filter, UserProfilesFactory, UsersFactory, $rootScope, $state, $http, flash, MyServices, CancellationPolicy, ConstListingStatus, $cookies, ConstService, $timeout, ConstInputTypes, $builder, $validator, Category) {
        $scope.ConstService = ConstService;
        $rootScope.ConstInputTypes = ConstInputTypes;
        $scope.form_answwer ={};
        $scope.form_answer = [];
        $scope.interview = {};
        
        $scope.interview.policy_id = 1;
        $rootScope.ListingStatus = ConstListingStatus;
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
            $state.go('user_profile', { type: 'personal' });
        }
        $scope.services = [];
        $scope.hourlyRate = {};
        $scope.serviceErrorMsg = false;
        $scope.showServiceCategoryError = false;
        $scope.atleast_one_service = false;
        $scope.tempValue = false;//for disabling submit button
        $rootScope.subHeader = "Listing"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.user = {};
        
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Services and Rates");
            $scope.aside = {
                "title": "Title",
                "content": "Hello Aside<br />This is a multiline message!"
            };

            var params = {};
            params.filter = '{"include":{"0":"service_user.service.form_field_groups.form_fields.input_types","service_user.service.form_field_groups":{"where":{"is_belongs_to_service_provider":"1"}},"2":"service_user.user.form_field_submission.form_field.input_types"}}';
            // {"where":{"is_belongs_to_service_provider":"1"},"include":{"1":"form_fields"}}
            // params.filter = '{"include":{"0":"service_users.service.form_field_groups":{"where":{"is_belongs_to_service_provider":"1"}}}}';
            // .form_fields.input_types
            MyServices.get(params,function (response) {
              
                $scope.field_detail = [];
                $scope.show_fields = [];
                $scope.form_fields = [];
                angular.forEach(response.data, function(values) {
                 angular.forEach(values.service_user, function(value) {
                     if(value.service.form_field_groups.length > 0){
                         values.is_show_form = true;
                        //  return;
                     }else{
                         values.is_show_form = false;
                     }
                     $scope.field_detail.push(value);
                 });
                });
                $scope.services = response.data;
                params = {};
                params.filter = '{"include":{"0":"service_users","1":"user_profile","2":"category", "3":"form_field_submission.form_field"}}';
                UsersFactory.get(params, { username: $rootScope.auth.id }, function (response) {
                    $scope.userInfo = response.data;
                    $scope.interview.phone_number = $scope.userInfo.phone_number;
                    $scope.interview.mobile_code = $scope.userInfo.mobile_code;
                        angular.forEach(response.data.service_users, function (service) {
                            angular.forEach($scope.services, function (value) {
                                if (parseInt(service.service_id) === parseInt(value.id)) {
                                    $scope.serviceErrorMsg = false;
                                    value.is_checked = true;
                                    value.rate = service.rate;
                                    value.policy_id = service.cancellation_policy_id;
                                    $scope.tempValue = true;
                                }
                            });
                        });
                        $scope.form_data = [];
                        angular.forEach($scope.field_detail, function (values) {
                            angular.forEach(values.service.form_field_groups, function (value) {
                                $scope.is_there = false;
                                //checking whether data already in the array
                                //This array for form_field_group
                                angular.forEach($scope.form_data, function (form_value) {
                                    if (parseInt(value.id) === form_value.id) {
                                        $scope.is_there = true;
                                    }
                                });
                                if ($scope.is_there === false) {
                                    $scope.form_data.push(value);
                                }
                                angular.forEach(value.form_fields, function (form_field_value) {
                                    $scope.is_form_there = false;
                                    //checking whether data already in the array
                                    //This array for form_field
                                    angular.forEach($scope.form_fields, function (child_value) {
                                        if (parseInt(form_field_value.id) === parseInt(child_value.id)) {
                                            $scope.is_form_there = true;
                                        }
                                    });
                                    if ($scope.is_form_there === false) {
                                        $scope.form_fields.push(form_field_value);
                                    }
                                });
                            });
                        });
                        
                        angular.forEach($scope.userInfo.form_field_submission, function(value){
                            angular.forEach($scope.form_fields, function(child_value){
                                if(parseInt(value.form_field_id) === parseInt(child_value.id)){
                                    $scope.show_fields.push(value);
                                }
                            });
                        });
                        $scope.defaultValue = {};
                        $scope.frmvalues = [];
                        $scope.showfrms = [];
                        var firstfrm = 1;
                        if ($scope.form_fields !== '') {
                            for (var ival = 1; ival < 10; ival++) {
                                /** to do form empty  */
                                if ($builder.forms['default-' + ival] !== undefined) {
                                    $builder.forms['default-' + ival] = [];
                                }
                            }
                            //angular.forEach($scope.form_fields, function (type_response) {
                            angular.forEach($scope.form_fields, function (field_type_response) {
                                var option_values;
                                if (field_type_response.options) {
                                    option_values = field_type_response.options.split(",");
                                    for (var i = 0; i < option_values.length; i++) {
                                        option_values[i] = $filter('translate')(option_values[i]);
                                    }
                                }
                                var textbox;
                                textbox = $builder.addFormObject('default-' + firstfrm, {
                                    id: field_type_response.id,
                                    // name: field_type_response.name,
                                    component: field_type_response.input_types.value,
                                    label: field_type_response.label,
                                    description: "",
                                    placeholder: $filter("translate")(field_type_response.label),
                                    required: field_type_response.is_required,
                                    options: option_values,
                                    editable: true
                                });

                                $scope.frmvalues[textbox.id] = field_type_response.name;
                                $scope.showfrms[firstfrm] = false;
                                // $builder.forms['default-' + firstfrm];
                                firstfrm++;
                                $scope.isformfield = true;
                            });
                            angular.forEach($scope.show_fields, function(data) {
                                if(data.form_field.input_type_id !== 4){
                                    $scope.defaultValue[data.form_field_id] = $filter('translate')(data.response);
                                }else{
                                    var checkbox_values = data.form_field.options.split(',');
                                    angular.forEach(checkbox_values, function(value){
                                        value = $filter('translate')(value); 
                                    });
                                    var checked_values = data.response.split(',');
                                    angular.forEach(checked_values, function(value){
                                        value = $filter('translate')(value); 
                                    }); 
                                    var correct_checkbox_values = [];
                                    //For removing space in corrected_values
                                    $scope.check = function(values){
                                        angular.forEach(values,function(value){
                                            var val = value.trim();
                                            correct_checkbox_values.push(val);
                                        });
                                    };
                                    $scope.check(checked_values);
                                    var overall_checked = [];
                                angular.forEach(checkbox_values,function(default_option){
                                            if ($.inArray(default_option, correct_checkbox_values) !== -1) {
                                        overall_checked.push(true);
                                        } else {
                                        overall_checked.push(false);
                                            }
                                });
                                    checkbox_values = data.response.split(',');
                                    angular.forEach(checked_values, function(value){
                                        value = $filter('translate')(value); 
                                    });
                                    $scope.defaultValue[data.form_field_id] = overall_checked;
                                }
                            });					
							$scope.form_fields_all = $scope.form_fields;
							$scope.firstfrm = firstfrm;
				    }
                    angular.forEach($scope.userInfo.service_users, function(value){
                        if(parseInt(value.service_id) === parseInt(ConstService.Interview)){
                            $scope.interview.interview_available = parseInt($scope.userInfo.user_profile.is_available_for_interview) === 1 ? true : false;
                            $scope.interview.is_person_interview = parseInt($scope.userInfo.user_profile.is_available_via_in_person_interview) === 1 ? true : false;
                            $scope.interview.is_phone_interview = parseInt($scope.userInfo.user_profile.is_available_via_phone_interview) === 1 ? true : false;
                            $scope.interview.is_skype_interview = parseInt($scope.userInfo.user_profile.is_available_via_skype_interview) === 1 ? true : false;
                            $scope.interview.skype_id = $scope.userInfo.user_profile.im_skype;
                            $scope.interview.rate = value.rate;
                            $scope.interview.policy_id = value.cancellation_policy_id;
                        }
                    });
                });
                
                    
            });
            
        };
        //CancellationPolicy list
        CancellationPolicy.get(function (response) {
            $scope.policies = response.data;
        });
        $scope.checkService = function (selectedService, index) {
            $scope.tempValue = false;
            $scope.services[index].policy_id = $scope.policies[0].id;
            $scope.serviceErrorMsg = false;
            angular.forEach($scope.services, function (value) {
                if (value.is_checked === true && value.id) {
                    $scope.tempValue = true;
                }
            });
            if (selectedService === ConstService.Service_3) {
                if ($scope.services[index].is_checked === true) {
                    if ($scope.tempValue === false) {
                        $scope.serviceErrorMsg = true;
                        $scope.services[index].is_checked = false;
                    } else {
                        $scope.serviceErrorMsg = false;
                        $scope.atleast_one_service = true;
                    }
                }

            } else {
                if ($scope.tempValue === false) {
                    $scope.services[2].is_checked = false;
                }

            }
        };
        if($rootScope.auth.category_id !== null && $rootScope.auth.category_id !== undefined && $rootScope.auth.category_id !== ""){
            $scope.init();
        }else{
            var params = {};
            params.filter = '{"limit":500,"skip":0}';
            Category.get(params, function(response){
                $scope.categories = response.data;
            });
        }
        $scope.changeInterviewAvailable = function(is_checked){
            if(is_checked === true){
                $scope.interview.is_phone_interview = true;
            }
        };
        
        $scope.UpdateService = function ($valid) {
            $scope.error = false;
             angular.forEach($scope.form_fields_all, function(value, key){
                        $validator.validate($scope, 'default-' + (key+1)).error(function(){
                            $scope.error = true;
                        });
                    });
            $timeout(function(){
                if($scope.error === true){
                    return;  
                }else{
                    //timeout for this will get run after form field required checking process
                    if ($valid) {
                        $scope.service = {};
                        $scope.service.service_user = [];
                        angular.forEach($scope.services, function (value) {
                            if (value.is_checked === true && value.id !== ConstService.Service_3) {
                                $scope.service.service_user.push({ "service_id": value.id, "rate": value.rate, "cancellation_policy_id": value.policy_id });
                            }
                        });

                        $scope.service.form_field_submissions = [];
                        angular.forEach($scope.form_answer, function(answer_value){
                            if(answer_value[0].value !== ""){
                                var temp = {};
                                temp[answer_value[0].id] = answer_value[0].value; 
                                $scope.service.form_field_submissions.push(temp);
                            }
                        });
                        //interview directive 
                        if($scope.interview.interview_available){
                            $scope.service.service_user.push({ "service_id": ConstService.Interview, "rate": $scope.interview.rate, "cancellation_policy_id": $scope.interview.policy_id });
                            $scope.service.is_available_for_interview = 1;
                            $scope.service.is_available_via_skype_interview = $scope.interview.is_skype_interview === true ? 1 : 0;
                            if($scope.interview.is_skype_interview){
                                $scope.service.im_skype = $scope.interview.skype_id;
                            }
                            $scope.service.is_available_via_phone_interview = $scope.interview.is_phone_interview === true ? 1 : 0;
                            $scope.service.is_available_via_in_person_interview = $scope.interview.is_person_interview === true ? 1 : 0;
                        }
                        UserProfilesFactory.update($scope.service).$promise.then(function (response) {
                            if (response.error.code === 0) {
                                flash.set($filter("translate")("Profile has been updated."), 'success', true);
                            }
                        }, function (errorResponse) {
                            flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
                        });
                    }
                }
            },5);        
            
        };
        $scope.getCategory = function(selectedCategory){
            if(!selectedCategory){
                $scope.showServiceCategoryError = true;
                return;
            }
            $scope.showServiceCategoryError = false;
            var params = {};
            params.category_id = selectedCategory;
            UserProfilesFactory.update(params, function(response){
                if(response.error.code === 0){
                    $scope.Authuser = {
                        id: $rootScope.auth.id,
                        username: $rootScope.auth.username,
                        role_id: $rootScope.auth.role_id,
                        refresh_token: $rootScope.auth.token,
                        attachment: $rootScope.auth.attachment,
                        is_profile_updated: $rootScope.auth.is_profile_updated,
                        affiliate_pending_amount : $rootScope.auth.affiliate_pending_amount,
                        category_id : selectedCategory,
                        user_profile : $rootScope.auth.user_profile,
                        blocked_user_count : $scope.auth.blocked_user_count
                    };
                }
                $cookies.put('auth', JSON.stringify($scope.Authuser), {
                        path: '/'
                });
                $rootScope.auth = JSON.parse($cookies.get('auth'));
                $scope.init();
            });

        };
    });
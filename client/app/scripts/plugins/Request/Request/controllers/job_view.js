'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    .controller('JobViewController', function ($scope, $location, $window, $rootScope, $filter,NgMap, $state, $timeout, $cookies, UsersFactory, md5, ConstListingStatus, ConstService, JobRequestGet, ConstBookingType, Service, CategoryService, RequestStatusId, JobTypeId, ConstAppintmentTimingType, ConstPaymentType,flash, ExpressInterest, RemoveInterest, SweetAlert) {
        $rootScope.JobTypeId = JobTypeId;
        $rootScope.AppointmentTimingType = ConstAppintmentTimingType;
        $rootScope.ConstPaymentType = ConstPaymentType;
        $rootScope.RequestStatusId = RequestStatusId;
        $scope.enableRadius = false;
        $scope.buttonText = "Already applied";
        $scope.options = {};
        var params = {};
        $scope.submission = [];
        $scope.options.singleDatePicker = true;
        $rootScope.allowedplace = false;
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
            $state.go('user_profile', { type: 'personal' });        }
        $rootScope.subHeader = "Job Board"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader(); 
            params.requestId= $state.params.job_id;
            params.filter='{"include":{"0":"user.attachment","1":"user.user_profile","3":"form_field_submission.form_field.form_field_group","4":"current_user_interest","5":"service","6":"requests_users.user.attachment","7":"requests_users.user.user_profile"}}';
            JobRequestGet.get(params).$promise.then(function (viewResponse) {
                $scope.job = viewResponse.data;
                $scope.job.user.created_at = moment($scope.job.user.created_at).format('YYYY-MM-DD'); 
                $scope.job.appointment_from_date = moment($scope.job.appointment_from_date).format('YYYY-MM-DD');
                $scope.job.appointment_to_date = moment($scope.job.appointment_to_date).format('YYYY-MM-DD');
                $scope.job.appointment_from_time = moment($scope.job.appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                $scope.job.appointment_to_time = moment($scope.job.appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                if($scope.job.user.attachment){
                    var hash = md5.createHash($scope.job.user.attachment.class + $scope.job.user.attachment.id + 'png' + 'big_thumb');
                    $scope.job.requestor_image = 'images/big_thumb/' + $scope.job.user.attachment.class + '/' + $scope.job.user.attachment.id + '.' + hash + '.png';
                } else {
                    $scope.job.requestor_image  = $window.theme + 'images/default.png';
                }
                $scope.job.list_of_days = [{'id':'sun_search',"text" : "Sun","checked":false},{'id':'mon_search',"text" : "Mon","checked":false},{'id':'tue_search',"text" : "Tue","checked":false},{'id':'wed_search',"text" : "Wed","checked":false},{'id':'thu_search',"text" : "Thu","checked":false},{'id':'fri_search',"text" : "Fri","checked":false},{'id':'sat_search',"text" : "Sat","checked":false}];
                angular.forEach($scope.job.list_of_days, function(value){
                    if(value.text === "Sun"){
                        value.checked = parseInt($scope.job.is_sunday_needed) === 1 ? true :false;
                        if(value.checked === true){
                            value.start = moment($scope.job.sunday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.sunday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }else if(value.text === "Mon"){
                        value.checked = parseInt($scope.job.is_monday_needed) === 1 ? true :false;
                        if(value.checked === true){
                            value.start = moment($scope.job.monday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.monday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }else if(value.text === "Tue"){
                        value.checked = parseInt($scope.job.is_tuesday_needed) === 1 ? true :false;
                         if(value.checked === true){
                            value.start = moment($scope.job.tuesday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.tuesday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }else if(value.text === "Wed"){
                        value.checked = parseInt($scope.job.is_wednesday_needed) === 1 ? true :false;
                         if(value.checked === true){
                            value.start = moment($scope.job.wednesday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.wednesday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }else if(value.text === "Thu"){
                        value.checked = parseInt($scope.job.is_thursday_needed) === 1 ? true :false;
                         if(value.checked === true){
                            value.start = moment($scope.job.thursday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.thursday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }else if(value.text === "Fri"){
                        value.checked = parseInt($scope.job.is_friday_needed) === 1 ? true :false;
                         if(value.checked === true){
                            value.start = moment($scope.job.friday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.friday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }else if(value.text === "Sat"){
                        value.checked = parseInt($scope.job.is_saturday_needed) === 1 ? true :false;
                         if(value.checked === true){
                            value.start = moment($scope.job.saturday_appointment_from_time, 'HH:mm:ss').format('hh:mm a');
                            value.end = moment($scope.job.saturday_appointment_to_time, 'HH:mm:ss').format('hh:mm a');
                        }
                    }    
                });
                if($state.current.name === "JobEdit") {
                    $scope.editJob();
                }
                angular.forEach($scope.job.requests_users, function(child_value){
                    if(child_value.user.attachment){
                        var hash = md5.createHash(child_value.user.attachment.class + child_value.user.attachment.id + 'png' + 'normal_thumb');
                            child_value.interestor_image = 'images/normal_thumb/' + child_value.user.attachment.class + '/' + child_value.user.attachment.id + '.' + hash + '.png';
                        } else {
                            child_value.interestor_image  = $window.theme + 'images/default.png';
                        }
                    });
                    if($scope.job.requests_user_count < 6){
                        for(var i = $scope.job.requests_user_count; i < 6;i++){
                            $scope.job.requests_users[i] ={"id" : 0};
                        }
                    }
            });
            $scope.ExpressInterest = function(requestId) {
                params = {};
                params.request_id = requestId;
                ExpressInterest.post(params, function(response){
                    if(response.error.code === 0){
                        flash.set($filter("translate")("Successfully Applied."), 'success', false);
                        $scope.job.current_user_interest = {"id": response.id,"request_id":response.request_id,"user_id":response.user_id};
                    }
                });

            };
            $scope.RemoveInterest = function(requestId){
                params = {};
                params.requestsUserId = requestId;
                RemoveInterest.delete(params, function(response){
                    if(response.error.code === 0){
                        flash.set($filter("translate")("Interest removed successfully."), 'success', false);
                        $scope.job.current_user_interest = null;
                    }
            });

        };
            $scope.closeJob = function(jobId){
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
                                    $scope.job.request_status_id = RequestStatusId.Closed;
                                }
                            });
                        }
                    });
                
            };
            $scope.editJob = function(){
                  $state.go('JobEdit', { job_id: $scope.job.id});
            };
            $scope.ChangeButtonText = function(type){
                if(type === "Enter"){
                    $scope.buttonText = "Remove Interest";
                }else{
                    $scope.buttonText = "Already applied";
                }
            };
            
    });
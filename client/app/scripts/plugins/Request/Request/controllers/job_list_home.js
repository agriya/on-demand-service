'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:HomeController
 * @description
 * # HomeController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
.controller('RequestListController', ['$scope', '$rootScope', '$window', '$filter', '$state', '$timeout', '$cookies', 'Category', 'UsersFactory', '$builder', '$validator', 'Country', 'ConstBookingType','JobRequestMe','JobRequestGet','JobRequestPost','JobTypeId','RequestStatusId', 'md5', 'ConstAppintmentTimingType', 'ConstPaymentType', 'flash', 'SweetAlert', function($scope, $rootScope, $window, $filter, $state, $timeout, $cookies, Category, UsersFactory, $builder, $validator, Country, ConstBookingType,JobRequestMe,JobRequestGet,JobRequestPost,JobTypeId,RequestStatusId, md5, ConstAppintmentTimingType, ConstPaymentType, flash, SweetAlert) {
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
            $state.go('user_profile', { type: 'personal' });        
        }
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("My Posted Jobs");
        $scope.job_type = "Open Jobs";
        $scope.RequestStatusId = RequestStatusId;
        $rootScope.subHeader = "Post a Job"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $rootScope.JobTypeId = JobTypeId;
        $rootScope.AppointmentTimingType = ConstAppintmentTimingType;
        $rootScope.ConstPaymentType = ConstPaymentType;
        $scope.currentPage = 1;
        $scope.lastPage = 1;
        $scope.request_status_id = 1;
        $scope.jobTypes = [{'id': $scope.RequestStatusId.Open, "text":$filter("translate")("Open Jobs")},{'id': $scope.RequestStatusId.Closed, "text":$filter("translate")("Closed Jobs")}];
        var params = {};
        $scope.getJobList = function(request_status_id){
            if($scope.RequestStatusId.Open === parseInt(request_status_id)){
                $scope.job_type = "Open Jobs";
            }else{
                $scope.job_type = "Closed Jobs";
            }
            params = {};
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
            params.filter = {"limit":$scope.itemsPerPage,"skip":$scope.skipvalue, "order": "id desc"};
            params.filter.include = {};
            params.filter.include["0"] = "user.attachment";
            params.filter.include["1"] = "user.user_profile";
            params.filter.include["2"] = "user.form_field_submission.form_field.form_field_group";
            params.filter.include["3"] = "service";
            params.filter.include["4"] = "requests_users.user.attachment";
            params.filter.include["5"] = "requests_users.user.user_profile";
            params.filter.where = {"request_status_id" : request_status_id}; 
            params.filter = JSON.stringify(params.filter);
            JobRequestMe.get(params, function(response){
                $scope.jobList = response.data;
                if(response._metadata){
                    $scope.currentPage = response._metadata.current_page;
                    $scope.lastPage = response._metadata.last_page;
                    $scope.itemsPerPage = response._metadata.per_page;
                    $scope.totalRecords = response._metadata.total;
                }
                angular.forEach($scope.jobList, function(value){
                    value.appointment_from_date = new Date(value.appointment_from_date);
                    value.appointment_to_date = new Date(value.appointment_to_date);
                    if(value.user.attachment){
                        var hash = md5.createHash(value.user.attachment.class + value.user.attachment.id + 'png' + 'big_thumb');
                        value.requestor_image = 'images/big_thumb/' + value.user.attachment.class + '/' + value.user.attachment.id + '.' + hash + '.png';
                    } else {
                        value.requestor_image  = $window.theme + 'images/default.png';
                    }
                    value.list_of_days = [{'id':'sun_search',"text" : "Sun","checked":false},{'id':'mon_search',"text" : "Mon","checked":false},{'id':'tue_search',"text" : "Tue","checked":false},{'id':'wed_search',"text" : "Wed","checked":false},{'id':'thu_search',"text" : "Thu","checked":false},{'id':'fri_search',"text" : "Fri","checked":false},{'id':'sat_search',"text" : "Sat","checked":false}];
                });
                angular.forEach($scope.jobList, function(value) {
                    angular.forEach(value.requests_users, function(child_value) {
                        if(child_value.user.attachment) {
                            var hash = md5.createHash(child_value.user.attachment.class + child_value.user.attachment.id + 'png' + 'normal_thumb');
                            child_value.interestor_image = 'images/normal_thumb/' + child_value.user.attachment.class + '/' + child_value.user.attachment.id + '.' + hash + '.png';
                        } else {
                            child_value.interestor_image  = $window.theme + 'images/default.png';
                        }
                    });
                    if(value.requests_user_count < 6){
                        for(var i = value.requests_user_count; i < 6;i++){
                            value.requests_users[i] ={"id" : 0};
                        }
                    }
                });
                angular.forEach($scope.jobList, function(search_value){
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
            });
        };
        $scope.getJobList($scope.RequestStatusId.Open);
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
                                    $scope.jobList[index].request_status_id = RequestStatusId.Closed;
                                }
                            });
                        }
                    });
            
        };
        $scope.gotoSearch = function(request_job){
            $scope.form_field_submissions = [];
            angular.forEach(request_job.form_field_submission, function(value){
                    var temp = {};
                    value.dummy_name = "f_f_" + value.form_field_id;
                    temp[value.dummy_name] = value.response;
                    $scope.form_field_submissions.push(temp);
            });
            $state.go('search', {
                latitude : request_job.work_location_latitude,
                longitude : request_job.work_location_longitude,
                sw_latitude : request_job.work_location_sw_latitude,
                sw_longitude : request_job.work_location_sw_longitude,
                ne_latitude : request_job.work_location_ne_latitude,
                ne_longitude :request_job.work_location_ne_longitude,
                service_id : request_job.service_id,
                appointment_from_date : moment(request_job.appointment_from_date).format('YYYY-MM-DD'),
                appointment_to_date : moment(request_job.appointment_to_date).format('YYYY-MM-DD'),
                address : request_job.work_location_address,
                more  : JSON.stringify($scope.form_field_submissions), 
                page : 1,
                zoom : 10,
                radius : 50,
                request_id : request_job.id
            });
        };
        $scope.paginate_search = function(currentPage){
             $scope.currentPage = currentPage;
             $scope.getJobList($scope.request_status_id);
        };
        
       
}]);
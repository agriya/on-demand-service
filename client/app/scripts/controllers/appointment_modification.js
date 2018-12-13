'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:AppointmentsModificationController
 * @description
 * # AppointmentsModificationController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
 .controller('AppointmentsModificationController', function ($scope, $state, $filter, $rootScope, flash, ConstUserType, appointmentModification, appointmentModificationAdd, appointmentModificationEdit, appointmentModificationDelete, SweetAlert, UsersFactory, ConstBookingType) {
            $scope.loaded = false;
            var params = {};
            $scope.options = {};
            $scope.currentPage = 1;
            $scope.modificationList = [];
        $scope.options.singleDatePicker = true;
            params.filter = '{"include":{"0":"user_profile","1":"attachment","13":"appointment_settings","2":"service_users.service"}}';
            UsersFactory.get(params,{username: $rootScope.auth.id}).$promise.then(function (response) {
            $scope.user = response.data;
            angular.forEach(response.data.service_users, function(value){
                if(parseInt(value.service.booking_option_id) === ConstBookingType.TimeSlot || parseInt(value.service.booking_option_id) === ConstBookingType.MultiHours){
                    $scope.calendar_slot = $scope.user.appointment_settings.calendar_slot_id;
                    $scope.loaded = true;
                } 
            });
            
            });
            $scope.getStartTime = function(){
                    if(!$scope.settingValue.unavailable_from_time){
                        $scope.settingValue.unavailable_from_time = moment("18:00:00", 'HH:mm:ss');
                    }
                };
                $scope.getEndTime = function(){
                    if(!$scope.settingValue.unavailable_to_time){
                        $scope.settingValue.unavailable_to_time = moment("22:00:00", 'HH:mm:ss');
                    }
                };
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Appointment Modification");
            $scope.ConstUserType = ConstUserType;
            if($state.current.name === 'appointmentModification'){
                $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
                $scope.getAppointmentModification();
            }
        };
        if($state.current.name === 'appointmentModificationAdd'){
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Add Modification Details");
            $scope.init = function(){
                $scope.dateBlockeBefore = $filter('date')(new Date(), "yyyy-MM-ddTHH:mm:ss.sssZ");
                // splitedTimeSlot.get().$promise.then(function (response){
                //     $scope.timeSlot = response.data;
                // });
            };
            $scope.init();
           
            $scope.appointmentModificationAdd = function(){
                $scope.settings = {};
                $scope.settings.type = 0;
                $scope.settings.unavailable_date = moment($scope.settingValue.datePicker).format('YYYY-MM-DD');
                $scope.settings.unavailable_from_time = moment($scope.settingValue.unavailable_from_time).format('HH:mm:ss');
                $scope.settings.unavailable_to_time = moment($scope.settingValue.unavailable_to_time).format('HH:mm:ss');
                appointmentModificationAdd.add($scope.settings).$promise.then(function (response) {
                    if(response.error.code === 0){
                        flash.set($filter("translate")("Manage Additional Unavailable Times added successfully."), 'success', false);
                        $state.go('appointmentModification');
                    }else{
                        flash.set($filter("translate")("Manage Additional Unavailable Times could not be added."), 'success', false);
                    }
                });
            };
        } else if($state.current.name === 'appointmentModificationEdit'){
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Edit Modification Details");
            appointmentModificationEdit.get({id: $state.params.id}).$promise.then(function (response) {
                $scope.settingValue = response;
                $scope.settingValue.type = 0;
                if(response.unavailable_from_time !==''){
                    var practiceOpen = response.unavailable_from_time;
                    $scope.settingValue.appt_time = practiceOpen.split(',');
                    
                }else{
                    $scope.settingValue.appt_time = '';
                }
                
                /* Get the Time Splited Slot */
                // splitedTimeSlot.get().$promise.then(function (response){
                //     $scope.timeSlot = response.data;
                // });
            });
            
            $scope.appointmentModificationEdit = function(){
                appointmentModificationEdit.update({id: $state.params.id},$scope.settingValue).$promise.then(function (response) {
                    flash.set($filter("translate")(response.Success), 'success', true);
                    $state.go('appointmentModification');
                });
            };
        }
        
        $scope.swithcOn = true;
        $scope.swithcOff = false;
        $scope.getAppointmentModification = function () {
            var params = {}; 
            $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
            params.filter = '{"limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"where":{"type":0}}';
            appointmentModification.get(params).$promise.then(function (response) {
                $scope.modificationList = response.data;
                if(response._metadata){
                    $scope.currentPage = response._metadata.current_page;
                    $scope.lastPage = response._metadata.last_page;
                    $scope.itemsPerPage = response._metadata.per_page;
                    $scope.totalRecords = response._metadata.total;
                }

            });
        };

         /**
         * @ngdoc method
         * @name paginate
         * @methodOf appointments.controller:AppointmentsController
         * @description
         *
         * This method will be load pagination the pages.
         **/
        $scope.paginate = function () {
            $scope.currentPage = parseInt($scope.currentPage);
            $scope.getAppointmentModification();
        };
        $scope.init();
        $scope.AptModificationDelete = function (id) {
            SweetAlert.swal({
                title: $filter("translate")("Are you sure you want to delete?"),
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Confirm",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    appointmentModificationDelete.delete({appointmentModificationId: id}).$promise.then(function (response) {
                        if (response.error.code === 0) {
                            $state.reload();
                            flash.set($filter("translate")("Manage Additional Unavailable Times deleted successfully."), 'success', false);
                        } else {
                            flash.set($filter("translate")("Manage Additional Unavailable Times could not be deleted."), 'error', false);
                        }
                    });
                }
            });
        };
         $scope.paginate_modification = function(currentPage) {
            $scope.currentPage = currentPage;
            $scope.getAppointmentModification();
        };
    });
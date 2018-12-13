'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:AppointmentsController
 * @description
 * # AppointmentsController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
.controller('AppointmentsController', function ($scope, $state, $filter, $rootScope,appointmentStatusDetails,AppointmentStatus,$location, CategoryService,flash, ConstUserType, ConstAppointmentStatus, SweetAlert, AppointmentFactory, appointmentView, changeStatus, ConstService, $window, md5, $cookies,ReviewFactory,ReviewUpdate, Service, $uibModal, $uibModalStack, appointmentPut, $timeout) {
        if($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0){
            $state.go('user_profile',{type:'personal'});
        }
        if(parseInt($state.params.error_code) === 0){
            flash.set($filter("translate")("Booking successfully completed."), 'success', true);
        }else if(parseInt($state.params.error_code) === 1){
            flash.set($filter("translate")("Booking could not be completed. Please try again."), 'error', false);
        }
        $scope.maxSize = 5;
        $scope.enabled = false;
        $scope.showClose = false;
        $scope.review = [];
        $scope.loader = false;
        $scope.acceptLoader = false;
        $scope.deleteLoader = false;
        $rootScope.subHeader = "Bookings & messages"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        $scope.user_review = [];
        $scope.user_review_count = 0;
        $scope.enable_review = true;
        $scope.showEdit = true;
        $scope.showUpdate = false;
        $scope.currentPage = 1;
        $scope.lastPage = 1;
        $scope.bonus_appointment = {};
        
        var temp={};
        temp.filter='{"where":{"is_active":1}}';
        Service.get(temp, function(response){
            $scope.services = response.data;
        });
        var params = {};
        //Select appointment status List
        $scope.appoinment_status = [];
        $scope.appoinment_status.push(
            { 'id': 1, "name": $filter("translate")('Booked and Waiting for Accept') },
            { 'id': 8, "name": $filter("translate")('Enquiry') },
            { 'id': 9, "name": $filter("translate")('Pre-approved') },
            { 'id': 2, "name": $filter("translate")('Confirmed') },
            { 'id': 7, "name": $filter("translate")('In Progress') },
            { 'id': 13, "name": $filter("translate")('Completed') },
            { 'id': 5, "name": $filter("translate")('Declined') },
            { 'id': 6, "name": $filter("translate")('Expired') },
            { 'id': 3, "name": $filter("translate")('Closed') },
            { 'id': 4, "name": $filter("translate")('Cancelled') }
        );
        $rootScope.ConstService = ConstService;   
        $scope.getAppointmentDoctorList = function (id) {
            params = {};
            if(id !== undefined && id !== ''){
                if($rootScope.auth.role_id === ConstUserType.ServiceProvider){
                    params = {'page': $scope.currentPage,'appointment_status_id':id, 'service_provider_id':$rootScope.auth.id};
                }
                else if($rootScope.auth.role_id === ConstUserType.Customer){
                    params = { 'page': $scope.currentPage,'appointment_status_id': id, 'user_id': $rootScope.auth.id};
                } 
                $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
                $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
                 params.filter = '{"order":"id desc","limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"where":{"appointment_status_id":'+id+'},"include":{"0":"user","1":"user.attachment","2":"provider_user","3":"provider_user.attachment","4":"provider_user.user_profile","5":"user.user_profile"}}';               
                 AppointmentFactory.get(params).$promise.then(function (response) {
                  $scope.tempFunction(response);
                 });
            }
            else{
                params = {};
                id = ConstAppointmentStatus.PaymentPending;
                $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
                $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
                params.filter = '{"order":"id desc","limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"where":{"appointment_status_id":{"nin":{"0":"'+id+'"}}},"include":{"0":"user","1":"user.attachment","2":"provider_user","3":"provider_user.attachment","4":"provider_user.user_profile","5":"user.user_profile"}}';
                 AppointmentFactory.get(params).$promise.then(function (response) {
                 $scope.tempFunction(response);
                }); 
            }
        };
        $scope.getAppointment = function(){
                    params.filter = '{"include":{"0":"user","1":"user.attachment","2":"provider_user","3":"provider_user.attachment","4":"provider_user.user_profile","5":"user.user_profile","6":"form_field_submission.form_field","7":"service","8":"cancellation_policy"}}';
                    appointmentView.get(params,{ id: $state.params.id }, function (response) {
                    $scope.appointment=response.data;
                    $scope.appointment.booked_on = moment(response.data.created_at).format('YYYY-MM-DD');
                    $scope.get_numberof_items = parseInt($scope.appointment.service.is_allow_to_get_number_of_items) === 1 ? true : false;
                    //for appoinment status
                    angular.forEach($scope.appoinment_status, function(value){
                        
                                if(value.id === $scope.appointment.appointment_status_id){
                                    $scope.appointment.status = value.name;
                                }
                        });
                    if($scope.appointment.bonus_amount <= 0){
                        $scope.bonusDisplay = {"display":"none"};
                    }    
                     
                    //for images
                    var hash='';
                    if (angular.isDefined($scope.appointment.user.attachment) && $scope.appointment.user.attachment !== null) {
                        hash = md5.createHash($scope.appointment.user.attachment.class + $scope.appointment.user.attachment.id + 'png' + 'small_thumb');
                        $scope.appointment.user_image = 'images/small_thumb/' + $scope.appointment.user.attachment.class + '/' + $scope.appointment.user.attachment.id + '.' + hash + '.png';
                        }else {
                            $scope.appointment.user_image = $window.theme + 'images/default.png';
                        }
                        if(angular.isDefined($scope.appointment.provider_user.attachment) && $scope.appointment.provider_user.attachment !== null) {
                        hash = md5.createHash($scope.appointment.provider_user.attachment.class + $scope.appointment.provider_user.attachment.id + 'png' + 'small_thumb');
                        $scope.appointment.provider_image = 'images/small_thumb/' + $scope.appointment.provider_user.attachment.class + '/' + $scope.appointment.provider_user.attachment.id + '.' + hash + '.png';
                        } else {
                        $scope.appointment.provider_image = $window.theme + 'images/default.png';
                        } 
                        if($scope.appointment.bonus_amount > 0){
                            if(parseInt($scope.appointment.service_id) !== ConstService.Interview){
                                $scope.site_commision = ($scope.appointment.bonus_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER))/100;
                            }else{
                                $scope.site_commision = ($scope.appointment.bonus_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER))/100;
                            }
                        }
                        if($state.current.name === "BookingDetail"){
                            $timeout(function(){
                                $scope.getAppointment(); 
                            },60000);   
                        }     
                    });
                
                };
        $scope.init = function () {
            $scope.ConstUserType = ConstUserType;
            $rootScope.ConstAppointmentStatus = ConstAppointmentStatus;
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
            $state.params.type = ($state.params.type !== undefined) ? $state.params.type : 'all';
            if ($state.current.name === 'MyBooking') {
                $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Bookings and Messages");
                $scope.getAppointmentList();
            } else if(($state.current.name === 'BookingDetail')) {
                $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Booking Details");
                $scope.getAppointment(); 
            }
            
            $rootScope.changeappointstatus = function (status,value) {
                
                $scope.loader = true;
                var param = {};
                param.appointment_status_id = status;
                param.id = $state.params.id;
                if(value !== 0) {
                    param.total_booking_amount = value;
                } else {
                    param.total_booking_amount = 0;
                }
                changeStatus.put(param, function (response) {
                    $uibModalStack.dismissAll();
                    $scope.loader = false;
                    if(response.error.code == 0) {//jshint ignore:line 
                        if(ConstAppointmentStatus.Completed === status){
                            flash.set($filter("translate")("Job has changed to completed status."), 'success', true);
                        }else{
                            flash.set($filter("translate")("Status successfully changed."), 'success', true);
                        }
                        
                        $state.reload();
                    } else if(response.error.code === 2) {
                        flash.set($filter("translate")("Appointment couldn't be accepted. You already having appointment on this day."), 'error', true);
                    }
                });
            };
        };
        $scope.getAppointmentList = function () {
           params = {};
           $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage - 1) * $scope.itemsPerPage : 0;
            $scope.itemsPerPage = $scope.itemsPerPage !== undefined ? $scope.itemsPerPage : 0;
           params.filter = '{"order":"id desc","limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"where":{"appointment_status_id":{"nin":{"0":"'+ConstAppointmentStatus.PaymentPending+'"}}},"include":{"0":"user","1":"user.attachment","2":"provider_user","3":"provider_user.attachment","4":"provider_user.user_profile","5":"user.user_profile"}}';
           AppointmentFactory.get(params).$promise.then(function (response) {
                 $scope.tempFunction(response);
                 if($state.current.name === "MyBooking"){
                     $timeout(function(){
                        $scope.getAppointmentList(); 
                    },60000);
                 }
                 
            });
        };
        $scope.tempFunction = function(response){
            $scope.appointments = response.data;
                if (angular.isDefined(response._metadata)) {
                    $scope.totalRecords = response._metadata.total;
                    $scope.itemsPerPage = response._metadata.per_page;
                    $scope.lastPage = response._metadata.last_page;
                    $scope.currentPage = response._metadata.current_page;
                }
            //for changing created_at date format
            angular.forEach($scope.appointments, function(value){
                value.created_date = moment(value.created_at).format('YYYY-MM-DD');
            });
            var hash = '';
            angular.forEach($scope.appointments, function(value,key){
                    if (angular.isDefined($scope.appointments[key].user.attachment) && $scope.appointments[key].user.attachment !== null) {
                    hash = md5.createHash($scope.appointments[key].user.attachment.class + $scope.appointments[key].user.attachment.id + 'png' + 'small_thumb');
                    $scope.appointments[key].user_image = 'images/small_thumb/' + $scope.appointments[key].user.attachment.class + '/' + $scope.appointments[key].user.attachment.id + '.' + hash + '.png';
                    }else {
                         $scope.appointments[key].user_image = $window.theme + 'images/default.png';
                    }
                    if(angular.isDefined($scope.appointments[key].provider_user.attachment) && $scope.appointments[key].provider_user.attachment !== null) {
                    hash = md5.createHash($scope.appointments[key].provider_user.attachment.class + $scope.appointments[key].provider_user.attachment.id + 'png' + 'small_thumb');
                    $scope.appointments[key].provider_image = 'images/small_thumb/' + $scope.appointments[key].provider_user.attachment.class + '/' + $scope.appointments[key].provider_user.attachment.id + '.' + hash + '.png';
                    } else {
                     $scope.appointments[key].provider_image = $window.theme + 'images/default.png';
                    }
                });
                //for appoinment status
               angular.forEach($scope.appoinment_status, function(value){
                    angular.forEach($scope.appointments, function(appvalue){
                        if(parseInt(value.id) === parseInt(appvalue.appointment_status_id)){
                            appvalue.status = value.name;
                        }
                    });
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
        $scope.paginate = function (currentPage) {
            $scope.currentPage = currentPage;
            $scope.getAppointmentList();
        };
        $scope.gotoPayment = function(appointmentId, booking_amount){
            if($rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('PaymentBooking/PaymentBooking') > -1){
                $state.go('PaymentGateway', {'class':'appointments','id':$state.params.id});
            }else{
                $scope.changeappointstatus($scope.ConstAppointmentStatus.Confirmed, booking_amount);
            }
        };
        $scope.init();
            $scope.preapprove = function(){
                // $('#preapprovemodal').show();
                    $uibModal.open({
                    animation: true,
                    ariaLabelledBy: 'modal-title',
                    ariaDescribedBy: 'modal-body',
                    templateUrl: 'preapprovemodal.html',
                    size: "lg",
                    });

                    // modalInstance.result.then(function (selectedItem) {
                    // }, function () {
                    // });
            };
     $scope.PayBonus = function($valid){
         if($valid){
             params = {};
             params.appointmentId = $state.params.id;
             params.bonus_amount = $scope.bonus_appointment.bonus_amount;
             params.bonus_amount_note = $scope.bonus_appointment.bonus_amount_note;
             appointmentPut.update(params, function(response){
                 if(response.error.code === 0){
                     if(response.data.user_id === $rootScope.auth.id){
                         $state.go('PaymentGateway', {is_bonus:1,class:'appointments','id':$state.params.id});
                     }else if(response.data.provider_user_id === $rootScope.auth.id){
                         $scope.appointment.bonus_amount = response.data.bonus_amount;
                         $scope.appointment.bonus_amount_note = response.data.bonus_amount_note;
                         flash.set($filter("translate")("Amount successfully updated."), 'success', true);
                     }
                     
                 }
             });
         }
     }; 
     $scope.$watch('bonus_appointment.bonus_amount', function(){
         if($scope.appointment){
             if(parseInt($scope.appointment.service_id) !== ConstService.Interview){
                $scope.site_commision = ($scope.bonus_appointment.bonus_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER))/100;
            }else{
                $scope.site_commision = ($scope.bonus_appointment.bonus_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER))/100;
            }
         }   
     });   
     $scope.toggleBonusForm = function(){
         $('#bonus').slideToggle("slow");
     };
    });
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
    .controller('UserController', function ($scope, $state, $rootScope, $filter, $location, $auth, flash, $anchorScroll, ConstUserType, UsersFactory, ConstSocialLogin, AppointmentWeekList, UserReviews, UserAppointment, ConstBookingType, BookAppoinment, md5, $window, FAQS, GetQualification, GetPostions, GetSkill, GetExperience, ConstService, UserFavorite, EditUserFavorite, ProviderCalendar, ServiceGet, Service, ConstAppointmentStatus, $timeout, $cookies,ConstProStatus, JobRequestGet) { //jshint ignore:line
        $rootScope.subHeader = "Bookings & messages"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        var param = {};
        var appointmetHide = '';
        var lessShow = '';
        var moreShow = '';
        var i;
        $scope.is_disabled = false;
        $scope.ConstProStatus = ConstProStatus;
        $scope.share_userId = $state.params.user_id;
        $scope.share_slug = $state.params.slug;
        $scope.absUrl = $location.absUrl();
        $scope.initial_start = moment("18:00:00", 'HH:mm:ss');
        $scope.initial_end = moment("22:00:00", 'HH:mm:ss');
        $scope.multiPlaceHolder = "Select one or more dates";
        $scope.singlePlaceHolder = "Select date";
        $scope.timePlaceHolder = "Select time";
        $scope.booking_time = {};
        $scope.booking_date = {};
        $scope.form_data = []; // for form_fields
        $scope.having_form_field = false;
        $scope.minDate = moment(new Date()).format('YYYY-MM-DD');
        $scope.viewing_user_id = $state.params.user_id;
        $scope.invalid_dates = [];
        $scope.showCaluculation = false;
        $scope.showTimeError = false;
        $rootScope.isHome = true; // for disabling sub header
        $scope.BookingType = ConstBookingType;
        $scope.available_dates = [];
        $scope.available_dates_temp = [];
        $scope.invalid_dates_temp = [];
        $scope.totalCounts = []; // get total counts of an item if enabled
        $scope.is_single = true;
        $scope.showDateError = false;
        $scope.showServiceError = false;
        $scope.is_came = false;
        $scope.hide_service = false;
        $scope.hide_Book_now = false;
        $scope.my_services = {};
        $scope.gotoAnchor = function (x) {
            $anchorScroll(x);
        };
        $scope.current_date = moment(new Date()).format('YYYY-MM-DD');
        $scope.ConstService = ConstService;
        function daysAdd() {
            $scope.today = $scope.dateAddFunction(0, $scope.ViewSlot);
            $scope.day2 = $scope.dateAddFunction(1, $scope.ViewSlot);
            $scope.day3 = $scope.dateAddFunction(2, $scope.ViewSlot);
            $scope.day4 = $scope.dateAddFunction(3, $scope.ViewSlot);
            $scope.day5 = $scope.dateAddFunction(4, $scope.ViewSlot);
            $scope.day6 = $scope.dateAddFunction(5, $scope.ViewSlot);
            $scope.day7 = $scope.dateAddFunction(6, $scope.ViewSlot);
        }
        $scope.form_field_group = [];
        var params = {};
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserController
         * @description
         * This method will initialze the page. It returns the page title.
         *
         **/
        
        $scope.init = function () {
            $scope.ConstSocialLogin = ConstSocialLogin;
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Booking");
            $scope.maxSize = 5;
            $scope.currentPage = ($scope.currentPage !== undefined) ? parseInt($scope.currentPage) : 1;
        };
        params = {};
        params.filter = '{"include":{"0":"user_profile","1":"attachment","2":"service_users.cancellation_policy","3":"service_users.service","4":"city","5":"country","6":"state","7":"listing_photo","8":"user_favorite_for_single_user","9":"form_field_submission.form_field.form_field_group","10":"appointment_settings","11":"blocking"}}';
        params.type = "view";
        UsersFactory.get(params, { username: $state.params.user_id }).$promise.then(function (response) {
            $scope.user = response.data;
            if(parseInt($scope.user.user_profile.is_available_for_interview) === 1){
                angular.forEach($scope.user.service_users, function(value){
                    if(parseInt(value.service_id) === ConstService.Interview){
                        $scope.my_services = value;    
                    }
                });
            }
            if(parseInt($scope.user.role_id) === ConstUserType.Customer){
                $scope.user.user_profile.listing_latitude = $scope.user.latitude;
                $scope.user.user_profile.listing_longitude = $scope.user.longitude;
            }
            $scope.calendar_slot = $scope.user.appointment_settings !== (undefined || null) ? $scope.user.appointment_settings.calendar_slot_id : 1;
            if ($scope.user.user_favorite_for_single_user !== null && $scope.user.user_favorite_for_single_user !== undefined) {
                $scope.user.is_favorite = $scope.user.id === $scope.user.user_favorite_for_single_user.provider_user_id ? true : false;
                $scope.user.favorite_id = $scope.user.id === $scope.user.user_favorite_for_single_user.provider_user_id ? $scope.user.user_favorite_for_single_user.id : null;
            } else {
                $scope.user.is_favorite = false;
                $scope.user.favorite_id = null;
            }
            if ($scope.user.category) {
                angular.forEach($scope.user.category.form_field_groups, function (value) {
                    if (value.form_fields.length > 0) {
                        $scope.form_data.push(value);
                        $scope.having_form_field = true;
                    }
                });
            }
            $scope.getStartTime = function () {
                if (!$scope.booking_time.start) {
                    $scope.booking_time.start = moment("18:00:00", 'HH:mm:ss');
                }
            };
            $scope.getEndTime = function () {
                if (!$scope.booking_time.end) {
                    $scope.booking_time.end = moment("22:00:00", 'HH:mm:ss');
                }
            };
            var i;
            for (i = 0; i < 3; i++) {
                // user disabled dates
                params = {};
                params.serviceProviderId = $state.params.user_id;
                params.month = parseInt(moment(new Date()).format('MM')) + i;
                params.year = parseInt(moment(new Date()).format('YYYY'));
                if (params.month > 12) {
                    params.month = params.month - 12;
                    params.year = parseInt(moment(new Date()).format('YYYY')) + 1;
                }
                ProviderCalendar.get(params, function (response) {
                    angular.forEach(response, function (value) {
                        if (value.title !== 'Available' && parseInt(value.appointment_status_id) !== ConstAppointmentStatus.Confirmed) {
                            if (value.start !== undefined) {
                                $scope.invalid_dates_temp.push(moment(value.start).format('YYYY-MM-DD'));
                            }
                        }
                        if (value.title === 'Available') {
                            $scope.available_dates.push({ 'title': value.title, 'start': value.start, 'end': value.end, 'color': value.color });
                            $scope.available_dates_temp.push(moment(value.start).format('YYYY-MM-DD'));
                            
                        }
                    });
                    $scope.invalid_dates_temp = $scope.unique($scope.invalid_dates_temp);
                    $scope.available_dates_temp = $scope.unique($scope.available_dates_temp);
                    //loading calendar after gets all the data
                    $scope.temp_array = [];
                    angular.forEach($scope.invalid_dates_temp, function(value){
                        if($scope.available_dates_temp.indexOf(value) === -1 && $scope.invalid_dates.indexOf(value) === -1){
                            $scope.invalid_dates.push(value);  
                        }
                    });
                    $scope.loadCalendar = i >= 2 ? true : false;
                }); //jshint ignore:line
            }
            

            if (angular.isDefined($scope.user.attachment) && $scope.user.attachment !== null) {
                var hash = md5.createHash($scope.user.attachment.class + $scope.user.attachment.id + 'png' + 'large_thumb');
                $scope.user.userimage = 'images/large_thumb/' + $scope.user.attachment.class + '/' + $scope.user.attachment.id + '.' + hash + '.png';
            } else {
                $scope.user.userimage = $window.theme + 'images/default.png';
            }
            //List of Photos
            angular.forEach($scope.user.listing_photo, function (value) {
                var hash = md5.createHash(value.class + value.id + 'png' + 'view_listing_thumb');
                value.photo_url = 'images/view_listing_thumb/' + value.class + '/' + value.id + '.' + hash + '.png';
            });
            $scope.photos = $scope.user.listing_photo;
            $scope.timeRating = parseInt($scope.user.timing_avg_rating / $scope.user.timing_rating_count);
            $scope.bedRating = parseInt($scope.user.bedside_avg_rating / $scope.user.bedside_rating_count);
            $scope.overAllRating = $scope.user.overall_avg_rating;
            // $scope.user.user_profile.cv = (response.user_profile.cv !=='null')?response.user_profile.cv:'';
            /* For Show More Concept */
            angular.forEach($scope.user.form_field_submission, function (value) {
                $scope.group_is_there = false;
                angular.forEach($scope.form_field_group, function (child_value) {
                    if (parseInt(child_value.group_id) === parseInt(value.form_field.form_field_group.id)) {
                        $scope.group_is_there = true;
                    }
                });
                if ($scope.group_is_there === false) {
                    $scope.form_field_group.push({ "group_id": value.form_field.form_field_group.id, "text": value.form_field.form_field_group.name });
                }

            });
            $scope.loadMore = function () {
                /* for appointment enable */
                appointmetHide = angular.element(document.getElementsByClassName('showmore'));
                if (appointmetHide.hasClass('hide')) {
                    appointmetHide.removeClass('hide');
                    appointmetHide.addClass('show');
                }
                /* For show less more button */
                var moreShow = angular.element(document.getElementsByClassName('showmore_btn'));
                if (moreShow.hasClass('show')) {
                    moreShow.removeClass('show');
                    moreShow.addClass('hide');
                }
                var lessShow = angular.element(document.getElementsByClassName('showless_btn'));
                if (lessShow.hasClass('hide')) {
                    lessShow.addClass('show');
                    lessShow.removeClass('hide');
                }
            };

            $(document).on('click', '.next', function () {
                params = {};
                params.serviceProviderId = $state.params.user_id;
                if ($scope.BookingType.SingleDate === $scope.booking || $scope.BookingType.MultiHours === $scope.booking || $scope.BookingType.TimeSlot === $scope.booking) {
                    params.month = $('#Single').data('daterangepicker').leftCalendar.month.format('MM');
                    params.year = $('#Single').data('daterangepicker').leftCalendar.month.format('YYYY');
                    //$scope.minDate =  $('#Single').data('daterangepicker').leftCalendar.month.format('YYYY/MM/DD');
                    params.month = parseInt(params.month) + 1;
                    if (params.month > 12) {
                        params.month = params.month - 12;
                        params.year = parseInt(params.year) + 1;
                    }
                } else {
                    params.month = $scope.BookingType.MultipleDate === $scope.booking ? $('#Multiple').data('daterangepicker').rightCalendar.month.format('MM') : null;
                    params.year = $scope.BookingType.MultipleDate === $scope.booking ? $('#Multiple').data('daterangepicker').rightCalendar.month.format('YYYY') : null;
                    params.month = parseInt(params.month) + 1;
                    if (params.month > 12) {
                        params.month = params.month - 12;
                        params.year = parseInt(params.year) + 1;
                    }
                }
                ProviderCalendar.get(params, function (response) {
                    // angular.forEach(response, function (value) {
                    //     if (value.title !== 'Available' && parseInt(value.appointment_status_id) !== ConstAppointmentStatus.Confirmed) {
                    //         $scope.is_there = false;
                    //         angular.forEach($scope.invalid_dates, function (childvalue) {
                    //             if (childvalue == value.start) {//jshint ignore:line
                    //                 $scope.is_there = true;
                    //             }
                    //         });
                    //         if ($scope.is_there === false) {
                    //             $scope.invalid_dates.push(moment(value.start).format('YYYY-MM-DD'));
                    //         }
                    //     }

                    // });

                    angular.forEach(response, function (value) {
                        if (value.title !== 'Available' && parseInt(value.appointment_status_id) !== ConstAppointmentStatus.Confirmed) {
                            if (value.start !== undefined) {
                                $scope.invalid_dates_temp.push(moment(value.start).format('YYYY-MM-DD'));
                            }
                        }
                        if (value.title === 'Available') {
                            $scope.available_dates.push({ 'title': value.title, 'start': value.start, 'end': value.end, 'color': value.color });
                            $scope.available_dates_temp.push(moment(value.start).format('YYYY-MM-DD'));
                            
                        }
                    });
                    $scope.invalid_dates_temp = $scope.unique($scope.invalid_dates_temp);
                    $scope.available_dates_temp = $scope.unique($scope.available_dates_temp);
                    //loading calendar after gets all the data
                    $scope.temp_array = [];
                    angular.forEach($scope.invalid_dates_temp, function(value){
                        if($scope.available_dates_temp.indexOf(value) === -1 && $scope.invalid_dates.indexOf(value) === -1){
                            $scope.invalid_dates.push(value);  
                        }
                    });
                });

            });
            $scope.showLess = function () {
                /* for appointment enable */
                appointmetHide = angular.element(document.getElementsByClassName('showmore'));
                if (appointmetHide.hasClass('show')) {
                    appointmetHide.removeClass('show');
                    appointmetHide.addClass('hide');
                }
                /* For show less more button */
                moreShow = angular.element(document.getElementsByClassName('showmore_btn'));
                if (moreShow.hasClass('hide')) {
                    moreShow.removeClass('hide');
                    moreShow.addClass('show');
                }
                lessShow = angular.element(document.getElementsByClassName('showless_btn'));
                if (lessShow.hasClass('show')) {
                    lessShow.addClass('hide');
                    lessShow.removeClass('show');
                }
            };
            $scope.userId = $scope.user.id;
            $scope.ViewSlot = 1;
            $scope.ConstUserType = ConstUserType;
            if ($rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('UserReviews/UserReviews') > -1) {
                /* For get all reviews */
                $scope.getReviewsList();
                /* For check the Auth User is a Patient & if Auth User is a patient is alreay added a reivew or Not */
                if ($auth.isAuthenticated()) {
                    if ($rootScope.auth.role_id === ConstUserType.Customer) {
                        /* For Check the auth user is vist the doctor at once */
                        UserAppointment.get({
                            doctor_id: $scope.userId,
                            user_id: $rootScope.auth.id
                        }).$promise.then(function (Appointmentresponse) {
                            $scope.isAlreadyVisted = Appointmentresponse.data.is_visited;
                            if ($scope.isAlreadyVisted === true) {
                                $scope.isvisited = true;
                                UserReviews.get({
                                    doctor_id: $scope.userId,
                                    user_id: $rootScope.auth.id
                                }).$promise.then(function (reviewResponse) {
                                    $scope.authUserAddedReview = reviewResponse.data;
                                    $scope.userReviewData = reviewResponse.status;
                                    if ($scope.userReviewData === true) {
                                        /* for template side block regard */
                                        $scope.reviewEnable = false;
                                        $scope.alreadyadded = true;
                                    } else {
                                        /* for template side block regard */
                                        $scope.reviewEnable = true;
                                        $scope.alreadyadded = false;
                                    }
                                });
                            } else {
                                $scope.reviewEnable = false;
                                $scope.isvisited = false;
                            }
                        });
                    } else {
                        $scope.reviewEnable = false;
                        /* for template side block reg */
                    }
                }
            }
            $scope.options_timesolot = {
                    singleDatePicker: true, minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                        return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                    }
                };

            var temp = {};
            
            //Service call for is_enable_common_hourly_rate_for_all_sub_services
            temp.filter = '{"include":{"0":"category"},"where":{"is_active":1}}';
            Service.get(temp, function (response) {
                $scope.main_service = response.data;
                angular.forEach($scope.user.service_users, function (value) {
                    angular.forEach($scope.main_service, function (childvalue) {
                        if (parseInt(childvalue.id) === parseInt(value.service_id)) {
                            value.slug = childvalue.name;
                            value.booking_option_id = childvalue.booking_option_id;
                            value.is_need_user_location = childvalue.is_need_user_location;
                            $scope.flat_rate = value.rate;
                        }
                        
                    });
                    if(parseInt(value.service_id) !== ConstService.Interview){
                        $scope.services.push(value);
                    }
                    $scope.selectedService = $scope.services[0].id;
                    $scope.flat_rate = $scope.services[0].rate;
                    //chenking whethere need to addnumber of items in the select box
                    $scope.get_numberof_items = parseInt($scope.services[0].service.is_allow_to_get_number_of_items) === 1 ? true : false;
                    //getting booking option id
                    $scope.booking = parseInt($scope.services[0].booking_option_id);
                     if ($scope.BookingType.SingleDate === $scope.booking || $scope.BookingType.MultiHours === $scope.booking || $scope.BookingType.TimeSlot === $scope.booking) {
                        $scope.options = {};
                        $scope.options = {
                            singleDatePicker: true, minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                                return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                            }
                        };

                    } else {
                        $scope.options = {};
                        // $scope.options = {
                        //     singleDatePicker: false, minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                        //         return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                        //     }
                        // };
                        $scope.options = {autoApply:true,singleDatePicker:false,eventHandlers : {'hide.daterangepicker': function() { 
                                                $scope.get_start_data = $("input[name=daterangepicker_start]");
                                                if($scope.is_came === false){
                                                    if($scope.get_start_data[0].value){
                                                        $scope.booking_date.datePicker={"startDate" : $scope.get_start_data[0].value, "endDate" : $scope.get_start_data[0].value};
                                                    }else{
                                                        if($scope.booking_date.datePicker){
                                                            $scope.booking_date.datePicker={"startDate" : $scope.booking_date.datePicker.startDate, "endDate" : $scope.booking_date.datePicker.endDate};
                                                        }
                                                        
                                                    }
                                             }
                                             $scope.is_came = false;
                                        }
                            },minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                            return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                            }};

                    }
                    //checking whether service need location 
                    $scope.need_location = parseInt($scope.services[0].is_need_user_location) === 1 ? true : false;
                    $scope.service_name = $scope.services[0].slug;
                    $scope.label_name = $scope.services[0].service.label_for_number_of_item;
                    angular.forEach($scope.main_service, function (value) {
                        if (value.id === parseInt($scope.services[0].service_id)) {
                            if (parseInt(value.category.is_enable_common_hourly_rate_for_all_sub_services) === 0) {
                                $scope.hourly_rate = false;
                                
                            } else if (parseInt(value.category.is_enable_common_hourly_rate_for_all_sub_services) === 1) {
                                $scope.hourly_rate = true;
                            }
                            $scope.calculateFees();
                        }
                    });
                });
                if ($scope.get_numberof_items) {
                    for (i = 0; i < parseInt($scope.services[0].service.maximum_number_to_allow); i++) {
                        $scope.totalCounts.push({ "id": i + 1, "text": (i + 1) + " " + $scope.services[0].service.label_for_number_of_item});
                    }
                    $scope.selectedCount = $scope.totalCounts[0].id;
                    $scope.booking_count = parseInt($scope.selectedCount) + " " + $scope.services[0].service.label_for_number_of_item;
                }

                if ($rootScope.auth) {
                    $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) > ($scope.flat_rate * 8) ? true : false;
                }
                if($scope.BookingType.MultiHours === $scope.booking){
                    $scope.singlePlaceHolder = "Start date";
                    $scope.timePlaceHolder = "Start time";
                }
                
                // if($state.params.request_id){
                //     $timeout(function(){
                //         angular.forEach($scope.services, function(value){
                //             if($state.params.request_id && parseInt(value.service_id) === parseInt($state.params.service_id)){
                //                 $scope.flat_rate = value.rate;
                //                 $scope.bookingChange(value.id);
                //             }
                //         });
                //     }, 50);
                    
                // }
            });

            $('#navigate').click(function () {
                $('html').animate({
                    scrollTop: $('#Navigatehere').offset().top
                }, 500);
                return false;
            });
            $('#label_avail').click(function () {
                $('html').animate({
                    scrollTop: $('#weekCalendar').offset().top
                }, 500);
                return false;
            });

            $scope.services = [];

            if (parseInt($scope.user.user_profile.response_time) < 2) {
                $scope.user.user_profile.response_time_value = "within a minute";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 3) {
                $scope.user.user_profile.response_time_value = "within 2 minutes";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 5) {
                $scope.user.user_profile.response_time_value = "within 5 minutes";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 31) {
                $scope.user.user_profile.response_time_value = "within 30 minutes";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 61) {
                $scope.user.user_profile.response_time_value = "within a hour";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 121) {
                $scope.user.user_profile.response_time_value = "within two hours";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 721) {
                $scope.user.user_profile.response_time_value = "within half a day";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 1441) {
                $scope.user.user_profile.response_time_value = "within a day";
            }
            else if (parseInt($scope.user.user_profile.response_time) < 10080) {
                $scope.user.user_profile.response_time_value = "within a week";
            } else if (parseInt($scope.user.user_profile.response_time) > 10080) {
                $scope.user.user_profile.response_time_value = "after a week";
            }
        });

        $scope.dateAddFunction = function (days, multipleCount) {
            var dateValue = {};
            if (parseInt(multipleCount) > 1) {
                $scope.addDays = (parseInt(multipleCount) - 1) * 7 + parseInt(days);
            } else {
                $scope.addDays = parseInt(days);
            }
            if ((parseInt(days) === '0') && (parseInt(multipleCount) === '1')) {
                dateValue = {
                    day: $filter('date')(new Date(), "EEE"),
                    date: $filter('date')(new Date(), "dd-MMM"),
                    year: $filter('date')(new Date(), "yyyy"),
                };
                return dateValue;
            } else if ((parseInt(days) === '0') && (parseInt(multipleCount) > 1)) {
                days = (parseInt(multipleCount) - 1) * 7;
                dateValue = {
                    day: $filter('date')(addDays(new Date(), days), "EEE"),
                    date: $filter('date')(addDays(new Date(), days), "dd-MMM"),
                    year: $filter('date')(addDays(new Date(), days), "yyyy"),
                };
                return dateValue;
            } else {
                dateValue = {
                    day: $filter('date')(addDays(new Date(), $scope.addDays), "EEE"),
                    date: $filter('date')(addDays(new Date(), $scope.addDays), "dd-MMM"),
                    year: $filter('date')(addDays(new Date(), $scope.addDays), "yyyy"),
                };
                return dateValue;
            }
        };
        daysAdd();
        $scope.init();
        function addDays(theDate, days) {
            return new Date(theDate.getTime() + days * 24 * 60 * 60 * 1000);
        }
        $scope.nextWeek = function () {
            $scope.ViewSlot = parseInt($scope.ViewSlot) + 1;
            AppointmentWeekList.get({ viewslot: $scope.ViewSlot, userids: $scope.userId }).$promise.then(function (searchResponse) {
                $scope.searchLists = searchResponse.user_profiles[0];
                $scope.ViewSlot = searchResponse.viewslot;
            });
            daysAdd();
        };
        $scope.prevWeek = function () {
            if ($scope.ViewSlot === '1') {
                $scope.ViewSlot = 1;
            } else {
                $scope.ViewSlot = parseInt($scope.ViewSlot) - 1;
            }
            AppointmentWeekList.get({ viewslot: $scope.ViewSlot, userids: $scope.userId }).$promise.then(function (searchResponse) {
                $scope.searchLists = searchResponse.user_profiles[0];
                $scope.ViewSlot = searchResponse.viewslot;
            });
            daysAdd();
        };


        $scope.isAuthenticated = function () {
            return $auth.isAuthenticated();
        };
        $scope.getReviewsList = function () {
            param = { 'doctor_id': $scope.userId, 'page': $scope.currentPage };
            UserReviews.get(param).$promise.then(function (response) {
                $scope.doctorReviews = response.data;
                $scope._metadata = response.meta.pagination;
                $scope.metadata = response.meta.pagination;
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
            $scope.getReviewsList();
        };


        $scope.$watch("booking_date.datePicker", function (date) {
            var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*
            if (date !== undefined) {
                if (date.endDate) {
                    $scope.is_came = true;
                    $rootScope.end_date = date.endDate._d !== undefined ? $filter('date')(date.endDate._d, "yyyy-MM-dd") : $scope.booking_date.datePicker.endDate;
                    $rootScope.start_date = date.startDate._d !== undefined ? $filter('date')(date.startDate._d, "yyyy-MM-dd") : $scope.booking_date.datePicker.startDate;
                        
                    $scope.firstDate = new Date($rootScope.end_date);
                    $scope.secondDate = new Date($rootScope.start_date);
                    $scope.diffDays = (Math.round(Math.abs(($scope.firstDate.getTime() - $scope.secondDate.getTime()) / (oneDay)))) + 1;
                } else {
                    $rootScope.start_date = $filter('date')(date._d, "yyyy-MM-dd");
                    $rootScope.end_date = $rootScope.start_date;
                    $scope.close_date = $filter('date')(date._d, "yyyy-MM-dd");
                }
                $scope.showDateError = false;
                $scope.calculateFees();
                if ($scope.BookingType.SingleDate === $scope.booking || $scope.BookingType.MultipleDate === $scope.booking) {
                    if(!$scope.showDateError){
                        $scope.showCaluculation = true;
                    }
                    
                }

            }
        });
        //calculating total booking amount
        $scope.calculateFees = function () {
            if(new Date($rootScope.end_date) < new Date($rootScope.start_date)){
                    $scope.showDateError = true;
                    return;
            }
            //Checking whether selected contains user unavailable dates
            $scope.dates = $scope.getDates(new Date(moment($rootScope.end_date).format("YYYY,MM,DD")), new Date(moment($rootScope.start_date).format("YYYY,MM,DD")));
            angular.forEach($scope.dates, function(selectedDate){
                angular.forEach($scope.invalid_dates, function(unavailabledates){
                    if(selectedDate === unavailabledates && moment().format('YYYY-MM-DD') !== selectedDate){
                        $scope.showDateError = true;
                    }
                });    
            });
            if($scope.showDateError){
                $scope.booking_date.datePicker = $rootScope.start_date = $rootScope.end_date =undefined;
                $scope.showCaluculation = false;
                return;
            }
            
            if ($scope.BookingType.SingleDate === $scope.booking || $scope.BookingType.MultipleDate === $scope.booking) {   
                $scope.showTimeError = false;
                if ($scope.BookingType.SingleDate === $scope.booking) {
                    $scope.booking_amount = $scope.flat_rate;
                    if($scope.get_numberof_items){
                        $scope.booking_amount = $scope.booking_amount * $scope.selectedCount;
                    }
                    $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= ($scope.flat_rate) ? true : false;
                } else {
                    $scope.booking_amount = $scope.flat_rate * $scope.diffDays;
                    $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= (parseFloat($scope.flat_rate) * ($scope.diffDays)) ? true : false;
                }
            } else {
                if (($scope.booking_time.start && $scope.booking_time.end && $rootScope.start_date) || ($scope.booking_time.start  && $rootScope.start_date && $scope.booking === ConstBookingType.TimeSlot)) {
                    if($rootScope.start_date === $rootScope.end_date){
                        if ((moment($scope.booking_time.end).format('HH:mm:ss') <= moment($scope.booking_time.start).format('HH:mm:ss') || !$scope.booking_time.start || !$scope.booking_time.end) && $scope.booking !== ConstBookingType.TimeSlot) {
                            $scope.showTimeError = true;
                            return;
                        }else if(!$scope.booking_time.start && $scope.booking === ConstBookingType.TimeSlot) {
                            $scope.showTimeError = true;
                            return;
                        }else{
                            $scope.showTimeError = false;
                        }
                    }
                    
                    var temp1 = $rootScope.start_date + " " + moment($scope.booking_time.start).format("HH:mm:ss");
                    var temp2 = $rootScope.end_date + " " + moment($scope.booking_time.end).format("HH:mm:ss");
                    $scope.start_time = new Date(temp1);
                    $scope.end_time = new Date(temp2);
                    var diff = ($scope.end_time.getTime() - $scope.start_time.getTime()) / 1000;
                    diff /= (60 * 60);
                    $scope.totalHours = Math.abs(Math.round(diff));
                    if ($scope.BookingType.SingleDate === $scope.booking || $scope.BookingType.MultiHours === $scope.booking || $scope.BookingType.TimeSlot === $scope.booking) {
                        if ($scope.hourly_rate === true) {
                            $scope.booking_amount = $scope.BookingType.TimeSlot === $scope.booking ? $scope.flat_rate : $scope.flat_rate * $scope.totalHours;
                            if($scope.get_numberof_items){
                                    $scope.booking_amount = $scope.booking_amount * $scope.selectedCount;
                                }
                            $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= ($scope.booking_amount) ? true : false;
                        } else {
                            $scope.booking_amount = $scope.flat_rate;
                            if($scope.get_numberof_items){
                                $scope.booking_amount = $scope.booking_amount * $scope.selectedCount;
                            }
                            $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= ($scope.flat_rate) ? true : false;
                        }

                    } else {
                        if ($scope.hourly_rate === true) {
                            $scope.booking_amount = $scope.flat_rate * $scope.totalHours * $scope.diffDays;
                            if($scope.get_numberof_items){
                                $scope.booking_amount = $scope.booking_amount * $scope.selectedCount;
                            }
                            $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= (parseFloat($scope.flat_rate) * ($scope.diffDays * $scope.totalHours)) ? true : false;
                        } else {
                            $scope.booking_amount = $scope.flat_rate * $scope.diffDays;
                            if($scope.get_numberof_items){
                                $scope.booking_amount = $scope.booking_amount * $scope.selectedCount;
                            }
                            $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= (parseFloat($scope.flat_rate) * ($scope.diffDays)) ? true : false;

                        }

                    }
                    $scope.showCaluculation = true;
                }
            }
            if($scope.hide_service === false){
                if($rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER !== null && $rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER !== undefined && $rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER !== ""){
                    $scope.site_commision =($scope.booking_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER))/100;
                }else{
                    $scope.site_commision = 0;
                }
            }else{
                if($rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER !== null && $rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER !== undefined && $rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER !== ""){
                    $scope.site_commision =($scope.booking_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER))/100;
                }else{
                    $scope.site_commision = 0;
                }
            }
            
        };

        $scope.$watch("close_date", function (date) {
            if (date !== undefined) {
                $rootScope.end_date = date._d !== undefined ? $filter('date')(date._d, "yyyy-MM-dd") : $filter('date')($scope.close_date, "yyyy-MM-dd");
                $scope.calculateFees();
            }
            
        });
        //
        $scope.bookingChange = function (selectedService) {
            $scope.showCaluculation = false;
            angular.forEach($scope.services, function (value) {
                if (value.id === selectedService) {
                    $scope.booking = value.booking_option_id;
                    $scope.selectedService = selectedService;
                    $scope.service_name = value.slug;
                    $scope.flat_rate = value.rate;
                    $scope.need_location = parseInt(value.is_need_user_location) === 1 ? true : false;
                    $scope.get_numberof_items = parseInt(value.service.is_allow_to_get_number_of_items) === 1 ? true : false;
                    $scope.label_name = value.service.label_for_number_of_item;      
                    angular.forEach($scope.main_service, function (main_value) {
                        if (main_value.id === parseInt(value.service_id)) {
                            //checking whether user category is hourly based or not .if hourly_based then take flat rate from user profile hourly_rate.else need to take service flat rate.
                            if (parseInt(main_value.category.is_enable_common_hourly_rate_for_all_sub_services) === 0) {
                                $scope.hourly_rate = false;
                            } if (parseInt(main_value.category.is_enable_common_hourly_rate_for_all_sub_services) === 1) {
                                $scope.hourly_rate = true;
                            }
                            $scope.calculateFees();
                        }
                    });
                }
            });
            if(parseInt(selectedService) === ConstService.Interview){
                $scope.flat_rate = $scope.my_services.rate;
                $scope.hourly_rate = false;
                $scope.service_name = $scope.my_services.service.name;
                $scope.need_location = false;
                $scope.get_numberof_items = false; 
                $scope.booking = $scope.my_services.service.booking_option_id;  
                $scope.selectedService = $scope.my_services.id;
                $scope.calculateFees(); 
            }
            if ($scope.get_numberof_items) {
               $scope.totalCounts = [];
                for (i = 0; i < parseInt($scope.services[0].service.maximum_number_to_allow); i++) {
                    $scope.totalCounts.push({ "id": i + 1, "text": (i + 1) + " " + $scope.label_name});
                }
                $scope.selectedCount = $scope.totalCounts[0].id;
                $scope.booking_count = parseInt($scope.selectedCount) + " " + $scope.label_name;
            }
            $scope.showServiceError = false;
            if ($scope.BookingType.SingleDate === $scope.booking || $scope.BookingType.MultiHours === $scope.booking || $scope.BookingType.TimeSlot === $scope.booking) {
                $scope.options = {};
                $scope.options = {
                    singleDatePicker: true, minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                        return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                    }
                };

            } else {
                $scope.options = {};
                // $scope.options = {
                //     singleDatePicker: false, minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                //         return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                //     }
                // };
                $scope.options = {autoApply:true,singleDatePicker:false,eventHandlers : {'hide.daterangepicker': function() { 
                                                $scope.get_start_data = $("input[name=daterangepicker_start]");
                                                if($scope.is_came === false){
                                                    if($scope.get_start_data[0].value){
                                                        $scope.booking_date.datePicker={"startDate" : $scope.get_start_data[0].value, "endDate" : $scope.get_start_data[0].value};
                                                    }else{
                                                        if($scope.booking_date.datePicker){
                                                            $scope.booking_date.datePicker={"startDate" : $scope.booking_date.datePicker.startDate, "endDate" : $scope.booking_date.datePicker.endDate};
                                                        }
                                                        
                                                    }
                                             }
                                             $scope.is_came = false;
                                        }
                            },minDate: $scope.minDate, autoUpdateInput: true, isInvalidDate: function (date) {
                            return !!($scope.invalid_dates.indexOf(date.format('YYYY-MM-DD')) > -1);//jshint ignore:line
                            }};

            }
            if($scope.BookingType.MultiHours === $scope.booking){
                    $scope.singlePlaceHolder = "Start date";
                    $scope.timePlaceHolder = "Start time";
            }else{
                $scope.singlePlaceHolder = "Select a date";
                $scope.timePlaceHolder = "Select time";
            }

        };
        $scope.countChanged = function (count) {
            $scope.selectedCount = count;
            $scope.booking_amount = $scope.flat_rate * parseInt(count) * $scope.totalHours;
            //for displaying purpose in screen
            $scope.booking_count = parseInt(count) + " " + $scope.label_name;
            //when user changes the count checking whether need to show calculation 
            $scope.show_info = parseFloat($rootScope.auth.affiliate_pending_amount) >= ($scope.booking_amount) ? true : false;
            if($rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER !== null && $rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER !== undefined && $rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER !== ""){
                $scope.site_commision =($scope.booking_amount * parseFloat($rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER))/100;
            }else{
                $scope.site_commision = 0;
            }
        };
        //UserFavorite
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
                }, function (errorMessage) {
                    flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                });
            } else if (parseInt(type) === 2) {
                user.is_favorite = true;
                UserFavorite.post(params, function (response) {
                    if (response.error.code !== 0) {
                        user.is_favorite = false;
                    } else {
                        flash.set($filter("translate")("Home assistant added to wish list "), 'success', false);
                    }
                }, function (errorMessage) {
                    flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                });
            }
        };
        //Booknow
        $scope.booknow = function (BookType) {
            $scope.appoinment = {};
            if (!$scope.selectedService || $scope.selectedService === null) {
                $scope.showServiceError = true;
                return;
            } else if (!$scope.booking_date.datePicker || $scope.booking_date.datePicker === null) {
                $scope.showDateError = true;
                return;
            } else if ($scope.BookingType.MultiHours === $scope.booking) {
                if($rootScope.start_date === $rootScope.end_date){
                    if (moment($scope.booking_time.end).format('HH:mm:ss') <= moment($scope.booking_time.start).format('HH:mm:ss') || !$scope.booking_time.start || !$scope.booking_time.end) {
                        $scope.showTimeError = true;
                        return;
                    }
                }
                
            } else if ($scope.BookingType.TimeSlot === $scope.booking) {
                if (!$scope.booking_time.start) {
                    $scope.showTimeError = true;
                    return;
                }

            }
            if($rootScope.start_date && $rootScope.end_date){
                if(new Date($rootScope.end_date) < new Date($rootScope.start_date)){
                    $scope.showDateError = true;
                    return;
                }
            }

            $scope.services_user_id = $scope.selectedService;
            if(parseInt($scope.my_services.id) !== parseInt($scope.selectedService)){
                angular.forEach($scope.services, function (value) {
                    if (parseInt($scope.selectedService) === parseInt(value.id)) {
                        $scope.service_id = value.service_id;
                    }
                });
            }else{
                $scope.service_id = $scope.my_services.service_id;
            }
            $scope.is_disabled = true;
            if (BookType === 'Book' && !$scope.is_came) {
                //checking whether user has form_field records or Not
                angular.forEach($scope.user.service_users, function (value) {
                    if (parseInt(value.service_id) === parseInt($scope.service_id)) {
                        angular.forEach(value.service.form_field_groups, function (childvalue) {
                            if (childvalue.form_fields.length > 0) {
                                $scope.form_data.push(childvalue);
                                $scope.having_form_field = true;
                            }
                        });
                    }
                });
                if ($scope.need_location || $scope.having_form_field) {
                    $rootScope.end_date = $rootScope.end_date !== (null || undefined) ? $rootScope.end_date : $rootScope.start_date;
                    $scope.slug = $filter("slugify")($scope.user.user_profile.first_name + $scope.user.user_profile.last_name);
                    $state.go('UserLocation', { "slug": $scope.slug, "user_id": $state.params.user_id, "from_date": $rootScope.start_date, "to_date": $rootScope.end_date, "services_user_id": $scope.services_user_id, "service_id": $scope.service_id, "from_time": moment($scope.booking_time.start).format('HH:mm:ss'), "to_time": moment($scope.booking_time.end).format('HH:mm:ss'), "amount": ($scope.booking_amount + $scope.site_commision),"count" : $scope.selectedCount,"request_id":$state.params.request_id});
                } else {
                    $scope.appoinment.services_user_id = $scope.services_user_id;
                    $scope.appoinment.appointment_from_date = $rootScope.start_date;
                    $scope.appoinment.appointment_to_date = $rootScope.end_date !== null ? $rootScope.end_date : $rootScope.start_date;
                    if ($scope.BookingType.MultiHours === $scope.booking) {
                        $scope.appoinment.appointment_to_date =  $rootScope.end_date;
                        $scope.appoinment.appointment_from_time = moment($scope.booking_time.start).format('HH:mm:ss');
                        $scope.appoinment.appointment_to_time = moment($scope.booking_time.end).format('HH:mm:ss');
                    } else if ($scope.BookingType.TimeSlot === $scope.booking) {
                        $scope.appoinment.appointment_from_time = moment($scope.booking_time.start).format('HH:mm:ss');
                    }
                    $scope.appoinment.appointment_status_id = 10; //10 for payment_pending
                    $scope.appoinment.number_of_item = $scope.selectedCount;
                    $scope.appoinment.is_appointment_for_interview = $scope.hide_service === true ? 1 : 0; //if user wants interview
                    if($state.params.request_id !== undefined){
                        $scope.appoinment.request_id = $state.params.request_id;
                    }
                    BookAppoinment.post($scope.appoinment, function (response) {
                        $scope.is_disabled = $scope.is_came = false;
                        if (response.error.code === 0) {
                            if (parseInt(response.appointment_status_id) === 10 && $rootScope.settings.SITE_ENABLED_PLUGINS.indexOf('PaymentBooking/PaymentBooking') > -1) {
                                $state.go('PaymentGateway', {'class':'appointments', 'id': response.id });
                            } else {
                                flash.set($filter("translate")("Appointment booked successfully."), 'success', false);
                                $scope.showDateError = false;
                                $scope.showServiceError = false;
                                $scope.booking_date.datePicker = '';
                                $scope.selectedService = '';
                                $scope.booking_time.start = '';
                                $scope.booking_time.end = '';
                                $scope.showCaluculation = false;
                                $scope.close_date = '';
                                $scope.hide_service = false;    
                            }
                        } else {
                            flash.set($filter("translate")(response.error.message), 'error', false);
                        }
                    }, function (errorMessage) {
                        $scope.is_disabled = $scope.is_came = false;
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

            } else if (BookType === "Enquiry") {
                if ($scope.BookingType.MultiHours === $scope.booking) {
                    $scope.appoinment.appointment_to_date = $filter('date')($scope.close_date, "yyyy-MM-dd");
                    $scope.appoinment.appointment_from_time = moment($scope.booking_time.start).format('HH:mm:ss');
                    $scope.appoinment.appointment_to_time = moment($scope.booking_time.end).format('HH:mm:ss');
                } else if ($scope.BookingType.TimeSlot === $scope.booking) {
                    $scope.appoinment.appointment_from_time = moment($scope.booking_time.start).format('HH:mm:ss');
                }
                $scope.slug = $filter("slugify")($scope.user.user_profile.first_name + $scope.user.user_profile.last_name);
                $state.go('UserLocation', { "slug": $scope.slug, "user_id": $state.params.user_id, "from_date": $rootScope.start_date, "to_date": $rootScope.end_date, "services_user_id": $scope.services_user_id, "service_id": $scope.service_id, "from_time": $scope.appoinment.appointment_from_time, "to_time": $scope.appoinment.appointment_to_time, "type": 'enquiry', "amount": ($scope.booking_amount + $scope.site_commision),"count" : $scope.selectedCount,"request_id":$state.params.request_id});
            }


        };
        //FAQ 
        FAQS.get(function (response) {
            $scope.faqs = response.data;
        });
        $scope.getDates = function(endDate,startDate) {
            var dates = [], currentDate = startDate,
            addDays = function(days) {
                var date = new Date(this.valueOf());
                date.setDate(date.getDate() + days);
                return date;
            };
            while (currentDate <= endDate) {
                dates.push(moment(currentDate).format('YYYY-MM-DD'));
                currentDate = addDays.call(currentDate, 1);
            }
            return dates;
        };
        $scope.uiConfig = {
            calendar: {
                height: 550,
                editable: true,
                header: {
                    left: '',
                    center: '',
                    right: 'prev,next'
                },
                defaultView: 'agendaWeek',
                events: $scope.available_dates,
                timeFormat: 'HH:mm'
            }
        };
        // if($state.params.request_id){
        //     params = {};
        //     params.requestId = $state.params.request_id;
        //     JobRequestGet.get(params, function(response){
        //         $scope.datePicker={"startDate": moment(new Date(response.data.appointment_from_date)), "endDate": moment(new Date(response.data.appointment_to_date))};
        //         $scope.booking_time = {"start" : moment(response.data.appointment_from_time, 'HH:mm:ss'), "end" : moment(response.data.appointment_to_time, 'HH:mm:ss')};
        //     });
        // }
        $scope.hideService = function(){
            $scope.hide_service = true;
            $scope.bookingChange(ConstService.Interview);
        };
         $scope.enableService = function(){
            $scope.hide_service = false;
            $scope.selectedService = $scope.services[0].id;
            $scope.bookingChange($scope.services[0].id);
        };
        $scope.unique= function(list) {
            var result = [];
            $.each(list, function(i, e) {
                if ($.inArray(e, result) == -1) result.push(e);//jshint ignore:line
            });
            return result;
        };
    });
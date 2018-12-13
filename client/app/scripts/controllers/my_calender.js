'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:CalenderController
 * @description
 * # CalenderController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:CalenderController
     * @description
     *
     * This is dashboard controller. It contains all the details about the user. It fetches the data of the user by using AuthFactory.
     **/
    .controller('CalenderController', function ($scope, $filter, $rootScope, $location, $compile, $timeout, calenderEvents, appointmentSetting, flash, UsersFactory, $cookies, $state, Modifications, ProviderCalendar, ModificationsMultiple, ModificationsDelete, $http, uiCalendarConfig, appointmentModificationDelete, ConstCalendarStatus, ConstAppointmentStatus, ConstBookingType) {
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0) {
            $state.go('user_profile', { type: 'personal' });        }
        $scope.events = []; //jshint ignore:line
        $scope.unavail = [];
        $scope.avail = [];
        $scope.unavailable_dates = [];
        $scope.makeavailable = [];
        $scope.common_break = [];
        $scope.monday_common_break = [];
        $scope.tuesday_common_break = [];
        $scope.wednesday_common_break = [];
        $scope.thursday_common_break = [];
        $scope.friday_common_break = [];
        $scope.saturday_common_break = [];
        $scope.sunday_common_break = [];
        $scope.is_time_enabled = false;
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("My Calender");
        };
        $scope.calendar_slots = [{'name':15},{'name':30},{'name':45},{'name':60},{'name':75},{'name':90},{'name':105},{'name':120}];
        $scope.practice = {};
        $scope.practice.start = new Date();
        $scope.practice.end = new Date();
        $scope.selectedDate = [];
        $scope.avail_checked = false;
        $scope.ConstCalendarStatus =ConstCalendarStatus;
        $scope.setting = {"is_monday":false,"is_tuesday":false,"is_wednesday":false,"is_thursday":false,"is_friday":false,"is_saturday":false,"is_sunday":false};
        $rootScope.subHeader = "Calendar"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        var params = {};
        $scope.disabledDates = [];
        $scope.allowedDays = [];
        $scope.dayoffs =[];
         $scope.testArray = [];
          params.username = $rootScope.auth.id;
        params.filter = '{"include":{"0":"user_profile","1":"appointment_settings","2":"appointment_modification","3":"service_users.service"}}';
        UsersFactory.get(params, function (response) {
            $scope.user_profile = response.data.user_profile;
            $scope.appoinment_settings = response.data.appointment_settings;
            $scope.practice.calendar_slot_id = 1;
            if(parseInt($scope.appoinment_settings.type) === 0){
                $scope.setting.same_for_all_days = true;
            }
            angular.forEach(response.data.service_users, function(value){
                if(parseInt(value.service.booking_option_id) === ConstBookingType.TimeSlot || parseInt(value.service.booking_option_id) === ConstBookingType.MultiHours){
                    $scope.is_time_enabled = true;
                    $scope.practice.calendar_slot_id = $scope.appoinment_settings.calendar_slot_id;
                } 
            });
            $scope.updateCalendar();            
            $scope.appoinment_modification = response.data.appointment_modification;
            angular.forEach($scope.appoinment_modification, function (value) {
                $scope.allowedDays.push(
                    {
                        date: value.unavailable_date, //anything usable with moment
                        selectable: false,

                    });
            });
        });
        
        //uib timer settings ends here
         $scope.dayClick = function(date1, allDay, jsEvent, view) //jshint ignore:line
       {  //jshint ignore:line
            var date = moment(date1).format('YYYY-MM-DD');
            $scope.selectedDate = moment(new Date(date)).format('YYYY-MM-DD');
            $scope.currenct_date = moment(new Date()).format('YYYY-MM-DD');
            $scope.viewing_month = (new Date($scope.calendarDate).getMonth())+1;
            $scope.selected_month = (new Date(date).getMonth())+1; 
            $scope.dayIndex = (new Date(date).getDay());
            $scope.alreadyBooked = false;
            //selected date is past date
            if($scope.selectedDate >= $scope.currenct_date && $scope.viewing_month == $scope.selected_month){//jshint ignore:line
                                    
                 $('.fc-day[data-date=' + date + ']').removeClass("fc-today");
            }else{
                return;
            }
            angular.forEach($scope.events, function(value){
                if(value.start === date && parseInt(value.appointment_status_id) === ConstAppointmentStatus.Confirmed){
                    $scope.alreadyBooked = true;
                }
            });
            if($scope.alreadyBooked === true){
                return;
            }

           $scope.SelectedDateBG = $(this).css('background-color');
           $scope.modification ={};
           $scope.is_need_to_remove = false;
           $scope.is_already_there = false;
           $scope.modification.id = '';
           angular.forEach($scope.unavail, function(value){
                if(date == value.start && value.id){ //jshint ignore:line
                        $scope.is_need_to_remove = true;  
                        $scope.modification.id = value.id;        
                }
           });
           if($scope.setting.is_monday === false && parseInt($scope.dayIndex) === 1 ){
                $scope.is_need_to_remove = true;
           }else if($scope.setting.is_tuesday === false && parseInt($scope.dayIndex) === 2 ){
                $scope.is_need_to_remove = true;
           }else if($scope.setting.is_wednesday === false && parseInt($scope.dayIndex) === 3 ){
                $scope.is_need_to_remove = true;
           }else if($scope.setting.is_thursday === false && parseInt($scope.dayIndex) === 4 ){
                $scope.is_need_to_remove = true;
           }else if($scope.setting.is_friday === false && parseInt($scope.dayIndex) === 5 ){
                $scope.is_need_to_remove = true;
           }else if($scope.setting.is_saturday === false && parseInt($scope.dayIndex) === 6 ){
                $scope.is_need_to_remove = true;
           }else if($scope.setting.is_sunday === false && parseInt($scope.dayIndex) === 0 ){
                $scope.is_need_to_remove = true;
           }
           if($scope.is_need_to_remove === true){
               if(!$scope.modification.id){
                   angular.forEach($scope.avail, function(value){
                       if(date == value.start && value.id){ //jshint ignore:line
                                $scope.modification.id = value.id;        
                        }
                   });
               }
                var j=0; var k = 0;
                angular.forEach($scope.makeavailable, function(value){
                    if(date === value.date){
                        $scope.is_already_there = true;
                        k = j;           
                    }
                    j = j+1;
                });
                if($scope.is_already_there === true){
                    $scope.makeavailable.splice(k,1);
                    // $(this).css('background-color','rgb(238,238,238)');
                    if($('.fc-day[data-date=' + date + ']').hasClass('notAvailable')){
                        $('.fc-day[data-date=' + date + ']').removeClass("notAvailable");
                        $('.fc-day[data-date=' + date + ']').addClass("available");
                    }else{
                        $('.fc-day[data-date=' + date + ']').addClass("notAvailable");
                        $('.fc-day[data-date=' + date + ']').removeClass("available");
                    }
                    
                    return;
                }else if($scope.is_already_there === false){
                    $scope.makeavailable.push({'date':date,"id":$scope.modification.id});
                    if($('.fc-day[data-date=' + date + ']').hasClass('available')){
                         $('.fc-day[data-date=' + date + ']').addClass("notAvailable");
                        $('.fc-day[data-date=' + date + ']').removeClass("available");
                    }else{
                        $('.fc-day[data-date=' + date + ']').removeClass("notAvailable");
                        $('.fc-day[data-date=' + date + ']').addClass("available");
                    }
                    
                    return;
                }
            }
            if($scope.SelectedDateBG ==='rgba(0, 0, 0, 0)' || $scope.SelectedDateBG === 'rgb(252, 248, 227)'){
                //Checking whether date already in the list
                $scope.is_there = false;
                angular.forEach($scope.unavailable_dates, function(value){
                    if(date === value.date){
                        $scope.is_there = true;          
                    }
                });
                if($scope.is_there === false && $scope.is_need_to_remove === false){
                    $scope.unavailable_dates.push({"date":date}); 
                    $scope.unavail.push({'start':date});
                    //$(this).css('background-color','rgb(238,238,238)');
                    $('.fc-day[data-date=' + date + ']').addClass("notAvailable");
                    $('.fc-day[data-date=' + date + ']').removeClass("available");
                }
                
            }else if($scope.SelectedDateBG === 'rgb(238, 238, 238)'){
                //$(this).css('background-color','rgba(0,0,0,0)');
                $('.fc-day[data-date=' + date + ']').removeClass("notAvailable");
                $('.fc-day[data-date=' + date + ']').addClass("available");
                $scope.is_there = false;
                var i = 0;
                angular.forEach($scope.unavailable_dates, function(value){
                    if(date === value.date){
                        $scope.unavailable_dates.splice(i,1);          
                    }
                    i = i+1;
                });
                i = 0;
                angular.forEach($scope.unavail, function(value){
                    if(date === value.start){
                        $scope.unavail.splice(i,1);          
                        //$(this).css('background-color','rgb(0,0,0)'); 
                        $('.fc-day[data-date=' + value.start + ']').addClass("available");
                        $('.fc-day[data-date=' + value.start + ']').removeClass("notAvailable");
                    }
                    i = i+1;
                });
            }
            //need to add fc-today class if selected date is current date.
            if($scope.selectedDate === $scope.currenct_date){
                if($('.fc-day[data-date=' + date + ']').hasClass("available")){
                    $('.fc-day[data-date=' + date + ']').addClass("fc-today");
                }else if($('.fc-day[data-date=' + date + ']').hasClass("notAvailable")){
                    $('.fc-day[data-date=' + date + ']').removeClass("fc-today");
                }
            }
        };
        
        $scope.uiConfig = {
             calendar:{
                height: 450,
                header:{
                    left: 'title',
                    center: '',    
                    right: 'prev,next'
                    },
                viewRender: function(view, element, calendar){//jshint ignore:line
                    $scope.calendarDate =  moment($('#calendar').fullCalendar('getDate'));//jshint: ignore line
                    $scope.calendarMonth = $scope.calendarDate.format('M');
                    $scope.calendarYear = moment($scope.calendarDate).format('YYYY');
                    $scope.getCalendar();
                    if(uiCalendarConfig.calendars[calendar]){
                        uiCalendarConfig.calendars[calendar].fullCalendar('changeView',view);
                        
                    }
                },
                dayClick: $scope.dayClick
                }
        };
        $scope.renderCalender = function(calendar) {
            if(uiCalendarConfig.calendars[calendar]){
                uiCalendarConfig.calendars[calendar].fullCalendar('render');
            }
        };
        
         
        $scope.getCalendar = function(){
        params = {};
        params.month = $scope.calendarMonth;
        params.year = $scope.calendarYear;
       
        ProviderCalendar.get(params,{'serviceProviderId':$rootScope.auth.id}, function(response){
            $scope.calendarData = response;
               angular.forEach(response, function(value){
                    $scope.start = value.start !== undefined ? value.start.split('T') : value.start;
                    $scope.end = value.end !== undefined ? value.end.split('T') : value.end;
                   if(value.title && value.title !== 'Available' && value.title !== 'full-day-off' && value.title !== 'week-off' && value.title !== 'Unavailable'){
                      $scope.events.push({'title':value.title,'start':$scope.start[0],'end':$scope.end[0],'color':value.color});
                  }else if(value.title === 'full-day-off'){
                      $scope.unavail.push({'id':value.appointment_modification_id,'start':$scope.start[0],color: '#eee'});
                      }else if(value.title === 'Available'){
                      $scope.avail.push({'id':value.appointment_modification_id,'start':$scope.start[0],color: '#eee'});
                  }else if(value.title === 'week-off'){
                      $scope.testArray.push({'id':value.appointment_modification_id,'start':$scope.start[0],color: '#eee'});
                  }
        });
        $timeout(function(){
            $scope.updateCalendar2();
        },0);
        $scope.eventSources = [$scope.events];
    });

        };
        //$scope.getCalendar();
        
      
                    
        $scope.get_day = function () {
            $scope.tempArray = [];   
            var modificationParams = {};     
                
            angular.forEach($scope.unavailable_dates, function(value){
               $scope.temp = false;
               $scope.tempdate = new Date(value.date);
               $scope.tempday = $scope.tempdate.getDay();
               if ($scope.setting.is_monday === true) {
                   if($scope.tempday === 1){
                       $scope.temp = true;
                   }
                }
                else if ($scope.setting.is_tuesday === true) {
                    if($scope.tempday === 2){
                       $scope.temp = true;
                   }
                }else if ($scope.setting.is_wednesday === true) {
                    if($scope.tempday === 3){
                       $scope.temp = true;
                   }
                }else if ($scope.setting.is_thursday === true) {
                    if($scope.tempday === 4){
                       $scope.temp = true;
                   }
                }else if ($scope.setting.is_friday === true) {
                    if($scope.tempday === 5){
                       $scope.temp = true;
                   }
                }else if ($scope.setting.is_saturday === true) {
                    if($scope.tempday === 6){
                       $scope.temp = true;
                   }
                }else if ($scope.setting.is_sunday === true) {
                    if($scope.tempday === 0){
                      // $scope.tempArray.push(key);
                       $scope.temp = true;
                   }
                }
                if($scope.temp === false){
                   modificationParams = {};
                   modificationParams.user_id = $rootScope.auth.id;
                   if(value.id){
                    modificationParams.id= value.id;     
                   }
                   modificationParams.unavailable_date= value.date;
                   modificationParams.type = $scope.ConstCalendarStatus.Make_a_Day_Fully_Off;
                   $scope.tempArray.push(modificationParams);
                }
            });
           if($scope.tempArray.length > 0){
               var postVariable = {};
               postVariable.appointment_modifications = $scope.tempArray;
               ModificationsMultiple.post(postVariable, function(response){
                    if(response.error.code === 0){
                        $scope.unavailable_dates = [];
                        angular.forEach(response.data, function(value){
                            $scope.unavailable_dates.push({"date":value.unavailable_date,"id":value.id});
                        }); 
                    }
                });
           }
           $scope.temp_modification_ids = [];
           if($scope.makeavailable.length > 0){
               angular.forEach($scope.makeavailable, function(value){
                
                   $scope.temp_modification_ids.push({"id":value.id});
               });
              
              params = {};
              params.ids=$scope.temp_modification_ids;
              angular.forEach($scope.makeavailable, function(value){
                  if(value.id){
                      appointmentModificationDelete.delete({'appointmentModificationId':value.id}, function(){
                      });
                  }else{
                      params = {};
                      params.user_id = $rootScope.auth.id;
                      params.unavailable_date = value.date;
                      params.type = $scope.ConstCalendarStatus.Make_a_Day_Fully_On;
                      Modifications.post(params, function(){
                      });
                  }
              });
            
                
           }
            params = {};
            params.id = $scope.appoinment_settings.id; // appoinment setting id
            if ($scope.setting.is_monday === true) {
                params.is_monday_open = 1;
            }else{
                   params.is_monday_open = 0;
            } if ($scope.setting.is_tuesday === true) {
                params.is_tuesday_open = 1;
            }else{
                params.is_tuesday_open = 0;
            }if ($scope.setting.is_wednesday === true) {
                params.is_wednesday_open = 1;
            }else{
                params.is_wednesday_open = 0;
            } if ($scope.setting.is_thursday === true) {
                params.is_thursday_open = 1;
            }else{
                params.is_thursday_open = 0;
            } if ($scope.setting.is_friday === true) {
                params.is_friday_open = 1;
            }else{
                params.is_friday_open = 0;
            } if ($scope.setting.is_saturday === true) {
                params.is_saturday_open = 1;
            }else{
                params.is_saturday_open = 0;
            } if ($scope.setting.is_sunday === true) {
                params.is_sunday_open = 1;
            }else{
                params.is_sunday_open = 0;
            } 
            if ($scope.setting.same_for_all_days === true) {
                params.type = 0;
            } else {
                params.type = 1;
            }
            params.calendar_slot_id = $scope.practice.calendar_slot_id;
            if($scope.is_time_enabled === true){
                params.monday_practice_open = $scope.practice.monday_practice_open ? moment($scope.practice.monday_practice_open).format('HH:mm:ss') : null;
                params.monday_practice_close = $scope.practice.monday_practice_close ? moment($scope.practice.monday_practice_close).format('HH:mm:ss') : null;
                params.tuesday_practice_open = $scope.practice.tuesday_practice_open ? moment($scope.practice.tuesday_practice_open).format('HH:mm:ss') : null;
                params.tuesday_practice_close = $scope.practice.tuesday_practice_close ? moment($scope.practice.tuesday_practice_close).format('HH:mm:ss') : null;
                params.wednesday_practice_open = $scope.practice.wednesday_practice_open ? moment($scope.practice.wednesday_practice_open).format('HH:mm:ss') : null;
                params.wednesday_practice_close = $scope.practice.wednesday_practice_close ? moment($scope.practice.wednesday_practice_close).format('HH:mm:ss') : null;
                params.thursday_practice_open = $scope.practice.thursday_practice_open ? moment($scope.practice.thursday_practice_open).format('HH:mm:ss') : null;
                params.thursday_practice_close = $scope.practice.thursday_practice_close ? moment($scope.practice.thursday_practice_close).format('HH:mm:ss') : null;
                params.friday_practice_open = $scope.practice.friday_practice_open ? moment($scope.practice.friday_practice_open).format('HH:mm:ss') : null;
                params.friday_practice_close = $scope.practice.friday_practice_close ? moment($scope.practice.friday_practice_close).format('HH:mm:ss') : null;
                params.saturday_practice_open =$scope.practice.saturday_practice_open ? moment($scope.practice.saturday_practice_open).format('HH:mm:ss') : null;
                params.saturday_practice_close =$scope.practice.saturday_practice_close ? moment($scope.practice.saturday_practice_close).format('HH:mm:ss') : null;
                params.sunday_practice_open = $scope.practice.sunday_practice_open ? moment($scope.practice.sunday_practice_open).format('HH:mm:ss') : null;
                params.sunday_practice_close = $scope.practice.sunday_practice_close ? moment($scope.practice.sunday_practice_close).format('HH:mm:ss'): null;
                params.practice_open = $scope.practice.start ? moment($scope.practice.start).format('HH:mm:ss') : null;   
                params.practice_close = $scope.practice.end ? moment($scope.practice.end).format('HH:mm:ss') : null;
                $scope.break_temp_array = [];
                var obj = {};
                if($scope.common_break.length > 0){
                    angular.forEach($scope.common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "AllDay";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }    
                    });

                }
                if($scope.monday_common_break.length > 0){
                    angular.forEach($scope.monday_common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Monday";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                        
                    });

                }
                if($scope.tuesday_common_break.length > 0){
                    angular.forEach($scope.tuesday_common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Tuesday";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                    });

                }
                if($scope.wednesday_common_break.length > 0){
                    angular.forEach($scope.wednesday_common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Wednesday";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                    });

                }
                if($scope.thursday_common_break.length > 0){
                    angular.forEach($scope.thursday_common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Thursday";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                    });
                }
                if($scope.friday_common_break.length > 0){
                    angular.forEach($scope.friday_common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Friday";
                           obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                    });
                }
                if($scope.saturday_common_break.length > 0){
                    angular.forEach($scope.saturday_common_break, function(value){
                        if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Saturday";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                    });
                }
                if($scope.sunday_common_break.length > 0){
                    angular.forEach($scope.sunday_common_break, function(value){
                    if(value.break_start_time && value.break_end_time){
                            obj = {};
                            obj.type = ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively;
                            obj.day = "Sunday";
                            obj.unavailable_from_time = moment(value.break_start_time).format('HH:mm:ss');
                            obj.unavailable_to_time = moment(value.break_end_time).format('HH:mm:ss');
                            $scope.break_temp_array.push(obj);
                        }
                    });
                }
                params.appointment_modifications = $scope.break_temp_array;
            }else{
                params.monday_practice_open = "00:00:00";
                params.monday_practice_close = "23:59:59";
                params.tuesday_practice_open = "00:00:00";
                params.tuesday_practice_close = "23:59:59";
                params.wednesday_practice_open = "00:00:00";
                params.wednesday_practice_close = "23:59:59";
                params.thursday_practice_open = "00:00:00";
                params.thursday_practice_close = "23:59:59";
                params.friday_practice_open = "00:00:00";
                params.friday_practice_close = "23:59:59";
                params.saturday_practice_open = "00:00:00";
                params.saturday_practice_close = "23:59:59";
                params.sunday_practice_open = "00:00:00";
                params.sunday_practice_close = "23:59:59";
                params.practice_open = "00:00:00";   
                params.practice_close = "23:59:59";
            }
            
            appointmentSetting.put(params, function(response){
               if(response.error.code === 0){
                        flash.set($filter("translate")("Calendar updated Successfully"), 'success', true);
                    }
            });
            
            
        };
                $scope.getStartTime = function(day,index){
                    if(day === 0){
                        $scope.common_break[index].break_start_time = $scope.common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.common_break[index].break_start_time;
                    }else if(day === 1){
                        $scope.monday_common_break[index].break_start_time = $scope.monday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.monday_common_break[index].break_start_time;
                    }else if(day === 2){
                        $scope.tuesday_common_break[index].break_start_time = $scope.tuesday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.tuesday_common_break[index].break_start_time;
                    }else if(day === 3){
                        $scope.wednesday_common_break[index].break_start_time = $scope.wednesday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.wednesday_common_break[index].break_start_time;
                    }else if(day === 4){
                        $scope.thursday_common_break[index].break_start_time = $scope.thursday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.thursday_common_break[index].break_start_time;
                    }else if(day === 5){
                        $scope.friday_common_break[index].break_start_time = $scope.friday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.friday_common_break[index].break_start_time;
                    }else if(day === 6){
                        $scope.saturday_common_break[index].break_start_time = $scope.saturday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.saturday_common_break[index].break_start_time;
                    }else if(day === 7){
                        $scope.sunday_common_break[index].break_start_time = $scope.sunday_common_break[index].break_start_time !== null ? moment("18:00:00", 'HH:mm:ss') : $scope.sunday_common_break[index].break_start_time;
                    } 
                };
                $scope.getEndTime = function(day, index){
                    if(day === 0){
                        $scope.common_break[index].break_end_time = $scope.common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.common_break[index].break_end_time;
                    }else if(day === 1){
                        $scope.monday_common_break[index].break_end_time = $scope.monday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.monday_common_break[index].break_end_time;
                    }else if(day === 2){
                        $scope.tuesday_common_break[index].break_end_time = $scope.tuesday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.tuesday_common_break[index].break_end_time;
                    }else if(day === 3){
                        $scope.wednesday_common_break[index].break_end_time = $scope.wednesday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.wednesday_common_break[index].break_end_time;
                    }else if(day === 4){
                        $scope.thursday_common_break[index].break_end_time = $scope.thursday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.thursday_common_break[index].break_end_time;
                    }else if(day === 5){
                        $scope.friday_common_break[index].break_end_time = $scope.friday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.friday_common_break[index].break_end_time;
                    }else if(day === 6){
                        $scope.saturday_common_break[index].break_end_time = $scope.saturday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.saturday_common_break[index].break_end_time;
                    }else if(day === 7){
                        $scope.sunday_common_break[index].break_end_time = $scope.sunday_common_break[index].break_end_time !== null ? moment("19:00:00", 'HH:mm:ss') : $scope.sunday_common_break[index].break_end_time;
                    } 
                };
        $scope.week_avail = function(day){
            if(day === 1){
                if ($scope.setting.is_monday === true) {
                    $('.fc-mon').addClass("available");
                    $('.fc-mon').removeClass("notAvailable");
                }else if($scope.setting.is_monday === false) {
                    $('.fc-mon').removeClass("available");
                    $('.fc-mon').addClass("notAvailable");
                }
            }else if(day === 2){
                if ($scope.setting.is_tuesday === true) {
                    $('.fc-tue').removeClass("notAvailable");
                     $('.fc-tue').addClass("available");
                }else if($scope.setting.is_tuesday === false) {
                    $('.fc-tue').addClass("notAvailable");
                    $('.fc-tue').removeClass("available");
                }

            }else if(day === 3){
                if ($scope.setting.is_wednesday === true) {
                    $('.fc-wed').addClass("available");
                    $('.fc-wed').removeClass("notAvailable");
                }else if($scope.setting.is_wednesday === false){
                    $('.fc-wed').removeClass("available");
                    $('.fc-wed').addClass("notAvailable");
                }

            }else if(day === 4){
                if ($scope.setting.is_thursday === true) {
                     $('.fc-thu').removeClass("notAvailable");
                     $('.fc-thu').addClass("available");
                }else if($scope.setting.is_thursday === false){
                    $('.fc-thu').addClass("notAvailable");
                    $('.fc-thu').removeClass("available");
                } 

            }else if(day === 5){
                if ($scope.setting.is_friday === true) {
                    $('.fc-fri').removeClass("notAvailable");
                    $('.fc-fri').addClass("available");
                }else if($scope.setting.is_friday === false){
                    $('.fc-fri').addClass("notAvailable");
                    $('.fc-fri').removeClass("available");
                } 

            }else if(day === 6){
                if ($scope.setting.is_saturday === true) {
                    $('.fc-sat').removeClass("notAvailable");
                    $('.fc-sat').addClass("available");
                }else if($scope.setting.is_saturday === false){
                    $('.fc-sat').removeClass("available");
                    $('.fc-sat').addClass("notAvailable");
                } 

            }else if(day === 7){
               if ($scope.setting.is_sunday === true) {
                    $('.fc-sun').addClass("available");
                    $('.fc-sun').removeClass("notAvailable");
                }else if($scope.setting.is_sunday === false){
                    $('.fc-sun').removeClass("available");
                    $('.fc-sun').addClass("notAvailable");
                }
            }

            angular.forEach($scope.testArray, function(value){
                $scope.tempdate = new Date(value.start);
                $scope.tempday = $scope.tempdate.getDay();
                $scope.tempday = $scope.tempday === 0 ? 7: $scope.tempday; //jshint: ignore line
                $scope.checked = false;
                if(day === $scope.tempday){
                    if(day === 1){
                        $scope.checked = $scope.setting.is_monday;
                    }else if(day === 2){
                        $scope.checked = $scope.setting.is_tuesday;
                    }else if(day === 3){
                        $scope.checked = $scope.setting.is_wednesday;
                    }else if(day === 4){
                        $scope.checked = $scope.setting.is_thursday;
                    }else if(day === 5){
                        $scope.checked = $scope.setting.is_friday;
                    }else if(day === 6){
                        $scope.checked = $scope.setting.is_saturday;
                    }else if(day === 7){
                        $scope.checked = $scope.setting.is_sunday;
                    }

                }
                if(day === $scope.tempday && $scope.checked === true){
                    $('.fc-day[data-date=' + value.start + ']').removeClass("notAvailable");
                    $('.fc-day[data-date=' + value.start + ']').addClass("available");
                }else if(day === $scope.tempday && $scope.checked === false){
                    $('.fc-day[data-date=' + value.start + ']').addClass("notAvailable");
                    $('.fc-day[data-date=' + value.start + ']').removeClass("available");
                }        
                });
                angular.forEach($scope.unavail, function(value){
                   $('.fc-day[data-date=' + value.start + ']').addClass("notAvailable");
                   $('.fc-day[data-date=' + value.start + ']').removeClass("available");
                });
                angular.forEach($scope.avail, function(value){
                    if(value.id){
                        $('.fc-day[data-date=' + value.start + ']').removeClass("notAvailable");
                        $('.fc-day[data-date=' + value.start + ']').addClass("available");
                    }        
                });
                $('.fc-other-month').addClass("notAvailable");
                $('.fc-past').addClass("notAvailable");
            
        };
        $scope.alldayavailable = function(avail_checked){
            $('#showHide').slideToggle("slow");
            if(avail_checked === true){
                $scope.setting = {"is_monday":true,"is_tuesday":true,"is_wednesday":true,"is_thursday":true,"is_friday":true,"is_saturday":true,"is_sunday":true};
            }else{
                $scope.setting.is_monday = parseInt($scope.appoinment_settings.is_monday_open) === 1 ? true : false;
                $scope.setting.is_tuesday = parseInt($scope.appoinment_settings.is_tuesday_open) === 1 ? true : false;
                $scope.setting.is_wednesday = parseInt($scope.appoinment_settings.is_wednesday_open) === 1 ? true : false;
                $scope.setting.is_thursday = parseInt($scope.appoinment_settings.is_thursday_open) === 1 ? true : false;
                $scope.setting.is_friday = parseInt($scope.appoinment_settings.is_friday_open) === 1 ? true : false;
                $scope.setting.is_saturday = parseInt($scope.appoinment_settings.is_saturday_open) === 1 ? true : false;
                $scope.setting.is_sunday = parseInt($scope.appoinment_settings.is_sunday_open) === 1 ? true : false;
            }
            $scope.days_no = [1,2,3,4,5,6,7];
            angular.forEach($scope.days_no, function(value){
                $scope.week_avail(value);
            });
        };
       
        $scope.updateCalendar = function(){
                    $timeout(function () {
                    if (parseInt($scope.appoinment_settings.is_monday_open) === 1 || $scope.setting.is_monday === true) {
                        $scope.setting.is_monday = true;
                        $('.fc-mon').addClass("available");
                        $('.fc-mon').removeClass("notAvailable");
                    }else{
                        $('.fc-mon').removeClass("available");
                        $('.fc-mon').addClass("notAvailable ");
                    }
                    if (parseInt($scope.appoinment_settings.is_tuesday_open) === 1 || $scope.setting.is_tuesday === true) {
                        $scope.setting.is_tuesday = true;
                        $('.fc-tue').addClass("available");
                        $('.fc-tue').removeClass("notAvailable");
                    }else{
                        $('.fc-tue').removeClass("available");
                        $('.fc-tue').addClass("notAvailable");
                    }
                    if (parseInt($scope.appoinment_settings.is_wednesday_open) === 1 || $scope.setting.is_wednesday === true) {
                        $scope.setting.is_wednesday = true;
                       $('.fc-wed').addClass("available");
                       $('.fc-wed').removeClass("notAvailable");
                    }else{
                        $('.fc-wed').removeClass("available");
                        $('.fc-wed').addClass("notAvailable");
                    }
                    if (parseInt($scope.appoinment_settings.is_thursday_open) === 1 || $scope.setting.is_thursday === true) {
                        $scope.setting.is_thursday = true;
                        $('.fc-thu').addClass("available");
                        $('.fc-thu').removeClass("notAvailable");
                    }else{
                        $('.fc-thu').removeClass("available");
                        $('.fc-thu').addClass("notAvailable");
                    }
                    if (parseInt($scope.appoinment_settings.is_friday_open) === 1 || $scope.setting.is_friday === true) {
                        $scope.setting.is_friday = true;
                        $('.fc-fri').addClass("available");
                        $('.fc-fri').removeClass("notAvailable");
                    }else{
                        $('.fc-fri').removeClass("available");
                        $('.fc-fri').addClass("notAvailable");
                    }
                    if (parseInt($scope.appoinment_settings.is_saturday_open) === 1 || $scope.setting.is_saturday === true) {
                        $scope.setting.is_saturday = true;
                        $('.fc-sat').addClass("available");
                        $('.fc-sat').removeClass("notAvailable");
                    }else{
                        $('.fc-sat').addClass("available");
                        $('.fc-sat').removeClass("notAvailable");
                    }
                    
                    if (parseInt($scope.appoinment_settings.is_sunday_open) === 1 || $scope.setting.is_sunday === true) {
                        $scope.setting.is_sunday = true;
                        $('.fc-sun').addClass("available");
                        $('.fc-sun').removeClass("notAvailable");
                    }else{
                        $('.fc-sun').addClass("available");
                        $('.fc-sun').removeClass("notAvailable");
                    }
                    
                    $timeout(function(){
                        $('.fc-other-month').addClass("notAvailable");
                        $('.fc-past').addClass("notAvailable");
                        angular.forEach($scope.unavail, function(value){
                            $('.fc-day[data-date=' + value.start + ']').addClass("notAvailable");
                            $('.fc-day[data-date=' + value.start + ']').removeClass("available");
                        });
                        angular.forEach($scope.testArray, function(value){
                            $('.fc-day[data-date=' + value.start + ']').addClass("notAvailable");
                            $('.fc-day[data-date=' + value.start + ']').removeClass("available");
                        });
                        angular.forEach($scope.makeavailable, function(value){
                            $('.fc-day[data-date=' + value.start + ']').addClass("available");
                            $('.fc-day[data-date=' + value.start + ']').removeClass("notAvailable");
                        });
                        angular.forEach($scope.avail, function(value){
                            $('.fc-day[data-date=' + value.start + ']').removeClass("notAvailable");
                            $('.fc-day[data-date=' + value.start + ']').addClass("available");
                        });
                    },5);
                    if($scope.is_time_enabled === true){
                        $scope.practice.start = $scope.appoinment_settings.practice_open !== null ? moment($scope.appoinment_settings.practice_open, 'HH:mm:ss') : moment("18:00:00", 'HH:mm:ss');
                        $scope.practice.end = $scope.appoinment_settings.practice_close !== null ? moment($scope.appoinment_settings.practice_close, 'HH:mm:ss') : moment("22:00:00", 'HH:mm:ss');
                        $scope.practice.monday_practice_open= $scope.appoinment_settings.monday_practice_open !== null ? moment($scope.appoinment_settings.monday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss'); 
                        $scope.practice.monday_practice_close =  $scope.appoinment_settings.monday_practice_close !== null ? moment($scope.appoinment_settings.monday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss');
                        $scope.practice.tuesday_practice_open = $scope.appoinment_settings.tuesday_practice_open !== null ?  moment($scope.appoinment_settings.tuesday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss'); 
                        $scope.practice.tuesday_practice_close = $scope.appoinment_settings.tuesday_practice_close !== null ?  moment($scope.appoinment_settings.tuesday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss');
                        $scope.practice.wednesday_practice_open = $scope.appoinment_settings.wednesday_practice_open !== null ? moment($scope.appoinment_settings.wednesday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss');
                        $scope.practice.wednesday_practice_close = $scope.appoinment_settings.wednesday_practice_close !== null ? moment($scope.appoinment_settings.wednesday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss'); 
                        $scope.practice.thursday_practice_open = $scope.appoinment_settings.thursday_practice_open !== null ?  moment($scope.appoinment_settings.thursday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss');
                        $scope.practice.thursday_practice_close = $scope.appoinment_settings.thursday_practice_close !== null ? moment($scope.appoinment_settings.thursday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss'); 
                        $scope.practice.friday_practice_open = $scope.appoinment_settings.friday_practice_open !== null ?  moment($scope.appoinment_settings.friday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss'); 
                        $scope.practice.friday_practice_close = $scope.appoinment_settings.friday_practice_close !== null ?  moment($scope.appoinment_settings.friday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss');
                        $scope.practice.saturday_practice_open = $scope.appoinment_settings.saturday_practice_open !== null ? moment($scope.appoinment_settings.saturday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss');
                        $scope.practice.saturday_practice_close = $scope.appoinment_settings.saturday_practice_close !== null ? moment($scope.appoinment_settings.saturday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss'); 
                        $scope.practice.sunday_practice_open = $scope.appoinment_settings.sunday_practice_open !== null ? moment($scope.appoinment_settings.sunday_practice_open, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_open, 'HH:mm:ss');
                        $scope.practice.sunday_practice_close = $scope.appoinment_settings.sunday_practice_close !== null ? moment($scope.appoinment_settings.sunday_practice_close, 'HH:mm:ss') : moment($scope.appoinment_settings.practice_close, 'HH:mm:ss');
                        angular.forEach($scope.appoinment_modification, function(value){
                            $scope.start_time = moment(value.unavailable_from_time, 'HH:mm:ss'); 
                            $scope.end_time = moment(value.unavailable_to_time, 'HH:mm:ss'); 
                            if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "AllDay"){
                                $scope.common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Monday"){
                                $scope.monday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Tuesday"){
                                $scope.tuesday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Wednesday"){
                                $scope.wednesday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Thursday"){
                                $scope.thursday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Friday"){
                                $scope.friday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Saturday"){
                                $scope.saturday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }else if(parseInt(value.type) === ConstCalendarStatus.Unavailable_In_Every_Particular_Day_And_Time_Recursively && value.day === "Sunday"){
                                $scope.sunday_common_break.push({break_start_time: $scope.start_time,break_end_time:$scope.end_time});
                            }
                        });
                                if($scope.common_break.length <= 0){
                                    $scope.common_break.push({id:1});
                                }
                                if($scope.monday_common_break.length <= 0){
                                    $scope.monday_common_break.push({id:1});
                                }
                                if($scope.tuesday_common_break.length <= 0){
                                    $scope.tuesday_common_break.push({id:1});
                                }
                                if($scope.wednesday_common_break.length <= 0){
                                    $scope.wednesday_common_break.push({id:1});
                                }
                                if($scope.thursday_common_break.length <= 0){
                                    $scope.thursday_common_break.push({id:1});
                                }
                                if($scope.friday_common_break.length <= 0){
                                    $scope.friday_common_break.push({id:1});
                                }
                                if($scope.saturday_common_break.length <= 0){
                                    $scope.saturday_common_break.push({id:1});
                                }
                                if($scope.sunday_common_break.length <= 0){
                                    $scope.sunday_common_break.push({id:1});
                                }
                    }
                    
                    
                 }, 10);
            };

            $scope.updateCalendar2 = function(){
                    $timeout(function () {
                    if ($scope.setting.is_monday === true) {
                        $('.fc-mon').addClass("available");
                        $('.fc-mon').removeClass("notAvailable");
                    }else{
                        $('.fc-mon').removeClass("available");
                        $('.fc-mon').addClass("notAvailable");
                    }
                    if ($scope.setting.is_tuesday === true) {
                        $('.fc-tue').addClass("available");
                        $('.fc-tue').removeClass("notAvailable");
                    }else{
                        $('.fc-tue').removeClass("available");
                        $('.fc-tue').addClass("notAvailable");
                    }
                    if ($scope.setting.is_wednesday === true) {
                       $('.fc-wed').addClass("available");
                       $('.fc-wed').removeClass("notAvailable");
                    }else{
                        $('.fc-wed').removeClass("available");
                        $('.fc-wed').addClass("notAvailable");
                    }
                    if ($scope.setting.is_thursday === true) {
                        $('.fc-thu').addClass("available");
                        $('.fc-thu').removeClass("notAvailable");
                    }else{
                        $('.fc-thu').removeClass("available");
                        $('.fc-thu').addClass("notAvailable");
                    }
                    if ( $scope.setting.is_friday === true) {
                        $('.fc-fri').addClass("available");
                        $('.fc-fri').removeClass("notAvailable");
                    }else{
                        $('.fc-fri').removeClass("available");
                        $('.fc-fri').addClass("notAvailable");
                    }
                    if ($scope.setting.is_saturday === true) {
                        $('.fc-sat').addClass("available");
                        $('.fc-sat').removeClass("notAvailable");
                    }else{
                        $('.fc-sat').removeClass("available");
                        $('.fc-sat').addClass("notAvailable");
                    }
                    
                    if ($scope.setting.is_sunday === true) {
                        $('.fc-sun').addClass("available");
                        $('.fc-sun').removeClass("notAvailable");
                    }else{
                        $('.fc-sun').removeClass("available");
                        $('.fc-sun').addClass("notAvailable");
                    }
                    
                    $timeout(function(){
                        angular.forEach($scope.unavail, function(value){
                            $('.fc-day[data-date=' + value.start + ']').addClass("notAvailable");
                            $('.fc-day[data-date=' + value.start + ']').removeClass("available");
                        });
                        angular.forEach($scope.testArray, function(value){
                            $('.fc-day[data-date=' + value.start + ']').addClass("notAvailable");
                            $('.fc-day[data-date=' + value.start + ']').removeClass("available");
                        });
                        angular.forEach($scope.makeavailable, function(value){
                            $('.fc-day[data-date=' + value.date + ']').addClass("available");
                            $('.fc-day[data-date=' + value.date + ']').removeClass("notAvailable");
                        });
                        var day;
                        angular.forEach($scope.avail, function(value){
                            $scope.tempdate = new Date(value.start);
                            $scope.tempday = $scope.tempdate.getDay();
                            $scope.checked = false;
                                if($scope.tempday === 1){
                                    $scope.checked = $scope.setting.is_monday;
                                    day = 1;
                                }else if($scope.tempday === 2){
                                    $scope.checked = $scope.setting.is_tuesday;
                                    day = 2;
                                }else if($scope.tempday === 3){
                                    $scope.checked = $scope.setting.is_wednesday;
                                    day = 3;
                                }else if($scope.tempday === 4){
                                    $scope.checked = $scope.setting.is_thursday;
                                    day = 4;
                                }else if($scope.tempday === 5){
                                    $scope.checked = $scope.setting.is_friday;
                                    day = 5;
                                }else if($scope.tempday === 6){
                                    $scope.checked = $scope.setting.is_saturday;
                                    day = 6;
                                }else if($scope.tempday === 7){
                                    $scope.checked = $scope.setting.is_sunday;
                                    day = 7;
                                }    
                            if(day === $scope.tempday && $scope.checked === false){
                                // $('.fc-day[data-date=' + value.start + ']').css('background-color', 'rgba(0,0,0,0)');
                                $('.fc-day[data-date=' + value.start + ']').removeClass("notAvailable");
                                $('.fc-day[data-date=' + value.start + ']').addClass("available");
                                
                             } 
                        });
                        
                    },10);
                    $('.fc-other-month').addClass("notAvailable");
                    $('.fc-past').addClass("notAvailable");
                 }, 10);
                 
            };
            //add common break time
            $scope.add_common_break = function(day){
                
                
                if(day === 0){
                    $scope.common_break.push({id:1});
                }else if(day === 1){
                    $scope.monday_common_break.push({id:1});
                }else if(day === 2){
                    $scope.tuesday_common_break.push({id:1});
                }else if(day === 3){
                    $scope.wednesday_break.push({id:1});
                }else if(day === 4){
                    $scope.thursday_break.push({id:1});
                }else if(day === 5){
                    $scope.friday_common_break.push({id:1});
                }else if(day === 6){
                    $scope.saturday_common_break.push({id:1});
                }else if(day === 7){
                    $scope.sunday_common_break.push({id:1});
                } 
            };
            $scope.remove_common_break = function(index,day){
                if(day === 0){
                    $scope.common_break.splice(index,1);
                }else if(day === 1){
                    $scope.monday_common_break.splice(index,1);
                }else if(day === 2){
                    $scope.tuesday_common_break.splice(index,1);
                }else if(day === 3){
                    $scope.wednesday_break.splice(index,1);
                }else if(day === 4){
                    $scope.thursday_break.splice(index,1);
                }else if(day === 5){
                    $scope.friday_common_break.splice(index,1);
                }else if(day === 6){
                    $scope.saturday_common_break.splice(index,1);
                }else if(day === 7){
                    $scope.sunday_common_break.splice(index,1);
                } 
            };
        $scope.init();
        $scope.eventSources = [$scope.events];
        
           });
'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.appointment
 * @description
 * # appointment
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('AppointmentFactory', ['$resource', function($resource) {
        return $resource('/api/v1/me/appointments', {}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('appointmentView', ['$resource', function($resource) {
        return $resource('/api/v1/appointments/:id', {id: '@id'}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('appointmentPut', ['$resource', function($resource) {
        return $resource('/api/v1/appointments/:appointmentId', {appointmentId: '@appointmentId'}, {
            update: {
                method: 'PUT'
            }
        });
}])
.factory('appointmentStatusDetails',['$resource', function($resource) {
        return $resource('/api/v1/appointments', {},{
             get: {
                method: 'GET'
            }
        });
    }])
.factory('appointmentSetting', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_settings/:id', {id:'@id'}, {
            put: {
                method: 'PUT'
            }
        });
}])
.factory('appointmentModification', ['$resource', function($resource) {
        return $resource('/api/v1/me/appointment_modifications', {}, {
            get: {
                method: 'GET'
            }
        });
}]) 
.factory('appointmentModificationDelete', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_modifications/:appointmentModificationId', {appointmentModificationId:'@appointmentModificationId'}, {
            delete: {
                method: 'DELETE'
            }
        });
}])    
.factory('appointmentModificationAdd', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_modifications', {}, {
            add: {
                method: 'POST'
            }
        });
}])      
.factory('appointmentModificationEdit', ['$resource', function($resource) {
        return $resource('/api/v1/appointments/modifications/edit/:id', {id: '@id'}, {
            get: {
                method: 'GET'
            },
            update:{
                method: 'POST'
            }
        });
}])   
.factory('BookingAppointment', ['$resource', function($resource) {
        return $resource('/api/v1/appointments/booking/:doctorid/:apt_date/:apt_time', {doctorid:'@doctorid', apt_date:'@apt_date', apt_time:'@apt_time'}, {
            get: {
                method: 'GET'
            }
        });
}])      
.factory('Gender', ['$resource', function($resource) {
        return $resource('/api/v1/genders', {}, {
            genderList: {
                method: 'GET'
            }
        });
}])     
.factory('AppointmentBookingAdd', ['$resource', function($resource) {
        return $resource('/api/v1/appointments/booking/add', {}, {
            add: {
                method: 'POST'
            }
        });
}])      
.factory('changeStatus', ['$resource', function($resource) {
        return $resource('/api/v1/appointments/:id/change_status', {id:'@id'}, {
            put: {
                    method: 'PUT'
                }
        });
}])      
.factory('splitedTimeSlot', ['$resource', function($resource) {
        return $resource('/api/v1/search/timeslot', {}, {
            get:{
                    method: 'GET'
                }
        });
}])            
.factory('MyDocotors', ['$resource', function($resource) {
        return $resource('/api/v1/user/favorite', {}, {
            get:{
                    method: 'GET'
                }
        });
}]).factory('Modifications', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_modifications', {}, {
            post:{
                    method: 'POST'
                }
        });
}])
.factory('ProviderCalendar', ['$resource', function($resource) {
        return $resource('/api/v1/service_provider/:serviceProviderId/appointments/calendar', {serviceProviderId:'@serviceProviderId'}, {
            get:{
                    method: 'GET'
                }
        });
}]).factory('ModificationsMultiple', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_modifications/multiple', {}, {
            post:{
                    method: 'POST'
                }
        });
}]).factory('ModificationsDelete', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_modifications/multiple', {}, {
            remove:{
                    method: 'DELETE',
                    hasBody: true
                }
        });
}]);

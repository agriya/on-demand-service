angular.module('hirecoworkerApp')
.factory('SmsRecend', ['$resource', function($resource) {
       return $resource('/api/v1/users/resend_otp', {}, {
            'post': {
                method: 'POST'
            }
        });
}])
.factory('SmsVerify', function ($resource) {
        return $resource('/api/v1/users/:id/verify_otp', {id:'@id'}, {
            'save': {
                method: 'POST'
            }
        });
    });


'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('usersLogin', ['$resource', function($resource) {
        return $resource('/api/v1/users/login', {}, {
            login: {
                method: 'POST'
            }
        });
}])
.factory('twitterLogin', ['$resource', function($resource) {
        return $resource('/api/v1/users/social_login', {}, {
            login: {
                method: 'POST'
            }
        });
}])
.factory('usersRegister', ['$resource', function($resource) {
        return $resource('/api/v1/users/register', {}, {
            create: {
                method: 'POST'
            }
        });
    }])
.factory('usersLogout', ['$resource', function($resource) {
        return $resource('/api/v1/users/logout', {}, {
            logout: {
                method: 'GET'
            }
        });
}])        
.factory('AuthFactory', ['$resource', function($resource) {
        return $resource('/api/v1/users/auth', {}, {
            fetch: {
                method: 'GET'
            }
        });
}])
.factory('providers', ['$resource', function($resource) {
        return $resource('/api/v1/providers', {}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('UserProfilesFactory', ['$resource', function($resource) {
        return $resource('/api/v1/user_profiles', {}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
}])
.factory('UsersFactory', ['$resource', function($resource) {
        return $resource('/api/v1/users/:username', { username: '@username'}, {filter:'@filter'}, {
            get: {
                method: 'GET'
            }
        });
}]) .factory('CloseAccountFactory', ['$resource', function($resource) {
            return $resource('/api/v1/account_close_reasons', {}, {
                get: {
                    method: 'GET'
                }
            });
    }])
.factory('UserActivateFactory', ['$resource', function($resource) {
        return $resource('/api/v1/users/:userId/activate/:hash', { userId: '@userId', hash: '@hash'}, {
            activate: {
                method: 'GET'
            }
        });
}])
.factory('ForgotPasswordFactory', ['$resource', function($resource) {
        return $resource('/api/v1/users/forgot_password', {}, {
            forgot_password: {
                method: 'POST'
            }
        });
}]) 
.factory('YachtTypes', ['$resource', function($resource) {
        return $resource('/api/v1/yacht_types', {}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('YachtSizeExperiences', ['$resource', function($resource) {
        return $resource('/api/v1/yacht_size_experiences', {}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('ChangePWd', ['$resource', function($resource) {
        return $resource('/api/v1/users/:id/change_password', {id: '@id'}, {
            put: {
                method: 'PUT'
            }
        });
}]) 
.factory('UserAttachmentFactory', ['$resource', function($resource) {
        return $resource('/api/v1/users/:id/attachment', {id: '@id'}, {
            get: {
                method: 'GET'
            }
        });
}]) 
.factory('Service', ['$resource', function($resource) {
        return $resource('/api/v1/services', {}, {
            get: {
                method: 'GET'
            }
        });
}])      
.factory('Genders', ['$resource', function($resource) {
        return $resource('/api/v1/genders', {}, {
            genderList: {
                method: 'GET'
            }
        });
}])   
.factory('Language', ['$resource', function($resource) {
        return $resource('/api/v1/languages', {}, {
            languageList: {
                method: 'GET'
            }
        });
}])     
.factory('City', ['$resource', function($resource) {
        return $resource('/api/v1/cities', {}, {
            cityList: {
                method: 'GET'
            }
        });
}])    
.factory('States', ['$resource', function($resource) {
        return $resource('/api/v1/states', {}, {
            stateList: {
                method: 'GET'
            }
        });
}])   
.factory('Country', ['$resource', function($resource) {
        return $resource('/api/v1/countries', {}, {
            countryList: {
                method: 'GET'
            }
        });
}]) 
.factory('Category', ['$resource', function($resource) {
        return $resource('/api/v1/categories', {}, {
            categoryList: {
                method: 'GET'
            }
        });
}])     
.factory('UserNotification', ['$resource', function($resource) {
        return $resource('/api/v1/users/:id', {id : '@id'},{}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
}]) 
.factory('MyServices', ['$resource', function($resource) {
        return $resource('/api/v1/me/services_users', {filter:'@filter'}, {
            get: {
                method: 'GET'
            }
        });
}])  
.factory('ServiceGet', ['$resource', function($resource) {
        return $resource('/api/v1/services/:servicesId', {servicesId:'@servicesId'}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
}])       
.factory('MyAppointment', ['$resource', function($resource) {
        return $resource('/api/v1/service/form_fileds/:id', {id: '@id'}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
}])      
.factory('MyLanguages', ['$resource', function($resource) {
        return $resource('/api/v1/user/languages', {}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
}])      
.factory('ProfileSearchList', ['$resource', function($resource) {
        return $resource('/api/v1/search/getdoctorweeklist/:userids/:viewslot', { userids:'@userids',viewslot:'@viewslot'}, {
            get: {
                method: 'GET'
            }
        });
}])        
.factory('AppointmentWeekList', ['$resource', function($resource) {
        return $resource('/api/v1/search/getdoctorweeklist/:userids/:viewslot', { userids:'@userids',viewslot:'@viewslot'}, {
            get: {
                method: 'GET'
            }
        });
}])    
.factory('calenderEvents', ['$resource', function($resource) {
        return $resource('/api/v1/calender/events/:month', {  param1:'@param1' }, {
            get: {
                method: 'GET'
            }
        });
}])      
.factory('UserAppointment', ['$resource', function($resource) {
        return $resource('/api/v1/bookings/:doctor_id/:user_id', { doctor_id:'@doctor_id', user_id:'@user_id' }, {
            get: {
                    method: 'GET'
                } 
        });
}]) 
 .factory('myUserFactory', ['$resource', function($resource) {
        return $resource('/api/v1/me/users', {}, {
            get: {
                method: 'GET'
            }
        });
    }])
 .factory('ServiceUser', ['$resource', function($resource) {
        return $resource('/api/v1/me/services_users', {},{filter:'@filter'},{
            get: {
                method: 'GET'
            }
        });
  }]).factory('GetPostions', ['$resource', function($resource) {
        return $resource('/api/v1/positions', {},{filter:'@filter'},{
            get: {
                method: 'GET'
            }
        });
    }])
    .factory('GetExperience', ['$resource', function($resource) {
        return $resource('/api/v1/experiences', {},{filter:'@filter'},{
            get: {
                method: 'GET'
            }
        });
    }])
    .factory('GetQualification', ['$resource', function($resource) {
        return $resource('/api/v1/qualifications', {},{filter:'@filter'},{
            get: {
                method: 'GET'
            }
        });
    }])
    .factory('GetSkill', ['$resource', function($resource) {
        return $resource('/api/v1/skills', {},{filter:'@filter'},{
            get: {
                method: 'GET'
            }
        });
    }])
    .factory('BookAppoinment', ['$resource', function($resource) {
        return $resource('/api/v1/appointments', {},{
            post: {
                method: 'POST'
            }
        });
    }])
     .factory('AppointmentStatus', ['$resource', function($resource) {
        return $resource('/api/v1/appointment_statuses', {},{
            get: {
                method: 'GET'
            }
        });
    }])
    //cancellation_policies
    .factory('CancellationPolicy', ['$resource', function($resource) {
        return $resource('/api/v1/cancellation_policies', {},{
            get: {
                method: 'GET'
            }
        });
    }])
    .factory('FAQS', ['$resource', function($resource) {
        return $resource('/api/v1/faqs', {}, {
            get: {
                method: 'GET'
            }
        });
  }]).factory('FormField', ['$resource', function($resource) {
        return $resource('/api/v1/form_fields', {}, {
            get: {
                method: 'GET'
            }
        });
  }]);
  
    


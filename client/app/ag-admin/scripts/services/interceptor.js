angular.module('base')
    .factory('interceptor', ['$q', '$location', '$injector', '$window', '$rootScope', '$timeout', '$cookies', function($q, $location, $injector, $window, $rootScope, $timeout, $cookies) {
        return {
            // On response success
            response: function(response) {
                if (angular.isDefined(response.data)) {
                    if (angular.isDefined(response.data.error)) {
                        if (parseInt(response.data.error.code) === 1 && (response.data.error.message === 'Authentication failed' || response.data.error.message === 'Authorization Failed')) {
                            if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                                var auth = JSON.parse($cookies.get("auth"));
                                var refresh_token = auth.refresh_token;
                                if (refresh_token === null || refresh_token === '' || refresh_token === undefined) {
                                    $cookies.remove('auth', {
                                        path: '/'
                                    });
                                    $cookies.remove('token', {
                                        path: '/'
                                    });
                                    $location.path('/users/login');
                                    $rootScope.refresh_token_loading = false;
                                    window.location.href = redirectto;
                                } else {
                                    if ($rootScope.refresh_token_loading !== true) {
                                        //jshint unused:false
                                        $rootScope.refresh_token_loading = true;
                                        var params = {};
                                        auth = JSON.parse($cookies.get("auth"));
                                        params.token = auth.refresh_token;
                                        var refreshToken = $injector.get('refreshToken');
                                        refreshToken.get(params, function(response) {
                                            if (angular.isDefined(response.access_token)) {
                                                $rootScope.refresh_token_loading = false;
                                                $cookies.put('token', response.access_token, {
                                                    path: '/'
                                                });
                                            } else {
                                                $cookies.remove('auth', {
                                                    path: '/'
                                                });
                                                $cookies.remove('token', {
                                                    path: '/'
                                                });
                                                $location.path('/users/login');
                                                /*  var redirectto = $location.absUrl()
                                                      .split('/#/');
                                                  redirectto = redirectto[0] + '/users/login';*/
                                                $rootScope.refresh_token_loading = false;
                                                window.location.href = redirectto;
                                            }
                                            $timeout(function() {
                                                $window.location.reload();
                                            }, 1000);
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
                // Return the response or promise.
                return response || $q.when(response);
            },
            // On response failture
            responseError: function(response) {
                if (angular.isDefined(response.data.error)) {
                    if (parseInt(response.data.error.code) === 1 && (response.data.error.message === 'Authentication failed' || response.data.error.message === 'Authorization Failed')) {
                        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                            var auth = JSON.parse($cookies.get("auth")),
                                refresh_token = auth.refresh_token;
                            if (refresh_token === null || refresh_token === '' || refresh_token === undefined) {
                                $cookies.remove('auth', {
                                    path: '/'
                                });
                                $cookies.remove('token', {
                                    path: '/'
                                });
                                $location.path('/users/login');
                                window.location.href = redirectto;
                            } else {
                                if ($rootScope.refresh_token_loading !== true) {
                                    //jshint unused:false
                                    $rootScope.refresh_token_loading = true;
                                    var params = {};
                                    auth = JSON.parse($cookies.get("auth"));
                                    params.token = auth.refresh_token;
                                    var refreshToken = $injector.get('refreshToken');
                                    refreshToken.get(params, function(response) {
                                        if (angular.isDefined(response.access_token)) {
                                            $rootScope.refresh_token_loading = false;
                                            $cookies.put('token', response.access_token, {
                                                path: '/'
                                            });
                                        } else {
                                            $cookies.remove('auth', {
                                                path: '/'
                                            });
                                            $cookies.remove('token', {
                                                path: '/'
                                            });
                                            $location.path('/users/login');
                                            /*  var redirectto = $location.absUrl()
                                                  .split('/#/');
                                              redirectto = redirectto[0] + '/users/login';*/
                                            $rootScope.refresh_token_loading = false;
                                            window.location.href = redirectto;
                                        }
                                        $timeout(function() {
                                            $window.location.reload();
                                        }, 1000);
                                    });
                                }
                            }
                        }
                    }
                }
                // Return the promise rejection.
                return $q.reject(response);
            },
            request: function(config) {
                config.headers['x-ag-app-id'] = "4542632501382585";
                config.headers['x-ag-app-secret'] = "3f7C4l1Y2b0S6a7L8c1E7B3Jo3";
                console.log('testtest');
                if ($cookies.get("token") !== null && angular.isDefined($cookies.get("token"))) {
                    config.headers.Authorization = 'Bearer ' + $cookies.get("token");
                }
                var exceptional_array = ['/api/v1/stats', '/api/v1/settings', '/api/v1/users/logout', '/api/v1/oauth/refresh_token'];
                if ($cookies.get('auth') !== null && $cookies.get('auth') !== undefined) {
                    var auth = angular.fromJson($cookies.get('auth'));
                }
                if(config.url.indexOf('/api/v1/insurance_companies') !== -1) {
                    config.url = config.url.replace('/api/v1/insurance_companies', '/api/v1/insurances');
                }
                if(config.url.indexOf('/api/v1/social_interests') !== -1) {
                    config.url = config.url.replace('/api/v1/social_interests', '/api/v1/interests');
                }
                if (config.url.indexOf('/api/v1/event_broad_casts') !== -1) {
                    config.url = config.url.replace('/api/v1/event_broad_casts', '/api/v1/posts');
                }
                if (config.url.indexOf('/api/v1/user_reviews') !== -1) {
                    config.url = config.url.replace('/api/v1/user_reviews', '/api/v1/reviews');
                }
                if (config.url.indexOf('/api/v1/service_providers') !== -1) {
                    config.url = config.url.replace('/api/v1/service_providers', '/api/v1/users');
                }
                if (config.url.indexOf('/api/v1/customers') !== -1) {
                    config.url = config.url.replace('/api/v1/customers', '/api/v1/users');
                }
                if (config.url.indexOf('/api/v1/administrators') !== -1) {
                    config.url = config.url.replace('/api/v1/administrators', '/api/v1/users');
                }                
                if (config.url.indexOf('/api/v1/add_users') !== -1) {
                    config.url = config.url.replace('/api/v1/add_users', '/api/v1/users');
                }
                if (config.url.indexOf('/api/v1/bookings') !== -1) {
                    config.url = config.url.replace('/api/v1/bookings', '/api/v1/appointments');
                }
                if (config.url.indexOf('/api/v1/listing_favorites') !== -1) {
                    config.url = config.url.replace('/api/v1/listing_favorites', '/api/v1/user_favorites');
                }
                if (config.url.indexOf('/api/v1/listing_views') !== -1) {
                    config.url = config.url.replace('/api/v1/listing_views', '/api/v1/user_views');
                }
                if (config.url.indexOf('/api/v1/listings') !== -1) {
                    config.url = config.url.replace('/api/v1/listings', '/api/v1/users');
                }
                return config;
            },
        };
    }]);   
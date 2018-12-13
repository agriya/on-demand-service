'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserLoginController
 * @description
 * # UserLoginController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserLoginController
     * @description
     *
     * This is user login controller having the methods init, setMetaData, and login. It is used for controlling the login functionalities.
     **/
    .controller('UserLoginController', function ($auth, $state, providers, $scope, $rootScope, $filter, $location, $cookies, $window, flash, AuthFactory, usersLogin, md5, ConstUserType, twitterLogin, myUserFactory) {
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserLoginController
         * @description
         * This method will initialze the page and it uses the setmetadata() function.
         *
         **/
        $scope.login = {};
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Login");
        };
        $scope.init();
        $scope.userType = function (userType) {
            $state.go('register', {
                'user_type': userType
            });
        };
        providers.get().$promise.then(function (response) {
            $scope.providers = response.data;
        });
        /* $cookies.get('auth') set*/
        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
            $rootScope.$emit('updateParent', {
                isAuth: true
            });
            $rootScope.header = $rootScope.settings.SITE_NAME + ' | Home';
            $state.go('user_dashboard');
        }
        /**
         * @ngdoc method
         * @name login
         * @methodOf user.controller:UserLoginController
         * @description
         * This method will validate the credentials and log in the user.
         *
         * @param {Boolean} isvalid Boolean flag to indicate whether the function call is valid or not
         **/
        $scope.login = function (isvalid) {
            if (isvalid) {
                var credentials = {
                    email: $scope.email,
                    password: $scope.password
                };
                // Use Satellizer's $auth service to login
                /**
                 * @ngdoc service
                 * @name user.login
                 * @kind function
                 * @description
                 * The auth service get the credentials from the user and validate it.
                 * @params {string=} credentials login credentials provided by the user
                 **/
                usersLogin.login(credentials).$promise.then(function (response) {
                    $scope.user = {};
                    $rootScope.auth = response;
                    $scope.response = response;
                    delete $scope.response.scope;
                    $scope.user.id = response.id;
                    $scope.user.username = response.username;
                    $scope.user.role_id = response.role_id;
                    $scope.user.refresh_token = response.token;
                    $scope.user.attachment = response.attachment;
                    $scope.user.affiliate_pending_amount = response.affiliate_pending_amount;
                    $scope.user.is_profile_updated = response.is_profile_updated;
                    $scope.user.category_id = response.category_id;
                    $scope.user.blocked_user_count = response.blocked_user_count;
                    $scope.user.user_profile = response.user_profile;

                    // If login is successful, redirect to the home page
                    if ($scope.response.error.code === 0) {
                        delete $scope.response.listing_photo;
                        delete $scope.response.user_profile.listing_description;
                        $cookies.put('auth', angular.toJson($scope.user), {
                            path: '/'
                        });
                        $cookies.put('token', response.token, {
                            path: '/'
                        });
                        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                            $rootScope.isAuth = true;
                            $rootScope.auth = JSON.parse($cookies.get("auth"));
                            if (angular.isDefined($rootScope.auth.attachment) && $rootScope.auth.attachment !== null) {
                                var hash = md5.createHash($rootScope.auth.attachment.class + $rootScope.auth.attachment.id + 'png' + 'small_thumb');
                                $rootScope.auth.userimage = 'images/small_thumb/' + $rootScope.auth.attachment.class + '/' + $rootScope.auth.attachment.id + '.' + hash + '.png';
                            } else {
                                $rootScope.auth.userimage = $window.theme + 'images/default.png';
                            }
                        } else {
                            $state.go('login');
                        }
                        flash.set($filter("translate")("Login successfully."), 'success', false);
                        if (response.role_id === ConstUserType.Admin) {
                            $scope.site_url = '/ag-admin/#/dashboard';
                            var site_url = $scope.site_url;
                            window.location.href = site_url;
                            $cookies.put('site_name', $rootScope.settings.SITE_NAME);
                        } else {
                            if (response.is_profile_updated === 1) {
                                $state.go('user_dashboard');
                            } else {
                                $state.go('user_profile');
                            }
                        }


                    } else {
                        flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                    }
                }, //jshint unused:false 
                    function (error) {
                        flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                    });
            }
        };
        /* social login submit function*/
        $scope.authenticate = function (provider) {
            $scope.social_login_provider = provider;
            $cookies.put('provider_name', $scope.social_login_provider);
            $auth.authenticate(provider).then(function (response) {
                $scope.response = response.data;
                /* login user details get factory*/
                if ($scope.response.error.code === 0) {
                    $scope.user = {};
                    $rootScope.auth = $scope.response;
                    delete $scope.response.scope;
                    $scope.user.id = $scope.response.id;
                    $scope.user.username = $scope.response.username;
                    $scope.user.role_id = $scope.response.role_id;
                    $scope.user.refresh_token = $scope.response.token;
                    $scope.user.attachment = $scope.response.attachment;
                    $scope.user.affiliate_pending_amount = $scope.response.affiliate_pending_amount;
                    $scope.user.is_profile_updated = $scope.response.is_profile_updated;
                    $scope.user.category_id = $scope.response.category_id;
                    $scope.user.blocked_user_count = $scope.response.blocked_user_count;
                    $scope.user.user_profile = $scope.response.user_profile;
                    // If login is successful, redirect to the home page
                    if ($scope.response.error.code === 0) {
                        delete $scope.response.listing_photo;
                        delete $scope.response.user_profile.listing_description;
                        $cookies.put('auth', angular.toJson($scope.user), {
                            path: '/'
                        });
                        $cookies.put('token', $scope.response.token, {
                            path: '/'
                        });
                        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                            $rootScope.isAuth = true;
                            $rootScope.auth = JSON.parse($cookies.get("auth"));
                            if (angular.isDefined($rootScope.auth.attachment) && $rootScope.auth.attachment !== null) {
                                var hash = md5.createHash($rootScope.auth.attachment.class + $rootScope.auth.attachment.id + 'png' + 'small_thumb');
                                $rootScope.auth.userimage = 'images/small_thumb/' + $rootScope.auth.attachment.class + '/' + $rootScope.auth.attachment.id + '.' + hash + '.png';
                            } else {
                                $rootScope.auth.userimage = $window.theme + 'images/default.png';
                            }
                        } else {
                            $state.go('login');
                        }
                        flash.set($filter("translate")("Login successfully."), 'success', false);
                        if ($scope.response.role_id === ConstUserType.Admin) {
                            $scope.site_url = '/ag-admin/#/dashboard';
                            var site_url = $scope.site_url;
                            window.location.href = site_url;
                            $cookies.put('site_name', $rootScope.settings.SITE_NAME);
                        } else {
                            if ($scope.response.is_profile_updated === 1) {
                                $state.go('user_dashboard');
                            } else {
                                $state.go('user_profile');
                            }
                        }
                    } else {
                        flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                    }
                }
                if ($scope.response.error.code === 1) {
                    $window.localStorage.setItem("twitter_auth", JSON.stringify($scope.response));
                    if(provider === 'twitter') {
                        $state.go('get_email');
                    } else {
                        if($rootScope.settings.IS_ALLOW_TO_REGISTER_SERVICE_PROVIDER === '1') {
                            $state.go('get_email');
                        } else {
                            $scope.user = {};
                            var params = {};
                            var auth = JSON.parse($window.localStorage.getItem("twitter_auth"));
                            $scope.user.access_token = auth.error.access_token;
                            $scope.user.access_token_secret = auth.error.access_token_secret;
                            $window.localStorage.removeItem("twitter_auth");
                            $scope.user.role_id = ConstUserType.Customer;
                            if (provider === 'facebook') {
                                params.type = 'facebook';
                            }
                            if (provider === 'google') {
                                params.type = 'google';
                            }
                            twitterLogin.login(params, $scope.user, function (response) {
                                $scope.response = response;
                                $scope.save_btn = false;
                                if ($scope.response.error.code === 0) {
                                    myUserFactory();
                                    if (provider === 'facebook' || provider === 'google') {
                                        $scope.user = {};
                                        $rootScope.auth = $scope.response;
                                        delete $scope.response.scope;
                                        $scope.user.id = $scope.response.id;
                                        $scope.user.username = $scope.response.username;
                                        $scope.user.role_id = $scope.response.role_id;
                                        $scope.user.refresh_token = $scope.response.token;
                                        $scope.user.attachment = $scope.response.attachment;
                                        $scope.user.affiliate_pending_amount = $scope.response.affiliate_pending_amount;
                                        $scope.user.is_profile_updated = $scope.response.is_profile_updated;
                                        $scope.user.category_id = $scope.response.category_id;
                                        $scope.user.blocked_user_count = $scope.response.blocked_user_count;
                                        $scope.user.user_profile = $scope.response.user_profile;
                                        // If login is successful, redirect to the home page
                                    if ($scope.response.error.code === 0) {
                                            delete $scope.response.listing_photo;
                                            delete $scope.response.user_profile.listing_description;
                                            $cookies.put('auth', angular.toJson($scope.user), {
                                                path: '/'
                                            });
                                            $cookies.put('token', $scope.response.token, {
                                                path: '/'
                                            });
                                            if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                                                $rootScope.isAuth = true;
                                                $rootScope.auth = JSON.parse($cookies.get("auth"));
                                                if (angular.isDefined($rootScope.auth.attachment) && $rootScope.auth.attachment !== null) {
                                                    var hash = md5.createHash($rootScope.auth.attachment.class + $rootScope.auth.attachment.id + 'png' + 'small_thumb');
                                                    $rootScope.auth.userimage = 'images/small_thumb/' + $rootScope.auth.attachment.class + '/' + $rootScope.auth.attachment.id + '.' + hash + '.png';
                                                } else {
                                                    $rootScope.auth.userimage = $window.theme + 'images/default.png';
                                                }
                                            } else {
                                                $state.go('login');
                                            }
                                            flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                                            if ($scope.response.role_id === ConstUserType.Admin) {
                                                $scope.site_url = '/ag-admin/#/dashboard';
                                                var site_url = $scope.site_url;
                                                window.location.href = site_url;
                                                $cookies.put('site_name', $rootScope.settings.SITE_NAME);
                                            } else {
                                                if ($scope.response.is_profile_updated === 1) {
                                                    $state.go('user_dashboard');
                                                } else {
                                                    $state.go('user_profile');
                                                }
                                            }
                                        } else {
                                            flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                                        }
                                    }
                                }
                            });
                        }
                    }
                } else if ($scope.response.access_token) {
                    $scope.Authuser = {
                        id: $scope.response.id,
                        username: $scope.response.username,
                        role_id: $scope.response.role_id,
                        refresh_token: $scope.response.refresh_token,
                    };
                    $cookies.put('auth', JSON.stringify($scope.Authuser), {
                        path: '/'
                    });
                    $cookies.put('token', $scope.response.access_token, {
                        path: '/'
                    });
                    $rootScope.user = $scope.response;
                    $rootScope.$emit('updateParent', {
                        isAuth: true
                    });
                    if ($cookies.get("redirect_url") !== null && $cookies.get("redirect_url") !== undefined) {
                        $location.path($cookies.get("redirect_url"));
                        $cookies.remove('redirect_url');
                    } else {
                        $location.path('/');
                    }
                }
            })
            .catch(function (error) {
                console.log("error in login", error);
            });
        };
    })

    /*  twitter controller*/
    .controller('TwitterLoginController', ['$rootScope', '$scope', 'twitterLogin', 'providers', '$auth', 'flash', '$window', '$location', '$state', '$cookies', '$filter', '$timeout', 'myUserFactory', 'ConstUserType', 'md5','CategoryFactory', function ($rootScope, $scope, twitterLogin, providers, $auth, flash, $window, $location, $state, $cookies, $filter, $timeout, myUserFactory, ConstUserType, md5,CategoryFactory) {
        $scope.ConstUserType = ConstUserType;
         $scope.show = false;
         var params ={};
         $scope.user = {};
         $scope.userChoose = {};
         $scope.object = {};
        params.filter = '{"where":{"is_active":1}}';
        CategoryFactory.get(params,function (response) {
                $scope.categories = response.data;
            });
        $scope.provider_login = $cookies.get('provider_name', $scope.social_login_provider);
        $scope.save_btn = false;
        $scope.submit_value = function(){
            if($scope.show === true && $scope.object.user !== undefined){
                $scope.user.role_id = $scope.ConstUserType.ServiceProvider;
                $scope.user.category_id = $scope.object.user;
                $scope.show = false;
            }
        };
        $scope.loginNow = function (form) {
            if (form) {
                $scope.save_btn = true;
                $scope.twitterEmail.$setPristine();
                $scope.twitterEmail.$setUntouched();
                var params = {};
                var auth = JSON.parse($window.localStorage.getItem("twitter_auth"));
                $scope.user.access_token = auth.error.access_token;
                $scope.user.access_token_secret = auth.error.access_token_secret;
                if($rootScope.settings.IS_ALLOW_TO_REGISTER_SERVICE_PROVIDER === '1') {
                    if ($scope.userChoose.data === 'serviceprovider') {
                        if($scope.categories.length > 1){
                            $scope.show = true;
                            $scope.submit_value();
                        }else{
                            $scope.user.role_id = $scope.ConstUserType.ServiceProvider;
                            $scope.show = false;
                        }
                    } else if ($scope.userChoose.data === 'customer') {
                        $scope.user.role_id = $scope.ConstUserType.Customer;
                        $scope.show = false;
                    }
                } else {
                    $scope.user.role_id = $scope.ConstUserType.Customer;
                    $scope.show = false;
                }
                if ($scope.provider_login === 'twitter') {
                    $scope.user.email = $scope.user_email;
                    params.type = 'twitter';
                    $scope.show = false;
                }
                if ($scope.provider_login === 'facebook') {
                    params.type = 'facebook';
                }
                if ($scope.provider_login === 'google') {
                    params.type = 'google';
                }
                if($scope.show === false){
                twitterLogin.login(params, $scope.user, function (response) {
                    $window.localStorage.removeItem("twitter_auth");
                    $scope.response = response;
                    $scope.save_btn = false;
                    if ($scope.response.error.code === 0) {
                        myUserFactory();
                        if ($scope.provider_login === 'facebook' || $scope.provider_login === 'google') {
                            $scope.user = {};
                            $rootScope.auth = $scope.response;
                            delete $scope.response.scope;
                            $scope.user.id = $scope.response.id;
                            $scope.user.username = $scope.response.username;
                            $scope.user.role_id = $scope.response.role_id;
                            $scope.user.refresh_token = $scope.response.token;
                            $scope.user.attachment = $scope.response.attachment;
                            $scope.user.affiliate_pending_amount = $scope.response.affiliate_pending_amount;
                            $scope.user.is_profile_updated = $scope.response.is_profile_updated;
                            $scope.user.category_id = $scope.response.category_id;
                            $scope.user.blocked_user_count = $scope.response.blocked_user_count;
                            $scope.user.user_profile = $scope.response.user_profile;
                            // If login is successful, redirect to the home page
                        if ($scope.response.error.code === 0) {
                                delete $scope.response.listing_photo;
                                delete $scope.response.user_profile.listing_description;
                                $cookies.put('auth', angular.toJson($scope.user), {
                                    path: '/'
                                });
                                $cookies.put('token', $scope.response.token, {
                                    path: '/'
                                });
                                if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
                                    $rootScope.isAuth = true;
                                    $rootScope.auth = JSON.parse($cookies.get("auth"));
                                    if (angular.isDefined($rootScope.auth.attachment) && $rootScope.auth.attachment !== null) {
                                        var hash = md5.createHash($rootScope.auth.attachment.class + $rootScope.auth.attachment.id + 'png' + 'small_thumb');
                                        $rootScope.auth.userimage = 'images/small_thumb/' + $rootScope.auth.attachment.class + '/' + $rootScope.auth.attachment.id + '.' + hash + '.png';
                                    } else {
                                        $rootScope.auth.userimage = $window.theme + 'images/default.png';
                                    }
                                } else {
                                    $state.go('login');
                                }
                                flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                                if ($scope.response.role_id === ConstUserType.Admin) {
                                    $scope.site_url = '/ag-admin/#/dashboard';
                                    var site_url = $scope.site_url;
                                    window.location.href = site_url;
                                    $cookies.put('site_name', $rootScope.settings.SITE_NAME);
                                } else {
                                    if ($scope.response.is_profile_updated === 1) {
                                        $state.go('user_dashboard');
                                    } else {
                                        $state.go('user_profile');
                                    }
                                }
                            } else {
                                flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                            }
                        }
                    }
                });
            }
            }
        };
    }]);
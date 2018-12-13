'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:InsuranceController
 * @description
 * # InsuranceController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserRegisterController
     * @description
     *
     * This is user register controller having the methods setmMetaData, init, and signup. It controls the register functionalities.
     **/
    .controller('UserRegisterController', function ($auth, $state, $scope, flash, ConstUserType, $rootScope, $filter, AuthFactory, usersRegister, $location, $window, $cookies, vcRecaptchaService, Genders, Services, providers, Language, Category, CategoryService, usersLogin,CategoryFactory, md5, myUserFactory, twitterLogin) {        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserRegisterController
         * @description
         * This method will initialze the page. It uses setMetaData() and captcha_site_key.
         *
         **/
        $scope.disable_submit = false;
        $scope.show = false;
        $scope.ConstUserType = ConstUserType;
        var params ={};
        $scope.object = {};
        params.filter = '{"where":{"is_active":1}}';
        CategoryFactory.get(params,function (response) {
                $scope.categories = response.data;
            });
        $scope.loadServices = function (category_id) {
            CategoryService.get({ id: category_id }).$promise.then(function (response) {
                $scope.serviceLists = response.services;
            });
        };
         $scope.userType = function (userType) {
            $state.go('register', {
                'user_type': userType
            }, { reload: true });
        };
        
        $scope.providerSignUp = function ($valid,value) {
            if($valid){
            $scope.show = true;
            $scope.categoryId = parseInt(value.user);
            }
        };        
        $scope.init = function () {
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Register");
        };
        $scope.user_type = $state.params.user_type;
        $scope.init();
        if ($scope.user_type === 'serviceprovider') {
            Services.get({}).$promise.then(function (response) {
                $rootScope.serviceLists = response.data;
            });

        }
        providers.get().$promise.then(function (response) {
            $scope.providers = response.data;
        });
        Genders.genderList({
        }).$promise.then(function (genderResponse) {
            $rootScope.genderLists = genderResponse.data;
        });

        /**
         * @ngdoc method
         * @name signup
         * @methodOf user.controller:UserRegisterController
         * @description
         * This method will validate the credentials and signup the user.
         *
         * @param {Boolean} isvalid Boolean flag to indicate whether the function call is valid or not
         **/

        $scope.signup = function (isvalid) {
            if ($rootScope.captchaFailed === false) {
                isvalid = false;
            }
            $scope.captchaErr = '';
            if (isvalid && $scope.user.password === $scope.user.confirm_password) {
                if ($state.params.user_type === 'serviceprovider') {
                    $scope.user.role_id = $scope.ConstUserType.ServiceProvider;
                    if($scope.categoryId !== undefined){
                        $scope.user.category_id = $scope.categoryId;
                    }
                }
                else {
                    $scope.user.role_id = $scope.ConstUserType.Customer;
                } 
                if ($cookies.get("referral_username") !== undefined && $cookies.get("referral_username") !== null) {
                    $scope.user.referral_username = $cookies.get("referral_username");
                    $cookies.remove('referral_username');
                }
                /**
                 * @ngdoc service
                 * @name user.signup
                 * @kind function
                 * @description
                 * The auth service get the credentials from the user and validate it.
                 * @params {string} credentials login credentials provided by the user
                 **/
                $scope.disable_submit = true;
                usersRegister.create($scope.user,function(response) {
                    $scope.disable_submit = false;
                    if (response.error.code === 0) {
                        //flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                        // $state.go('home');
                        var credentials = {
                            email: $scope.user.email,
                            password: $scope.user.password
                        };
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
                            delete $scope.response.listing_photo;
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
                            }
                             $state.go('user_profile',{"type":"personal"});
                        });
                    }
                    
                },function(errorMessage){
                    $scope.disable_submit = false;
                    if(errorMessage.data.error.fields.email[0] === "unique"){
                        flash.set($filter("translate")("Email is already registered in our website."), 'error', true);
                    }
                    else{
                        flash.set($filter("translate")(errorMessage.data.error.message), 'error', false);
                    }
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
                    $scope.user.user_profile = $scope.response.user_profile;
                    $scope.user.blocked_user_count = $scope.response.blocked_user_count;
                    // If login is successful, redirect to the home page
                    if ($scope.response.error.code === 0) {
                        delete $scope.response.listing_photo;
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
                                        $scope.user.user_profile = $scope.response.user_profile;
                                        // If login is successful, redirect to the home page
                                    if ($scope.response.error.code === 0) {
                                            delete $scope.response.listing_photo;
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
    .directive('simpleCaptcha', function () {
        return {
            restrict: 'E',
            scope: {
                valid: '=',
                from: '='
            },
            template: '<input ng-model="a.value" ng-show="a.input" style="width:2em; text-align: center;" name="normalcapcha1" ng-required="true"><span ng-hide="a.input">{{a.value}}</span>&nbsp;{{operation}}&nbsp;<input ng-model="b.value" ng-show="b.input" style="width:2em; height:28px; text-align: center;" name="normalcapcha2" ng-required="true"><span ng-hide="b.input">{{b.value}}</span><span class="text-20">&nbsp;=&nbsp;{{result}}</span>',
            controller: function ($scope, $rootScope) {
                var show = Math.random() > 0.5;
                var value = function (max) {
                    return Math.floor(max * Math.random());
                };
                var int = function (str) {
                    return parseInt(str, 10);
                };
                $scope.a = {
                    value: show ? undefined : 1 + value(4),
                    input: show
                };
                $scope.b = {
                    value: !show ? undefined : 1 + value(4),
                    input: !show
                };
                $scope.operation = '+';
                $scope.result = 5 + value(5);
                var a = $scope.a;
                var b = $scope.b;
                var result = $scope.result;
                var checkValidity = function () {
                    if (a.value && b.value) {
                        var calc = int(a.value) + int(b.value);
                        $scope.valid = calc === result;
                        if ($scope.valid === true) {
                            $rootScope.captchaFailed = true;
                        }
                    } else {
                        $scope.valid = false;
                        $rootScope.captchaFailed = false;
                    }
                };
                $scope.$watch('a.value', function () {
                    checkValidity();
                });
                $scope.$watch('b.value', function () {
                    checkValidity();
                });
            }
        };
    });
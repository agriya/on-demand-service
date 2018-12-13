/*globals $:false */
'use strict';
/**
 * @ngdoc overview
 * @name ofosApp
 * @description
 * # ofosApp
 *
 * Main module of the application.
 */
angular.module("hirecoworkerApp", [
    'hirecoworkerApp.Constant',
    'ngResource',
    'ui.router.state',
    'ui.router',
    'ui.bootstrap',
    'ui.calendar',
    'ngSanitize',
    'satellizer',
    'ngAnimate',
    'angular-growl',
    'pascalprecht.translate',
    'ngCookies',
    'ngMap',
    'builder',
    'builder.components',
    'google.places',
    'angular-input-stars',
    'tmh.dynamicLocale',
    'angular-loading-bar',
    'angular-input-stars',
    'mwl.calendar',
    'ngFileUpload',
    'vcRecaptcha',
    'mgcrea.ngStrap',
    'hm.readmore',
    'checklist-model',
    'daterangepicker',
    'oitozero.ngSweetAlert',
    'angular-md5',
    'ngRateIt',
    'angularjs-dropdown-multiselect',
    'slugifier',
    '720kb.socialshare',
    'ui.carousel',
    'payment'    
])
    .config(['$stateProvider', '$urlRouterProvider', '$translateProvider', function ($stateProvider, $urlRouterProvider, $translateProvider) {
        //$translateProvider.translations('en', translations).preferredLanguage('en');
        $translateProvider.useStaticFilesLoader({
            prefix: 'scripts/l10n/',
            suffix: '.json'
        });
        // $translateProvider.preferredLanguage('en');
        $translateProvider.useLocalStorage(); // saves selected language to localStorage
        // Enable escaping of HTML
        $translateProvider.useSanitizeValueStrategy(null);
        //	$translateProvider.useCookieStorage();
    }])
    .config(function (tmhDynamicLocaleProvider) {
        tmhDynamicLocaleProvider.localeLocationPattern('scripts/l10n/angular-i18n/angular-locale_{{locale}}.js');
    })
    .factory('authInterceptorService', ['$cookies',
        function ($cookies) {
            var oauthTokenInjector = {
                request: function (config) {
                    config.headers['x-ag-app-id'] = '4542632501382585';
                    config.headers['x-ag-app-secret'] = '3f7C4l1Y2b0S6a7L8c1E7B3Jo3';
                    if (config.url.indexOf('.html') === -1) {
                        if ($cookies.get("token") !== null && angular.isDefined($cookies.get("token"))) {
                            config.headers.Authorization = 'Bearer ' + $cookies.get("token");
                        } else {
                            config.headers.Authorization = '';
                        }
                    }
                    return config;
                }
            };
            return oauthTokenInjector;
        }
    ])
    .config(['$httpProvider',
        function ($httpProvider) {
            $httpProvider.interceptors.push('interceptor');
            $httpProvider.interceptors.push(function () {
                return {
                    'request': function (config) {
                        return config;
                    }
                };
            });
            $httpProvider.interceptors.push('authInterceptorService');
        }
    ])
    .config(function ($authProvider, $windowProvider, $provide) {
        var $window = $windowProvider.$get();
        var params = {};
        var providers = '';
        params.filter = '{"include":{"0":{"fields":{"api_key","slug"}}}}';
        $.ajax({
            url: '/api/v1/providers',
            data: params,
            type: "GET",
            headers: { 'x-ag-app-id': '4542632501382585', 'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3' },
            success: function (response) {
                var credentials = {};
                var url = '';
                providers = response;
                angular.forEach(providers.data, function (res, i) {
                    //jshint unused:false
                    url = $window.location.protocol + '//' + $window.location.host + '/api/v1/users/social_login?type=' + res.slug;
                    credentials = {
                        clientId: res.api_key,
                        redirectUri: url,
                        url: url
                    };
                    if (res.slug === 'facebook') {
                        $authProvider.facebook(credentials);
                    }
                });
            }

        });
        $provide.provider('providerSetting', function () {
            this.$get = function () {
                var appname = providers;
                return {
                    provider_details: appname
                };
            };
        });
    })
    .config(['$locationProvider', function ($locationProvider) {
        $locationProvider.html5Mode(true);
    }])
    .config(function ($stateProvider, $urlRouterProvider) {
        var getToken = {
            'TokenServiceData': function (TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
        };
        $urlRouterProvider.otherwise('/');
        $stateProvider.state('home', {
            url: '/',
            templateUrl: 'views/home.html',
            controller: 'HomeController',
            resolve: getToken
        })
            .state('login', {
                url: '/users/login',
                templateUrl: 'views/login.html',
                controller: 'UserLoginController',
                resolve: getToken
            })
            .state('register', {
                url: '/users/register/:user_type',
                templateUrl: 'views/register.html',
                controller: 'UserRegisterController',
                resolve: getToken
            })
            .state('users_logout', {
                url: '/users/logout',
                controller: 'UsersLogoutController',
                resolve: getToken
            }).state('users_activation', {
                url: '/users/:id/activate/:hash',
                controller: 'UserActivateController',
                resolve: getToken
            })
            .state('users_change_password', {
                url: '/users/change_password',
                templateUrl: 'views/change_password.html',
                controller: 'ChangePasswordController',
                resolve: getToken
            })
            .state('user_profile', {
                url: '/users/user_profile?type',
                templateUrl: 'views/user_profile.html',
                controller: 'UserProfileController',
                resolve: getToken
            })
            .state('description_details', {
                url: '/users/description_details',
                templateUrl: 'views/description_details.html',
                controller: 'UserProfileController',
                resolve: getToken
            })
            .state('transactions', {
                url: '/transactions',
                templateUrl: 'views/transactions.html',
                controller: 'TransactionController',
                resolve: getToken
            })
            .state('payout_details', {
                url: '/users/money_transfer_account', 
                templateUrl: 'views/money_transfer_account.html',
                controller: 'MoneyTransferAccountController',
                resolve: getToken
            })
            .state('email_verify', {
                url: '/email_verification',
                templateUrl: 'views/email_index.html',
                controller: 'EmailController',
                resolve: getToken
            })
            .state('pages_view', {
                url: '/pages/{id}/:slug',
                templateUrl: 'views/pages_view.html',
                resolve: getToken
            })
            .state('account_payout', {
                url: '/account/payout',
                templateUrl: 'views/account_payout.html',
                resolve: getToken
            })
            .state('user_listing', {
                url: '/users/listing',
                templateUrl: 'views/user_listing.html',
                controller: 'UserListingController',
                resolve: getToken
            })
            .state('user_notification', {
                url: '/users/notification',
                templateUrl: 'views/user_notification.html',
                controller: 'UserNotificationController',
                resolve: getToken
            })
            .state('contact', {
                url: '/contactus?address_verified',
                templateUrl: 'views/contacts.html',
                controller: 'ContactUsController',
                resolve: getToken
            })
            .state('contacts', {
                url: '/contacts',
                templateUrl: 'views/contact.html',
                controller: 'ContactsController',
                resolve: getToken
            })
            .state('forgot_password', {
                url: '/users/forgot_password',
                templateUrl: 'views/forgot_password.html',
                controller: 'ForgotPasswordController',
                resolve: getToken
            })
            .state('UserView', {
                url: '/users/{user_id}/:slug?request_id&service_id',
                templateUrl: 'views/user_view.html',
                controller: 'UserController',
                resolve: getToken
            })
            .state('UserEnquiry', {
                url: '/users/{user_id}/:slug/enquiry?from_date&to_date&services_user_id&&service_id&from_time&to_time&request_id&count',
                templateUrl: 'views/user_enquiry.html',
                controller: 'UserEnquiryController',
                resolve: getToken
            })
            .state('UserLocation', {
                url: '/users/{user_id}/:slug/additional_information?from_date&to_date&services_user_id&&service_id&from_time&to_time&type&amount&request_id&count',
                templateUrl: 'views/user_address_location.html',
                controller: 'UserEnquiryController',
                resolve: getToken
            })
            .state('my_services', {
                url: '/user/services',
                templateUrl: 'views/my_services.html',
                controller: 'ServiceController',
                resolve: getToken
            })
            .state('hourly_rate', {
                url: '/user/hourly_rate',
                templateUrl: 'views/hourly_rate.html',
                controller: 'ServiceController',
                resolve: getToken
            })
            .state('preferences', {
                url: '/user/preferences',
                templateUrl: 'views/user_preference.html',
                controller: 'UserPreferenceController',
                resolve: getToken
            })
            .state('my_languages', {
                url: '/user/languages',
                templateUrl: 'views/my_languages.html',
                controller: 'LanguageController',
                resolve: getToken
            })
            .state('search', {
                url: '/users?latitude&longitude&sw_latitude&sw_longitude&ne_latitude&ne_longitude&service_id&appointment_from_date&appointment_to_date&address&display_type&page&couple_select&zoom&more&iso2&radius&search_type&user_search_id&search_title&request_id&is_search',
                templateUrl: 'views/search.html',
                controller: 'SearchController',
                resolve: getToken
            })
            .state('my_calender', {
                url: '/user/calendar',
                templateUrl: 'views/my_calender.html',
                controller: 'CalenderController',
                resolve: getToken
            })
            .state('appointmentModification', {
                url: '/appointments/modifications',
                templateUrl: 'views/appointment_modifications.html',
                controller: 'AppointmentsModificationController',
                resolve: getToken
            })
            .state('appointmentModificationAdd', {
                url: '/appointments/modifications/add',
                templateUrl: 'views/appointment_modifications_add.html',
                controller: 'AppointmentsModificationController',
                resolve: getToken
            })
            .state('appointmentModificationDelete', {
                url: '/appointments/modifications/delete/{id}',
                controller: 'AppointmentsModificationController',
                resolve: getToken
            })
            .state('appointmentModificationEdit', {
                url: '/appointments/modifications/edit/{id}',
                templateUrl: 'views/appointment_modifications_edit.html',
                controller: 'AppointmentsModificationController',
                resolve: getToken
            })
            .state('MyBooking', {
                url: '/booking/all?error_code',
                templateUrl: 'views/booking_index.html',
                controller: 'AppointmentsController',
                resolve: getToken
            })
            .state('MyAppointmentServices', {
                url: '/Myservices',
                templateUrl: 'views/services_index.html',
                controller: 'AppointmentsController',
                resolve: getToken
            })
            .state('become_a_service_provider', {
                url: '/become-a-service-provider',
                templateUrl: 'views/become_a_service_provider.html',
                controller: 'PageViewController',
                resolve: getToken
            })
            .state('BookingDetail', {
                url: '/booking/{id}',
                templateUrl: 'views/booking_view.html',
                controller: 'AppointmentsController',
                resolve: getToken
            })
            .state('get_email', {
                url: '/users/get_email',
                templateUrl: 'views/get_email.html',
                controller: 'TwitterLoginController',
                resolve: getToken
            })
            .state('user_dashboard', {
                url: '/user_dashboard',
                templateUrl: 'views/user_dashboard.html',
                controller: 'UserDashboardController',
                resolve: getToken
            })
            .state('close_account', {
                url: '/close_account',
                templateUrl: 'views/close_account.html',
                controller: 'CloseAccountController',
                resolve: getToken
            });
    })
    .config(['growlProvider', function (growlProvider) {
        growlProvider.onlyUniqueMessages(true);
        growlProvider.globalTimeToLive(5000);
        growlProvider.globalPosition('top-center');
        growlProvider.globalDisableCountDown(true);
    }])
    .run(function ($rootScope, $location, $window, $cookies) {
        $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            if ($location.path() === '/') {
                $rootScope.innerPage = '';
                $rootScope.isHome = true;
            } else {
                $rootScope.innerPage = 'sub-pages';
                $rootScope.isHome = false;
            }

            //jshint unused:false
            var state_name = toState.name;
            if (state_name === 'search') {
                $rootScope.searchPage = 'search-page';
            } else {
                $rootScope.searchPage = '';
            }
            var url = toState.name;
            var exception_arr = ['home', 'login', 'register', 'forgot_password', 'pages_view', 'contact','search', 'UserView','affiliates', 'become-a-service-provider','JobView','get_email'];
            $rootScope.$broadcast('updateParentCss', {});
            if (url !== undefined) {
                if (exception_arr.indexOf(url) === -1 && ($cookies.get("auth") === null || $cookies.get("auth") === undefined)) {
                    $location.path('/users/login');
                }
            }
        });
        $rootScope.$on('$viewContentLoaded', function () {
            if (!$('#preloader').hasClass('loadAG')) {
                $('#status').fadeOut(600);
                $('#preloader').delay(600).fadeOut(600 / 2);
            }
        });
        $rootScope.$on('$stateChangeSuccess', function () {
            $('html, body')
                .stop(true, true)
                .animate({
                    scrollTop: 0
                }, 600);
        });
        var query_string = $location.search()
            .action;
        if (query_string !== '') {
            $('html, body')
                .stop(true, true)
                .animate({
                    scrollTop: 0
                }, 450);
        }
    })
    .config(function (cfpLoadingBarProvider) {
        // true is the default, but I left this here as an example:
        cfpLoadingBarProvider.includeSpinner = false;
    })
    .factory('interceptor', ['$q', '$location', 'flash', '$window', '$timeout', '$rootScope', '$filter', '$cookies', function ($q, $location, flash, $window, $timeout, $rootScope, $filter, $cookies) {
        return {
            // On response success
            response: function (response) {
                $rootScope.isOn404 = false;
                if (response.status === 200) {
                    $rootScope.isOn404 = false;
                    $('.main_div')
                        .css('display', 'block');
                    $('.js-404-div-open')
                        .css('display', 'none');
                }
                if (angular.isDefined(response.data)) {
                    if (angular.isDefined(response.data.thrid_party_login)) {
                        if (angular.isDefined(response.data.error)) {
                            if (angular.isDefined(response.data.error.code) && parseInt(response.data.error.code) === 0) {
                                $cookies.put('auth', JSON.stringify(response.data.user), {
                                    path: '/'
                                });
                                /* $timeout(function() {
                                     location.reload(true);
                                 });*/
                            } else {
                                var flashMessage;
                                flashMessage = $filter("translate")("Please choose different email address.");
                                flash.set(flashMessage, 'error', false);
                            }
                        }
                    }
                }
                // Return the response or promise.
                return response || $q.when(response);
            },
            // On response failture
            responseError: function (response) {
                $timeout(function () {
                    if (response.status === 404) {
                        $rootScope.isOn404 = true;
                        $('.main_div')
                            .css('display', 'none');
                    }
                }, 500);
                $timeout(function () {
                    if (response.status === 404) {
                        $rootScope.isOn404 = true;
                        $('.js-404-div-open')
                            .css('display', 'block');
                    }
                }, 500);
                // Return the promise rejection.
                if (response.status === 401) {
                    var redirectto = $location.absUrl().split('/');
                    redirectto = redirectto[0] + '/users/login';
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
                            $rootScope.refresh_token_loading = false;
                            window.location.href = redirectto;
                        } else {
                            if ($rootScope.refresh_token_loading !== true) {
                                $rootScope.$broadcast('useRefreshToken');
                            }
                        }
                    } else {
                        $cookies.remove('auth', {
                            path: '/'
                        });
                        $cookies.remove('token', {
                            path: '/'
                        });
                        $rootScope.refresh_token_loading = false;
                        window.location.href = redirectto;
                    }
                }
                return $q.reject(response);
            }
        };
    }])
    .directive("scroll", function ($window) {
        return function (scope) {
            angular.element($window).bind("scroll", function () {
                if (this.pageYOffset >= 100) {
                    scope.boolChangeClass = true;
                } else {
                    scope.boolChangeClass = false;
                }
                scope.$apply();
            });
        };
    })
    /**
     * @ngdoc filter
     * @name Abs.filter:html
     * @description
     * It returns the filtered html data.
     */
    .filter('html', function ($sce) {
        return function (val) {
            return $sce.trustAsHtml(val);
        };
    })
    /**
     * @ngdoc filter
     * @name Abs.filter:html
     * @description
     * It returns the filtered html data.
     */
    .filter('dateFormat', function ($filter) {
        return function (val) {
            if (val === null) {
                return "";
            }
            var dateTime = val.replace(/(.+) (.+)/, "$1T$2Z");
            var formatedDate = $filter('date')(new Date(dateTime), 'MMM dd, yyyy');
            return formatedDate;
        };
    })
    .filter('unsafe', function ($sce) {
        return function (val) {
            return $sce.trustAsHtml(val);
        };
    })
    .filter('splitedShow', function () {
        return function (passValue) {
            if (passValue !== null) {
                var splitedValues = passValue.split(',');
                var ulBuild = "";
                $.each(splitedValues, function (i, value) {
                    ulBuild = ulBuild + '<li>' + value + '</li>';
                });
                return ulBuild;
            } else {
                return "";
            }
        };
    })
    .filter('nl2br', function() {
        var span = document.createElement('span');
        return function(input) {
            if (!input) {
                return input;
            }
            var lines = input.split('\n');
            for (var i = 0; i < lines.length; i++) {
                span.innerText = lines[i];
                span.textContent = lines[i]; //for Firefox
                lines[i] = span.innerHTML;
            }
            return lines.join('<br />');
        };
    })
    .filter('reverse', function () {
        return function (items) {
            if (items) {
                return items.slice()
                    .reverse();
            }
            // return items.slice()
            //     .reverse();
        };
    }).filter('customCurrency', function ($rootScope, $filter) {
        var currency_symbol = $rootScope.settings.CURRENCY_SYMBOL;
        return function (input, symbol, fractionSize) {
            if (isNaN(input)) {
                input = symbol + $filter('number')(input, fractionSize || 2);
                return input;
            } else if (currency_symbol) {
                var symbol = symbol || $rootScope.settings.CURRENCY_SYMBOL; //jshint ignore:line
                input = symbol + $filter('number')(input, fractionSize || 2); //jshint ignore:line
                return input;
            } else {
                var symbol = symbol || $rootScope.settings.CURRENCY_CODE; //jshint ignore:line
                input = symbol + $filter('number')(input, fractionSize || 2); //jshint ignore:line
                return input;
            }
        };
    })//jshint ignore:line
    .filter("timeago", function ($rootScope) {
        //time: the time
        //local: compared to what time? default: now
        //raw: wheter you want in a format of "5 minutes ago", or "5 minutes"
        return function (time, local, raw) {
            var timeZone = ($rootScope.settings.SITE_TIMEZONE) ? $rootScope.settings.SITE_TIMEZONE : '+0000';

            if (!time) return "never";//jshint ignore:line

            if (!local) {
                (local = Date.now())//jshint ignore:line
            }

            if (angular.isDate(time)) {
                time = time.getTime() + (timeZone * 60000);
            } else if (typeof time === "string") {
                time = new Date(time).getTime() + (timeZone * 60000);
            }

            if (angular.isDate(local)) {
                local = local.getTime() + (timeZone * 60000);
            } else if (typeof local === "string") {
                local = new Date(local).getTime() + (timeZone * 60000);
            }

            if (typeof time !== 'number' || typeof local !== 'number') {
                return;
            }

            var
                offset = Math.abs((local - time) / 1000),
                span = [],
                MINUTE = 60,
                HOUR = 3600,
                DAY = 86400,
                WEEK = 604800,
                MONTH = 2629744,//jshint ignore:line
                YEAR = 31556926,
                DECADE = 315569260;

            if (offset <= MINUTE) span = ['', raw ? 'now' : 'less than a minute'];//jshint ignore:line
            else if (offset < (MINUTE * 60)) span = [Math.round(Math.abs(offset / MINUTE)), 'min'];//jshint ignore:line
            else if (offset < (HOUR * 24)) span = [Math.round(Math.abs(offset / HOUR)), 'hr'];//jshint ignore:line
            else if (offset < (DAY * 7)) span = [Math.round(Math.abs(offset / DAY)), 'day'];//jshint ignore:line
            else if (offset < (WEEK * 52)) span = [Math.round(Math.abs(offset / WEEK)), 'week'];//jshint ignore:line
            else if (offset < (YEAR * 10)) span = [Math.round(Math.abs(offset / YEAR)), 'year'];//jshint ignore:line
            else if (offset < (DECADE * 100)) span = [Math.round(Math.abs(offset / DECADE)), 'decade'];//jshint ignore:line
            else span = ['', 'a long time'];//jshint ignore:line

            span[1] += (span[0] === 0 || span[0] > 1) ? 's' : '';
            span = span.join(' ');

            if (raw === true) {
                return span;
            }
            return (time <= local) ? span + ' ago' : 'in ' + span;
        };
    }).filter('nl2br', function() {
        var span = document.createElement('span');
        return function(input) {
            if (!input) {
                return input;
            }
            var lines = input.split('\n');
            for (var i = 0; i < lines.length; i++) {
                span.innerText = lines[i];
                span.textContent = lines[i]; //for Firefox
                lines[i] = span.innerHTML;
            }
            return lines.join('<br />');
        };
    });

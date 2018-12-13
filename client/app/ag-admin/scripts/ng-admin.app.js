var ngapp = angular.module('base', ['ng-admin', 'ng-admin.jwt-auth', 'http-auth-interceptor', 'angular-md5', 'ngResource', 'ngCookies', 'ngTagsInput', 'ui.bootstrap']);
var admin_api_url = '/';
var limit_per_page = 20;
var $cookies;
var auth;
var site_settings;
var enabled_plugins;
angular.injector(['ngCookies'])
    .invoke(['$cookies', function(_$cookies_) {
        $cookies = _$cookies_;
    }]);
ngapp.config(['$httpProvider',
    function($httpProvider) {
        $httpProvider.interceptors.push('interceptor');
        //$httpProvider.interceptors.push('oauthTokenInjector');
        menucollaps();
    }
]);
deferredBootstrapper.bootstrap({
    element: document.body,
    module: 'base',
    resolve: {
        CmsConfig: function($http) {
            var config = {headers:  {
                    'x-ag-app-id': '4542632501382585',
                    'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'
                }
            };            
            return $http.get(admin_api_url + 'api/v1/admin-config', config);
        }
    }
});
if ($cookies.get('auth') !== undefined && $cookies.get('auth') !== null) {
    auth = JSON.parse($cookies.get('auth'));
}
if ($cookies.get('enabled_plugins') !== undefined && $cookies.get('enabled_plugins') !== null) {
    enabled_plugins = JSON.parse($cookies.get('enabled_plugins'));
}
if ($cookies.get('SETTINGS') !== undefined && $cookies.get('SETTINGS') !== null) {
    site_settings = JSON.parse($cookies.get('SETTINGS'));
    var site_name = site_settings.SITE_NAME;
    if (site_settings.SITE_IS_ENABLE_ZAZPAY_PLUGIN !== undefined && site_settings.SITE_IS_ENABLE_ZAZPAY_PLUGIN !== null) {
        var SITE_IS_ENABLE_ZAZPAY_PLUGIN = site_settings.SITE_IS_ENABLE_ZAZPAY_PLUGIN;
    }
} else {
    var site_name = '';
}
ngapp.constant('user_types', {
    admin: 1,
    user: 2
 });
ngapp.constant('ConstTransactionTypes', {
            'BookedAndWaitingForApproval': 8,
            'BookingCanceledAndVoided': 9,
            'BookingDeclinedAndVoided': 10,
            'BookingAcceptedAndAmountMovedToEscrow': 11,
            'CompletedAndAmountMovedToWallet': 12,
            'AdminCanceledBookingAndVoided': 13,
            'BookingCanceledAndRefunded': 14, 
            'BookingCanceledAndCreditedCancellationAmount': 15,
            'PROPayment': 16,
            'TopListed': 17,
            'Bonus': 18
        });
ngapp.directive('paymentGateways', function (paymentGateway, $state, PaymentGatewaySettings) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&"
        },
        controller: function ($rootScope, $scope, $location, notification) {
            angular.element(document.querySelector('ma-submit-button')
                .remove());
            $scope.test_mode_value = {};
            $scope.live_mode_value = {};
            $scope.liveMode = false;
            if( $scope.entry().values.is_test_mode === 0) {
                $scope.liveMode = true;   
            }
            if( $scope.entry().values.is_test_mode === 1) {
                $scope.liveMode = false;   
            }           
            $scope.save = function () {
                $scope.data = {};
                if ($scope.liveMode === true) {
                    $scope.data.live_mode_value = $scope.live_mode_value;
                    $scope.data.is_live_mode = true;
                } else {
                    $scope.data.test_mode_value = $scope.test_mode_value;
                    $scope.data.is_live_mode = false;
                }
                $scope.data.id = $scope.entry()
                    .values.id;
                paymentGateway.update($scope.data, function (response) {
                    if (angular.isDefined(response.error.code === 0)) {
                        notification.log('Data updated successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            };
            $scope.index = function () {
                angular.forEach($scope.entry()
                    .values.payment_settings,
                    function (value, key) {
                        $scope.test_mode_value[value.name] = value.test_mode_value;
                        $scope.live_mode_value[value.name] = value.live_mode_value;
                    });
                if (parseInt($state.params.id) === PaymentGatewaySettings.PayPal) {
                    $scope.PayPal = true;
                } else {
                    $scope.PayPal = false;
                }
            };
            $scope.index();
        },
        template: '<span ng-show="!wallet"><input type="checkbox" ng-model="liveMode" ng-checked="{{liveMode}}" ng-value="true"></span>&nbsp;<label ng-if="!wallet">Live Mode?</label><table ng-show="PayPal && !wallet"><tr><th></th><th>Live Mode Credential</th><th>&nbsp;</th><th>Test Mode Credential</th></tr><tr><td>Client Secret &nbsp;&nbsp;</td><td><input type="text" ng-model="live_mode_value.paypal_client_Secret" class="form-control"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-readonly="live_mode" ng-model="test_mode_value.paypal_client_Secret"></td></tr><tr><td>Client ID</td><td><input type="text" class="form-control" ng-model="live_mode_value.paypal_client_id"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-readonly="live_mode" ng-model="test_mode_value.paypal_client_id"></td></tr><tr><td>&nbsp;</td><td><button type="button" ng-click="save()" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;<span class="hidden-xs">Save changes</span></button></td><td>&nbsp;</td><td></td></tr></table>',
    };
});
ngapp.directive('reAssign', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var service_id;
            $scope.service_id = $scope.entry().values.id;
            $scope.status = $scope.entry().values["appointment_status.id"];
        },
        template: '<span ng-show="status === 2"><a ui-sref="reAssignProvider({id:{{service_id}}})"><button type="button">Reassign</button></a></span>'
    };
});
ngapp.directive('displayCustomerName', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_user_phone = $scope.users["phone_number"] ? true :false;
        },
        template: '<p>{{users["user_profile.first_name"]}} {{users["user_profile.last_name"]}}</p><p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{{users.email}}</p><p ng-if="is_display_user_phone"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>{{users.mobile_code}} {{users["phone_number"]}}</p>'
    };
});
ngapp.directive('displayContactName', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_user_phone = $scope.users["user.phone_number"] ? true :false;
            },
            template: '<p>{{users.first_name}} {{users.last_name}}</p><p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{{users.email}}</p><p ng-if="is_display_user_phone"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>{{users["user.mobile_code"]}} {{users.phone_number}}</p>'
    };
});
ngapp.directive('displayCustomerFavourite', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_user_phone = $scope.users["user.phone_number"] ? true :false;
        },
        template: '<p>{{users["user.user_profile.first_name"]}} {{users["user.user_profile.last_name"]}}</p><p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{{users["user.email"]}}</p><p ng-if="is_display_user_phone"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>{{users["user.mobile_code"]}} {{users["user.phone_number"]}}</p>'
    };
});
ngapp.directive('displayProviderFavourite', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_user_phone = $scope.users["provider_user.phone_number"] ? true :false;
        },
        template: '<p>{{users["provider_user.user_profile.first_name"]}} {{users["provider_user.user_profile.last_name"]}}</p><p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{{users["provider_user.email"]}}</p><p ng-if="is_display_user_phone"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>{{users["provider_user.mobile_code"]}} {{users["provider_user.user_profile.phone"]}}</p>'
    };
});
ngapp.directive('displayProviderReviews', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_user_phone = $scope.users["to_user.phone_number"] ? true :false;
        },
        template: '<p>{{users["to_user.user_profile.first_name"]}} {{users["to_user.user_profile.last_name"]}}</p><p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{{users["to_user.email"]}}</p><p ng-if="is_display_user_phone"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>{{users["to_user.mobile_code"]}} {{users["to_user.user_profile.phone"]}}</p>'
    };
});
ngapp.directive('displayProviderMessages', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_other_user_phone = $scope.users["other_user.phone_number"] ? true :false;
        },
        template: '<p>{{users["other_user.user_profile.first_name"]}} {{users["other_user.user_profile.last_name"]}}</p><p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>{{users["other_user.email"]}}</p><p ng-if="is_display_other_user_phone"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span>{{users["other_user.mobile_code"]}} {{users["other_user.phone_number"]}}</p>'
    };
});
ngapp.directive('showMobileNumber', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var params= {};
            $scope.users = $scope.entry().values;
            $scope.is_display_other_user_phone = $scope.users["users.phone_number"] ? true :false;
        },
        template: '<p ng-if="is_display_other_user_phone">{{users.mobile_code}} {{users["users.phone_number"]}}</p>'
    };
});
ngapp.directive('showProuserStatus', function () {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope) {
            $scope.prouser = $scope.entry().values;
            if(parseInt($scope.prouser["user_profile.pro_account_status_id"]) === 1){
                $scope.prouserText = "No";
            }else if(parseInt($scope.prouser["user_profile.pro_account_status_id"]) === 2){
                $scope.prouserText = "Waiting for approval";
            }else if(parseInt($scope.prouser["user_profile.pro_account_status_id"]) === 3){
                $scope.prouserText = "Yes";
            }    
        },
        template: '<p>{{prouserText}}</p>'
    };
});
ngapp.directive('downloadFile', function (md5,$location) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        controller: function ($http, $scope, $rootScope) {
            var model = this;
            var that = this;
            var service_id;
            var params = {};
            $scope.attachment = $scope.entry().values;
            
            $scope.check_attachment = $scope.entry().values.attachment;
            if($scope.attachment.attachment[0]){
                var checking_value = $scope.attachment.attachment[0].filename.split('.').pop();
            }
                if (angular.isDefined($scope.attachment.attachment[0]) && $scope.attachment.attachment !== null) {
                var download_file = md5.createHash($scope.attachment.attachment[0].class + $scope.attachment.attachment[0].id + checking_value + 'download') + '.' + checking_value;

        $scope.user_image = $location.protocol() + '://' + $location.host() + '/download/' + $scope.attachment.attachment[0].class + '/' + $scope.attachment.attachment[0].id + '/' + download_file;    
        }else{
                $scope.user_image = undefined;
            }  
        },
        template: '<span ng-show="user_image !== undefined"><a ng-href="{{user_image}}" download><button type="button">{{"Download"|translate}}</button></a></span>'
    };
});
ngapp.directive('reviewContact', function (md5,$location) {
    return {
        restrict: 'E',
        scope: true,
        controller: function ($http, $scope, $rootScope, UserProfilesFactory, notification, ListingAddress) {
            params = {};
            $scope.ListingAddress = ListingAddress; 
            $scope.status_id = $scope.entry.values["user.user_profile.listing_address_verified_status_id"];
            $scope.ReviewContact = function(type){
                params.id = $scope.entry.values["user.id"];
                params.listing_address_verified_status_id = type === 1 ? ListingAddress.Accepted : ListingAddress.Rejected;
                UserProfilesFactory.update(params, function(response){
                    if(response.error.code === 0){
                        notification.log('Status changed successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        $scope.status_id = $scope.entry.values["user.user_profile.listing_address_verified_status_id"]= response.data.user_profile.listing_address_verified_status_id;
                    }
                });    
            }; 
        },
        template: '<span ng-if="status_id == ListingAddress.PendingVerification"><button ng-click="ReviewContact(1)">{{"Mark As Address Verified"|translate}}</button> &nbsp; <button ng-click="ReviewContact(2)">{{"Reject Address Verification Submission"|translate}}</button></span>'
    };
});
ngapp.constant('PaymentGatewaySettings', {
    'PayPal': 1
});
ngapp.constant('ListingAddress', {
    'NotSubmitted': 1,
    'PendingVerification': 2,
    'Accepted': 3,
    'Rejected': 4
});

function truncate(value) {
    if (!value) {
        return '';
    }
    return value.length > 50 ? value.substr(0, 50) + '...' : value;
}

function statusdisplay(value) {
    if (value == true) {
        return '<span class="glyphicon glyphicon-ok"></span>';
    }
    return '';
}

function covertstringtonumber(value) {
    return parseFloat(value);
}
// dashboard page redirect changes
function homeController($scope, $http, $location) {
    $location.path('/dashboard');
}
ngapp.config(function($stateProvider) {
    var getToken = {
        'TokenServiceData': function(adminTokenService, $q) {
            return $q.all({
                SettingServiceData: adminTokenService.promiseSettings
            });
        }
    };
    $stateProvider.state('change_password', {
            parent: 'main',
            url: '/change_password/:id',
            templateUrl: 'views/change_password.html',
            params: {
                id: null
            },
            controller: 'ChangePasswordController',
            resolve: getToken
        }).state('home', {
            parent: 'main',
            url: '/',
            controller: homeController,
            controllerAs: 'controller',
            resolve: getToken
        }).state('servicelocations', {
            parent: 'main',
            url: '/servicelocation/:id',
            controller: 'ServicelocationController',
            templateUrl: 'views/servicelocation.html',
            resolve: getToken
        }).state('reAssignProvider', {
            parent: 'main',
            url: '/re_assign_provider/:id',
            controller: 'ReassignController',
            templateUrl: 'views/re_assign_provider.html',
            resolve: getToken
        }).state('plugins', {
            parent: 'main',
            url: '/plugins',
			templateUrl: 'views/plugins.html',
            controller: 'PluginsController',
            controllerAs: 'controller',
        }).state('translations', {
            parent: 'main',
            url: '/translations/all',
            controller: 'TranslationsController',
            templateUrl: 'views/translations.html',
            resolve: getToken
        })
       .state('translation_edit', {
            parent: 'main',
            url: '/translations?lang_code',
            controller: 'TranslationsController',
            templateUrl: 'views/translation_edit.html',
            resolve: getToken
        })
        .state('translation_add', {
            parent: 'main',
            url: '/translations/add',
            controller: 'TranslationsController',
            templateUrl: 'views/make_new_translation.html',
            resolve: getToken
        })
        .state('transactions', {
            parent: 'main',
            url: '/transactions',
            controller: 'TransactionController',
            templateUrl: 'views/transaction.html',
            resolve: getToken
        }) 
        .state('pages', {
            parent: 'main',
            url: '/pages/add',
            templateUrl: 'views/pages_add.html',
            controller: 'pagesController',
            resolve: getToken
	
        })
});



ngapp.directive('starRating', function() {
    return {
        restrict: 'E',
        scope: {
            stars: '@'
        },
        link: function(scope, elm, attrs, ctrl) {
            scope.starsArray = Array.apply(null, {
                    length: parseInt(scope.stars)
                })
                .map(Number.call, Number);
        },
        template: '<i ng-repeat="star in starsArray" class="glyphicon glyphicon-star"></i>'
    };
});
//custom header  controller defined here.
function customHeaderController($state, $scope, $http, $location, notification) {
    
    if ($cookies.get('auth') !== undefined && $cookies.get('auth') !== null) {
        auth = JSON.parse($cookies.get('auth'));
        $scope.adminDetail = auth;
    }else if(auth !== undefined){
            $scope.adminDetail = auth;
    }
    $scope.logoutFunction = function(){
        $state.go('logout');
    };
}
ngapp.directive('customHeader', ['$location', '$state', '$http', function ($location, $state, $http, $scope) {
    return {
        restrict: 'E',
        scope: {},
        templateUrl: '../ag-admin/views/custom_header.html',
        link: function (scope) {
            scope.siteUrl = admin_api_url;
        },
		controller: customHeaderController,
            controllerAs: 'controller',
            resolve: {}
    };
}]);
ngapp.directive('googlePlaces', ['$location', function($location) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        link: function(scope) {
            var inputFrom = document.getElementById('goo-place');
            var autocompleteFrom = new google.maps.places.Autocomplete(inputFrom);
            google.maps.event.addListener(autocompleteFrom, 'place_changed', function() {
                scope.entry()
                    .values['city.name'] = '';
                scope.entry()
                    .values['address'] = '';
                scope.entry()
                    .values['address1'] = '';
                scope.entry()
                    .values['state.name'] = '';
                scope.entry()
                    .values['country.iso_alpha2'] = '';
                scope.entry()
                    .values['zip_code'] = '';
                var place = autocompleteFrom.getPlace();
                scope.entry()
                    .values.latitude = place.geometry.location.lat();
                scope.entry()
                    .values.longitude = place.geometry.location.lng();
                scope.entry()
                    .values.address_latitude = place.geometry.location.lat();
                scope.entry()
                    .values.address_longitude = place.geometry.location.lng();
                scope.entry()
                    .values.address = place.formatted_address;                    
                var k = 0;
                angular.forEach(place.address_components, function(value, key) {
                    //jshint unused:false
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        if (k === 0) {
                            scope.entry()
                                .values['city.name'] = value.long_name;
                            document.getElementById("city.name")
                                .disabled = true;
                        }
                        if (value.types[0] === 'locality') {
                            k = 1;
                        }
                    }
                    if (value.types[0] === 'sublocality_level_1' || value.types[0] === 'sublocality_level_2') {
                        if (scope.entry()
                            .values['address1'] !== '') {
                            scope.entry()
                                .values['address1'] = scope.entry()
                                .values['address1'] + ',' + value.long_name;
                        } else {
                            scope.entry()
                                .values['address1'] = value.long_name;
                        }
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        scope.entry()
                            .values['state.name'] = value.long_name;
                        document.getElementById("state.name")
                            .disabled = true;
                    }
                    if (value.types[0] === 'country') {
                        scope.entry()
                            .values['country.iso_alpha2'] = value.short_name;
                        document.getElementById("country.iso_alpha2")
                            .disabled = true;
                    }
                    if (value.types[0] === 'postal_code') {
                        scope.entry()
                            .values.zip_code = parseInt(value.long_name);
                        document.getElementById("zip_code")
                            .disabled = true;
                    }
                });
                scope.$apply();
            });
        },
        template: '<input class="form-control" id="goo-place"/>'
    };
}]);
ngapp.directive('changePassword', ['$location', '$state', '$http', 'notification', function($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class=\"btn btn-default btn-xs\" title="Change Password" ng-click=\"password()\" >\n<span class=\"glyphicon glyphicon-lock sync-icon\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-spinner fa-pulse fa-lg\"></i></span>\n</a>',
        link: function(scope, element) {
            var id = scope.entry()
                .values.id;
            scope.password = function() {
                $state.go('change_password', {
                    id: id
                });
            };
        }
    };
}]);
ngapp.directive('displayImage', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.type = attrs.type;
            scope.thumb = attrs.thumb;
            if (angular.isDefined(scope.entry()
                    .values['attachment.foreign_id']) && scope.entry()
                .values['attachment.foreign_id'] !== null && scope.entry()
                .values['attachment.foreign_id'] !== 0) {
                var hash = md5.createHash(scope.type + scope.entry()
                    .values.id + 'png' + scope.thumb);
                scope.image = '/images/' + scope.thumb + '/' + scope.type + '/' + scope.entry()
                    .values.id + '.' + hash + '.png';
            } else {
                scope.image = '../images/no-image.png';
            }
        },
        template: '<img ng-src="{{image}}" height="42" width="42" />'
    };
});

ngapp.directive('displayImages', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.type = attrs.type;
            scope.thumb = attrs.thumb;
            if (angular.isDefined(scope.entry()
                    .values['attachment'][0]['foreign_id']) && scope.entry()
                .values['attachment'][0]['foreign_id'] !== null && scope.entry()
                .values['attachment'][0]['foreign_id'] !== 0) {
                var hash = md5.createHash(scope.type + scope.entry()
                    .values.id + 'png' + scope.thumb);
                scope.image = '/images/' + scope.thumb + '/' + scope.type + '/' + scope.entry()
                    .values.id + '.' + hash + '.png';
            } else {
                scope.image = '../images/no-image.png';
            }
        },
        template: '<img ng-src="{{image}}" height="42" width="42" />'
    };
});
ngapp.directive('batchActive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'active' ? ' Mark as Active' : ' Mark as Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? ' Mark as Active' : ' Mark as Active';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]); 
ngapp.directive('batchInActive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Mark as Inactive' : 'Mark as Inactive';
            scope.icon = attrs.type == 'active' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'active' ? 'Mark as Inactive' : 'Mark as Inactive';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changePendingApprovalStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Pending Approval';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Pending Approval';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template:  
        // '<span ng-repeat="appointment_status in appointment_statuses"><input type="radio" value="{{appointment_status.id}}" ng-model="appointment.id" ng-change="updateStatus(action,appointment.id,type)" name="{{appointment_status.name}}">{{appointment_status.name}}<br></span>'
        '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeApprovedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Approved';
            scope.icon =  'glyphicon-ok';
            scope.label = ' Mark as Approved';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 2;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeClosedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Closed';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Closed';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 3;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive("batchWithdrawPending", ["$location", "$state", "notification", "$q", "Restangular", function ($location, $state, notification, $q, Restangular) { 
    return { 
        restrict: "E",
        scope: { selection: "=", type: "@", action: "@" },
        link: function (scope, element, attrs) { 
            const status_name = ("pending" == attrs.type, "Mark as Pending"); 
            scope.icon = ("pending" == attrs.type, "glyphicon-ok"); 
            scope.label = ("pending" == attrs.type, "Mark as Pending");
            scope.action = attrs.action,scope.updateStatus = function (action) { 
                $q.all(scope.selection.map(function (e) { 
                    var p = Restangular.one("/" + action + "/" + e.values.id);
                    p.withdrawal_status_id = 1; 
                    p.put().then(function () { 
                        $state.reload() 
                    }) 
                })).then(function () { 
                    notification.log(scope.selection.length + " status changed to  " + status_name, { addnCls: "humane-flatty-success" }) 
                }) 
            } 
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>' 
    } 
}]);
ngapp.directive("batchWithdrawProcess", ["$location", "$state", "notification", "$q", "Restangular", function ($location, $state, notification, $q, Restangular) { 
    return { 
        restrict: "E",
        scope: { selection: "=", type: "@", action: "@" },
        link: function (scope, element, attrs) { 
            const status_name = ("process" == attrs.type, "Under Process"); 
            scope.icon = ("process" == attrs.type, "glyphicon-asterisk");
            scope.label = ("process" == attrs.type, "Under Process"); 
            scope.action = attrs.action;
            scope.updateStatus = function (action) { 
                $q.all(scope.selection.map(function (e) { 
                    var p = Restangular.one("/" + action + "/" + e.values.id); 
                    p.withdrawal_status_id = 2; 
                    p.put().then(function () {
                        $state.reload() 
                    }) 
                })).then(function () 
                { 
                    notification.log(scope.selection.length + " status changed to  " + status_name, { addnCls: "humane-flatty-success" }) 
                }) 
            } 
        }, 
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>' 
    } 
}]);
ngapp.directive("batchWithdrawReject", ["$location", "$state", "notification", "$q", "Restangular", function ($location, $state, notification, $q, Restangular) { 
    return { 
        restrict: "E", 
        scope: { selection: "=", type: "@", action: "@" }, 
        link: function (scope, element, attrs) { 
            const status_name = ("reject" == attrs.type, "Mark as Rejected"); 
            scope.icon = ("reject" == attrs.type, "glyphicon-remove"); 
            scope.label = ("reject" == attrs.type, "Mark as Rejected");
            scope.action = attrs.action;
            scope.updateStatus = function (action) { 
                $q.all(scope.selection.map(function (e) {
                    var p = Restangular.one("/" + action + "/" + e.values.id);
                    p.withdrawal_status_id = 3;
                    p.put().then(function () {
                         $state.reload() 
                    }) 
                })).then(function () { 
                    notification.log(scope.selection.length + " status changed to  " + status_name, { addnCls: "humane-flatty-success" }) 
                }) 
            } 
        }, 
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>' 
    } 
}]);
ngapp.directive("batchWithdrawSuccess", ["$location", "$state", "notification", "$q", "Restangular", function ($location, $state, notification, $q, Restangular) { 
    return { 
        restrict: "E", 
        scope: { selection: "=", type: "@", action: "@" }, 
        link: function (scope, element, attrs) { 
            const status_name = ("reject" == attrs.type, "Mark as Transfered"); 
            scope.icon = ("reject" == attrs.type, "glyphicon-transfer"), 
            scope.label = ("reject" == attrs.type, "Mark as Transfered"), 
            scope.action = attrs.action; 
            scope.updateStatus = function (action) { 
                $q.all(scope.selection.map(function (e) { 
                    var p = Restangular.one("/" + action + "/" + e.values.id); 
                    p.withdrawal_status_id = 5;
                    p.put().then(function () { 
                        $state.reload() 
                    }) 
                })).then(function () { 
                    notification.log(scope.selection.length + " status changed to  " + status_name, { addnCls: "humane-flatty-success" }) 
                }) 
            } 
        }, 
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>' 
    } 
}]);
ngapp.directive('changeCancelledStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Cancelled';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Cancelled';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 4;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeRejectedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Rejected';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Rejected';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 5;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeExpiredStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Expired';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Expired';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 6;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changePresentStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Present';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Present';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 7;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeEnquiryStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Enquiry';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Enquiry';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 8;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changePreApprovedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Pre-approved';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Pre-approved';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 9;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changePaymentPendingStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Payment Pending';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Payment Pending';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 10;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeCanceledByAdminStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as CanceledByAdmin';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as CanceledByAdmin';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 11;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeReassignedServiceProviderStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as ReassignedServiceProvider';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as ReassignedServiceProvider';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 12;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeCompletedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Completed';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Completed';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.appointment_status_id = 13;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeAddressVerifiedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Address Verified';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Address Verified';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_listing_address_verified = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeAddressNotVerifiedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Address Unverified';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Address Unverified';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_listing_address_verified = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeOnlineTestCompletedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Online Assessment Test Completed';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Online Assessment Test Completed';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_online_assessment_test_completed = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeOnlineTestNotCompletedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Online Assessment Test Not Completed';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Online Assessment Test Not Completed';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_online_assessment_test_completed = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeListingDraftStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Draft';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Draft';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.listing_status_id = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeListingAdminApprovalStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
           const status_name = 'Mark as Waiting for Admin Approval';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Waiting for Admin Approval';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.listing_status_id = 2;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeListingApprovedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
    const status_name = 'Mark as Approved';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Approved';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.listing_status_id = 3;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeListingInvisibleStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
        const status_name = 'Marked as Invisible By Provider';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Marked as Invisible By Provider';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.listing_status_id = 4;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeListingRejectedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
           const status_name = 'Mark as Rejected Approval Request';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Rejected Approval Request';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.listing_status_id = 5;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeDeleteStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Soft Delete';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Soft Delete';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_deleted = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeEmailVerifiedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = ' Mark as Email Verified';
            scope.icon =  'glyphicon-ok';
            scope.label = ' Mark as Email Verified';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_email_confirmed = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeEmailNotVerifiedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Email Not Verified';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Email Not Verified';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_email_confirmed = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeMobileVerifiedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Mobile Number Verified';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Mobile Number Verified';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_mobile_number_verified = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }

        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeMobileNotVerifiedStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as Mobile Number Not Verified';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as Mobile Number Not Verified';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.is_mobile_number_verified = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);

ngapp.directive('changeProUserMarkStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Unmark as PRO';
            scope.icon =  'glyphicon-remove';
            scope.label = 'Unmark as PRO';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.pro_account_status_id = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeProUserUnmarkStatus', ['$location','$http', '$state', 'notification', '$q', 'Restangular', function($location,$http, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = 'Mark as PRO';
            scope.icon =  'glyphicon-ok';
            scope.label = 'Mark as PRO';
            scope.action = attrs.action;
            scope.type = attrs.type;
            scope.updateStatus = function(action,type) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one(type + '/' + e.values.id);
                        p.pro_account_status_id = 3;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action,type)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchAdminsuspend', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'suspend' ? 'Mark as Suspend' : 'Mark as Suspend';
            scope.icon = attrs.type == 'suspend' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'suspend' ? 'Mark as Suspend' : 'Mark as Suspend';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_admin_suspend = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchAdminunsuspend', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'unsuspend' ? 'Mark as Unsuspend' : 'Mark as Unsuspend';
            scope.icon = attrs.type == 'unsuspend' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'unsuspend' ? 'Mark as Unsuspend' : 'Mark as Unsuspend';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_admin_suspend = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchAdminactive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Mark as Active' : 'Mark as Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'active' ? 'Mark as Active' : 'Mark as Active';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchAdmininactive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'inactive' ? 'Mark as Inactive' : 'Mark as Inactive';
            scope.icon = attrs.type == 'inactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'inactive' ? 'Mark as Inactive' : 'Mark as Inactive';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('changeStatues', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            type: '@',
            action: '@',
            id: '@',
            status: '@'
        },
        link: function(scope, element, attrs) {
            scope.label = attrs.type;
            scope.action = attrs.action;
            scope.id = attrs.id;
            scope.status = attrs.status;
            scope.updateMyStatus = function(action, id, status) {
                var p = Restangular.one('/' + action + '/' + id);
                p.contest_status_id = status;
                p.put()
                    .then(function() {
                        notification.log(' status changed to  ' + scope.label, {
                            addnCls: 'humane-flatty-success'
                        });
                        $state.reload()
                    })
            }
        },
        template: '<span class="label label-primary" ng-click="updateMyStatus(action,id,status)">&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('formFields', [function() {
    return {
        restrict: 'E',
        scope: {
            entry: "&"
        },
        link: function(scope, element, attrs) {
            scope.myformfields = scope.entry()
                .id;
        },
        template: '<ul><li ng-repeat="formdata in myformfields"><h5><strong>{{formdata.form_field[0].label}}<strong></h5><p>{{formdata.response}}</p></li></ul>'
        };
}]);
ngapp.directive('batchEmailConfirm', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Email Confirmed' : 'Email Confirmed';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Email Confirmed' : 'Email Confirmed';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_email_confirmed = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('inputType', function() {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            elem.bind('change', function() {
                scope.$apply(function() {
                    scope.entry()
                        .values.value = scope.value;
                    if (scope.entry()
                        .values.type === 'checkbox') {
                        scope.entry()
                            .values.value = scope.value ? 1 : 0;
                    }
                    if (scope.entry()
                        .values.type === 'select') {
                        scope.entry()
                            .values.value = scope.value;
                    }
                });
            });
        },
        controller: function($scope) {
            $scope.text = 1;
            $scope.value = $scope.entry()
                .values.value;
            if ($scope.entry()
                .values.type === 'checkbox') {
                $scope.text = 2;
                $scope.value = Number($scope.value);
            }
            else if ($scope.entry()
                .values.type === 'select') {
                $scope.text = 3;
                $scope.option_values = $scope.entry()
                .values.option_values.split(",");
            }
        },
        template: '<textarea ng-model="$parent.value" id="value" name="value" class="form-control" ng-if="text==1"></textarea><input type="checkbox" ng-model="$parent.value" id="value" name="value" ng-if="text==2" ng-true-value="1" ng-false-value="0" ng-checked="$parent.value == 1"/><select ng-if="text==3" ng-model="$parent.value" name="value" class="form-control" ng-options="option_value for option_value in option_values"></select>'
    };
});
ngapp.directive('dashboardSummary', ['$location', '$state', '$http', '$rootScope', function($location, $state, $http, $rootScope) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@",
            revenueDetails: "&"
        },
        templateUrl: 'views/dashboardSummary.html',
        link: function(scope) {
            $http.get(admin_api_url + 'api/v1/stats')
                .success(function(response) {
                    scope.adminstats = response;
                    scope.enabled_plugins = $rootScope.enabled_plugins;
                });
        }
    };
}]);
//plugins controller function
function pluginsController($scope, $http, notification, $state, $window) {
	$scope.languageArr = [];
	getPluginDetails();
	function getPluginDetails(){
		$http.get(admin_api_url + 'api/v1/plugins', {}).success(function(response) {
			$scope.other_plugin = response.data.other_plugin;
			$scope.enabled_plugin = response.data.enabled_plugin;
			enabledPlugin = response.data.enabled_plugin;
			$.cookie('enabled_plugins', JSON.stringify(enabledPlugin), {
							path: '/'
						});
		}, function(error){});
	};
	$scope.checkStatus = function(plugin, enabled_plugins){
		if ($.inArray(plugin, enabled_plugins) > -1) {
            return true;
        }else{
			return false;
		}
	}
	$scope.updatePluginStatus = function(e, plugin_name, status, hash){
		e.preventDefault();
		var target = angular.element(e.target);
        checkDisabled = target.parent().hasClass('disabled');
		if(checkDisabled === true){
			return false;
		}
		var params = {};
		var confirm_msg = '';
		params.plugin_name = plugin_name;
		params.is_enabled = status;
		confirm_msg = (status === 0)?"Are you sure want to disable?":"Are you sure want to enable?";
		notification_msg = (status === 0)?"disabled":"enabled";
		if (confirm(confirm_msg)) {
		   $http.put(admin_api_url + 'api/v1/plugins', params).success(function(response) {
				if(response.error.code === 0){
					notification.log(plugin_name+' Plugin '+notification_msg+' successfully.',{ addnCls: 'humane-flatty-success'});				
					getPluginDetails();
				}
                else{
                     notification.log(response.error.message, {addnCls: 'humane-flatty-error'});
                }
			}, function(error){});
		}						
	}
	$scope.fullRefresh = function(){
		$window.location.reload();
	}
};
ngapp.config(['RestangularProvider', function(RestangularProvider) {
    RestangularProvider.setDefaultHeaders({'x-ag-app-secret': '3f7C4l1Y2b0S6a7L8c1E7B3Jo3'});
    RestangularProvider.setDefaultHeaders({'x-ag-app-id': '4542632501382585'});
    RestangularProvider.addFullRequestInterceptor(function(element, operation, what, url, headers, params) {
        headers = headers || {};
        headers['x-ag-app-secret'] = '3f7C4l1Y2b0S6a7L8c1E7B3Jo3';
        var filter = {};
        if (operation === 'getList') {
            var whereCond = {};
            if(url == '/api/v1/service_providers') {
                filter.include = {};
                whereCond.role_id ={"inq":{"0":3,"1":4}};
                filter.include["0"] ="role";
                filter.include["1"] ="user_profile";
                filter.include["2"] ="category";
                if(angular.isDefined(params._filters) && (params._filters.is_top_listed || params._filters.pro_account_status_id)){
                    filter.include.user_profile = {};
                    filter.include.user_profile.whereHas = {};
                }
                if (angular.isDefined(params._filters) && params._filters.is_top_listed !== undefined) {
                    filter.include.user_profile.whereHas.is_top_listed = params._filters.is_top_listed;
                    delete params._filters.is_top_listed;
                }
                if (angular.isDefined(params._filters) && params._filters.pro_account_status_id !== undefined) {
                    filter.include.user_profile.whereHas.pro_account_status_id = params._filters.pro_account_status_id;
                    delete params._filters.pro_account_status_id;
                }
            }
            if(url == '/api/v1/setting_categories'){
                whereCond['is_active'] = 1;
            }
            if(url == '/api/v1/listings'){
                whereCond['role_id'] = 3;
                filter.include = {"0": "role", "1": "user_profile.listing_status"};
            }
            if(url == '/api/v1/customers'){
                whereCond['role_id'] ={"inq":{"0":2,"1":4}};
                filter.include = {"0": "role", "1": "user_profile"};
            }
            if(url == '/api/v1/administrators'){
                whereCond['role_id'] = 1;
                filter.include = {"0": "role", "1": "user_profile"};
            }            
            else if(url == '/api/v1/user_profiles'){
                filter.include = {"0": "user","1": "listing_country","2": "listing_state","3": "listing_city"};
            }          
            else if(url == '/api/v1/contacts'){
                 filter.include = {"0":"user","1":"attachment","2":"user.user_profile"};
            }            
            else if(url == '/api/v1/cities'){
                filter.include = {"0": "state","1": "country"};
            }            
            else if(url == '/api/v1/states'){
                filter.include = {"0": "country"};
            }            
            else if(url == '/api/v1/listing_favorites'){
                filter.include = {"0": "user.user_profile","1": "provider_user.user_profile"};
            } 
            else if(url == '/api/v1/user_logins'){
                filter.include = {"0": "user.user_profile"};
            } 
            else if(url == '/api/v1/user_reviews'){
                whereCond['class'] = 'Appointment';
                filter.include = {"0": "user.user_profile","1": "to_user.user_profile","2":"foreign_review_model"};
            }
            else if(url == '/api/v1/listing_views'){
                filter.include = {"0": "user.user_profile","1": "other_user.user_profile"};
            }  
            else if(url == '/api/v1/services'){
                filter.include = {"0": "category","1": "service"};
            }
            else if(url == '/api/v1/quiz_questions'){
                filter.include = {"0": "quiz"};
            }  
            else if(url == '/api/v1/quiz_question_answer_options'){
                filter.include = {"0": "quiz","1": "quiz_question"};
            }                        
            else if(url == '/api/v1/appointment_modifications'){
                filter.include = {"0": "user"};
            } 
            else if(url == '/api/v1/appointment_settings'){
                filter.include = {"0": "user"};
            }             
            else if(url == '/api/v1/bookings'){
                filter.include = {"0": "user.user_profile","2":"provider_user.user_profile","3":"appointment_status", "4": "service"};
            } 
            else if(url == '/api/v1/transactions'){
                filter.include = {"0": "user.user_profile","1": "other_user.user_profile","2": "foreign"};
            }  
            else if(url == '/api/v1/user_cash_withdrawals'){
                filter.include = {"0": "user.user_profile","1": "withdrawal_status","2": "money_transfer_account"};
            }
            else if(url == '/api/v1/money_transfer_accounts'){
                filter.include = {"0": "user.user_profile"};
            }
            else if(url == '/api/v1/form_field_submissions'){
                filter.include = {"0": "form_field"};
            }
            else if(url == '/api/v1/messages'){
                filter.include = {"0": "user.user_profile", "1": "other_user.user_profile", "2": "parent", "3": "message_content", "4": "foreign_message", "5": "children"};
            } else if(url == '/api/v1/requests'){
                filter.include = {"0": "user", "1": "service"};
            } else if(url == '/api/v1/requests_users') {
                filter.include = {"0": "request.user", "1": "user"};
                if (angular.isDefined(params._filters) && params._filters.customer_id !== 'undefined') {
                    filter.include = {"0": "request.user", "1": "user", "request":{"whereHas":{"user_id":params._filters.customer_id}}};
                    delete params._filters.customer_id;
                }
            }
        }
        else if (operation === 'get') {
            if(url.indexOf('/api/v1/service_providers') !== -1) {
                filter.include = {"0": "role", "1": "city", "2": "state", "3": "country", "4": "referred_by_user", "user_profile":["listing_city", "listing_state", "listing_country"]};
            }
            if(url.indexOf('/api/v1/customers') !== -1) {
                filter.include = {"0": "role", "1": "city", "2": "state", "3": "country", "4": "referred_by_user", "user_profile":["listing_city", "listing_state", "listing_country"]};
            }
            if(url.indexOf('/api/v1/listings') !== -1) {
                filter.include = {"0": "role", "1": "user_profile.listing_status"};
            }
            if(url.indexOf('/api/v1/bookings') !== -1) {
                filter.include = {"0": "user.user_profile","2":"provider_user.user_profile","3":"work_location_city","4":"work_location_state","5":"work_location_country"};
            }
            if(url.indexOf('/api/v1/user_reviews') !== -1) {
                filter.include = {"0": "user.user_profile","1": "to_user.user_profile","2":"foreign_review_model"};
            }
            if(url.indexOf('/api/v1/services') !== -1) {
                filter.include = {"0": "category","1": "service", "2": "form_field_groups.form_fields"};
            }
            if(url.indexOf('/api/v1/categories') !== -1) {
                filter.include = {"0": "form_field_groups.form_fields"};
            }
            if(url.indexOf('/api/v1/messages') !== -1) {
                filter.include = {"0": "user", "1": "other_user", "2": "parent", "3": "message_content", "4": "foreign_message", "5": "children"};
            }
            if(url.indexOf('/api/v1/quiz_questions') !== -1) {
                filter.include = {"0": "quiz", "1": "quiz_question_answer_option"};
            }
            if(url.indexOf('/api/v1/requests') !== -1) {
                filter.include = {"0": "user", "1": "service"};
            }
            if(url.indexOf('/api/v1/requests_users') !== -1) {
                filter.include = {"0": "request.user", "1": "user"};
            }
        }
        var addtional_param = {};
            for (var k in params) {
                if (params.hasOwnProperty(k)) {
                    if (k == "_page") {
                        filter.skip = (params[k] - 1) * params._perPage;
                        filter.limit = params._perPage;
                    }
                    else if (k == "_sortField") {
                        if (params._sortDir) {
                            filter.order = params[k] + ' ' +params._sortDir;
                        }else{
                            filter.order = params[k] + ' DESC';
                        }
                    }
                    else if (k == "_filters") {                        
                        for (var field in params._filters) {
                            if(field !== 'q' && field != 'autocomplete'){
                                if (field == 'user_profile.listing_status_id') {
                                    filter.include[Object.keys(filter.include).length] = {"user_profile":{"whereHas":{"listing_status_id":params[k][field]}}};                                  
                                } else {
                                    whereCond[field] = params[k][field];
                                }
                            }else{
                                addtional_param[field] = params[k][field];
                            }
                        }
                    }
                    if(Object.keys(whereCond).length > 0){
                        filter.where = whereCond;
                    }                                   
                }
            }
            if(Object.keys(filter).length > 0 || Object.keys(addtional_param).length > 0){
                filter = JSON.stringify(filter);
                filter = {'filter': filter};
                Object.assign(filter, addtional_param);
            }
        return {
            params: filter,
            url: url
        };
    });
    RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response) {
        headers = headers || {};
        headers['x-ag-app-secret'] = '3f7C4l1Y2b0S6a7L8c1E7B3Jo3';
        if (operation === "getList") {
            var headers = response.headers();
            if (typeof response.data._metadata !== 'undefined' && response.data._metadata.total !== null) {
                response.totalCount = response.data._metadata.total;
            }
        }
        return data;
    });
    //To cutomize single view results, we added setResponseExtractor.
    //Our API Edit view results single array with following data format data[{}], Its not working with ng-admin format
    //so we returned data like data[0];
    RestangularProvider.setResponseExtractor(function(data, operation, what, url) {
        var extractedData;
        // .. to look for getList operations        
        extractedData = data.data;
        return extractedData;
    });
}]);
ngapp.config(['NgAdminConfigurationProvider', 'user_types', 'CmsConfig', 'ngAdminJWTAuthConfiguratorProvider', function(NgAdminConfigurationProvider, userTypes, CmsConfig,  ngAdminJWTAuthConfigurator) {
    var nga = NgAdminConfigurationProvider;
    ngAdminJWTAuthConfigurator.setJWTAuthURL(admin_api_url + 'api/v1/users/login');
    ngAdminJWTAuthConfigurator.setCustomLoginTemplate('views/users_login.html');
    console.log('ng-admin.app');
    ngAdminJWTAuthConfigurator.setCustomAuthHeader({
        name: 'Authorization',
        template: 'Bearer {{token}}'
    });
    var admin = nga.application(site_name + '\t' + 'Admin')
        .baseApiUrl(admin_api_url + 'api/v1/'); // main API endpoint;
  //  var customHeaderTemplate = '<div class="navbar-header">' + '<button type="button" class="navbar-toggle" ng-click="isCollapsed = !isCollapsed">' + '<span class="icon-bar"></span>' + '<span class="icon-bar"></span>' + '<span class="icon-bar"></span>' + '</button>' + '<a class="al-logo ng-binding ng-scope" href="#/dashboard" ng-click="appController.displayHome()"><span>' + site_name + '</span> Admin Panel</a>' + '<a href="" ng-click="isCollapsed = !isCollapsed" class="collapse-menu-link ion-navicon" ba-sidebar-toggle-menu=""></a>' + '</div>' + '<custom-header></custom-header>';
    // customize header
    var customHeaderTemplate = '<div class="navbar-header">' +
        '<button type="button" class="navbar-toggle" ng-click="isCollapsed = !isCollapsed">' +
        '<span class="icon-bar"></span>' +
        '<span class="icon-bar"></span>' +
        '<span class="icon-bar"></span>' +
        '</button>' +
        '<a class="navbar-brand" ui-sref="dashboard"><img src="assets/img/logo.png" alt="[Image: '+site_name+']" title="'+site_name+'" width="90px" /></a>' +
        '</div>' + '<custom-header></custom-header>';  
    admin.header(customHeaderTemplate);
    admin.menu(nga.menu()
        .addChild(nga.menu()
            .title(' Dashboard')
            .icon('<span class="fa fa-home fa-fw"></span>')
            .link("/dashboard")));
    generateMenu(CmsConfig.menus);
    var entities = {};
    if (angular.isDefined(CmsConfig.dashboard)) {
        dashboard_template = '';
        var collections = [];
        angular.forEach(CmsConfig.dashboard, function(v, collection) {
            var fields = [];
            dashboard_template = dashboard_template + v.addCollection.template;
            if (angular.isDefined(v.addCollection)) {
                angular.forEach(v.addCollection, function(v1, k1) {
                    if (k1 == 'fields') {
                        angular.forEach(v1, function(v2, k2) {
                            var field = nga.field(v2.name, v2.type);
                            if (angular.isDefined(v2.label)) {
                                field.label(v2.label);
                            }
                            if (angular.isDefined(v2.template)) {
                                field.template(v2.template);
                            }
                            fields.push(field);
                        });
                    }
                });
            }
            collections.push(nga.collection(nga.entity(collection))
                    .name(v.addCollection.name)
                    .title(v.addCollection.title)
                    .perPage(v.addCollection.perPage)
                    .fields(fields)
                    .order(v.addCollection.order));
        });
        dashboard_page_template = '<div class="row list-header"><div class="col-lg-12"><div class="page-header">' + '<h4><span>Dashboard</span></h4></div></div></div>' + '<dashboard-summary></dashboard-summary>' + '<div class="row dashboard-content">' + dashboard_template + '</div>';
        var nga_dashboard = nga.dashboard();
        angular.forEach(collections, function(v, k) {
            nga_dashboard.addCollection(v);
        });
        nga_dashboard.template(dashboard_page_template)
        admin.dashboard(nga_dashboard);
    }
    if (angular.isDefined(CmsConfig.tables)) {
        angular.forEach(CmsConfig.tables, function(v, table) {
            var listview = {},
                editionview = {},
                creationview = {},
                showview = {},
                editViewCheck = false,
                editViewFill = "",
                showViewCheck = false,
                showViewFill = "";
            listview.fields = [];
            editionview.fields = [];
            creationview.fields = [];
            listview.filters = [];
            listview.listActions = [];
            listview.batchActions = [];
            listview.actions = [];
            showview.fields = [];
            listview.infinitePagination = "",
                listview.perPage = "";
            entities[table] = nga.entity(table);
            if (angular.isDefined(v.listview)) {
                angular.forEach(v.listview, function(v1, k1) {
                    if (k1 == 'fields') {
                        angular.forEach(v1, function(v2, k2) {
                            var field = nga.field(v2.name, v2.type);
                            if (angular.isDefined(v2.label)) {
                                field.label(v2.label);
                            }
                            if (angular.isDefined(v2.isDetailLink)) {
                                field.isDetailLink(v2.isDetailLink);
                            }
                            if (angular.isDefined(v2.detailLinkRoute)) {
                                field.detailLinkRoute(v2.detailLinkRoute);
                            }
                            if (angular.isDefined(v2.template)) {
                                field.template(v2.template);
                            }
                            if (angular.isDefined(v2.permanentFilters)) {
                                field.permanentFilters(v2.permanentFilters);
                            }
                            if (angular.isDefined(v2.infinitePagination)) {
                                field.infinitePagination(v2.infinitePagination);
                            }
                            if (angular.isDefined(v2.singleApiCall)) {
                                if (angular.isDefined(v2.targetEntity)) {
                                    field.targetEntity(nga.entity(v2.targetEntity));
                                }
                                if (angular.isDefined(v2.targetField)) {
                                    field.targetField(nga.field(v2.targetField));
                                }
                            }
                            if (angular.isDefined(v2.singleApiCall)) {
                                field.singleApiCall(v2.singleApiCall);
                            }
                            if (angular.isDefined(v2.batchActions)) {
                                field.batchActions(v2.batchActions);
                            }
                            if (angular.isDefined(v2.stripTags)) {
                                field.stripTags(v2.stripTags);
                            }
                            if (angular.isDefined(v2.exportOptions)) {
                                field.exportOptions(v2.exportOptions);
                            }
                            if (angular.isDefined(v2.remoteComplete)) {
                                field.remoteComplete(true, {
                                    searchQuery: function(search) {
                                        return {
                                            q: search,
                                            autocomplete: true
                                        };
                                    }
                                });
                            }
                            if (angular.isDefined(v2.map)) {
                                angular.forEach(v2.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
                            listview.fields.push(field);
                        });
                    }
                    if (k1 == 'filters') {
                        angular.forEach(v1, function(v3, k3) {
                            var field;
                            if (v3.type === "template") {
                                field = nga.field(v3.name);
                            } else {
                                field = nga.field(v3.name, v3.type);
                            }
                            if (angular.isDefined(v3.label)) {  
                                field.label(v3.label);
                            }
                            if (angular.isDefined(v3.choices)) {
                                field.choices(v3.choices);
                            }
                            if (angular.isDefined(v3.pinned)) {
                                field.pinned(v3.pinned);
                            }
                            if (angular.isDefined(v3.template) && v3.template !== "") {
                                field.template(v3.template);
                            }
                            if (angular.isDefined(v3.targetEntity)) {
                                field.targetEntity(nga.entity(v3.targetEntity));
                            }
                            if (angular.isDefined(v3.targetField)) {
                                field.targetField(nga.field(v3.targetField));
                            }
                            if (angular.isDefined(v3.permanentFilters)) {
                                field.permanentFilters(v3.permanentFilters);
                            }
                            if (angular.isDefined(v3.remoteComplete)) {
                                field.remoteComplete(true, {
                                    searchQuery: function(search) {
                                        var remoteComplete = {
                                            q: search,
                                            autocomplete: true
                                        };
                                        if (angular.isDefined(v3.remoteCompleteAdditionalParams)) {
                                            angular.forEach(v3.remoteCompleteAdditionalParams, function(value, key) {
                                                remoteComplete[key] = value;
                                            });
                                        }
                                        return remoteComplete;
                                    }
                                });
                            }
                            if (angular.isDefined(v3.map)) {
                                angular.forEach(field.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
                            listview.filters.push(field);
                        });
                    }
                    if (k1 == 'listActions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                if (v3 === "edit") {
                                    editViewCheck = true;
                                }
                                if (v3 === "show") {
                                    showViewCheck = true;
                                }
                                listview.listActions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.listActions.push(v1);
                        }
                    }
                    if (k1 == 'batchActions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                listview.batchActions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.batchActions.push(v1);
                        }
                    }
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                listview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.actions.push(v1);
                        }
                    }
                    if (k1 == 'infinitePagination') {
                        entities[table].listView()
                            .infinitePagination(v1);
                    }
                    if (k1 == 'perPage') {
                        entities[table].listView()
                            .perPage(v1);
                    }
                    if (k1 == 'sortDir') {
                        entities[table].listView()
                            .sortDir(v1);
                    }
                });
                if (angular.isDefined(v.creationview)) {
                    editViewFill = generateFields(v.creationview.fields);
                    creationview.fields.push(editViewFill);
                    if (editViewCheck === true && !angular.isDefined(v.editionview)) {
                        editionview.fields.push(editViewFill);
                    } else if (angular.isDefined(v.editionview)) {
                        editionview.fields.push(generateFields(v.editionview.fields));
                    }
                }
            }
             if (angular.isDefined(v.editionview)) {
                angular.forEach(v.editionview, function(v1, k1) {
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            editionview.actions = [];
                            angular.forEach(v1, function(v3, k3) {
                                editionview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            editionview.actions.push(v1);
                        }
                    }
                });
             }
            if (angular.isDefined(v.showview)) {
                showview.fields.push(generateFields(v.showview.fields));
            } else if (showViewCheck === true) {
                showview.fields.push(listview.fields);
            }
            if (angular.isDefined(v.showview)) {
                angular.forEach(v.showview, function(v1, k1) {
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            showview.actions = [];
                            angular.forEach(v1, function(v3, k3) {
                                showview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            showview.actions.push(v1);
                        }
                    }
                });
            }
            admin.addEntity(entities[table]);
            entities[table].listView()
                .title(v.listview.title)
                .fields(listview.fields)
                .listActions(listview.listActions)
                .batchActions(listview.batchActions)
                .actions(listview.actions)
                .filters(listview.filters);
            if (angular.isDefined(v.creationview)) {
                entities[table].creationView()
                    .title(v.creationview.title)
                    .fields(creationview.fields)
                    .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function(progression, notification, $state, entry, entity) {
                        progression.done();
                        notification.log(toUpperCase(entity.name()) + ' added successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        $state.go($state.get('list'), {
                            entity: entity.name()
                        });
                        return false;
                    }])
                     .onSubmitError(['error', 'form', 'progression', 'notification', 'entity', function(error, form, progression, notification, entity) {
                        angular.forEach(error.data.errors, function(value, key) {
                            if (this[key]) {
                                this[key].$valid = false;
                            }
                        }, form);
                        progression.done();
                        if(entity.name() === 'users')
                        {
                        if (angular.isDefined(error.data.error.fields) && angular.isDefined(error.data.error.fields.unique) && error.data.error.fields.unique.length !== 0) {
                                notification.log(' Please choose different ' + ' ' + error.data.error.fields.unique.join(), {
                                addnCls: 'humane-flatty-error'
                                });
                            }else {
                                notification.log(error.data.message, {
                                addnCls: 'humane-flatty-error'
                                });
                            }
                        }
                        if (entity.name() === 'countries') {
                            notification.log(error.data.error.message, {
                                addnCls: 'humane-flatty-error'
                            });
                        }
                        return false;
                    }]);
                if (angular.isDefined(v.creationview.prepare)) {
                    entities[table].creationView()
                        .prepare(['entry', function(entry) {
                            angular.forEach(v.creationview.prepare, function(value, key) {
                                entry.values[key] = value;
                            });
                            return entry;
                        }]);
                }
            }
            if (angular.isDefined(v.editionview) || editViewCheck === true) {
                var editTitle;
                if (editViewCheck === true && angular.isDefined(v.editionview)) {
                    editTitle = v.editionview.title;
                }
                else {
                     editTitle = v.creationview.title;
                }
                entities[table].editionView()
                    .title(editTitle)
                    .fields(editionview.fields)
                    .actions(editionview.actions)
                    .onSubmitSuccess(['progression', 'notification', '$location', '$state', 'entry', 'entity', function(progression, notification, $location, $state, entry, entity) {
                        progression.done();
                        console.log(entity.name());
                        if (entity.name() === 'email_templates' ||entity.name() === 'user_cash_withdrawals' ) {
                            var entity_name = toUpperCase(entity.name());
                        var entity_rep = entity_name.replace(/_/g , " ");
                            notification.log(entity_rep +' ' + 'updated successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        }
                        else {
                        notification.log(toUpperCase(entity.name()) + ' updated successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        }
                        if (entity.name() === 'settings') {
                            var current_id = entry.values.setting_category_id;
                            $location.path('/setting_categories/show/' + current_id);
                        } else {
                            $state.go($state.get('list'), {
                                entity: entity.name()
                            });
                        }
                        return false;
                    }])
                    .onSubmitError(['error', 'form', 'progression', 'notification', 'entity', function(error, form, progression, notification, entity) {
                        angular.forEach(error.data.errors, function(value, key) {
                            if (this[key]) {
                                this[key].$valid = false;
                            }
                        }, form);
                        progression.done();
                        if (entity.name() === 'countries') {
                            notification.log(error.data.error.message, {
                                addnCls: 'humane-flatty-error'
                            });
                        } else { 
                            notification.log(error.data.error.message, {
                            addnCls: 'humane-flatty-error'
                            });
                        }
                        return false;
                    }]);
            }
            if (angular.isDefined(v.showview) || showViewCheck === true) {
                if (showViewCheck === true) {
                    entities[table].showView()
                        .title(v.listview.title);
                } else if (angular.isDefined(v.showview) && angular.isDefined(v.showview.title)) {
                    entities[table].showView()
                        .title(v.showview.title);
                }
                entities[table].showView()
                    .fields(showview.fields)
                    .actions(showview.actions);
            }
        });
    }

    function generateMenu(menus) {
        angular.forEach(menus, function(menu_value, menu_keys) {
            var menus;
            if (angular.isDefined(menu_value.link)) {
                menusIndex = nga.menu();
                menusIndex.link(menu_value.link);
            } else if (angular.isDefined(menu_value.child_sub_menu)) {
                menusIndex = nga.menu();
            } else {
                menusIndex = nga.menu(nga.entity(menu_keys));
            }
            if (angular.isDefined(menu_value.title)) {
                menusIndex.title(menu_value.title);
            }
            if (angular.isDefined(menu_value.icon_template)) {
                menusIndex.icon(menu_value.icon_template);
            }
            if (angular.isDefined(menu_value.child_sub_menu)) {
                angular.forEach(menu_value.child_sub_menu, function(val, key) {
                    var child = nga.menu(nga.entity(key));
                    if (angular.isDefined(val.title)) {
                        child.title(val.title);
                    }
                    if (angular.isDefined(val.icon_template)) {
                        child.icon(val.icon_template);
                    }
                    if (angular.isDefined(val.link)) {
                        child.link(val.link);
                    }
                    menusIndex.addChild(child);
                });
            }
            admin.menu()
                .addChild(menusIndex);
        });
    }

    function generateFields(fields) {
        var generatedFields = [];
        angular.forEach(fields, function(targetFieldValue, targetFieldKey) {
            var field = nga.field(targetFieldValue.name, targetFieldValue.type),
                fieldAdd = true;
            if (angular.isDefined(targetFieldValue.label)) {
                field.label(targetFieldValue.label);
            }
            if (angular.isDefined(targetFieldValue.stripTags)) {
                field.stripTags(targetFieldValue.stripTags);
            }
            if (angular.isDefined(targetFieldValue.choices)) {
                field.choices(targetFieldValue.choices);
            }
            if (angular.isDefined(targetFieldValue.editable)) {
                field.editable(targetFieldValue.editable);
            }
            if (angular.isDefined(targetFieldValue.attributes)) {
                field.attributes(targetFieldValue.attributes);
            }
            if (angular.isDefined(targetFieldValue.perPage)) {
                field.perPage(targetFieldValue.perPage);
            }
            if (angular.isDefined(targetFieldValue.listActions)) {
                field.listActions(targetFieldValue.listActions);
            }
            if (angular.isDefined(targetFieldValue.targetEntity)) {
                field.targetEntity(nga.entity(targetFieldValue.targetEntity));
            }
            if (angular.isDefined(targetFieldValue.targetReferenceField)) {
                field.targetReferenceField(targetFieldValue.targetReferenceField);
            }
            if (angular.isDefined(targetFieldValue.targetField)) {
                field.targetField(nga.field(targetFieldValue.targetField));
            }
            if (angular.isDefined(targetFieldValue.map)) {
                angular.forEach(targetFieldValue.map, function(v2m, k2m) {
                    field.map(eval(v2m));
                });
            }
            if (angular.isDefined(targetFieldValue.format)) {
                field.format(targetFieldValue.format);
            }
            if (angular.isDefined(targetFieldValue.template)) {
                field.template(targetFieldValue.template);
            }
            if (angular.isDefined(targetFieldValue.permanentFilters)) {
                field.permanentFilters(targetFieldValue.permanentFilters);
            }
            if (angular.isDefined(targetFieldValue.defaultValue)) {
                field.defaultValue(targetFieldValue.defaultValue);
            }
            if (angular.isDefined(targetFieldValue.validation)) {
                field.validation(eval(targetFieldValue.validation));
            }
            if (angular.isDefined(targetFieldValue.remoteComplete)) {
                field.remoteComplete(true, {
                    searchQuery: function(search) {
                        return {
                            q: search,
                            autocomplete: true
                        };
                    }
                });
            }
            if (angular.isDefined(targetFieldValue.uploadInformation) && angular.isDefined(targetFieldValue.uploadInformation.url) && angular.isDefined(targetFieldValue.uploadInformation.apifilename)) {
                field.uploadInformation({
                    'url': admin_api_url + targetFieldValue.uploadInformation.url,
                    'apifilename': targetFieldValue.uploadInformation.apifilename
                });
            }
            if (targetFieldValue.type === "file" && (!angular.isDefined(targetFieldValue.uploadInformation) || !angular.isDefined(targetFieldValue.uploadInformation.url) || !angular.isDefined(targetFieldValue.uploadInformation.apifilename))) {
                fieldAdd = false;
            }
            if (angular.isDefined(targetFieldValue.targetFields) && (targetFieldValue.type === "embedded_list" || targetFieldValue.type === "referenced_list")) {
                var embField = generateFields(targetFieldValue.targetFields);
                field.targetFields(embField);
            }
            if (fieldAdd === true) {
                generatedFields.push(field);
            }
        });
        return generatedFields;
    }
    nga.configure(admin);

    function getUsers(userIds) {
        return {
            "user_id[]": userIds
        };
    }
    function getFlags(flagIds) {
        return {
            "flag_id[]": flagIds
        };
    }            
    function getStatus(statusIds) {
        return {
            "Requestor_id[]": statusIds
        };
    }

    function getInputType(InputypeIds) {
        return {
            "input_type_id[]": InputypeIds
        };
    }

    function getRequest(requestorIds) {
        return {
            "Service_id[]": requestorIds
        };
    }

    function getdiscountType(DiscountypeIds) {
        return {
            "discount_type_id[]": DiscountypeIds
        };
    }
}]);
ngapp.run(['$rootScope', '$location', '$window', '$state', 'user_types', function($rootScope, $location, $window, $state, userTypes) {
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
        var url = toState.name;
        if ($cookies.get('enabled_plugins') !== undefined && $cookies.get('enabled_plugins') !== null) {
            $rootScope.enabled_plugins = JSON.parse($cookies.get('enabled_plugins'));
        }
        if ($cookies.get('SETTINGS') !== undefined && $cookies.get('SETTINGS') !== null) {
            $rootScope.settings = JSON.parse($cookies.get('SETTINGS'));
        }
        var exception_arr = ['login', 'logout'];
        if (($cookies.get("auth") === null || $cookies.get("auth") === undefined) && exception_arr.indexOf(url) === -1) {
            $location.path('/users/login');
        }
        if (exception_arr.indexOf(url) === 0 && $cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
            $location.path('/dashboard');
        }
        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
            var auth = JSON.parse($cookies.get("auth"));
            if (auth.role_id === userTypes.user) {
                $location.path('/users/logout');
            }
        }
        trayOpen();
    });
}]);
ngapp.filter('date_format', function($filter) {
    return function(input, format) {
        return $filter('date')(new Date(input), format);
    };
})
function addFields(getFields) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0)
            .toUpperCase() + txt.substr(1)
            .toLowerCase();
    });
}
function trayOpen() {
    setTimeout(function() {
        /* For open sub-menu tray */
        if ($('.active')
            .parents('.with-sub-menu')
            .attr('class')) {
            $('.active')
                .parents('.with-sub-menu')
                .addClass('ba-sidebar-item-expanded');
        }
        /* For open collaps menu when menu in collaps state */
        $('.al-sidebar-list-link')
            .click(function() {
                if ($('.js-collaps-main')
                    .hasClass('menu-collapsed')) {
                    $('.js-collaps-main')
                        .removeClass('menu-collapsed');
                }
            });
    }, 100);
}

function menucollaps() {
    setTimeout(function() {
        /* For menu collaps and open */
        $('.collapse-menu-link')
            .click(function() {
                if ($('.js-collaps-main')
                    .hasClass('menu-collapsed')) {
                    $('.js-collaps-main')
                        .removeClass('menu-collapsed');
                } else {
                    $('.js-collaps-main')
                        .addClass('menu-collapsed');
                }
            });
    }, 1000);
}

function toUpperCase(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0)
            .toUpperCase() + txt.substr(1)
            .toLowerCase();
    });
}

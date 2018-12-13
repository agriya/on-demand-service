'use strict';
angular.module('hirecoworkerApp')
	.directive('dashboardSettings', function ($filter) {
        return {
            restrict: 'E',
            templateUrl: 'views/dashboard_settings.html',
            link: function (scope) {
                scope.currentDate = $filter('date')(new Date(), 'yyyy-MM-dd');
                if (localStorage.zone === undefined) {
                    localStorage.zone = moment(new Date()).format('Z');
                }
            },
            scope: true
        };
    }).directive('dashboardMenu', function ($filter) {
        return {
            restrict: 'E',
            templateUrl: 'views/dashboard_menu.html',
            link: function (scope) {
                scope.currentDate = $filter('date')(new Date(), 'yyyy-MM-dd');
                if (localStorage.zone === undefined) {
                    localStorage.zone = moment(new Date()).format('Z');
                }
            },
            controller:function($rootScope){
                $rootScope.gotoHeader();
            },
            scope: true
        };
    });

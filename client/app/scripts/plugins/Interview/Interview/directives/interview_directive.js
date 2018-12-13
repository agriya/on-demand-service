'use strict';
angular.module('hirecoworkerApp')
	.directive('interviewBlock', function () {
        return {
            restrict: 'E',
            templateUrl: 'scripts/plugins/Interview/Interview/views/interview.html',
            scope: true,
        };
    });
'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.home
 * @description
 * # cities
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
 .factory('AllReviews', ['$resource', function($resource) {
        return $resource('/api/v1/reviews', {}, {
            get: {
                    method: 'GET'
                },
                add:{
                    method: 'POST',
                }
        });
}]) 
 .factory('ReviewFactory', ['$resource', function($resource) {
        return $resource('/api/v1/reviews', {}, {
                add:{
                    method: 'POST',
                }
        });
}]) 
 .factory('ReviewUpdate', ['$resource', function($resource) {
        return $resource('/api/v1/reviews/:reviewId', {reviewId:'@reviewId'}, {
                update:{
                    method: 'PUT',
                }
        });
}]) 
.factory('UserReviews', ['$resource', function($resource) {
        return $resource('/api/v1/reviews/:doctor_id/:user_id', {  doctor_id:'@doctor_id', user_id:'@user_id' }, {
            get: {
                    method: 'GET'
                },
                add:{
                    method: 'POST',
                }
        });
}])      
.factory('ReviewAdd', ['$resource', function($resource) {
        return $resource('/api/v1/reviews/add', {}, {
            add: {
                    method: 'POST'
                } 
        });
}]);
    


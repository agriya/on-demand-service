'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.JobRequest
 * @description
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('JobRequestMe', function ($resource) {
        return $resource('/api/v1/me/requests', {}, {
            'get': {
                method: 'GET'
            }
        });
    })
    .factory('JobRequestPost', function ($resource) {
        return $resource('/api/v1/requests', {}, {
            'post':{
                method: 'POST'
            }
        });
    }).factory('JobRequestGet', function ($resource) {
        return $resource('/api/v1/requests/:requestId', {requestId:'@requestId'}, {
            'get':{
                method: 'GET'
            },
            'put':{
                method: 'PUT'
            },
            'delete':{
                method: 'DELETE'
            }
        });
    }).factory('ExpressInterest', function ($resource) {
        return $resource('/api/v1/requests_users' ,{} ,{
            'post':{
                method: 'POST'
            }
        });
    })
    .factory('RemoveInterest', function ($resource) {
        return $resource('/api/v1/requests_users/:requestsUserId' ,{requestsUserId:'@requestsUserId'} ,{
            'delete':{
                method: 'DELETE'
            }
        });
    });;


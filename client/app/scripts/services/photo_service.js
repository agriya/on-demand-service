'use strict';
angular.module('hirecoworkerApp')
.factory('PhotosFactory', ['$resource', function($resource) {
       return $resource('/api/v1/photos/:username', {username: '@username'}, {
            'get': {
                method: 'GET'
            }
        });
}])
.factory('PhotosAddFactory', function ($resource) {
        return $resource('api/v1/photos', {}, {
            'save': {
                method: 'POST'
            }
        });
    })
.factory('PhotoDeleteFactory', function ($resource) {
        return $resource('/api/v1/attachments/:id', {
                id: '@id'
            }, {
                'delete': {
                    method: 'DELETE'
                }
            }
        );
    });


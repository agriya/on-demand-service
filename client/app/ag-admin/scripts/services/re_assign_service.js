'use strict';
/**
 * @ngdoc service
 * @name getlancerv3.servicelocation
 * @description
 * # paymentGateway
 * Factory in the getlancerv3.
 */
angular.module('base')
    .factory('AppointmentMeFactory', function($resource) {
        return $resource('/api/v1/appointments/:id', {}, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                }
            }
        });
    })
	.factory('UserFactory', function($resource) {
        return $resource('/api/v1/users', {}, {
            get: {
                method: 'GET',
            }
        });
    })
    .factory('ChangeProviderFactory', function($resource) {
        return $resource('/api/v1/appointments/:id/change_service_provider', {}, {
            put: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
    });    
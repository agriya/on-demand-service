'use strict';
/**
 * @ngdoc function
 * @name ofosApp.controller:ServicelocationController
 * @description
 * # ServicelocationController
 * Controller of the getlancerv3
 */
angular.module('base')
    .controller('ReassignController', function($scope, $http, $filter, $location, notification, $state, AppointmentMeFactory,UserFactory,ChangeProviderFactory) {
        var params = {};
         params.filter = '{"include":{"0":"provider_user.user_profile"}}';
        params.id = $state.params.id;
     	AppointmentMeFactory.get(params, function (response) {
             $scope.appointment = response.data;
		});
        var params ={};
        //  params.filter = {"include":{"where":{"AND":{"role_id":3,"is_active":1,"is_deleted":0}}},"include":{"0":"user_profile"}};
         params.filter = {"include":{"0":"user_profile"},"where":{"AND":{"role_id":3,"is_active":1,"is_deleted":0}}};
        UserFactory.get(params, function (response) {
             $scope.users = response.data;
		});
		$scope.reassignProvider = function(user,$valid) {
			var params = {};
				params.id = $state.params.id;
                params.new_service_provider = user.selected.id;
                if($valid){
				ChangeProviderFactory.put(params, function (response) {
					if (angular.isDefined(response.error.code === 0)) {
						notification.log($filter("translate")("Reassign service provider changed successfully"),{
                            addnCls: 'humane-flatty-success'
                        });
                            $location.path('/bookings/list')
					}
				});
                }
		};
	
    });
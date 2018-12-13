'use strict';
angular.module('hirecoworkerApp')
	.directive('blockUser', function () {
        return {
            restrict: 'EA',
            scope: true,
            template: '<button type="button" class="btn btn-pinterest" ng-click="blockUser()" ng-if="user.blocking == null || user.blocking == undefined"><i class="fa fa-ban" aria-hidden="true"></i> {{"Block" | translate}}</button> '+
                      '<button type="button" class="btn btn-block-user" ng-click="unlockUser(user.blocking.id)" ng-mouseover="changeText(1)" ng-mouseleave="changeText(2)"  ng-if="user.blocking != null && user.blocking != undefined"><i class="fa fa-unlock" aria-hidden="true"></i> {{buttonText}}</button>',

            controller : function($scope, $rootScope, $stateParams, BlockUser, $cookies, DeleteBlockUser, flash, $filter){
                if($cookies.getObject("auth")){
                    $scope.auth_user_detail = $cookies.getObject("auth");
                }
                $scope.buttonText =$filter("translate")("Blocked");
                $scope.changeText = function(type){
                    if(type === 1){
                        $scope.buttonText =$filter("translate")("Unblock");
                    }else{
                        $scope.buttonText =$filter("translate")("Blocked");
                    }
                };
                var params = {};
                $scope.blockUser = function(){
                    params = {};
                    params.blocked_user_id = $stateParams.user_id;
                    BlockUser.post(params, function(response){
                        if(response.error.code === 0){
                            $scope.user.blocking = response.data;
                            flash.set($filter("translate")("User blocked successfully."), 'success', false);
                            $scope.Authuser = {
                                id: $scope.auth_user_detail.id,
                                username: $scope.auth_user_detail.username,
                                role_id: $scope.auth_user_detail.role_id,
                                refresh_token: $scope.auth_user_detail.refresh_token,
                                attachment: $scope.auth_user_detail.attachment,
                                is_profile_updated: $scope.auth_user_detail.is_profile_updated,
                                affiliate_pending_amount : $scope.auth_user_detail.affiliate_pending_amount,
                                category_id : $scope.auth_user_detail.category_id,
                                user_profile : $scope.auth_user_detail.user_profile,
                                blocked_user_count : 1
                            };
                            $cookies.put('auth', JSON.stringify($scope.Authuser), {
                                path: '/'
                            });
                            $rootScope.auth = JSON.parse($cookies.get('auth'));
                            
                        }
                    });
                };
                    $scope.unlockUser = function(id){
                        params = {};
                        params.blockedUserId = id;
                        DeleteBlockUser.delete(params, function(response){
                            if(response.error.code === 0){
                                $scope.user.blocking = null;
                                flash.set($filter("translate")("User unblocked successfully."), 'success', false);
                            }
                        });
                    };
            }        
        };
    });
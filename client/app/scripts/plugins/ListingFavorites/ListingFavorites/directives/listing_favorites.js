'use strict';
angular.module('hirecoworkerApp')
	.directive('listingFavorites', function ($filter) {
        return {
            restrict: 'E',
            template:'<span class="text-center text-12 fav-list pointer" ng-click="UserFavorite(user,1)" ng-show="user.is_favorite" ><i class="fa fa-heart fa-2x" aria-hidden="true"></i> </span>' +
            '<p class="no-mar"><span class="text-center text-12 pointer" ng-click="UserFavorite(user,2)" ng-show="!user.is_favorite"> <i class="fa fa-heart-o fa-2x" aria-hidden="true"></i> <span ng-if="state_user_view"> {{"Add to wish list" | translate}} </span> </span></p>',
            scope: true,
            controller: function($scope, $rootScope, EditUserFavorite, UserFavorite, $state, flash){
                    $scope.state_user_view = false;
                    if($state.current.name === "UserView"){
                        $scope.state_user_view = true;
                    }
                    $scope.UserFavorite = function(user, type){
                    var params = {};
                    params.user_id = user.id;
                    params.username = user.username;
                    if(parseInt(type) === 1){
                        user.is_favorite = false;
                        EditUserFavorite.delete({userFavoriteId:user.favorite_id}, function(response){
                            if(response.error.code !== 0){
                                user.is_favorite = true;
                            }else{
                                if($state.current.name === "UserView"){
                                    flash.set($filter("translate")("User removed from favorite list."), 'success', false);
                                }

                            }
                        });
                    }else if(parseInt(type) === 2){
                        user.is_favorite = true;
                        UserFavorite.post(params, function(response){
                            if(response.error.code !== 0){
                                user.is_favorite = false;
                            }else{
                                user.favorite_id = response.data.id;
                                if($state.current.name === "UserView"){
                                    flash.set($filter("translate")("User added to favorite list."), 'success', false);
                                    
                                }
                            }   
                        });
                    }
                };
            }
        };
    });
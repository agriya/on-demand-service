'use strict';
angular.module('hirecoworkerApp')
	.directive('reviewPost', function ($filter) {
        return {
            restrict: 'E',
            templateUrl: 'scripts/plugins/Review/Review/views/review_booking_view.html',
            scope: true,
            controller:"ReviewController"
        };
    }).directive('reviewShow', function ($filter) {
        return {
            restrict: 'E',
            templateUrl: 'scripts/plugins/Review/Review/views/review_user_view.html',
            scope: true,
            controller:function($scope, $rootScope, AllReviews, $state, $window, md5){
                 var params = {};
                 $scope.reviews = [];
                 $scope.currentPage = $scope.lastPage = 1;
                 $scope.show_more = false;
                 $scope.getReviews = function(){
                    $scope.skipvalue = $scope.itemsPerPage !== undefined ? ($scope.currentPage) * $scope.itemsPerPage : 0;
                    $scope.itemsPerPage = 10;
                    params.filter = '{"order":"id desc","limit":'+$scope.itemsPerPage+',"skip":'+$scope.skipvalue+',"where":{"to_user_id":'+$state.params.user_id+'},"include": {"0":"user","1":"user.attachment","2":"user.user_profile"}}';
                    AllReviews.get(params).$promise.then(function (response) {
                            if(response._metadata){
                                $scope.currentPage = response._metadata.current_page;
                                $scope.lastPage = response._metadata.last_page;
                                $scope.itemsPerPage = response._metadata.per_page;
                                $scope.totalRecords = response._metadata.total;
                                $scope.show_more = $scope.totalRecords > ($scope.currentPage * $scope.itemsPerPage) ? true : false;
                            }
                            angular.forEach(response.data, function(value){
                                if (angular.isDefined(value.user) && angular.isDefined(value.user.attachment)) {
                                    var hash = md5.createHash(value.user.attachment.class + value.user.attachment.id + 'png' + 'small_thumb');
                                    value.review_image = 'images/small_thumb/' + value.user.attachment.class + '/' + value.user.attachment.id + '.' + hash + '.png';
                                } else {
                                    value.review_image = $window.theme + 'images/default.png';
                                }
                                $scope.reviews.push(value);
                            });
                            
                    });
                 };
                 $scope.getReviews();
                  $scope.paginate_reviews = function() {
                    $scope.getReviews();
                  };
            }
        };
    });

'use strict';
/**
 * @ngdoc function
 * @name olxApp.controller:UsersChangePasswordController
 * @description
 * # UsersChangePasswordController
 * Controller of the olxApp
 */
angular.module('base').controller("pagesController", function($scope, $http, $location, notification, languageList) {
    $scope.languageArr = [];
    var admin_api_url = '/';
    $scope.init = function () {
        languageList.get(function(response){
            $scope.languageList = response.site_languages;
        });
    };
    $scope.pageAdd = function () {
        var params = {};
        params.slug = $scope.languageArr.pages.slug;
        params.pages = [];
        angular.forEach($scope.languageList, function (value, key) {
            if(key !== "slug"){
                console.log(value);
                params.pages.push({"title":value.title,"page_content":value.content,"language_id":value.id,"is_active":value.is_active});
            }
        });
        $http.post(admin_api_url + 'api/v1/pages', params).success(function (response) {
            if (response.error.code === 0) {
                notification.log('Page added successfully.', {
                        addnCls: 'humane-flatty-success'
                    });
                $location.path('/pages/list');
            } else {
                notification.log('Page could not be updated. Please, try again.', {addnCls: 'humane-flatty-error'});
            }
        });
    };
    $scope.init();
 });
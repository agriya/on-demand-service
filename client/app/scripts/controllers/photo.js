'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:PhotosController
 * @description
 * # PhotosController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name user.controller:PhotosController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/

 .controller('PhotosController', function ($state, $scope, flash, $filter, $rootScope, $location, Upload, PhotosFactory, PhotoDeleteFactory, SweetAlert, $cookies, UserProfilesFactory, md5) {
        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:PhotosController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        $rootScope.subHeader = "Listing"; //for setting active class for subheader
         $cookies.put('subHeader', $rootScope.subHeader);
         $rootScope.gotoHeader();
        $scope.setMetaData = function () {
            var pageTitle = $filter("translate")("My Photos");
            var fullUrl = $location.absUrl();
            var appUrl = $rootScope.settings['scheme_name'] + ":/" + $location.url();// jshint ignore:line
            angular.element('html head meta[property="og:title"], html head meta[name="twitter: title"]').attr("content", $rootScope.settings['site.name'] + " | " + pageTitle);
            angular.element('meta[property="al:ios:url"], meta[property="al:ipad:url"], meta[property="al:android:url"], meta[property="al:windows_phone:url"], html head meta[name="twitter:app:url:iphone"], html head meta[name="twitter:app:url:ipad"], html head meta[name="twitter:app:url:googleplay"]').attr('content', appUrl);
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:PhotosController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + " | " + $filter("translate")("Photos");
        };
        $scope.init();
        var params = {};
        params.filter='{"include":{"0":"user.listing_photo"}}';
        UserProfilesFactory.get(params, function(response){
            $scope.photos = response.user.listing_photo;
            angular.forEach($scope.photos, function(value){
                if (angular.isDefined(value) && value !== null) {
                var c = new Date();
                var hash = md5.createHash(value.class + value.id + 'png' + 'big_thumb');
                value.listimage = 'images/big_thumb/' + value.class + '/' + value.id + '.' + hash + '.png?' + c.getTime();
                }
            });
        });
        $scope.photoDelete = function (id) {
            SweetAlert.swal({
                title:$filter("translate")("Are you sure you want to remove this photo?"),
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55", confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
                function (isConfirm) {
                    if (isConfirm) {
                        PhotoDeleteFactory.delete({ id: id }).$promise.then(function (response) {
                            if (response.error.code === 0) {
                                flash.set($filter("translate")("Photo deleted successfully"), 'success', true);
                                $state.reload();
                            } else {
                                flash.set($filter("translate")(response.error.message), 'error', false);
                            }
                           });
                    }
                });

        };

        $scope.allPhotos = function () {
            $scope.showPhoto = true;
        };
        /*multiple image file upload */
        $scope.$on('MulitpleUploader', function(event, data) {
            $scope.imagedata = data;
            $scope.image = [];
            
        });
        $scope.UploadPhotos = function(){
            /* image */
           $scope.user_profile = {};
           $scope.user_profile.listing_photos = [];
                angular.forEach($scope.imagedata, function(img) {
                    angular.forEach(img, function(listPhoto) {
                        if (listPhoto.attachment !== undefined) {
                            $scope.user_profile.listing_photos.push({"image": listPhoto.attachment });
                        }
                    });
                });
                if($scope.user_profile.listing_photos.length > 0){
                    UserProfilesFactory.update($scope.user_profile, function(){
                        flash.set($filter("translate")("Photo Uploaded Successfully"), 'success', true);
                        $state.go('UserPhotos');
                    });
                }
        };        
    });


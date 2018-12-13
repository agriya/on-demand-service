'use strict';
angular.module('hirecoworkerApp')
    .directive('multiImageUpload', function() {
        return {
            restrict: 'E',
            templateUrl: 'views/multi_image_upload.html',
            transclude: true,
            scope: {
                formName: '@formName',
                type: '@type',
                maxSize: '@maxSize',
                required: '@required',
                stepName: '@stepName',
                objectName: '@objectName',
                className: '@className',
                attachments: '@attachments',
            },
            controller: function($window, $rootScope, $state, $location, $scope, Upload, md5, flash, $filter) {
                $scope.loader = true;
                $scope.multiple_attachment_files = [];
                var Multiple_attachments = [];
                if ($scope.type === 'preview&Upload') {
                    if ($scope.attachments !== undefined && $scope.attachments !== null && $scope.attachments !== '') {
                        $scope.attachments = JSON.parse($scope.attachments);
                    }
                    if ($scope.attachments.length > 0) {
                        angular.forEach($scope.attachments, function(newvalue) {
                            if (angular.isDefined(newvalue.id) && newvalue.foreign_id !== null) {
                                var hash = md5.createHash(newvalue.class + newvalue.foreign_id + 'png' + 'medium_thumb');
                                newvalue.image = 'images/medium_thumb/' + newvalue.class + '/' + newvalue.foreign_id + '.' + hash + '.png';
                                /*    $scope.multiple_attachment_files*/
                                $scope.multiple_attachment_files.push(newvalue);
                            }
                        });
                    }
                    $scope.loader = false;
                }
                $scope.loader = false;
                $scope.upload = function(file) {
                    angular.forEach(file, function(file) {
                        Upload.upload({
                                url: '/api/v1/attachments?class=ListingPhoto',
                                data: {
                                    file: file
                                }
                            })
                            .then(function(response) {
                                if (response.data.error.code === 0) {
                                    $scope.file = file;
                                    /*Preview array*/
                                    $scope.multiple_attachment_files.push({
                                        'image': file
                                    });
                                    /*Updating Details of the image array*/
                                    var obj = {};
                                    obj[$scope.objectName] = response.data.attachment;
                                    Multiple_attachments.push(obj);
                                    $scope.$emit('MulitpleUploader', {
                                        image_uploaded: Multiple_attachments,
                                        type: $scope.type,
                                        step_name: $scope.stepName
                                    });
                                    $rootScope.images = Multiple_attachments;
                                }
                            },function(errorResponse){
                                flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
                            });
                    });
                };
                /*Deleting  the image*/
                $scope.removeImage = function(index) {
                    if ($scope.multiple_attachment_files[index].id !== null && $scope.multiple_attachment_files[index].id !== undefined) {
                       // if ($scope.stepName === 'trade') {
                            /*Delete photo from attachment */
                        //     AttachmentFactory.remove({
                        //         attachmentId: $scope.multiple_attachment_files[index].id
                        //     }, function() {
                        //         $scope.multiple_attachment_files.splice(index, 1);
                        //     }, function() {
                        //         flash.set("Error occurred while deleting image.Please try again later.", 'error', false);
                        //     });
                        // } else if ($scope.stepName === 'show-case') {
                        //     /*Delete photo from company_photo */
                            
                        //     CompanyPhotoFactory.remove({
                        //         id: $scope.multiple_attachment_files[index].company_photo_id
                        //     }, function() {
                        //         $scope.multiple_attachment_files.splice(index, 1);
                        //     }, function() {
                        //         flash.set("Error occurred while deleting image.Please try again later.", 'error', false);
                        //     });
                        // }
                   // } else {
                        //$scope.multiple_attachment_files.splice(index, 1);
                       // Multiple_attachments.splice(index, 1);
                    }
                    $scope.$emit('MulitpleUploader', {
                        image_uploaded: Multiple_attachments,
                        type: $scope.type,
                        step_name: $scope.stepName
                    });
                };
            }
        };
    })
    .directive('multiUploadImage', function() {
        return {
            restrict: 'E',
            templateUrl: 'views/multi_image_upload.html',
            transclude: true,
            scope: {
                formName: '@formName',
                type: '@type',
                maxSize: '@maxSize',
                required: '@required',
                stepName: '@stepName',
                objectName: '@objectName',
                className: '@className',
                attachments: '@attachments',
            },
            controller: function($window, $rootScope, $state, $location, $scope, Upload, md5, flash, $filter) {
                $scope.loader = true;
                $scope.multiple_attachment_files = [];
                var Multiple_attachments = [];
                if ($scope.type === 'preview&Upload') {
                    if ($scope.attachments !== undefined && $scope.attachments !== null && $scope.attachments !== '') {
                        $scope.attachments = JSON.parse($scope.attachments);
                    }
                    if ($scope.attachments.length > 0) {
                        angular.forEach($scope.attachments, function(newvalue) {
                            if (angular.isDefined(newvalue.id) && newvalue.foreign_id !== null) {
                                var hash = md5.createHash(newvalue.class + newvalue.foreign_id + 'png' + 'medium_thumb');
                                newvalue.image = 'images/medium_thumb/' + newvalue.class + '/' + newvalue.foreign_id + '.' + hash + '.png';
                                /*    $scope.multiple_attachment_files*/
                                $scope.multiple_attachment_files.push(newvalue);
                            }
                        });
                    }
                    $scope.loader = false;
                }
                $scope.loader = false;
                $scope.upload = function(file) {
                    angular.forEach(file, function(file) {
                        Upload.upload({
                                url: '/api/v1/attachments?class=Contact',
                                data: {
                                    file: file
                                }
                            })
                            .then(function(response) {
                                if (response.data.error.code === 0) {
                                    $scope.file = file;
                                    /*Preview array*/
                                    $scope.multiple_attachment_files.push({
                                        'image': file
                                    });
                                    /*Updating Details of the image array*/
                                    var obj = {};
                                    obj[$scope.objectName] = response.data.attachment;
                                    Multiple_attachments.push(obj);
                                    $scope.$emit('MulitpleUploader', {
                                        image_uploaded: Multiple_attachments,
                                        type: $scope.type,
                                        step_name: $scope.stepName
                                    });
                                    $rootScope.images = Multiple_attachments;
                                }
                            },function(errorResponse){
                                flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
                            });
                    });
                };
                /*Deleting  the image*/
                $scope.removeImage = function(index) {
                    if ($scope.multiple_attachment_files[index].id !== null && $scope.multiple_attachment_files[index].id !== undefined) {
                       // if ($scope.stepName === 'trade') {
                            /*Delete photo from attachment */
                        //     AttachmentFactory.remove({
                        //         attachmentId: $scope.multiple_attachment_files[index].id
                        //     }, function() {
                        //         $scope.multiple_attachment_files.splice(index, 1);
                        //     }, function() {
                        //         flash.set("Error occurred while deleting image.Please try again later.", 'error', false);
                        //     });
                        // } else if ($scope.stepName === 'show-case') {
                        //     /*Delete photo from company_photo */
                            
                        //     CompanyPhotoFactory.remove({
                        //         id: $scope.multiple_attachment_files[index].company_photo_id
                        //     }, function() {
                        //         $scope.multiple_attachment_files.splice(index, 1);
                        //     }, function() {
                        //         flash.set("Error occurred while deleting image.Please try again later.", 'error', false);
                        //     });
                        // }
                   // } else {
                        //$scope.multiple_attachment_files.splice(index, 1);
                       // Multiple_attachments.splice(index, 1);
                    }
                    $scope.$emit('MulitpleUploader', {
                        image_uploaded: Multiple_attachments,
                        type: $scope.type,
                        step_name: $scope.stepName
                    });
                };
            }
        };
    });
'use strict';
/**
 * @ngdoc function
 * @name getlancerApp.controller:MessagesController
 * @description
 * # MessagesController
 * Controller of the getlancerApp
 */
angular.module('hirecoworkerApp')
.controller('MessagesController', function($rootScope, $scope,$state, $location, $stateParams, $filter, $cookies, messagesFactory, sendMessagesFactory,flash,$timeout, Upload, md5, $interval){
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Messages");
        $scope.showSingleMessage = [];
        $scope.loadmore = false;
        $scope.left_currentPage = 1;
        //Assigning login user id 
        $scope.loginUserId = $rootScope.auth.id;        
        $scope.MessagePage = 1;
          // Get Single message
        $scope.GetMessage = function(messageContentId,msgPage) {
            $scope.MessagePage=msgPage;
            $scope.skipValue = $scope.MessagePage == 1 ? 0 : ($scope.MessagePage-1)*10;
            var messageParams = {};
            messageParams.filter = '{"limit":10,"skip":'+$scope.skipValue+',"order":"id desc","include":{"0":"user.attachment","1":"foreign_message","2":"children","3":"attachment","4":"message_content","5":"other_user.attachment","6":"user.user_profile","7":"other_user.user_profile"},"where":{"user_id":'+$rootScope.auth.id+',"class":"Appointment","foreign_id":'+$stateParams.id+'}}';
            messagesFactory.get(messageParams, function(response) {
               $scope.SingleMessage = response.data;
               if(response.data.length > 0){
                   $scope.foreignId = response.data[0].foreign_id;
               }
               
               if (angular.isDefined(response._metadata)) {
                    $scope.messageNoOfPages = response._metadata.last_page;
                    $scope.remainingMessage = response._metadata.total - (response._metadata.current_page * 10);
                    $scope.messageTotal = response._metadata.current_page * 10;
                    $scope.Total = response._metadata.total;
                    if(response._metadata.total > $scope.messageTotal && $scope.messageTotal > 0)
                    {
                         
                    }
                    else
                    {
                        $scope.messageTotal=0;
                    }
                    $scope.currentPage = response._metadata.current_page;
                }
                if ($scope.MessagePage === 1) {
                    $scope.showSingleMessage = [];
                    setTimeout(function() {
                        $('div#bottom').animate({ //jshint ignore:line
                            "scrollTop": $('div#bottom')[0].scrollHeight //jshint ignore:line
                        }, "slow"); //jshint ignore:line
                    }, 10);
                }
                var hash ='';
                angular.forEach($scope.SingleMessage, function(value, key) {
                    if (value.user.attachment !== null) {
                        hash = md5.createHash(value.user.attachment.class + value.user.attachment.id + 'png' + 'small_thumb');
                        value.users_avatar_url = 'images/small_thumb/' + value.user.attachment.class + '/' + value.user.attachment.id + '.' + hash + '.png';
                    } else {
                        value.users_avatar_url = 'images/default.png';
                    }
                    if (value.other_user.attachment !== null) {
                        hash = md5.createHash(value.other_user.attachment.class + value.other_user.attachment.id + 'png' + 'small_thumb');
                        value.other_user_avatar_url = 'images/small_thumb/' + value.other_user.attachment.class + '/' + value.other_user.attachment.id + '.' + hash + '.png';
                    } else {
                        value.other_user_avatar_url = 'images/default.png';
                    }
                        $scope.showSingleMessage.push(value);
                });
            });
        };
        // Sending Message Function
        $scope.sendMessage = function() {
            var sendMessageParams = {};
            sendMessageParams.parent_id = $scope.messageContentId;
            sendMessageParams.foreign_id = $stateParams.id;
            sendMessageParams.class = "Appointment";
            sendMessageParams.subject = "Enquiry";
            if ($scope.getUserMessage !== null && $scope.getUserMessage !== "") {
                sendMessageParams.message = $scope.getUserMessage;
            } else {
                return;
            }
            sendMessagesFactory.create(sendMessageParams, function() {
                $scope.getUserMessage = '';
                $scope.loadmore = false;
                $scope.GetMessage($scope.messageContentId, 1);
            });
        };
        $scope.upload = function (file) {
            Upload.upload({
                url: '/api/v1/attachments?class=UserProfile',
                data: {
                    file: file
                }
            }).then(function (response) {
                $scope.messageImage = response.data.attachment;
            });
        };
                /*load more function*/
        $scope.message_pagination = function() {
            $scope.loadmore = true;
            $scope.MessagePage = parseInt($scope.currentPage) + 1;
            $scope.GetMessage($scope.messageContentId, $scope.MessagePage);
        };
        
        $scope.GetMessage('', $scope.MessagePage);
         if($state.current.name === "BookingDetail"){
                    $interval(function(){
                        $scope.GetMessage('', $scope.MessagePage); 
                    },60000);
                }

    })
     .directive('messageChat', function () {
        var linker = function (scope, element, attrs) {
            
        };
        return {
            restrict: 'E',
            templateUrl: 'scripts/plugins/Message/Message/views/message.html',
            link: linker,
            controller: 'MessagesController',
            bindToController: true
        };
    });
   



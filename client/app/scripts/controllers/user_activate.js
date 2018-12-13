'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserActivateController
 * @description
 * # UserActivateController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    /**
     * @ngdoc controller
     * @name user.controller:UserActivateController
     * @description
     *
     * This is user controller having the methods setmMetaData, init, upload and user_profile.
     **/
.controller('UserActivateController', function ($auth, $state, $rootScope, $filter, UserActivateFactory, flash, AuthFactory) {
        var model = this;
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserActivateController
         * @description
         * This method will confirm the email and return token or error message
         *
         **/
        model.init = function () {
            // activate users link
            UserActivateFactory.activate({
                id: $state.params.id,
                hash: $state.params.hash
            }).$promise.then(function (response) {
                if(response.message === undefined){
                    if (response.token) {
                        $auth.setToken(response.token);
                        AuthFactory.fetch().$promise.then(function (user) {
                            $rootScope.auth = user;
                            $state.go('home');
                        });
                    } else {
                        flash.set($filter("translate")("Account activated successfully"), 'success', true);
                        $state.go('login');
                    }
                }else{
                    flash.set($filter("translate")(response.message), 'success', true);
                    $state.go('login');
                }
            }, //jshint unused:false  
            function (error) {
                flash.set($filter("translate")("Account could not be activated"), 'error', true);
                $state.go('login');
            });
        };
        model.init();
    });
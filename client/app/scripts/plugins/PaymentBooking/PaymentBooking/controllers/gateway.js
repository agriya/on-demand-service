'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:GatewayController
 * @description
 * # GatewayController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')
    .controller('GatewayController', function($rootScope, $scope, $window, $stateParams, flash, $location, $filter, $state, ConstPaymentGateways, PaymentGatewaySingle, appointmentView, md5, PaymentGatewayList, PaymentOrder, CreditCardGetFactory, ConstService) {
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Confirm your Payment");
        $scope.id = $stateParams.id;
        $scope.minimum_wallet_amount = $rootScope.settings.WALLET_MIN_WALLET_AMOUNT;
        $scope.maximum_wallet_amount = $rootScope.settings.WALLET_MAX_WALLET_AMOUNT;
        $scope.buyer = {"cardType":""};
        $scope.buyer.credit_card_expired = {};
        $scope.plan = {};
        $scope.showCard = false;
        $scope.class_showing = false;
        $scope.showCardDetail = false;
        $scope.paynow_is_disabled = false;
        $scope.payment_note_enabled = false;
        $scope.payer_form_enabled = true;
        $scope.is_wallet_page = true;
        $scope.plan_info = {};
        $scope.save_btn = false;
        $scope.first_gateway_id = "";
        $scope.cardType = '';
        $scope.plan_info.price_final = 10;
        $scope.affiliate_plugin_enabled = false;
        $scope.credit_card_enabled = false;
        $scope.paypal_plugin_enabled = false;
        $scope.is_bonus = false;
        $scope.index = function() {
        var params = {};
        params.filter = '{"limit":500, "skip":0}';
        if($state.params.class === 'pro_account' || $state.params.class === 'top_user'){
            $scope.class_showing = true;
        }
        if(parseInt($state.params.is_bonus) === 1){
            $scope.is_bonus = true;
        }
        PaymentGatewayList.get(params, function(payment_response){
             var payment_gateways = new Array();//jshint ignore:line
            if (payment_response.wallet) {
                    $scope.wallet_enabled = true;
                    if (parseInt($scope.user_available_balance) === 0) {
                        $scope.is_show_wallet_paybtn = false;
                    } else {
                        $scope.is_show_wallet_paybtn = true;
                    }
                }
				if (payment_response.PayPalREST) {
					var response = payment_response.PayPalREST;
					if(response.paypalrest_enabled) {
                    	$scope.paypal_plugin_enabled = true;
					}
                }
                $scope.group_gateway_id = "";
                if (payment_response.error.code === 0) {
                    if (payment_response.zazpay !== undefined) {
                        angular.forEach(payment_response.zazpay.gateways, function(gateway_group_value, gateway_group_key) {
                            if (gateway_group_key === 0) {
                                $scope.group_gateway_id = gateway_group_value.id;
                                $scope.first_gateway_id = gateway_group_value.id;
                            }
                            //jshint unused:false
                            angular.forEach(gateway_group_value.gateways, function(payment_geteway_value, payment_geteway_key) {
                                var payment_gateway = {};
                                var suffix = 'sp_';
                                if (gateway_group_key === 0) {
                                    $scope.sel_payment_gateway = 'sp_' + payment_geteway_value.id;
                                }
                                suffix += payment_geteway_value.id;
                                payment_gateway.id = payment_geteway_value.id;
                                payment_gateway.payment_id = suffix;
                                payment_gateway.group_id = gateway_group_value.id;
                                payment_gateway.display_name = payment_geteway_value.display_name;
                                payment_gateway.thumb_url = payment_geteway_value.thumb_url;
                                payment_gateway.suffix = payment_geteway_value._form_fields._extends_tpl.join();
                                payment_gateway.form_fields = payment_geteway_value._form_fields._extends_tpl.join();
                                payment_gateway.instruction_for_manual = payment_geteway_value.instruction_for_manual;
                                payment_gateways.push(payment_gateway);
                            });
                        });
                        $scope.gateway_groups = payment_response.zazpay.gateways;
                        $scope.payment_gateways = payment_gateways;
                        $scope.form_fields_tpls = payment_response.zazpay._form_fields_tpls;
                        $scope.show_form = [];
                        $scope.form_fields = [];
                        angular.forEach($scope.form_fields_tpls, function(key, value) {
                            if (value === 'buyer') {
                                $scope.form_fields[value] = 'views/buyer.html';
                            }
                            if (value === 'credit_card') {
                                $scope.form_fields[value] = 'views/credit_card.html';
                            }
                            if (value === 'manual') {
                                $scope.form_fields[value] = 'views/manual.html';
                            }
                            $scope.show_form[value] = true;
                        });
                        $scope.gateway_id = ConstPaymentGateways.ZazPay;
                    } 
                }
        });
        params = {};
        params.filter='{"limit":500,"skip":0}';
        CreditCardGetFactory.get(params, function(response){
            $scope.credit_card_details = response.data;
            if($scope.credit_card_details.length > 0){
                $scope.buyer.selected_card = $scope.credit_card_details[0].id;
            }
        });
        if($state.params.class === 'appointments'){
            params = {};
            params.id = $state.params.id;
            params.filter = '{"include":{"1":"user.attachment","2":"provider_user.attachment","3":"user.user_profile","4":"provider_user.user_profile"}}';    
            appointmentView.get(params, function(response){
                $scope.appointmentInfo = response.data;
                var hash ;
                if($scope.appointmentInfo.user.attachment){
                    hash = md5.createHash($scope.appointmentInfo.user.attachment.class + $scope.appointmentInfo.user.attachment.id + 'png' + 'small_thumb');
                    $scope.appointmentInfo.userImage = 'images/small_thumb/' + $scope.appointmentInfo.user.attachment.class + '/' + $scope.appointmentInfo.user.attachment.id + '.' + hash + '.png';
                }
                if($scope.appointmentInfo.provider_user.attachment){   
                    hash = md5.createHash($scope.appointmentInfo.provider_user.attachment.class + $scope.appointmentInfo.provider_user.attachment.id + 'png' + 'small_thumb');
                    $scope.appointmentInfo.providerImage = 'images/small_thumb/' + $scope.appointmentInfo.provider_user.attachment.class + '/' + $scope.appointmentInfo.provider_user.attachment.id + '.' + hash + '.png';
                }
                $scope.site_commision = parseInt($scope.appointmentInfo.service_id) !== ConstService.Interview ?  $rootScope.settings.SITE_COMMISSION_FOR_BOOKING_FROM_CUSTOMER : $rootScope.settings.SITE_COMMISSION_FOR_INTERVIEW_FROM_CUSTOMER; 
                $scope.Booking_amount = parseInt($state.params.is_bonus) !== 1 ? parseFloat($scope.appointmentInfo.total_booking_amount) + parseFloat($scope.appointmentInfo.used_affiliate_amount) : $scope.Booking_amount = parseFloat($scope.appointmentInfo.bonus_amount) + parseFloat($scope.appointmentInfo.used_affiliate_amount);
                $scope.Booking_amount =$scope.Booking_amount + ($scope.Booking_amount * parseFloat($scope.site_commision))/100; 
                $scope.tempArray = ($rootScope.settings.SITE_ENABLED_PLUGINS).split(',');   
                angular.forEach($scope.tempArray, function(value){
                    if(value === "Referral"){
                        $scope.affiliate_plugin_enabled = true;
                    }
                });
                if($scope.appointmentInfo.user.affiliate_pending_amount > 0 && $scope.affiliate_plugin_enabled === true){

                    $scope.total_amount = parseFloat($scope.appointmentInfo.user.affiliate_pending_amount) > parseFloat($scope.Booking_amount)? parseInt($scope.appointmentInfo.user.affiliate_pending_amount) - parseFloat($scope.Booking_amount) : parseFloat($scope.Booking_amount) - parseFloat($scope.appointmentInfo.user.affiliate_pending_amount);

                    $scope.total_affiliate_amount = parseFloat($scope.appointmentInfo.user.affiliate_pending_amount) - parseFloat($scope.Booking_amount);
                    if($scope.total_affiliate_amount > 0){
                        $scope.total_amount = 0;
                    }
                }else {
                    $scope.total_amount = parseFloat($scope.Booking_amount);
                }
            
            });
        } 
        
        $scope.paypal_paynow = function($valid,type){
                params = {};
                if(type === "card"){
                    if($scope.buyer.selected_card !== '' && $scope.buyer.selected_card !== undefined && $scope.buyer.selected_card !== null){
                      params.user_credit_card_id = $scope.buyer.selected_card; 
                      $valid = true; 
                    }else{
                        params.cvv2=$scope.buyer.credit_card_code;
                        params.credit_card_type = $scope.buyer.cardType;
                        params.expire_year = $scope.buyer.credit_card_expired.year;
                        params.expire_month = $scope.buyer.credit_card_expired.month;
                        params.credit_card_number = $scope.buyer.credit_card_number;
                        if($scope.buyer.remember){
                        params.is_store_this_card = $scope.buyer.remember;   
                        }
                    }
                    
                }
                
               if($valid){
                if(type === "paypal" || type === "card"){
                    params.payment_gateway_id = ConstPaymentGateways.PayPal;
                }
                if($state.params.class === 'pro_account'){
                  params.class = "ProUser";  
                }else if($state.params.class === 'top_user'){
                  params.class = "TopUser";  
                }else{
                params.class = "Appointment";
                }
                if(parseInt($state.params.is_bonus) === 1){
                     params.is_bonus = 1;
                }
                params.foreign_id = $state.params.id;
                $scope.paynow_is_disabled = true;
                PaymentOrder.post(params, function(response){
                    var flashMessage;
                    if (response.error.code === 0) {
                            if (response.redirect_url !== undefined) {
                                $window.location.href = response.redirect_url;
                            } else if (response.data.gateway_callback_url !== undefined) {
                                $window.location.href = response.data.gateway_callback_url;
                            } else if (response.data.status === 'Pending') {
                                flashMessage = $filter("translate")("Your request is in pending.");
                                flash.set(flashMessage, 'error', false);
                                $state.reload();
                            } else if (response.data.status === 'Captured') {
                                flashMessage = $filter("translate")("Amount added successfully.");
                                flash.set(flashMessage, 'success', false);
                                $state.reload();
                            } else if (response.error.code === 0) {
                                if (response.payment_response.status === 'Captured') {
                                    $scope.my_user.available_wallet_amount = $scope.my_user.available_wallet_amount - parseInt(response.data.total_listing_fee);
                                }
                                flashMessage = $filter("translate")("Payment successfully completed.");
                                flash.set(flashMessage, 'success', false);
                            } else if (response.error.code === 512) {
                                flashMessage = $filter("translate")("Process Failed. Please, try again.");
                                flash.set(flashMessage, 'error', false);
                            }
                            if($state.params.class === "appointments"){
                                $state.go('MyBooking');
                            }else{
                                $state.go('user_dashboard');
                            }
                        } else {

                            $scope.errorMessage = $filter("translate")("We are unable to process your request. Please try again.");
                            if(response.error.fields){
                                angular.forEach(response.error.fields, function(value){
                                    $scope.errorMessage = $scope.errorMessage + " "+value.issue;  
                                });
                            }
                            flashMessage = $filter("translate")($scope.errorMessage);
                            flash.set(flashMessage, 'error', false);
                        }
                        $scope.paynow_is_disabled = false;
                });

            }
            
        };
        $scope.showCardDetail = function(){
            $('#showCardDetail').slideDown("slow");
            $scope.buyer.selected_card = '';
        };
        $scope.hideCardDetail = function(){
            if($scope.credit_card_details.length >0){
                $('#showCardDetail').slideUp("fast");
            }
            
        };
        $scope.clearSelectedCard = function(){
            $scope.buyer.selected_card = '';
        };
       
            //var payment_gateways = [];
            // paymentGateways.get(function(payment_response) {
            //     if (payment_response.wallet) {
            //         $scope.wallet_enabled = true;
            //         if (parseInt($scope.user_available_balance) === 0) {
            //             $scope.is_show_wallet_paybtn = false;
            //         } else {
            //             $scope.is_show_wallet_paybtn = true;
            //         }
            //     }
			// 	if (payment_response.PayPalREST) {
			// 		var response = payment_response.PayPalREST;
			// 		if(response.paypalrest_enabled) {
            //         	$scope.paypal_enabled = true;
			// 		}
            //     }
            //     $scope.group_gateway_id = "";
            //     if (payment_response.error.code === 0) {
            //         if (payment_response.zazpay !== undefined) {
            //             angular.forEach(payment_response.zazpay.gateways, function(gateway_group_value, gateway_group_key) {
            //                 if (gateway_group_key === 0) {
            //                     $scope.group_gateway_id = gateway_group_value.id;
            //                     $scope.first_gateway_id = gateway_group_value.id;
            //                 }
            //                 //jshint unused:false
            //                 angular.forEach(gateway_group_value.gateways, function(payment_geteway_value, payment_geteway_key) {
            //                     var payment_gateway = {};
            //                     var suffix = 'sp_';
            //                     if (gateway_group_key === 0) {
            //                         $scope.sel_payment_gateway = 'sp_' + payment_geteway_value.id;
            //                     }
            //                     suffix += payment_geteway_value.id;
            //                     payment_gateway.id = payment_geteway_value.id;
            //                     payment_gateway.payment_id = suffix;
            //                     payment_gateway.group_id = gateway_group_value.id;
            //                     payment_gateway.display_name = payment_geteway_value.display_name;
            //                     payment_gateway.thumb_url = payment_geteway_value.thumb_url;
            //                     payment_gateway.suffix = payment_geteway_value._form_fields._extends_tpl.join();
            //                     payment_gateway.form_fields = payment_geteway_value._form_fields._extends_tpl.join();
            //                     payment_gateway.instruction_for_manual = payment_geteway_value.instruction_for_manual;
            //                     payment_gateways.push(payment_gateway);
            //                 });
            //             });
            //             $scope.gateway_groups = payment_response.zazpay.gateways;
            //             $scope.payment_gateways = payment_gateways;
            //             $scope.form_fields_tpls = payment_response.zazpay._form_fields_tpls;
            //             $scope.show_form = [];
            //             $scope.form_fields = [];
            //             angular.forEach($scope.form_fields_tpls, function(key, value) {
            //                 if (value === 'buyer') {
            //                     $scope.form_fields[value] = 'views/buyer.html';
            //                 }
            //                 if (value === 'credit_card') {
            //                     $scope.form_fields[value] = 'views/credit_card.html';
            //                 }
            //                 if (value === 'manual') {
            //                     $scope.form_fields[value] = 'views/manual.html';
            //                 }
            //                 $scope.show_form[value] = true;
            //             });
            //             $scope.gateway_id = ConstPaymentGateways.ZazPay;
            //         } 
            //     }
			// 	$scope.loader = false;
            // });
        };
        
       
        
               
	$scope.payNowPayPalClick = function() { 
			
			
		};
       
        $scope.index();
    });
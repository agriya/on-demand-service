'use strict';
/**
 * @ngdoc function
 * @name hirecoworkerApp.controller:UserProfileController
 * @description
 * # UserProfileController
 * Controller of the hirecoworkerApp
 */
angular.module('hirecoworkerApp')

    /**
     * @ngdoc controller
     * @name user.controller:UserProfileController
     * @description
     *
     * This is user profile controller having the methods setmMetaData, init, upload and user_profile. It controld the user profile functions.
     **/

    .controller('UserProfileController', function ($state, $builder, $scope, $cookies, flash, UserProfilesFactory, $filter, $rootScope, $location, Upload, GENERAL_CONFIG, ConstSocialLogin, ConstThumb, City, States, Country, $stateParams, md5, GetPostions, GetExperience, GetQualification, GetSkill, UserNotification, $timeout, ConstListingStatus, ConstStatus, $validator, ConstUserType) {
        $scope.user_profile = [];
        $scope.form_field_group = [];
        $scope.IsplaceChange = true;
        $scope.IsAddressPlaceChange = true;
        $scope.form_answwer = {};
        $rootScope.subHeader = "Account"; //for setting active class for subheader
        $cookies.put('subHeader', $rootScope.subHeader);
        $rootScope.gotoHeader();
        if ($rootScope.isAuth && parseInt($rootScope.auth.is_profile_updated) === 0 && $stateParams.type !== 'personal') {
            $state.go('user_profile', { type: 'personal' });
        }
        $scope.showSaveProfile = $stateParams.type === undefined || $stateParams.type === null ? false : true;
        $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Account");
        if ($stateParams.type === 'personal') {
            $scope.pesonal_profile = true;
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Personal Details");
        } if ($stateParams.type === 'payout_details') {
            $scope.payout_detail = true;
        } if ($stateParams.type === 'transaction_history') {
            $scope.transaction_history = true;
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Transaction");
        } if ($state.current.name === "description_details") {
            $scope.showSaveProfile = false;
            $scope.description_and_details = true;
            $rootScope.subHeader = "Listing"; //for setting active class for subheader
            $rootScope.pageTitle = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Your Personalised Profile - Details");
        }


        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined && $cookies.get("auth") !== 'null' && $cookies.get("auth") !== 'undefined') {
            $scope.auth_user_detail = $cookies.getObject("auth");
        }
        $rootScope.makePlaceTrue = function () {
            $rootScope.allowedplace = false;
        };

        $scope.active = false;
        $scope.update = false;
        $scope.updating = false;
        $scope.hide = false;
        $scope.ConstListingStatus = ConstListingStatus;
        if ($location.search().type !== undefined) {
            $scope.type = $location.search().type;
            $scope.active = true;
            $scope.update = true;
            $scope.updating = false;
        } else {
            $scope.type = '';
            $scope.active = false;
            $scope.update = false;
            $scope.updating = false;
        }
        $scope.showUpdate = function () {
            $scope.updating = true;
            $scope.update = false;
        };
        //list of countries
        var params = {};
        params.filter = '{"limit":500,"skip":0}';
        Country.get(params, function (response) {
            $rootScope.countries = response.data;
        });
        $scope.countryChange = function (countryValue) {
            $scope.user_profile.country = {};
            $scope.user_profile.listing_country = {};
            $scope.user_profile.country.iso2 = countryValue.iso2;
            $scope.user_profile.country.name = countryValue.name;
            $scope.user_profile.listing_country.iso2 = countryValue.iso2;
            $scope.user_profile.listing_country.name = countryValue.name;
        };
        $scope.getCountryPhoneCode = function (id) {
                angular.forEach($rootScope.countries, function (country) {
                    if (id === country.iso2) {
                        if($scope.user_profile.phone_number === (undefined || "") || $scope.user_profile.phone_number === null || !$scope.user_profile.phone_number){
                            $scope.user_profile.mobile_code = country.phone;
                        }
                        if($scope.user_profile.secondary_phone_number === (undefined || "") || $scope.user_profile.secondary_phone_number === null || !$scope.user_profile.secondary_phone_number){
                            $scope.user_profile.secondary_mobile_code = country.phone;
                        }
                        
                    }
                });
        };
        function getCountryIso(phonecode) {
            angular.forEach($rootScope.countries, function (country) {
                if (phonecode === country.phone) {
                    $scope.country_iso = country.iso2;
                }
            });
            return $scope.country_iso;
        }

        /**
         * @ngdoc method
         * @name setMetaData
         * @methodOf user.controller:UserProfileController
         * @description
         *
         * This method will set the meta data dynamically by using the angular.element function.
         **/
        $scope.setMetaData = function () {
            var fullUrl = $location.absUrl();
            angular.element('meta[property="og:url"]').attr('content', fullUrl);
        };
        /**
         * @ngdoc method
         * @name init
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will initialze the page. It returns the page title
         *
         **/
        $scope.init = function () {
            $scope.setMetaData();
            $scope.ConstSocialLogin = ConstSocialLogin;
            $scope.thumb = ConstThumb.user;
            $scope.ConstStatus = ConstStatus;
            $scope.ConstUserType = ConstUserType;
            $scope.params = {};
            //Select Gender List
            $scope.genderArray = [];
            $scope.genderArray.push(
                { 'id': 1, "name": $filter("translate")('Male') },
                { 'id': 2, "name": $filter("translate")('Female') }
            );
            $scope.enabledPlugins = $rootScope.settings.SITE_ENABLED_PLUGINS;
            $scope.enabledPlugins = $scope.enabledPlugins.split(',');
            //checking for enquiry and pre-approval plugin
            angular.forEach($scope.enabledPlugins, function (value) {
                if (value === 'SMS') {
                    $scope.enabled = true;
                }
            });
            //Get user details
            $scope.params.filter = '{"include":{"0":"listing_city","1":"listing_state","2":"listing_country","3":"user","4":"user.attachment","5":"user.city","6":"user.country","7":"user.state","8":"user.form_field_submission","9":"user.category.form_field_groups.form_fields.input_types","10":"user.form_field_submission.form_field.input_types"}}';
            // $scope.params.filter = '{"include":{"0":"listing_city","1":"listing_state","2":"listing_country","user":{"include":{"0":"attachment","1":"city","2":"country","3":"state","4":"category"}}}}';
            // $scope.params.filter = '{"include":{"0":"listing_city","1":"listing_state","2":"listing_country","user":{"include":{"0":"attachment","1":"city","2":"country","3":"state","category":{"include":{"0":"form_field_groups"}}}}}}';
            UserProfilesFactory.get($scope.params).$promise.then(function (response) {
                $scope.user_profile.city = {};
                $scope.user_profile.state = {};
                $scope.user_profile.country = {};
                if (response.user.country_id) {
                    $scope.selectedCountry = response.user.country.iso2;
                }
                if (response.listing_country) {
                    $scope.selectedListingCountry = response.listing_country.iso2;
                }
                $scope.user_profile = response;
                $scope.update_profile = parseInt(response.user.is_profile_updated);
                $scope.user_profile.phone_number = parseInt(response.user.phone_number);
                $scope.user_profile.secondary_phone_number = parseInt(response.user.secondary_phone_number);
                $timeout(function(){
                    $scope.user_profile.mobile_code = response.user.mobile_code;
                    $scope.user_profile.secondary_mobile_code = response.user.secondary_mobile_code;
                },5);
                $scope.user_profile.city = response.user.city;
                $scope.user_profile.state = response.user.state;
                $scope.user_profile.country = response.user.country;
                $scope.user_profile.address = response.user.address;
                $scope.user_profile.postal_code = response.user.postal_code;
                if (response.user.city) {
                    $scope.user_profile.city_id = response.user.city.id;
                }
                if (response.user.state) {
                    $scope.user_profile.state_id = response.user.state.id;
                }
                $scope.userId = response.user_id;
                $scope.user = response.User;
                if (angular.isDefined($scope.user_profile.user.attachment) && $scope.user_profile.user.attachment !== null) {
                    var c = new Date();
                    var hash = md5.createHash(response.user.attachment.class + response.user.attachment.id + 'png' + 'big_thumb');
                    $scope.profile_image = 'images/big_thumb/' + response.user.attachment.class + '/' + response.user.attachment.id + '.' + hash + '.png?' + c.getTime();
                } else {
                    $scope.profile_image = 'images/default.png';
                }

                if (response.cv === null || response.cv === 'null') {
                    $scope.user_profile.cv = '';
                }
                if (response.gender_id === 0) {
                    $scope.user_profile.gender_id = '';
                } else {
                    $scope.user_profile.gender_id = parseInt(response.gender_id);
                }
                if (parseInt(response.is_email_subscribed) === $scope.ConstStatus.Confirmed) {
                    $scope.user_profile.is_email_subscribed = true;
                }
                if (parseInt(response.user.is_mobile_number_verified) === $scope.ConstStatus.Confirmed) {
                    $scope.mobile_no = false;
                } else {
                    $scope.mobile_no = true;
                }
                if (parseInt(response.user.is_email_confirmed) === $scope.ConstStatus.Confirmed) {
                    $scope.email_verified = false;
                } else {
                    $scope.email_verified = true;
                }
                if (parseInt(response.user.is_profile_updated) === $scope.ConstStatus.Confirmed) {
                    $scope.profile_update = true;
                } else {
                    $scope.profile_update = false;
                }
                $scope.show_fields = response.user.form_field_submission;
                $scope.form_data = response.user.category !== (undefined || null) ? response.user.category.form_field_groups : undefined;
                $scope.values = response.user.category !== (undefined || null) ? response.user.category.form_field_groups : undefined;

                $scope.form_fields = [];
                // $scope.form_fields = response.user.category.form_field_groups[0].form_fields;
                angular.forEach($scope.values, function (values) {
                    angular.forEach(values.form_fields, function (value) {
                        $scope.form_fields.push(value);
                    });
                });
                angular.forEach($scope.values, function (value) {
                    $scope.form_field_group.push({ "group_id": value.id, "text": value.name });
                });
                $scope.defaultValue = {};
                $scope.frmvalues = [];
                $scope.showfrms = [];
                var firstfrm = 1;
                if ($scope.form_fields !== '') {
                    for (var ival = 1; ival < 10; ival++) {
                        /** to do form empty  */
                        if ($builder.forms['default-' + ival] !== undefined) {
                            $builder.forms['default-' + ival] = [];
                        }
                    }
                    angular.forEach($scope.form_fields, function (field_type_response) {
                        angular.forEach($scope.show_fields, function (answer) {
                            if (parseInt(answer.form_field_id) === parseInt(field_type_response.id) && answer.form_field.input_type_id !== 4) {
                                $scope.prefill_value = answer.response;
                            } else if (parseInt(answer.form_field_id) === parseInt(field_type_response.id) && parseInt(answer.form_field.input_type_id) === 4) {
                                $scope.option_values = field_type_response.options.split(",");
                                $scope.selected_values = answer.response.split(',');
                                angular.forEach($scope.selected_values, function (value, key) {
                                    $scope.selected_values[key] = $filter('translate')(value.trim());
                                });
                                var overall_checked = [];
                                angular.forEach($scope.option_values, function (default_option) {
                                    if ($scope.selected_values.indexOf(default_option) > -1) {
                                        overall_checked.push(true);
                                    } else {
                                        overall_checked.push(false);
                                    }
                                });
                                $scope.prefill_value = overall_checked;
                            }
                        });
                        var option_values;
                        if (field_type_response.options) {
                            option_values = field_type_response.options.split(",");
                            for (var i = 0; i < option_values.length; i++) {
                                option_values[i] = $filter('translate')(option_values[i]);
                            }
                        }
                        var textbox;
                        textbox = $builder.addFormObject('default-' + firstfrm, {
                            id: field_type_response.id,
                            // name: field_type_response.name,
                            component: field_type_response.input_types.value,
                            label: field_type_response.label,
                            description: "",
                            placeholder: $filter("translate")(field_type_response.label),
                            required: field_type_response.is_required,
                            options: option_values,
                            editable: true
                        });
                        $scope.defaultValue[textbox.id] = $scope.prefill_value;
                        $scope.frmvalues[textbox.id] = field_type_response.name;
                        $scope.showfrms[firstfrm] = false;

                        firstfrm++;
                        $scope.isformfield = true;
                    });
                    $scope.form_fields_all = $scope.form_fields;
                    $scope.firstfrm = firstfrm;
                }
            });

            $scope.country = [];
            $scope.cities = [];
            if ($rootScope.settings.ALLOWED_SERVICE_LOCATIONS) {
                $scope.locations = JSON.parse($rootScope.settings.ALLOWED_SERVICE_LOCATIONS);
                angular.forEach($scope.locations.allowed_countries, function (value) {
                    $scope.country.push(value.iso2);
                });
                angular.forEach($scope.locations.allowed_cities, function (value) {
                    $scope.cities.push(value.name);
                });
            }

            $scope.options = {
                types: [], componentRestrictions: { country: $scope.country }
            };

        };
        $scope.init();
        $scope.dateBlockeBefore = $filter('date')(new Date(), "yyyy-MM-ddTHH:mm:ss.sssZ");

        // upload on file select or drop
        /**
         * @ngdoc method
         * @name upload
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will save the user profile data
         *
         * @param {!Array.<string>} profileData contains the array of user profile data
         **/


        $scope.upload = function (file) {
            Upload.upload({
                url: '/api/v1/attachments?class=UserAvatar',
                data: {
                    file: file
                }
            }).then(function (response) {
                $scope.updating = false;
                $scope.image_data = response.data.attachment;
                $scope.user_profile.attachment = {};
                $scope.user_profile.attachment = { 'filename': $scope.image_data, 'class': 'UserProfile' };
                $scope.user_data = {};
                $scope.user_data.image = $scope.image_data;
                UserProfilesFactory.update($scope.user_data).$promise.then(function (response) {
                    flash.set($filter("translate")("Profile has been updated."), 'success', true);
                    $scope.updating = false;
                    var c = new Date();
                    var hash = md5.createHash(response.data.user.attachment.class + response.data.user.attachment.id + 'png' + 'big_thumb');
                    $scope.profile_image = 'images/big_thumb/' + response.data.user.attachment.class + '/' + response.data.user.attachment.id + '.' + hash + '.png?' + c.getTime();
                    hash = md5.createHash(response.data.user.attachment.class + response.data.user.attachment.id + 'png' + 'small_thumb');
                    $rootScope.auth.userimage = 'images/small_thumb/' + response.data.user.attachment.class + '/' + response.data.user.attachment.id + '.' + hash + '.png';
                    $cookies.remove("auth", { path: "/" });
                    $scope.Authuser = {
                        id: $scope.auth_user_detail.id,
                        username: $scope.auth_user_detail.username,
                        role_id: $scope.auth_user_detail.role_id,
                        refresh_token: $scope.auth_user_detail.token,
                        attachment: response.data.attachment,
                        is_profile_updated: response.data.is_profile_updated,
                        affiliate_pending_amount: response.data.affiliate_pending_amount,
                        category_id: $scope.auth_user_detail.category_id,
                        user_profile: $scope.auth_user_detail.user_profile,
                        blocked_user_count: $scope.auth_user_detail.blocked_user_count
                    };
                    $cookies.put('auth', JSON.stringify($scope.Authuser), {
                        path: '/'
                    });
                    $state.go('user_profile', {
                        type: '',
                    });

                });
            }, function (errorResponse) {
                flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
                $scope.updating = false;
            }, function () {
            });
        };
        //Update user details
        /**
         * @ngdoc method
         * @name userProfile
         * @methodOf user.controller:UserProfileController
         * @description
         * This method will upload the file and returns the success message.
         *
         **/
        $scope.userProfile = function ($valid, formname) {
            if (!formname.$valid) { angular.element("[name='" + formname.$name + "']").find('.ng-invalid:visible:first').focus(); return false; }
            if ($scope.file) {
                $scope.upload($scope.file);
            } else if ($valid) {
                var params = {};
                $scope.errorData = [];
                $scope.error = false; // for form field requirment
                if ($state.current.name === "description_details") {
                    if ($scope.cities.length > 1) {
                        $rootScope.allowedplace = $scope.cities.indexOf($scope.user_profile.listing_city.name) === -1 ? true : false;
                    } else {
                        $rootScope.allowedplace = false;
                    }
                    if ($rootScope.allowedplace === false) {
                        params.listing_city = $scope.user_profile.listing_city;
                        params.listing_state = $scope.user_profile.listing_state;
                        params.listing_country = $scope.user_profile.listing_country;
                        params.listing_postal_code = $scope.user_profile.listing_postal_code;
                        params.listing_title = $scope.user_profile.listing_title;
                        params.listing_description = $scope.user_profile.listing_description;
                        params.listing_address = $scope.user_profile.listing_address;
                        params.listing_address1 = $scope.user_profile.listing_address1;
                        params.listing_latitude = $scope.user_profile.listing_latitude;
                        params.listing_longitude = $scope.user_profile.listing_longitude;
                    } else if ($rootScope.allowedplace === true) {
                        flash.set($filter("translate")("Please select allowed address."), 'error', true);
                        return false;
                    }

                    angular.forEach($scope.form_fields_all, function (value, key) {
                        $validator.validate($scope, 'default-' + (key + 1)).error(function () {
                            $scope.error = true;
                        });
                    });

                }

                if ($stateParams.type === 'personal') {
                    params.email = $scope.user_profile.user.email;
                    params.first_name = $scope.user_profile.first_name;
                    params.last_name = $scope.user_profile.last_name;
                    params.gender_id = $scope.user_profile.gender_id;
                    params.address = $scope.user_profile.address;
                    params.address1 = $scope.user_profile.user.address1;
                    params.city = $scope.user_profile.city;
                    params.state = $scope.user_profile.state;
                    params.country = $scope.user_profile.country;
                    params.postal_code = $scope.user_profile.postal_code;
                    params.mobile_code = $scope.user_profile.mobile_code;
                    params.secondary_mobile_code = $scope.user_profile.secondary_mobile_code;
                    params.phone_number = $scope.user_profile.phone_number;
                    params.latitude = $scope.user_profile.latitude;
                    params.longitude = $scope.user_profile.longitude;
                    params.secondary_phone_number = $scope.user_profile.secondary_phone_number !== (undefined || null) ? $scope.user_profile.secondary_phone_number : undefined;
                    if ($scope.user_profile.mobile_code) {
                        params.mobile_code = $scope.user_profile.mobile_code.indexOf("+") > -1 ? getCountryIso($scope.user_profile.mobile_code) : $scope.user_profile.mobile_code;
                    }
                    if ($scope.user_profile.secondary_mobile_code && $scope.user_profile.secondary_phone_number !== (undefined || null)) {
                        params.secondary_mobile_code = $scope.user_profile.secondary_mobile_code.indexOf("+") > -1 ? getCountryIso($scope.user_profile.secondary_mobile_code) : $scope.user_profile.secondary_mobile_code;
                    }

                    params.driving_license_information = $scope.user_profile.driving_license_information;
                    params.cv = $scope.user_profile.cv;
                }
                params.form_field_submissions = [];
                angular.forEach($scope.form_fields_all, function (values) {
                    angular.forEach($scope.form_answwer, function (value) {
                        if (parseInt(values.id) === parseInt(value[0].id)) {
                            var selected = {};
                            selected[values.id] = value[0].value;
                            params.form_field_submissions.push(selected);
                        }
                    });
                });
                //timeout for this will get run after form field required checking process
                $timeout(function () {
                    if ($scope.error === true) {
                        return;
                    } else {
                        UserProfilesFactory.update(params).$promise.then(function (response) {
                            formname.$setPristine();
                            $rootScope.auth.is_profile_updated = parseInt(response.data.user.is_profile_updated);
                            flash.set($filter("translate")("Profile has been updated."), 'success', true);
                            $scope.updating = false;
                            if (parseInt(response.data.user.is_profile_updated) === 1 && $scope.update_profile === 0 && $rootScope.auth.role_id === 2) {
                                $state.go('search');
                            }
                            if (parseInt(response.data.user.is_profile_updated) === 1 && $scope.update_profile === 0 && $rootScope.auth.role_id === 3) {
                                $state.go('user_listing');
                            }
                            if ($scope.update_profile === 1) {
                                $rootScope.auth.is_profile_updated = parseInt(response.data.user.is_profile_updated);
                                $state.reload();
                            }
                        }, function (errorResponse) {
                            $scope.errorData = [];
                            if ($stateParams.type === 'personal') {
                                // if (errorResponse.data.error.fields.unique[0] === "email") {
                                //     flash.set($filter("translate")("Please enter a valid email address."), 'error', true);
                                // } else {
                                //     flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
                                // }
                                flash.set($filter("translate")(errorResponse.data.error.message), 'error', true);
                                if (errorResponse.data) {
                                    if (errorResponse.data.error.fields) {
                                        angular.forEach(errorResponse.data.error.fields, function (raw_value, raw_key) {
                                            if (raw_key === 'mobile') {
                                                raw_key = 'Mobile number';
                                            }
                                            else if (raw_key === 'secondary_mobile') {
                                                raw_key = 'Secondary mobile number';
                                            } else if (raw_key === 'unique') {
                                                raw_key = 'Email';
                                            }

                                            if (Array.isArray(raw_value)) {
                                                angular.forEach(raw_value, function (error) {
                                                    if (raw_key === 'Email') {
                                                        error = 'This email already used.';
                                                    }
                                                    $scope.errorData.push({ "field": raw_key, "error": error });
                                                });
                                            } else {
                                                $scope.errorData.push({ "field": raw_key, "error": raw_value });
                                            }

                                        });
                                    }
                                }
                                $('html').animate({ scrollTop: $('#show_error').offset().top }, 100);
                                return false;
                            } else if ($state.current.name === "description_details") {
                                if (errorResponse.data.error.fields.address) {
                                    flash.set($filter("translate")("Please select allowed address."), 'error', true);
                                }
                            }
                        });
                    }
                }, 5);

            }
        };
        $scope.uploading = function (file) {
            if (file !== null) {
                $scope.upload(file);
                $scope.file = '';
                $scope.hide = true;
            }else{
                flash.set($filter("translate")("Please select valid image files."), 'error', true);
            }
        };
        if ($rootScope.auth) {
            UserNotification.get({ id: $rootScope.auth.id }).$promise.then(function (response) {
                $scope.userInfo = response.data;
                $rootScope.auth.is_profile_updated = parseInt(response.data.is_profile_updated);
            });
        }
        $scope.location = function () {
            $scope.IsplaceChange = false;
            if ($scope.user_profile.listing_address !== undefined) {
                angular.forEach($scope.user_profile.listing_address.address_components, function (value) {
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        $scope.user_profile.listing_city = {};
                        $scope.user_profile.listing_city.name = value.long_name;
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        $scope.user_profile.listing_state = {};
                        $scope.user_profile.listing_state.name = value.long_name;
                    }
                    if (value.types[0] === 'country') {
                        $scope.user_profile.listing_country = {};
                        $scope.user_profile.listing_country.iso2 = value.short_name;
                        $scope.user_profile.listing_country.name = value.long_name;
                        $scope.selectedListingCountry = value.short_name;
                        $scope.getCountryPhoneCode(value.short_name);
                    }
                    if (value.types[0] === 'postal_code') {
                        $scope.user_profile.listing_postal_code = parseInt(value.long_name);
                    }

                });
                if ($scope.user_profile.listing_address.address_components) {
                    $scope.IsplaceChange = true;
                    $scope.user_profile.listing_latitude = $scope.user_profile.listing_address.geometry.location.lat();
                    $scope.user_profile.listing_longitude = $scope.user_profile.listing_address.geometry.location.lng();
                    $scope.user_profile.listing_full_address = $scope.user_profile.listing_address.name + " " + $scope.user_profile.listing_address.vicinity;
                    $scope.user_profile.listing_address = $scope.user_profile.listing_address.formatted_address;
                }
            } else {
                $scope.IsplaceChange = true;
            }

        };

        $scope.addresslocation = function () {
            $scope.IsAddressPlaceChange = false;
            if ($scope.user_profile.address !== undefined) {
                angular.forEach($scope.user_profile.address.address_components, function (value) {
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        $scope.user_profile.city = {};
                        $scope.user_profile.city.name = value.long_name;
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        $scope.user_profile.state = {};
                        $scope.user_profile.state.name = value.long_name;
                    }
                    if (value.types[0] === 'country') {
                        $scope.user_profile.country = {};
                        $scope.user_profile.country.iso2 = value.short_name;
                        $scope.user_profile.country.name = value.long_name;
                        $scope.selectedCountry = value.short_name;
                        $scope.getCountryPhoneCode(value.short_name);
                    }
                    if (value.types[0] === 'postal_code') {
                        $scope.user_profile.postal_code = parseInt(value.long_name);
                    }

                });
                if ($scope.user_profile.address.address_components) {
                    $scope.IsAddressPlaceChange = true;
                    $scope.user_profile.latitude = $scope.user_profile.address.geometry.location.lat();
                    $scope.user_profile.longitude = $scope.user_profile.address.geometry.location.lng();
                    $scope.user_profile.full_address = $scope.user_profile.address.name + " " + $scope.user_profile.address.vicinity;
                    $scope.user_profile.address = $scope.user_profile.address.formatted_address;
                }
            } else {
                $scope.IsAddressPlaceChange = true;
            }
        };

    })
    /* For select when search */
    .directive('convertToNumber', function () {
        return {
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function (val) {
                    return val ? parseInt(val, 10) : null;
                });
                ngModel.$formatters.push(function (val) {
                    return val ? '' + val : '';
                });
            }
        };
    });


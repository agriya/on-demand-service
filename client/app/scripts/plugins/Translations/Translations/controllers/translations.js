'use strict';   
/**
 * @ngdoc function
 * @name ofosApp.controller:SearchController
 * @description
 * # SearchController
 * Controller of the ofosApp
 */
angular.module('hirecoworkerApp.Translations')
    .factory('languageList', function($resource) {
        return $resource('/api/v1/settings/site_languages', {}, {
            get: {
                method: 'GET',
            }
        });
    })
    .service('LocaleService', function($translate, $rootScope, tmhDynamicLocale, languageList, $cookies) {
        /*jshint -W117 */
        var localesObj;
        var localesObj1 = {};
        localesObj1.locales = {};
        localesObj1.preferredLocale = {};
        var _LOCALES_DISPLAY_NAMES = [];
        var _LOCALES;
		languageList.get(function(response){
            $.each(response.site_languages, function(i, data) {
                localesObj1.locales[data.iso2] = data.name;
            });
            localesObj1.preferredLocale = response.site_languages[0].iso2;
            localesObj = localesObj1.locales;
            _LOCALES = Object.keys(localesObj);
            if (!_LOCALES || _LOCALES.length === 0) {
                console.error('There are no _LOCALES provided');
            }
            _LOCALES.forEach(function(locale) {
                _LOCALES_DISPLAY_NAMES.push(localesObj[locale]);
            });
        });
        var currentLocale = $translate.use() || $translate.preferredLanguage(); // because of async loading
        $cookies.put('currentLocale', currentLocale, {
            path: '/'
        });
        var checkLocaleIsValid = function(locale) {
            return _LOCALES.indexOf(locale) !== -1;
        };
        var setLocale = function(locale) {
            if (!checkLocaleIsValid(locale)) {
                console.error('Locale name "' + locale + '" is invalid');
                return;
            }
            currentLocale = locale;
            $cookies.put('currentLocale', currentLocale, {
                path: '/'
            });
            $translate.use(locale);
        };
        $rootScope.$on('$translateChangeSuccess', function(event, data) {
            document.documentElement.setAttribute('lang', data.language);
            $rootScope.$emit('changeLanguage', {
                currentLocale: data.language,
            });
            tmhDynamicLocale.set(data.language.toLowerCase()
                .replace(/_/g, '-'));
        });
        return {
            getLocaleDisplayName: function() {
                if (angular.isDefined(localesObj)) {
                    return localesObj[currentLocale];
                }
            },
            setLocaleByDisplayName: function(localeDisplayName) {
                setLocale(_LOCALES[_LOCALES_DISPLAY_NAMES.indexOf(localeDisplayName)]);
            },
            getLocalesDisplayNames: function() {
                return _LOCALES_DISPLAY_NAMES;
            }
        };
    })
    .directive('ngTranslateLanguageSelect', function(LocaleService) {
        return {
            restrict: 'AE',
            templateUrl: 'scripts/plugins/Translations/Translations/views/language_translate.html',
            controller: function($scope, $rootScope, $timeout, languageList, $window,$cookies) {
		            languageList.get(function(response){
                       $scope.localesDisplayNames = response.site_languages;
                       $scope.check_language = $cookies.get("currentLocale");
                    if($scope.check_language != 'undefined' && $scope.check_language){
                        $scope.default_language = $cookies.get("currentLocale");
                    }
                    else{
                       $scope.default_language = $rootScope.settings.SITE_DAFAULT_LANGUAGE; 
                    }
                       angular.forEach($scope.localesDisplayNames,function(value){
                           if(value.iso2 === $scope.default_language){
                                $scope.currentLocaleDisplayName = value.name;   
                                $scope.changeLanguage(value.name);
                           }
                       });
                    //    $scope.currentLocaleDisplayName = $window.localStorage.getItem('Lang', $scope.currentLocaleDisplayName);
                    $scope.visible = $scope.localesDisplayNames && $scope.localesDisplayNames.length > 1;
                  });
                  $scope.changeLanguage = function (locale) {
                    LocaleService.setLocaleByDisplayName(locale);
                };
            }   
        };
    });
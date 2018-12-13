'use strict';
/**
 * @ngdoc service
 * @name hirecoworkerApp.search
 * @description
 * # search
 * Factory in the hirecoworkerApp.
 */
angular.module('hirecoworkerApp')
.factory('Languages', ['$resource', function($resource) {
        return $resource('/api/v1/languages', {}, {
            languageList: {
                method: 'GET'
            }
        });
}])
.factory('Cities', ['$resource', function($resource) {
        return $resource('/api/v1/cities', {}, {
            citiesliList: {
                method: 'GET'
            }
        });
}])    
.factory('SpecialtyDiseas', ['$resource', function($resource) {
        return $resource('/api/v1/specialty_diseases', {}, {
            specialtyDiseasliList: {
                method: 'GET'
            }
        });
}])     
.factory('Gender', ['$resource', function($resource) {
        return $resource('/api/v1/genders', {}, {
            genderList: {
                method: 'GET'
            }
        });
}])    
.factory('SearchList', ['$resource', function($resource) {
        return $resource('/api/v1/search', {}, {
            searchList: {
                method: 'GET'
            }
        });
}])    
.factory('WeekList', ['$resource', function($resource) {
        return $resource('/api/v1/search/weeklist/:userids/:viewslot', { userids:'@userids', viewslot:'@viewslot'}, {
            get: {
                method: 'GET'
            }
        });
}])  
.factory('ServiceDetails', ['$resource', function($resource) {
        return $resource('/api/v1/service/:id', { id:'@id'}, {
            get: {
                method: 'GET'
            }
        });
}])    
.factory('CategoryService', ['$resource', function($resource) {
        return $resource('/api/v1/category_service/:id', { id:'@id'}, {
            get: {
                method: 'GET'
            }
        });
}])
.factory('CategoryFactory', ['$resource', function($resource) {
        return $resource('/api/v1/categories', {}, {
            get: {
                method: 'GET'
            }
        });
}]);  
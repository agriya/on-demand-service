angular.module('hirecoworkerApp')
.factory('messagesFactory', ['$resource', function($resource) {
       return $resource('/api/v1/messages', {filter:'@filter'}, {}, {
            'get': {
                method: 'GET'
            }
        });
}])
.factory('sendMessagesFactory', ['$resource', function($resource) {
       return $resource('/api/v1/messages', {}, {
            create: {
                method: 'POST'
            }
        });
}]);

angular.module('hirecoworkerApp')
.factory('ReferFriends', function ($resource) {
        return $resource('/api/v1/invite_friends', {}, {
            'save': {
                method: 'POST'
            }
        });
    });


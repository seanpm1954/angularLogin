myApp.factory('Status', function($cookieStore){

    return {
        getStatus: function(){
            return $cookieStore.get('username');
        },
        setStatus: function(usr){
            $cookieStore.put('username', usr);
        }
    }
});

myApp.controller('StatusCtrl', function($rootScope, $scope, $location, $cookieStore, $route, Status){
        $rootScope.username = Status.getStatus('username');


    $scope.logout = function(){
        $cookieStore.remove('username');
        $scope.username ='';
        $location.path('/login');
    }


    });
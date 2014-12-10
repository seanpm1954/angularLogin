myApp.controller('LoginCtrl', function($scope, $rootScope, $location, $cookieStore, AuthService){
    //$scope.$on('$viewContentLoaded', function(){
    //});

    $scope.login = function(){
        AuthService.login($scope.user).
            then(function(response) {

                if (response.data != 'false') {
                    $cookieStore.put('username', response.data.username);
                    $location.path('/issues');
                }else{
                    $scope.errMessage = 'Logon failed';
                    $location.path('/login');
                }

            });
    }
    //$scope.username = $cookieStore.get('username');

    $scope.logout = function(){
        $cookieStore.remove('username');
        $rootScope.username ='';
        $location.path('/login');
    }
});
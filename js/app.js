var myApp = angular.module('myApp',[
    'ngRoute',
    'ngCookies'
]);

//var appControllers = angular.module('appControllers',[]);

myApp.config(['$routeProvider', function($routeProvider){
   $routeProvider
       .when('/login',{
       templateUrl: 'partials/login.html',
           controller: 'LoginCtrl'
        })
       .when('/issues',{
           templateUrl: 'partials/issues.html'
       })
       .when('/users',{
           templateUrl: 'partials/users.html'
       })
       .when('/error',{
           templateUrl: 'partials/error.html'
       })
       .otherwise({
           redirectTo: '/login'
       });
}])
    .run(function( $rootScope,$location, $cookieStore){
       $rootScope.$on('$locationChangeSuccess', function(){
           $rootScope.username = $cookieStore.get('username');
       });

        $rootScope.$on('$locationChangeStart', function(){
            $rootScope.username = $cookieStore.get('username');
            if($rootScope.username == null && $location.path() != '/login'){

                if($location.path() != ''){
                    $rootScope.errMsg = 'You must login to view this page';
                    event.preventDefault();
                    $location.path('/error');
                }

            }
        });
    });

;



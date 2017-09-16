angular.module('readArkrit', ['ngRoute'])

.config(['$routeProvider', '$locationProvider','$httpProvider', function ($routeProvider, $locationProvider,$httpProvider) {

    $locationProvider.html5Mode(true);

    /* LAS RUTAS DEL HTML, PARTEN DESDE DONDE ESTÁ menuApp.html */

    $httpProvider.defaults.headers.common['token'] = sessionStorage.getItem('token');
    $routeProvider
        .when('/', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })
        .when('/estadisticas', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })
        .when('/libro', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'LibroCtrl'
        })
        /*.when('/estadistica', {
            templateUrl: '../estadistica/listarEstadistica.html'
        })*/

        // ALUMNO
        .when('/alumno/crear', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'alumnoCrearCtrl'
        })
        .when('/alumno/modificar', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'LibroCtrl'
        })
        .when('/libro', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'LibroCtrl'
        })



        .otherwise({
            redirectTo: '/estadisticas'
        });

}])
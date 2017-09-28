var app = angular.module('readArkrit', ['ngRoute', 'datatables']);

app.config(['$routeProvider', '$locationProvider','$httpProvider', function ($routeProvider, $locationProvider,$httpProvider) {

    $locationProvider.html5Mode(true);

    /* LAS RUTAS DEL HTML, PARTEN DESDE DONDE ESTÁ menuApp.html */
    $httpProvider.defaults.headers.common['token'] = sessionStorage.getItem('tokenREADARKRIT');
    $httpProvider.defaults.headers.common['token'] = sessionStorage.getItem('token');
    $routeProvider
        .when('/', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })
        .when('/estadisticas', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })
        /*.when('/estadistica', {
            templateUrl: '../estadistica/listarEstadistica.html'
        })*/

        // ALUMNO
        .when('/alumno/crear', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'altaAlumnoCtrl'
        })
        .when('/alumno/modificar', {
            url: '/alumno/modificar',
            templateUrl: './html/libro/listarLibro.html',
            controller: 'modificarAlumnoCtrl'
        })
        /*.when('/libro', {
            templateUrl: './html/libro/listarLibro.html',
            controller: 'LibroCtrl'
        })*/


        .when('/profesor', {
            templateUrl: './html/profesor/listarProfesor.html',
            controller: 'profesorCtrl'
        })


        .otherwise({
            redirectTo: '/estadisticas'
        });

}]);
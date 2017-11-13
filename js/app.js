var app = angular.module('readArkrit', ['ngRoute', 'datatables']);

app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});

app.config(['$routeProvider', '$locationProvider','$httpProvider', function ($routeProvider, $locationProvider,$httpProvider) {

    $locationProvider.html5Mode(true);

    /* LAS RUTAS DEL HTML, PARTEN DESDE DONDE ESTÁ menuApp.html */
    $httpProvider.defaults.headers.common['token'] = sessionStorage.getItem('tokenREADARKRIT');


    $routeProvider
        .when('/', {
            templateUrl: './html/estadistica/listarEstadistica.html'
        })

        // ESTADÍSTICAS
        .when('/estadisticas', {
            templateUrl: './html/estadistica/listarEstadistica.html',
            controller: 'estadisticasCtrl'
        })

        // MI BIBLIOTECA
        .when('/miBiblioteca', {
            templateUrl: './html/miBiblioteca/listarMiBiblioteca.html',
            controller: 'miBibliotecaCtrl'
        })

        // ALUMNO
        .when('/alumno', {
            templateUrl: './html/alumno/adminAlumno.html',
            controller: 'alumnoCtrl'
        })        
        .when('/alumno/modificar', {
            templateUrl: './html/alumno/formAlumno.html',
            controller: 'modificarAlumnoCtrl'
        })
        
        // LIBRO
        .when('/libro', {
            templateUrl: './html/libro/adminLibro.html',
            controller: 'libroCtrl'
        })

        // PROFESOR
        .when('/profesor', {
            templateUrl: './html/profesor/listarProfesor.html',
            controller: 'profesorCtrl'
        })
        .when('/profesor/modificar', {
            templateUrl: './html/profesor/formProfesor.html',
            controller: 'modificarProfesorCtrl'
        })
        .when('/profesor/terminar',{
            templateUrl: './html/profesor/formProfesor.html',
            controller: 'terminarProfesorCtrl'
        })

        // CLUB DE LECTURA        
        .when('/clubLectura', {
            templateUrl: './html/clubLectura/anadirComentarioClub.html',
            controller: 'comentarioClubCtrl'
        })
        .when('/clubLectura/adminClubLectura', {
            templateUrl: './html/clubLectura/adminClubLectura.html',
            controller: 'clubLecturaCtrl'
        })

        // SEGUIR ESTANTERÍAS DE OTROS USUARIOS
        .when('/seguirEstanteria', {
            templateUrl: './html/seguirEstanteria/listarSeguirEstanteria.html',
            controller: 'seguirEstanteriaCtrl'
        })

        // ALMAS GEMELAS
        .when('/almasGemelas', {
            templateUrl: './html/almasGemelas/listarAlmasGemelas.html',
            controller: 'almasGemelasCtrl'
        })

        .otherwise({
            redirectTo: '/estadisticas'
        });

}]);
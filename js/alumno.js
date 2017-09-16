angular.module('ngApp', ['ngRoute'])

    .controller('AlumnoCtrl', function ($scope) {
        alert($scope.id, $scope.type);
    });
var app = angular.module('myApp', ['ui.bootstrap']);

app.controller('myController', function( $scope, $sce, $http, $filter ) {
    $scope.retrievePosts = function( accessToken ) {
        $scope.loading = true;
        $http({
            'method': 'GET',
            'url': '/api/tools/fetch_messages?token=' + accessToken
        }).success(function(data, status, headers, config) {
            console.log(data);
            $scope.postings = data;
            $scope.loading = false;

        }).error(function(data, status, headers, config) {
            alert(data.message);
            console.log(data);
            $scope.loading = false;
        });
    }
});
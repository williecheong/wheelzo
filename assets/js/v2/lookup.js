$core.extensionController = function($scope, $sce, $http, $filter, $modal, toaster) {

    $scope.searchUsers = function(name) {
        $scope.loading = true;
        if (name.length < 2) {
            toaster.pop('error', 'Error', 'A more specific name would be nice');
            $scope.loading = false;
            return;
        }

        $http({
            'method': 'GET',
            'url': '/api/v2/users?name=' + name
        }).success(function(data, status, headers, config) {
            $scope.users = data;
            $scope.loading = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve users');
            $scope.loading = false;
            console.log(data);
        });
    };

    $scope.initialize = function() {        
        $scope.name = "";
        $scope.users = [ ];
    };

    $scope.initialize();

};


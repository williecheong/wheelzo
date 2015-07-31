$core.extensionController = function($scope, $sce, $http, $filter, $modal, toaster) {

    

    $scope.initialize = function() {        
        $scope.rides = [ ];
        $scope.loadSession();
    };

    $scope.initialize();

};


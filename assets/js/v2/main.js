$core.extensionController = function($scope, $sce, $http, $filter, $modal, toaster) {

    $scope.loadRides = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/rides'
        }).success(function(data, status, headers, config) {
            $scope.rides = data;
            $scope.filterRides();
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + 'Could not retrieve rides');
            console.log(data);
        });
    };

    $scope.filterRides = function() {
        var searchOrigin = $scope.inputSearchOrigin.toLowerCase();
        var searchDestination = $scope.inputSearchDestination.toLowerCase();

        var ridesToDisplay = JSON.parse(JSON.stringify($scope.rides)); // copy rides
        for (var i = 0; i < ridesToDisplay.length; i++) {
            if (searchOrigin.length > 0) {
                if (!ridesToDisplay[i].origin.toLowerCase().contains(searchOrigin)) {
                    ridesToDisplay[i] = null;
                    continue;
                }
            }
            
            if (searchDestination.length > 0) {
                if (!ridesToDisplay[i].destination.toLowerCase().contains(searchDestination)) {
                    ridesToDisplay[i] = null;
                    continue;
                }
            } 
        };

        $scope.displayRides = ridesToDisplay.filter(function(n){ return n != null }); 
    };

    $scope.initialize = function() {        
        $scope.rides = [ ];
        $scope.displayRides = [ ];
        $scope.inputSearchOrigin = "";
        $scope.inputSearchDestination = "";

        $scope.loadSession();
        $scope.loadRides();
    };

    $scope.initialize();

};


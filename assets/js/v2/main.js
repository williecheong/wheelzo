$core.extensionController = function($scope, $sce, $http, $filter, $modal, toaster) {

    $scope.rides = [ ];
    $scope.ridesByDate = [ ];
    $scope.ridesToDisplay = [ ];
    $scope.disableEntirePage = true;

    $scope.loadRides = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/rides'
        }).success(function(data, status, headers, config) {
            $scope.rides = data;
            $scope.filterRides();
            $scope.disableEntirePage = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve rides');
            $scope.disableEntirePage = false;
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
        ridesToDisplay = ridesToDisplay.filter(function(n){ return n != null });
        ridesToDisplay.sort(compareByStart);
        
        var ridesByDate = [ ];
        for (var key in ridesToDisplay) {
            var startParts = ridesToDisplay[key].start.split(' ');
            var rideDate = startParts[0] + " 12:00:00";
            
            var dateGroupIndex = null;
            var dateGroupExists = false;
            for (var i in ridesByDate) {
                if (rideDate == ridesByDate[i].start) {
                    dateGroupIndex = i;
                    dateGroupExists = true;
                    break;
                } 
            }

            if (dateGroupExists == false) {
                dateGroupIndex = ridesByDate.length;
                ridesByDate.push({
                    "start" : rideDate,
                    "rides" : [ ]
                }); 
            }

            ridesByDate[dateGroupIndex].rides.push(ridesToDisplay[key]);
        }
        ridesByDate.sort(compareByStart);
        $scope.ridesToDisplay = ridesToDisplay;
        $scope.ridesByDate = ridesByDate;
    };

    $scope.pluralize = function(amount, singular, plural) {
        if ( amount == 1 )
            return singular;
        else
            return plural;
    };

    $scope.initialize = function() {        
        $scope.inputSearchOrigin = "";
        $scope.inputSearchDestination = "";
        $scope.activeDateFilter = "";

        $scope.loadSession();
        $scope.loadRides();
    };

    $scope.initialize();

};


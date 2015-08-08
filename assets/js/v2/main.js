$core.extensionController = function($scope, $sce, $http, $filter, $modal, toaster) {

    $scope.rides = [ ];
    $scope.displayRides = [ ];
    $scope.disableEntirePage = true;

    $scope.loadRides = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/rides'
        }).success(function(data, status, headers, config) {
            $scope.rides = data;
            $scope.filterRides();
            setTimeout(function() {
                $scope.disableEntirePage = false;
            }, 500);
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve rides');
            $scope.disableEntirePage = false;
            console.log(data);
        });
    };

    $scope.filterDate = function(day) {
        if (day!='Monday' && day!='Tuesday' && day!='Wednesday' && day!='Thursday' && day!='Friday' && day!='Saturday' && day!='Sunday') {
            toaster.pop('error', 'Error', 'Invalid day selection for filtering rides');
            return;
        }

        if ($scope.activeDateFilter == day) {
            $scope.activeDateFilter = "";
            $scope.filterRides();
            return;
        }

        $scope.activeDateFilter = day;
        $scope.filterRides();
    };

    $scope.filterRides = function() {        
        var activeDateFilter = $scope.activeDateFilter;
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

            if (activeDateFilter != "") {
                var ONE_WEEK = 7 * 24 * 60 * 60 * 1000; /* ms */
                var thisRideStart = $filter('mysqlDateToIso')(ridesToDisplay[i].start);
                if (((thisRideStart.getTime()) - (new Date().getTime())) > ONE_WEEK) {
                    ridesToDisplay[i] = null;
                    continue;
                }

                if ($filter('date')(thisRideStart, 'EEEE') != activeDateFilter) {
                    ridesToDisplay[i] = null;
                    continue;
                }
            }
        };
        ridesToDisplay = ridesToDisplay.filter(function(n){ return n != null });
        ridesToDisplay.sort(compareByStart);
        $scope.displayRides = ridesToDisplay;
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


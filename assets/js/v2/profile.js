$core.extensionController = function($scope, $sce, $http, $filter, $modal, toaster) {

    $scope.loadRides = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/rides/isdriver'
        }).success(function(data, status, headers, config) {
            $scope.driverRides = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve personal driving rides');
            console.log(data);
        });

        $http({
            'method': 'GET',
            'url': '/api/v2/rides/ispassenger'
        }).success(function(data, status, headers, config) {
            $scope.passengerRides = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve personal passenger rides');
            console.log(data);
        });
    };

    $scope.loadRideRequests = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/rrequests/personal'
        }).success(function(data, status, headers, config) {
            $scope.rideRequests = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve personal ride requests');
            console.log(data);
        });
    };

    $scope.loadStatistics = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/statistics'
        }).success(function(data, status, headers, config) {
            $scope.statistics = data;
            $scope.loadSupporters();
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve personal statistics');
            console.log(data);
        });
    };

    $scope.loadSupporters = function() {
        var hashedIds = { };
        for (var i=0; i<$scope.statistics.points.length; i++) {
            if (!hashedIds[$scope.statistics.points[i].giver_id]) {
                $scope.supporters.push({
                    'id' : $scope.statistics.points[i].giver_id,
                    'facebook_id' : $scope.statistics.points[i].giver_facebook_id,
                    'name' : $scope.statistics.points[i].giver_name
                });
            }
            hashedIds[$scope.statistics.points[i].giver_id] = true;
        }
    };

    $scope.pluralize = function(amount, singular, plural) {
        if ( amount == 1 )
            return singular;
        else
            return plural;
    };

    $scope.openInvitationModal = function(rrequest) {
        var modalInstance = $modal.open({
            templateUrl: 'invitation.html',
            controller: 'invitationModalController',
            size: 'md',
            resolve: {
                'initialize' : function() { return $scope.initialize; },
                'rrequest' : function() { return rrequest; }
            }
        });
    };

    $scope.initialize = function() {        
        $scope.supporters = [ ];
        $scope.statistics = false;
        $scope.driverRides = false;
        $scope.passengerRides = false;
        $scope.rideRequests = false;
        $scope.loadRideRequests();
        $scope.loadStatistics();
        $scope.loadRides();
    };

    $scope.initialize();

};


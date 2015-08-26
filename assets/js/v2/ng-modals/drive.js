angular.module('myApp').controller('driveModalController', function ($scope, $modalInstance, $modal, $http, $filter, toaster, initialize) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);

    $scope.suggestedPlaces = defaultSuggestedPlaces;
    $scope.permissions = { };
    $scope.fbgroups = [ ];
    $scope.rrequests = [ ];
    $scope.dateOptions = { 
        'show-weeks' : false
    };
    
    $scope.initializeModal = function() {
        var defaultDate = new Date();
        var defaultTime = new Date();
        defaultDate = defaultDate.setDate(defaultDate.getDate() + 3);
        defaultTime = new Date(defaultTime).setHours(12);
        defaultTime = new Date(defaultTime).setMinutes(0);
        defaultTime = new Date(defaultTime).setSeconds(0);
        
        $scope.input = { 
            'origin' : '',
            'destination' : '',
            'dropoffs' : [ ],
            'startDate' : defaultDate,
            'startTime' : defaultTime,
            'price' : '10',
            'capacity' : '2',
            'allowPayments': false,
            'invitees' : [ ],
            'shareToFacebook' : true,
            'shareToFacebookGroups' : { },
            'shareToFacebookMessage' : ""
        };

        $scope.loadPermissions();
        $scope.loadFbgroups();
    };

    $scope.loadPermissions = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/permissions'
        }).success(function(data, status, headers, config) {
            $scope.permissions = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            console.log(data);
        });
    };

    $scope.loadFbgroups = function() {
        $scope.input['shareToFacebookGroups'] = { };
        $http({
            'method': 'GET',
            'url': '/api/v2/users/fbgroups'
        }).success(function(data, status, headers, config) {
            $scope.fbgroups = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            console.log(data);
        });
    };

    $scope.addDropoff = function() {
        $scope.input.dropoffs.push('');
    };

    $scope.removeDropoff = function(index) {
        $scope.input.dropoffs.splice(index, 1);
    };    

    $scope.searchRrequests = function(input) {
        if ($scope.searchingNow) {
            return; // still loading a search. try again later.
        }

        var inputData = {
            'origin' : input.origin,
            'destination' : input.destination,
            'departureDate' : $filter('date')(input.startDate, 'yyyy-MM-dd'),
        }

        if ( inputData.origin.length < 3 || inputData.destination.length < 3 ) {
            return; // too little information about origin and destination
        } 

        if ( inputData.departureDate.length == 0 ) {
            return; // can't search with empty time
        }

        $scope.firstSearchExecuted = true;
        $scope.searchingNow = true;
        $http({
            'method': 'GET',
            'url': '/api/v2/rrequests/search?origin='+inputData.origin+'&destination='+inputData.destination+'&departure='+inputData.departureDate
        }).success(function(data, status, headers, config) {
            $scope.searchingNow = false;
            if ($scope.rrequests.length == data.length) { // potentially the same set of rrequests
                var isDuplicateSet = true;
                for (var i=0; i<$scope.rrequests.length; i++) {
                    var matchFound = false;
                    for (var j=0; j<data.length; j++) {
                        if ($scope.rrequests[i].id == data[j].id) {
                            matchFound = true;
                            break;
                        }
                    }
                    if (matchFound == false) {
                        isDuplicateSet = false;
                        break; 
                    } 
                }
                if (isDuplicateSet) {
                    return;
                }
            } 
            $scope.input.invitees = { };
            $scope.rrequests = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.searchingNow = false;
        });
    };

    $scope.submit = function (input) {
        var inputData = {
            'origin' : input.origin,
            'destination' : input.destination,
            'dropOffs' : input.dropoffs,
            'departureDate' : $filter('date')(input.startDate, 'yyyy-MM-dd'),
            'departureTime' : $filter('date')(input.startTime, 'HH:mm:ss'),
            'price' : input.price,
            'capacity' : input.capacity,
            'allowPayments' : input.allowPayments ? 1 : 0,
            'invitees' : [ ],
            'shareToFacebook' : input.shareToFacebook ? 1 : 0,
            'shareToFacebookGroups' : [ ],
            'shareToFacebookMessage' : input.shareToFacebookMessage,
        };

        for (var key in input.invitees) {
            if (input.invitees[key]) {
                inputData.invitees.push(key);
            }
        }

        for (var key in input.shareToFacebookGroups) {
            if (input.shareToFacebookGroups[key]) {
                inputData.shareToFacebookGroups.push(key);
            }
        }

        if ( inputData.origin.length == 0 || inputData.destination.length == 0 ) {
            toaster.pop('error', 'Error', 'Origin and destination cannot be empty'); 
            return;
        } 

        if ( inputData.origin == inputData.destination ) {
            toaster.pop('error', 'Error', 'Origin and destination cannot be the same'); 
            return;
        } 

        if ( inputData.departureDate.length == 0 || inputData.departureTime == 0 ) {
            toaster.pop('error', 'Error', 'Departure date and time must be specified'); 
            return;
        }

        if (inputData.shareToFacebook == 1) {
            if (inputData.shareToFacebookGroups.length == 0) {
                toaster.pop('error', 'Error', 'At least 1 Facebook group must be selected for publishing'); 
                return;
            }
        }

        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v2/rides',
            'data': inputData
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.loading = false;
            initialize();
            $modalInstance.close();
            
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.openFbgroupModal = function() {
        var modalInstance = $modal.open({
            templateUrl: 'fbgroup.html',
            controller: 'fbgroupModalController',
            size: 'md',
            resolve: {
                'initialize' : function() { return $scope.loadFbgroups; }
            }
        });
    };

    $scope.openReviewModal = function(userId) {
        var modalInstance = $modal.open({
            templateUrl: 'review.html',
            controller: 'reviewModalController',
            size: 'sm',
            resolve: {
                'userId' : function() { return userId; }
            }
        });
    };

    $scope.initializeModal();
});
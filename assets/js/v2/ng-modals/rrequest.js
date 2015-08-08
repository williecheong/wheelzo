angular.module('myApp').controller('rrequestModalController', function ($scope, $modalInstance, $http, $filter, toaster, initialize) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);

    $scope.suggestedPlaces = defaultSuggestedPlaces;

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
            'startDate' : defaultDate,
            'startTime' : defaultTime,
        };
    };

    $scope.submit = function (input) {
        var inputData = {
            'origin' : input.origin,
            'destination' : input.destination,
            'departureDate' : $filter('date')(input.startDate, 'yyyy-MM-dd'),
            'departureTime' : $filter('date')(input.startTime, 'HH:mm:ss')
        };

        if ( inputData.origin.length == 0 || inputData.destination.length == 0 ) {
            toaster.pop('error', 'Error', 'Origin and destination cannot be empty'); 
            return;
        } 

        if ( inputData.origin == inputData.destination ) {
            toaster.pop('error', 'Error', 'Origin and destination cannot be the same'); 
            return;
        } 

        if ( inputData.departureDate.length == 0 || inputData.departureTime == 0 ) {
            toaster.pop('error', 'Error', 'Departure date and time must be specified.'); 
            return;
        }

        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v2/rrequests',
            'data': inputData
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.loading = false;
            initialize();
            console.log(data);
            $modalInstance.close();
            
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.initializeModal();
});
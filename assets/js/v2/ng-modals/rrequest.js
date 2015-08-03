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
            'dropoffs' : [ ],
            'startDate' : defaultDate,
            'startTime' : defaultTime,
            'price' : '10',
            'capacity' : '2',
            'allowPayments': false
        };
    };

    $scope.addDropoff = function() {
        $scope.input.dropoffs.push('');
    };

    $scope.removeDropoff = function(index) {
        $scope.input.dropoffs.splice(index, 1);
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

        if ( inputData.price < 1 || inputData.price > 40 ) {
            toaster.pop('error', 'Error', 'Ride price must be between 1 and 40'); 
            return;
        }

        if ( inputData.capacity < 1 || inputData.capacity > 7 ) {
            toaster.pop('error', 'Error', 'Ride capacity must be between 1 and 7'); 
            return;
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

    $scope.initializeModal();
});
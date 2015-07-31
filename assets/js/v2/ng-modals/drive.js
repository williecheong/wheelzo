angular.module('myApp').controller('tournamentModalController', function ($scope, $modalInstance, $http, toaster, initialize) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);
    
    $scope.dateOptions = { };
    $scope.selectOptions = {
        "tournamentStyle" : ["Scramble", "Best Ball", "Better Ball", "Singles"],
        "startingFormat" : ["Shotgun Start", "Single Tee Start"]
    };
    
    $scope.initializeModal = function() {
        $scope.input = { };
        var defaultDate = new Date();
        defaultDate = defaultDate.setDate(defaultDate.getDate() + 7);
        defaultDate = new Date(defaultDate).setHours(12);
        defaultDate = new Date(defaultDate).setMinutes(0);
        defaultDate = new Date(defaultDate).setSeconds(0);
        $scope.input.start = new Date(defaultDate);
        $scope.input.tournamentStyle = $scope.selectOptions.tournamentStyle[0];
        $scope.input.startingFormat = $scope.selectOptions.startingFormat[0];
    };

    $scope.openStartCalendar = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.showStartCalendar = true;
    };

    $scope.openDeadlineCalendar = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.showDeadlineCalendar = true;
    };

    $scope.sumCost = function(input) {
        var costGolf = 0;
        var costCart = 0;
        var costMeal = 0;
        var costExtras = 0;
        if (input.costGolf) {
            costGolf = parseFloat(input.costGolf);
        }

        if (input.costCart) {
            costCart = parseFloat(input.costCart);
        }

        if (input.costMeal) {
            costMeal = parseFloat(input.costMeal);
        }

        if (input.costExtras) {
            costExtras = parseFloat(input.costExtras);
        }

        return costGolf + costCart + costMeal + costExtras;
    };

    $scope.submit = function (input) {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v1/tournament',
            'data': input
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
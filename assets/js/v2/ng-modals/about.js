angular.module('myApp').controller('aboutModalController', function ($scope, $modalInstance, $http, $filter, toaster, initialize) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);
    
    $scope.initializeModal = function() {

    };

    $scope.initializeModal();
});
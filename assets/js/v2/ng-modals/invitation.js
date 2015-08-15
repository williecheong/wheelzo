angular.module('myApp').controller('invitationModalController', function ($scope, $modalInstance, $modal, $http, $filter, toaster, initialize, rrequest) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);

    $scope.rrequest = rrequest;

    $scope.openRideModal = function(rideId) {
        var modalInstance = $modal.open({
            templateUrl: 'ride.html',
            controller: 'rideModalController',
            size: 'md',
            resolve: {
                'rideId' : function() { return rideId; }
            }
        });
    };

    $scope.deleteRrequest = function() {
        swal({
            title: "Delete this ride request?",
            type: "error",
            confirmButtonColor: "#D9534F",
            allowOutsideClick: true,
            showCancelButton: true,
            closeOnConfirm: false,
            closeOnCancel: true,
            allowHtml: true
        }, function(isConfirm){
            $scope.loading = true;
            $http({
                'method': 'DELETE',
                'url': '/api/v2/rrequests/index/' + $scope.rrequest.id
            }).success(function(data, status, headers, config) {
                toaster.pop('success', 'Success: ' + status, data.message);
                initialize();
                $modalInstance.close();
            }).error(function(data, status, headers, config) {
                toaster.pop('error', 'Error: ' + status, data.message);
            });
        });
    };
});
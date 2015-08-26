angular.module('myApp').controller('fbgroupModalController', function ($scope, $modalInstance, $modal, $http, $filter, toaster, initialize) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);
    $scope.allFbgroups = [ ];
    $scope.personalFbgroups = [ ];
    $scope.unpersonalFbgroups = [ ];

    $scope.initializeModal = function() {
        $scope.inputFacebookId = "";

        $scope.loadAllFbgroups();
        $scope.loadPersonalFbgroups();
    };

    $scope.loadAllFbgroups = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/fbgroups'
        }).success(function(data, status, headers, config) {
            $scope.allFbgroups = data;
            $scope.calculateUnpersonalFbgroups();
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            console.log(data);
        });
    };

    $scope.loadPersonalFbgroups = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/fbgroups'
        }).success(function(data, status, headers, config) {
            $scope.personalFbgroups = data;
            $scope.calculateUnpersonalFbgroups();
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            console.log(data);
        });
    };

    $scope.calculateUnpersonalFbgroups = function() {
        var unpersonalFbgroups = [ ];
        for (var i in $scope.allFbgroups) {
            var duplicateFound = false;            
            for (var j in $scope.personalFbgroups) {
                if ($scope.allFbgroups[i].facebook_id == $scope.personalFbgroups[j].facebook_id) {
                    duplicateFound = true;
                    break;
                }
            }
            if (duplicateFound == false) {
                unpersonalFbgroups.push($scope.allFbgroups[i]);
            }
        }
        $scope.unpersonalFbgroups = unpersonalFbgroups;
    };

    $scope.addToPersonal = function(facebookId) {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v2/users/fbgroups',
            'data': {
                'facebookId' : facebookId
            },
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;
            initialize();
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
            console.log(data);
        });
    };

    $scope.dropFromPersonal = function(facebookId) {
        $scope.loading = true;
        $http({
            'method': 'DELETE',
            'url': '/api/v2/users/fbgroups/' + facebookId
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;
            initialize();
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
            console.log(data);
        });
    };

    $scope.introduceFbgroup = function(facebookId) {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v2/fbgroups',
            'data': {
                'facebookId' : facebookId
            },
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
            console.log(data);
        });
    };

    $scope.initializeModal();
});
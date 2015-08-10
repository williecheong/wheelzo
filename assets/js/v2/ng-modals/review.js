angular.module('myApp').controller('reviewModalController', function ($scope, $modalInstance, $http, $filter, toaster, userId) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);

    $scope.user = { };

    $scope.initializeModal = function() {
        $scope.inputReview = "";
        $scope.loadUser();
        $scope.loadSession();
    };

    $scope.loadSession = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/session'
        }).success(function(data, status, headers, config) {
            $scope.session = data;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + 'Could not retrieve session');
            $scope.session.active = false;
            console.log(data);
        });
    };

    $scope.loadUser = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/?id=' + userId
        }).success(function(data, status, headers, config) {
            if (data.length > 0) {
                $scope.user = data[0];
                $scope.loadReviews();
            } else {
                toaster.pop('error', 'Error: ' + status, 'User not found');
                $modalInstance.close();
            }
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve user');
            console.log(data);
        });
    };

    $scope.loadReviews = function() {
        $scope.loadingReviews = true;
        $http({
            'method': 'GET',
            'url': '/api/v2/reviews?receiver_id=' + $scope.user.id
        }).success(function(data, status, headers, config) {
            $scope.user.reviews = data;
            $scope.loadingReviews = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve reviews');
            $scope.loadingReviews = false;
            console.log(data);
        });
    };

    $scope.submitReview = function (inputReview) {
        $scope.loading = true;

        if (inputReview.length < 15) {
            toaster.pop('error', 'Error', 'Reviews should be a little more detailed');
            $scope.loading = false;
            return;
        }

        $http({
            'method': 'POST',
            'url': '/api/v2/reviews',
            'data': {
                receiver_id : $scope.user.id,
                review : inputReview
            }
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.deleteReview = function(reviewId) {
        $scope.loading = true;
        $http({
            'method': 'DELETE',
            'url': '/api/v1/reviews/index/' + reviewId
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.submitPoint = function() {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v2/points',
            'data': {
                receiver_id : $scope.user.id
            }
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;            
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.copyShowMessage = function() {
        toaster.pop('success', 'Success', "Successfully copied reviews about " + $scope.user.name);
    };

    $scope.copyFallback = function(copy) {
        window.prompt('Copy the link below to share', copy);
    };

    $scope.initializeModal();
});
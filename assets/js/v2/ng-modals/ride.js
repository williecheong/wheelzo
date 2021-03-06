angular.module('myApp').controller('rideModalController', function ($scope, $modalInstance, $sce, $modal, $http, $filter, toaster, rideId) {
    
    $core.extensionModal($scope, $modalInstance, $http, toaster);

    $scope.ride = {
        'start' : '0000-00-00 00:00:00'
    };
    
    $scope.initializeModal = function() {
        $scope.colSizes = [ ]; 
        $scope.inputComment = "";
        $scope.loadRide();
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

    $scope.loadRide = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/rides/search?id=' + rideId
        }).success(function(data, status, headers, config) {
            if (data.length > 0) {
                $scope.ride = data[0];
                $scope.loadComments();
                $scope.loadPassengers();
                $scope.calculateColSizes();
                $scope.ride.drop_offs_html = $scope.templateListDropOffsForPopover($scope.ride.drop_offs);
            } else {
                toaster.pop('error', 'Error: ' + status, 'Ride not found');
                $modalInstance.close();
            }
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve ride');
            console.log(data);
        });
    };

    $scope.calculateColSizes = function() {
        var colSizes = [];
        var capacity = $scope.ride.capacity;
        if      ( capacity == 1 ) { colSizes = [12]; } 
        else if ( capacity == 2 ) { colSizes = [6,6]; } 
        else if ( capacity == 3 ) { colSizes = [4,4,4]; } 
        else if ( capacity == 4 ) { colSizes = [3,3,3,3]; } 
        else if ( capacity == 5 ) { colSizes = [4,4,4,6,6]; } 
        else if ( capacity == 6 ) { colSizes = [4,4,4,4,4,4]; } 
        else if ( capacity == 7 ) { colSizes = [3,3,3,3,4,4,4]; }
        else { for(var i=0; i < capacity; i++) colSizes[i] = 2; }
        $scope.colSizes = colSizes;
        return;        
    };

    $scope.loadPassengers = function() {
        $scope.loading = true;
        $http({
            'method': 'GET',
            'url': '/api/v2/user_rides?ride_id=' + $scope.ride.id
        }).success(function(data, status, headers, config) {
            $scope.ride.passengers = data;
            if (data.length > 0) {
                for (var i=0; i<data.length; i++) {
                    $scope.loadUserForObject(i, 'passengers');
                }
            } else {
                $scope.loading = false;
            }
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve passengers');
            $scope.loading = false;
            console.log(data);
        });
    };

    
    $scope.loadComments = function() {
        $scope.loading = true;
        $http({
            'method': 'GET',
            'url': '/api/v2/comments?ride_id=' + $scope.ride.id
        }).success(function(data, status, headers, config) {
            $scope.ride.comments = data;
            if (data.length > 0) {
                for (var i=0; i<data.length; i++) {
                    $scope.ride.comments[i].comment = $sce.trustAsHtml($scope.ride.comments[i].comment);
                    $scope.loadUserForObject(i, 'comments');
                }
            } else {
                $scope.loading = false;
            }
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve comments');
            $scope.loading = false;
            console.log(data);
        });
    };

    $scope.loadUserForObject = function(key, type) {
      $http({
            'method': 'GET',
            'url': '/api/v2/users?id=' + $scope.ride[type][key].user_id
        }).success(function(data, status, headers, config) {
            if (data.length > 0) {
                $scope.ride[type][key].userObject = data[0];
            }
            $scope.loading = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve users');
            $scope.loading = false;
            console.log(data);
        });
    };

    $scope.submitComment = function (inputComment) {
        $scope.loading = true;

        if (inputComment.length < 15) {
            toaster.pop('error', 'Error', 'Comments should contain a little more text');
            $scope.loading = false;
            return;
        }

        $http({
            'method': 'POST',
            'url': '/api/v2/comments',
            'data': {
                rideID : $scope.ride.id,
                comment : inputComment
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

    $scope.handlePayment = function (event) {
        var stripeHandler = StripeCheckout.configure({
            key: $wheelzo.stripePublicKey,
            image: $filter('fbImage')($scope.ride.driver_facebook_id),
            token: function(token) {
                // You can access the token ID with `token.id`
                $scope.executePayment($scope.ride.id, token);
            }
        });

        stripeHandler.open({
            name: 'Reserve Seat',
            image: $filter('fbImage')($scope.ride.driver_facebook_id),
            description : 'Make a payment to ' + $scope.ride.driver_name,
            amount : parseFloat($scope.ride.price) * 100,
            currency : 'CAD',
            allowRememberMe : false
        });

        event.preventDefault();
    }

    $scope.executePayment = function (rideId, stripeToken) {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/v2/user_rides',
            'data': {
                "rideID" : rideId,
                "stripeToken" : stripeToken.id,
                "receiptEmail" : stripeToken.email
            }
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.initializeModal();
            $scope.loading = false;
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    }

    $scope.deleteRide = function() {
        swal({
            title: "Confirm deleting this ride?",
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
                'url': '/api/v2/rides/index/' + $scope.ride.id
            }).success(function(data, status, headers, config) {
                toaster.pop('success', 'Success: ' + status, data.message);
                setTimeout(function() {
                    window.location.href = '/';
                }, 1500);
            }).error(function(data, status, headers, config) {
                toaster.pop('error', 'Error: ' + status, data.message);
                $scope.loading = false;
            });
        });
    };

    $scope.copyShowMessage = function() {
        toaster.pop('success', 'Success', "Successfully copied this ride");
    };

    $scope.copyFallback = function(copy) {
        window.prompt('Copy the link below to share', copy);
    };

    $scope.templateListDropOffsForPopover = function(dropOffs) {
        var html = "<div>";
        for (var i=0; i<dropOffs.length; i++) {
            html += '   <p>';
            html += '       <i class="fa fa-flag"></i> ' + dropOffs[i]; 
            html += '   </p>';
        };
        html += "</div>";
        return $sce.trustAsHtml(html);
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
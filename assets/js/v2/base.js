var $core = { } ;

var app = angular.module(
    'myApp', 
    ['ui.bootstrap', 'ngQuickDate', 'ngAnimate', 'ngtimeago', 'linkify', 'ngClipboard', 'toaster'], 
    function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    }
).config(['ngClipProvider', function(ngClipProvider) {
    ngClipProvider.setPath("/assets/vendor/v2/ng-clipboard/ZeroClipboard.swf");
}]);

app.controller('myController', function ($scope, $sce, $http, $filter, $modal, toaster) {
    angular.element(document).ready(function() {
        try {
            $core.extensionController($scope, $sce, $http, $filter, $modal, toaster);
        } catch (e) {
            console.log("Note: Javascript extension undefined...");
        }
    });

    $scope.loadSession = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/session'
        }).success(function(data, status, headers, config) {
            $scope.session = data;
            if (data.user_id > 0) {
                $scope.loadSessionUser();
            }
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + 'Could not retrieve session');
            $scope.session.active = false;
            console.log(data);
        });
    };

    $scope.loadSessionUser = function() {
        $http({
            'method': 'GET',
            'url': '/api/v2/users/me'
        }).success(function(data, status, headers, config) {
            if (data.length < 1) {
                toaster.pop('error', 'Error: ' + status, 'Session user object not returned despite success');
                return;
            }
            $scope.session.user = data[0];
        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, 'Could not retrieve session user');
            console.log(data);
        });
    };

    $scope.isActive = function() {
        if ($scope.session.user_id > 0) {
            return true;
        }
        return false;
    };


    $scope.refreshCurrentPage = function() {
        setTimeout(function() {
            location.reload();
        }, 1500);
    };

    $scope.refreshHomePage = function() {
        setTimeout(function() {
            window.location.href = '/';
        }, 1500);
    };

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

    $scope.openModal = function(modalTitle, modalSize, params) {
        var modalParams = { } ;

        if (typeof $scope.initialize == 'function') {
            modalParams.initialize = function() { return $scope.initialize; }
        }
        
        if (typeof params == 'object') {
            for (var key in params) {
                modalParams[params[key]] = function() { return $scope[params[key]]; }
            }
        }

        var modalInstance = $modal.open({
            templateUrl: modalTitle + '.html',
            controller: modalTitle + 'ModalController',
            size: modalSize,
            resolve: modalParams
        });
    };

    $scope.session = { };
    $scope.loadSession();

    if ($wheelzo.autoQueryRide) {
        $scope.openRideModal($wheelzo.autoQueryRide);
    } else if ($wheelzo.autoQueryUser) {
        $scope.openReviewModal($wheelzo.autoQueryUser);
    }

}).filter('mysqlDateToIso', function() {
    return function(badTime) {
        var components = badTime.split(' ');
        var dateComponent = components[0].split('-');
        var timeComponent = components[1].split(':')
        var goodDate = new Date(
            dateComponent[0],
            dateComponent[1] - 1,
            dateComponent[2],
            timeComponent[0],
            timeComponent[1],
            timeComponent[2]
        );
        return goodDate;
    };
}).filter('fbProfile', function() {
    return function(facebookId) {
        return '//facebook.com/' + facebookId ;
    };
}).filter('fbImage', function() {
    return function(facebookId) {
        return '//graph.facebook.com/'+facebookId+'/picture?width=200&height=200';
    };
}).filter('fbImageSquare', function() {
    return function(facebookId) {
        return '//graph.facebook.com/'+facebookId+'/picture?type=square';        
    };
}).filter('shortenString', function() {
    return function(subject, size) {
        if ( subject.length > size ) {
            return subject.substring(0, size-3) + '...';
        } else {
            return subject;
        }
    };
}).directive('validNumber', function() {
    return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            if(!ngModelCtrl) {
                return; 
            }

            ngModelCtrl.$parsers.push(function(val) {
                var clean = val.replace( /[^0-9]+/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });

            element.bind('keypress', function(event) {
                if(event.keyCode === 32) {
                    event.preventDefault();
                }
            });
        }
    };

}).directive('validDecimal', function() {
    return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            if(!ngModelCtrl) {
                return; 
            }
            ngModelCtrl.$parsers.push(function(val) {
                if (angular.isUndefined(val)) {
                    var val = '';
                }
                var clean = val.replace(/[^0-9\.]/g, '');
                var decimalCheck = clean.split('.');
                if(!angular.isUndefined(decimalCheck[1])) {
                    decimalCheck[1] = decimalCheck[1].slice(0,2);
                    clean =decimalCheck[0] + '.' + decimalCheck[1];
                }
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });
            element.bind('keypress', function(event) {
            if(event.keyCode === 32) {
                event.preventDefault();
                }
            });
        }
    };
}).directive('validStripe', function() {
    return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            if(!ngModelCtrl) {
                return; 
            }

            ngModelCtrl.$parsers.push(function(val) {
                var clean = val.replace( /[^0-9| |\/]+/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });

            element.bind('keypress', function(event) {
                if(event.keyCode === 32) {
                    event.preventDefault();
                }
            });
        }
    };

}).directive('validNumberPhone', function() {
    return {
        require: '?ngModel',
        link: function(scope, element, attrs, ngModelCtrl) {
            if(!ngModelCtrl) {
                return; 
            }

            ngModelCtrl.$parsers.push(function(val) {
                var clean = val.replace( /[^0-9|-]+/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });

            element.bind('keypress', function(event) {
                if(event.keyCode === 32) {
                    event.preventDefault();
                }
            });
        }
    };
    
});


/************************************************
 * Use this to add common functionality to modals
 ************************************************/
$core.extensionModal = function($scope, $modalInstance, $http, toaster) {
    $scope.cancel = function () {
        $modalInstance.close();
    };
};

/************************************************
 * General helpers to make life easier
 ************************************************/
String.prototype.contains = function(it) { 
    return this.indexOf(it) != -1; 
};

function compareByStart(a, b) {
    if (a.start < b.start)
        return -1;
    if (a.start > b.start)
        return 1;
    return 0;
}


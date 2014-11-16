var app = angular.module('myApp', ['ui.bootstrap', 'ngQuickDate', 'toaster']);

app.config(function(ngQuickDateDefaultsProvider) {
    // Configure with icons from font-awesome
    return ngQuickDateDefaultsProvider.set({
        closeButtonHtml: " <i class='fa fa-times'></i> ",
        buttonIconHtml: " <i class='fa fa-calendar'></i> ",
        nextLinkHtml: " <i class='fa fa-chevron-right'></i> ",
        prevLinkHtml: " <i class='fa fa-chevron-left'></i> ",
    });
}).controller('myController', function( $scope, $sce, $http, $filter, toaster ) {
    $scope.retrievePosts = function( accessToken ) {
        $scope.loading = true;
        $http({
            'method': 'GET',
            'url': '/api/tools/facebook_import/fetch_messages?token=' + accessToken
        }).success(function(data, status, headers, config) {
            if ( data.message ) {
                toaster.pop('success', 'Success: ' + status, data.message);
            } else {
                toaster.pop('success', 'Success: ' + status, "Retrieved posts. Sort away!");
                console.log(data);
                $scope.postings = data;
            }
            $scope.loading = false;

        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.forgetRide = function(posting, key) {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/tools/facebook_import/forget_ride',
            'data': {
                'posting': posting
            }
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            $scope.postings.splice(key, 1);
            $scope.loading = false;

        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.importRide = function(posting, key) {
        $scope.loading = true;
        $http({
            'method': 'POST',
            'url': '/api/tools/facebook_import/import_ride',
            'data': {
                'posting': posting
            }
        }).success(function(data, status, headers, config) {
            toaster.pop('success', 'Success: ' + status, data.message);
            console.log(data);
            $scope.postings.splice(key, 1);
            $scope.loading = false;

        }).error(function(data, status, headers, config) {
            toaster.pop('error', 'Error: ' + status, data.message);
            $scope.loading = false;
        });
    };

    $scope.defaultSuggestedPlaces = defaultSuggestedPlaces;

    $scope.onlyDatesInFutureDateFilter = function (date) {
        var currentDate = new Date();
        return date >= currentDate;
    };

    $scope.Date = function(arg) {
        if ( arg ) {
            return new Date( arg );
        } else {
            return new Date();
        }
    };

}).filter('dateToISO', function() {
  return function(input) {
    return new Date(input).toISOString();
  };
}).filter('badDateToISO', function() {
  return function(badTime) {
    var goodTime = badTime.replace(/(.+) (.+)/, "$1T$2Z");
    return goodTime;
  };
}).filter('facebookImage', function() {
    return function(facebookId) {
        return '//graph.facebook.com/' + facebookId + '/picture?width=40&height=40';
    };
}).filter('facebookPostLink', function() {
    return function(facebookPostId) {
        return '//facebook.com/' + facebookPostId;
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
});
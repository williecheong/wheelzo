var app = angular.module('myApp', ['ui.bootstrap', 'ngQuickDate', 'toaster']);

app.controller('myController', function( $scope, $sce, $http, $filter, toaster ) {
    
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

}).filter('mysqlToISO', function() {
  return function(input) {
    var date = new Date();  
    var parts = String(input).split(/[- :]/);  
    date.setFullYear(parts[0]);  
    date.setMonth(parts[1] - 1);  
    date.setDate(parts[2]);  
    date.setHours(parts[3]);  
    date.setMinutes(parts[4]);  
    date.setSeconds(parts[5]);  
    date.setMilliseconds(0);      
    return date.toISOString();  
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
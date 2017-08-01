function currentDate() {
    const date = new Date();
    const year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    month = (month > 10) ? month : '0' + month;
    day = (day > 10) ? day : '0' + day;
    return year + '-' + month + '-' + day;
}
var eventToronto = angular.module('eventToronto', []);

eventToronto.service('eventService', function($http) {
    this.getEvents = function(date, callback) {
        $http({
            method: 'GET',
            url: 'EventController.php?action=getEvents&date=' + date,
        }).then(function(res) {
            callback(res);
        });
    },
    this.saveEvent = function(eventId, callback) {
        $http({
            method: 'POST',
            url: 'EventController.php?action=saveEvent',
            data: $.param({'eventId': eventId}), //Need to find a better way
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then(function(res) {
            callback(res);
        });
    }
});

eventToronto.directive('datePicker', function(eventService) {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel) {
            $(function() {
                element.datepicker({
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(dateText, inst) {
                        scope.$apply(function() {
                            scope.date = dateText;
                            eventService.getEvents(dateText, function(res) {
                                scope.events = res.data;
                            });
                        });
                    }
                });
            });
        }
    }
});

eventToronto.filter('isFree', function() {
    return function(events, free) {
        if (free == true) {
            return events.filter(function(event) {
                return event.freeEvent === 'Yes';
            });
        } else {
            return events;
        }
    };
});

eventToronto.controller('listController', function($scope, eventService) {
    $scope.name = 'Events in Toronto';
    $scope.date = currentDate();
    $scope.free = false;
    $scope.events = [];
    eventService.getEvents(currentDate(), function(res) {
        $scope.events = res.data;
    });
    
    $scope.saveEvent = function(event) {
        eventService.saveEvent(event.id, function(res) {
            if (res.data.status == 'delete') {
                event.user_id = null;
            } else {
                event.user_id = res.data.user_id;
            }
        });
    };
});
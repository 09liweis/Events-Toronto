function currentDate() {
    const date = new Date();
    const year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    month = (month > 10) ? month : '0' + month;
    day = (day > 10) ? day : '0' + day;
    return year + '-' + month + '-' + day;
}
var eventToronto = angular.module('eventToronto', ['ngRoute']);

eventToronto.config(function($routeProvider) {
    $routeProvider
    .when('/', {
        templateUrl: '../pages/home.html',
        controller: 'listController'
    })
    .when('/date/:date', {
        templateUrl: '../pages/home.html',
        controller: 'listController'
    })
    .when('/event/:id', {
        templateUrl: '../pages/detail.html',
        controller: 'detailController'
    })
    .when('/calendar', {
        templateUrl: '../pages/calendar.html',
        controller: 'calendarController'
    });
});

eventToronto.service('eventService', function($http) {
    this.getEvents = function(date, callback) {
        $http({
            method: 'GET',
            url: 'EventController.php?action=getEvents&date=' + date,
        }).then(function(res) {
            callback(res);
        });
    },
    this.getEvent = function(id, callback) {
        $http({
            method: 'GET',
            url: 'EventController.php?action=getEvent&eventId=' + id,
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

eventToronto.directive('datePicker', function($route, eventService) {
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
    };
});

eventToronto.directive('calendar', function(eventService) {
    return {
        link: function(scope, element, attrs, ngModel) {
            $(function() {
                element.fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,basicWeek,basicDay'
                    },
                    navLinks: true,
                    editable: true,
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

eventToronto.filter('html', function($sce) {
    return function(input) {
        return $sce.trustAsHtml(input);
    };
});

eventToronto.controller('listController', function($scope, $routeParams, eventService) {
    $scope.name = 'Events on';
    $scope.date = currentDate();
    $scope.free = false;
    $scope.events = [];
    eventService.getEvents(currentDate(), function(res) {
        $scope.events = res.data;
    });
    
    $scope.saveEvent = function(event) {
        eventService.saveEvent(event.id, function(res) {
            if (res.data.code == 400) {
                Materialize.toast(res.data.msg, 3000);
            } else {
                if (res.data.status == 'delete') {
                    event.user_id = null;
                } else {
                    event.user_id = res.data.user_id;
                }
            }
        });
    };
});

eventToronto.controller('detailController', function($scope, $routeParams, eventService) {
    $scope.event = null;
    eventService.getEvent($routeParams.id, function(res) {
        if (res.status == 200) {
            $scope.event = res.data;
        }
    });
    $scope.saveEvent = function(event) {
        eventService.saveEvent(event.id, function(res) {
            if (res.data.code == 400) {
                Materialize.toast(res.data.msg, 3000);
            } else {
                if (res.data.status == 'delete') {
                    event.user_id = null;
                } else {
                    event.user_id = res.data.user_id;
                }
            }
        });
    };
});

eventToronto.controller('calendarController', function($scope, eventService) {
    $scope.title = 'Calendar';
});
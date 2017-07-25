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

eventToronto.controller('listController', function($scope, eventService) {
    $scope.name = 'Events in Toronto';
    $scope.date = currentDate();
    $scope.events = [];
    // $http.get('EventController.php?action=getEvents&date=' + currentDate()).then(function(res) {
    //     if (res.status == 200) {
    //         $scope.events = res.data;   
    //     } else {
    //         //todo
    //     }
    // });
    eventService.getEvents(currentDate(), function(res) {
        $scope.events = res.data;
    })
});
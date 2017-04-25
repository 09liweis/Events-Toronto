var events;
var activeEvents;
var detailMap;
var detailMarker;
var directionsDisplay = new google.maps.DirectionsRenderer();
var directionsService = new google.maps.DirectionsService();

function pageIneraction() {
    $('.modal').modal();
    
    $('#datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText, inst) { 
            $('#date').val(dateText);
            renderAll();
        }
    });
    
    $('#free').on('click', function() {
        renderAll();
    });
    
    $('#long-run').on('click', function() {
        renderAll();
    });
    
    
    $('#events').on('click', '.save', function(e) {
        e.preventDefault();
        var _this = $(this);
        var eventId = _this.data('event');
        $.ajax({
            url: 'EventController.php?action=saveEvent',
            method: 'POST',
            data: {eventId: eventId},
            success: function(res) {
                if (res.code == 200) {
                    if (res.status == 'delete') {
                        if (user.length == 1) {
                            _this.parent().parent().remove();
                        } else {
                            _this.removeClass('mdl-button--accent');
                            _this.addClass(('mdl-button--colored'));
                            _this.text('Save');
                        }
                    } else {
                        _this.removeClass('mdl-button--colored');
                        _this.addClass(('mdl-button--accent'));
                        _this.text('Saved');
                    }   
                } else {
                    Materialize.toast(res.msg, 3000);
                }
            }
        });
    });
    
    $('#events').on('click', '.detail', function(e) {
        e.preventDefault();
        var eventId = $(this).data('event');
        getEvent(eventId);
    });
}

function getEvents(action) {
    $.ajax({
        url: 'EventController.php?action=' + action,
        method: 'GET',
        success: function(results) {
            events = results;
            renderAll();
        }
    });
}

function getEvent(eventId) {
    $.ajax({
        url: 'EventController.php?action=getEvent',
        data: {eventId: eventId},
        method: 'GET',
        success: function(res) {
            renderEvent(res);
            $('#detail').modal('open');
        }
    });
}

function renderAll() {
    activeEvents = filterEvents(events);
    renderEvents(activeEvents);
}

function filterEvents(events) {
    activeEvents = events.filter(function(e) {
        var free = $('#free').is(':checked');
        if (free == true) {
            return e.freeEvent == 'Yes';
        } else {
            return true;
        }
    }).filter(function(e) {
        var date = $('#date').val();
        if (date != '') {
            return e.startDate == date;
        } else {
            return true;
        }
    }).filter(function(e) {
        var longRun = $('#long-run').is(':checked');
        if (longRun == true) {
            var endDate = new Date(e.endDate);
            var startDate = new Date(e.startDate);
            var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            return diffDays < 30;
        } else {
            return true;
        }
    });
    
    return activeEvents;
}


function renderEvents(events) {
    var eventsHTML = '<div class="events-wrapper">';
    events.map(function(event) {
        var image = (event.thumbImage == '') ? '/images/events.jpg' : event.thumbImage;
        eventsHTML +=   '<div class="event row">' +
                            '<div class="col s12 m4">' +
                                '<img class="valign-wrapper event__thumbImage" src="' + image + '" />' +
                            '</div>' +
                            '<div class="col s12 m8">' +
                                '<h5 class="">' + event.name + '</h5>' +
                                '<div>Free Event: ' + event.freeEvent + '</div>' +
                                '<div>' + event.startDate + ' to ' + event.endDate + '</div>' +
                                '<a class="waves-effect waves-light btn save" href="#" data-event="' + event.id + '">' + (event.user_id == null ? 'Save' : 'Saved') + '</a>' +
                                '<a class="right waves-effect waves-light btn detail" href="#" data-event="' + event.id + '">Detail</a>' +
                            '</div>' +
                        '</div>';
    });
    eventsHTML += '</div>';
    $('#events').html(eventsHTML);
}

function renderEvent(event) {
    $('#image img').attr('src', event.image);
    $('#event_name').text(event.name);
    $('#event_description').text(event.description);
    renderDetailMap(event);
}

function renderDetailMap(event) {
    
    directionsDisplay.setMap(null);
    if (detailMarker != null) {
        detailMarker.setMap(null);
    }
    
    var eventLocation = {lat: parseFloat(event.lat), lng: parseFloat(event.lng)};
    if (detailMap == null) {
        detailMap = new google.maps.Map(document.getElementById('detailMap'), {
            zoom: 17,
            options: {
                scrollwheel: false,
            }
        });    
    }
    
    detailMap.setCenter(eventLocation);
    
    detailMarker = new google.maps.Marker({
        position: eventLocation,
        map: detailMap,
        animation: google.maps.Animation.DROP,
        icon: 'http://m.mainstreethub.com/images/map-marker-bw.png',
    });
    
    directionsDisplay.setMap(detailMap);
    
    var request = {
        origin: currentLocation,
        destination: detailMarker.position,
        travelMode: 'DRIVING'
    };
    
    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsDisplay.setDirections(result);
        } else {
            alert('No route can be found');
        }
    });
}
var events;
var activeEvents;
var map;
var bounds;
var markers = [];
$(document).ready(function() {
    
    $('#map-tab').on('click', function() {
        renderMap();
    });
    
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
    
    
    $('#events').on('click', '.save', function(e) {
        e.preventDefault();
        var _this = $(this);
        var eventId = _this.data('event');
        $.ajax({
            url: 'EventController.php?action=saveEvent',
            method: 'POST',
            data: {eventId: eventId},
            success: function(res) {
                if (res.status == 'delete') {
                    _this.text('Save');
                } else {
                    _this.text('Saved');
                }
            }
        });
    });
    
    
    $.ajax({
        url: 'EventController.php?action=getEvents',
        method: 'GET',
        success: function(results) {
            events = results;
            renderAll();
        }
    });
});

function renderAll() {
    activeEvents = filterEvents(events);
    renderEvents(activeEvents);
    renderMarkers();
}

function filterEvents(events) {
    activeEvents = events.filter(function(e) {
        var free = $(this).is(":checked");
        if (free == true) {
            return e.freeEvent == 'Yes';
        } else {
            return true;
        }
    }).filter(function(e) {
        var date = $('#date').val();
        if (date != '') {
            return e.startDate <= date && e.endDate >= date;
        } else {
            return true;
        }
    });
    
    return activeEvents;
}


function renderEvents(events) {
    var eventsHTML = '';
    events.map(function(event) {
        eventsHTML +=   '<div class="mdl-cell mdl-cell--3-col mdl-cell--4-col-tablet mdl-cell--12-col-phone">' +
                            //'<div class="event__image" style="background-image: url(' + event.thumbImage + ')" ></div>' +
                            '<div class="">' +
                                '<img src="' + event.thumbImage + '" />' +
                                '<h4 class="">' + event.name + '</h4>' +
                                '<a class="save" href="#" data-event="' + event.id + '">' + (event.user_id == null ? 'Save' : 'Saved') + '</a>' +
                            '</div>' +
                        '</div>';
    });
    $('#events').html(eventsHTML);
}


function renderMap() {
    if (map == null) {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            options: {
                scrollwheel: false,
            }
        });
        
        bounds = new google.maps.LatLngBounds();
        renderMarkers();
    }
}

function renderMarkers() {
    
    if (map != null) {
        markers.map(function(m) {
            m.setMap(null);
        });
        
        markers = [];
        
        activeEvents.map(function(e) {
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(e.lat), lng: parseFloat(e.lng)},
                map: map,
                animation: google.maps.Animation.DROP,
                title: e.name,
            });
            
            markers.push(marker);
            bounds.extend(marker.position);
        });
        
        map.fitBounds(bounds);
    }
}
var events;
var activeEvents;
var map;
var bounds;
var markers = [];
var detailMap;
var detailMarker;

$(document).ready(function() {
    $('.modal').modal();
    
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
                    _this.removeClass('mdl-button--accent');
                    _this.addClass(('mdl-button--colored'));
                    _this.text('Save');
                } else {
                    _this.removeClass('mdl-button--colored');
                    _this.addClass(('mdl-button--accent'));
                    _this.text('Saved');
                }
            }
        });
    });
    
    $('#events').on('click', '.detail', function(e) {
        e.preventDefault();
        var eventId = $(this).data('event');
        getEvent(eventId);
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

function getEvent(eventId) {
    $.ajax({
        url: 'EventController.php?action=getEvent',
        data: {eventId: eventId},
        method: 'GET',
        success: function(res) {
            console.log(res);
            renderEvent(res);
            $('#detail').modal('open');
        }
    });
}

function renderAll() {
    activeEvents = filterEvents(events);
    renderEvents(activeEvents);
    renderMarkers(activeEvents);
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
        var image = (event.thumbImage == '') ? '/images/events.jpg' : event.thumbImage;
        eventsHTML +=   '<div class="col s12 m4 l3">' +
                            '<div class="">' +
                                '<img class="event__thumbImage" src="' + image + '" />' +
                                '<h4 class="">' + event.name + '</h4>' +
                                '<a class="waves-effect waves-light btn save" href="#" data-event="' + event.id + '">' + (event.user_id == null ? 'Save' : 'Saved') + '</a>' +
                                '<a class="waves-effect waves-light btn detail" href="#" data-event="' + event.id + '">Detail</a>' +
                            '</div>' +
                        '</div>';
    });
    $('#events').html(eventsHTML);
}

function renderEvent(event) {
    $('#image img').attr('src', event.image);
    $('#event_name').text(event.name);
    $('#event_description').text(event.description);
    renderDetailMap(event);
}


function renderMap() {
    if (map == null) {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            options: {
                scrollwheel: false,
            }
        });
    }
    bounds = null;
    bounds = new google.maps.LatLngBounds();
    renderMarkers(activeEvents);
}

function renderDetailMap(event) {
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
    });
}

function renderMarkers(events) {
    
    if (map != null) {
        
        markers.map(function(m) {
            m.setMap(null);
        });
        
        markers = [];
        events.map(function(e) {
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(e.lat), lng: parseFloat(e.lng)},
                map: map,
                animation: google.maps.Animation.DROP,
                title: e.name,
                id: e.id
            });
            
            marker.addListener('click', function() {
                var eventId = this.id;
                getEvent(eventId);
            });
            
            markers.push(marker);
            bounds.extend(marker.position);
        });
        
        map.fitBounds(bounds);
    }
}
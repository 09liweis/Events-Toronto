Vue.use(VueMaterial);

var app = new Vue({
    el: '#app',
    data: {
        view: 'list',
        events: [],
        //activeEvents: [],
        dates: [],
        markers: [],
        selectedDate: "",
        property: null,
        value: null,
        free: false,
        map: null,
    },
    mounted() {
        this.getEvents();
        this.getDates();
    },
    
    computed: {
        activeEvents() {
            return this.events
            .filter(function(event) {
                if (this.free === true) {
                    return event.freeEvent == 'Yes';
                } else {
                    return true;
                }
            }.bind(this))
            .filter(function(event) {
                if (this.selectedDate != "") {
                    return event.startDate == this.selectedDate;
                } else {
                    return true;
                }
            }.bind(this));
        }
    },
    watch: {
        view: function(newView) {
            if (newView == 'map') {
                var _this = this;
                setTimeout(function() {
                    _this.renderMap();
                }, 500);
            }
        }
    },
    methods:  {
        changeView(view) {
            this.view = view;
        },
        renderMap() {
            if (this.map == null) {
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    options: {
                        scrollwheel: false,
                    }
                });
                var bounds = new google.maps.LatLngBounds();
                this.events.map(function(event) {
                    var marker = new google.maps.Marker({
                        position: {lat: parseFloat(event.lat), lng: parseFloat(event.lng)},
                        map: this.map,
                        title: event.name,
                        animation: google.maps.Animation.DROP,
                    });
                    this.markers.push(marker);
                    bounds.extend(marker.position);
                }.bind(this));
                this.map.fitBounds(bounds);
            }
        },
        getDates() {
            this.$http.get('EventController.php?action=getDates').then(function(dates) {
                this.dates = dates.data;
            });
        },
        getEvents() {
            this.$http.get('EventController.php?action=getEvents').then(function(events) {
                this.events = events.data;
                this.activeEvents = events.data;
            });
        },
        
        displayEvent(event) {
            console.log(event);
        },
        
        filterFree(e) {
            this.free = e;
        },
    }
});
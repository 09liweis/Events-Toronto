<template>
    <div id="events">
        <h1>Total {{this.$store.state.events.length}} Events on {{date}}</h1>
        
        <div class="row justify-content-center">
            <div class="events__left col-md-4">
                <div class="form-group">
                    <flatPickr class="form-control" v-model="date" @on-change="changeDate" :config="config"></flatPickr>
                </div>
                <gmap-map ref="listMap" class="map" :center="center" :zoom="10">
                    <GmapMarker
                        :key="e.id"
                        v-for="(e, index) in this.$store.state.events"
                        :position="getPostion(e)"
                        :clickable="true"
                        @click="viewEvent(e.id)"
                    />
                </gmap-map>
            </div>
            <div class="col-md-8">
                <div class="event row" v-for="event in this.$store.state.events" v-on:click="viewEvent(event.id)" v-bind:class="{ selected: selected == event.id}">
                    <figure class="col-md-3 event__figure">
                        <img class="event__thumbnail" :src="event.thumbnail" :alt="event.name">
                    </figure>
                    <div class="col-md-9 event__info">
                        <!--<router-link :to="{ name: 'detail', params: { id: event.id }}"><h5 class="card-title" v-html="event.name"></h5></router-link>-->
                        <h5 class="card-title" v-html="event.name"></h5>
                        <div>{{event.start_date}}</div>
                        <div>{{event.address}}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal__container" v-if="view == 'detail'">
            <div class="modal-bg" v-on:click="hideModal()"></div>
            <div class="modal-content">
                <EventDetail v-bind:id="eventId" />
            </div>
        </div>
        
    </div>
</template>

<script>
import axios from 'axios';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

import EventDetail from './EventDetail.vue';

export default {
    components: {
        flatPickr,
        EventDetail
    },
    data() {
        return {
            selected: null,
            view: 'list',
            eventId: '',
            center: { lat: 0, lng: 0 },
            date: '',
            config: {
                disableMobile: true
            }
        };
    },
    mounted() {
        this.geolocate();
        
        this.date = this.formatDate(new Date());
        if (typeof this.$route.query.date != 'undefined') {
            this.date = this.$route.query.date;
        }
        
        this.getEvents();
        
        const eventId = this.$route.query.eventId;
        if (typeof eventId != 'undefined') {
            this.viewEvent(eventId);
        }
        
        window.addEventListener('scroll', this.handleScroll);
        
    },
    methods: {
        handleScroll() {
            const scrollPositoin = window.scrollY;
            if (scrollPositoin > 200) {
                //Not sure if this will be use later
            }
        },
        geolocate: function() {
            window.navigator.geolocation.getCurrentPosition(position => {
                this.center = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
            });
        },
        getEvents() {
            axios.get('/api/events?date=' + this.date).then(res => {
                this.$store.commit('getEvents', res.data);
                this.fitBounds(res.data);
            });
        },
        getPostion(event) {
            return {lat: parseFloat(event.lat), lng: parseFloat(event.lng)};
        },
        fitBounds(events) {
            const bounds = new google.maps.LatLngBounds();
            for (let event of events) {
                bounds.extend(this.getPostion(event));
            }
            this.$refs.listMap.fitBounds(bounds);
        },
        changeDate(date) {
            const startDate = date[0];
            const formatDate = this.formatDate(startDate);
            if (this.date != formatDate) {
                this.date = formatDate;
                this.$router.push({ path: '/?date=' + this.date });
                this.getEvents();
            }
            
        },
        formatDate(date) {
            const year = date.getFullYear();
            const month = date.getMonth() > 9 ? date.getMonth() + 1 : '0' + (date.getMonth() + 1);
            const day = date.getDate() > 9 ? date.getDate() : '0' + date.getDate();
            return year + '-' + month + '-' + day;
        },
        viewEvent(id) {
            this.eventId = id;
            this.$router.push({ path: '/?date=' + this.date + '&eventId=' + this.eventId });
            this.view = 'detail';
        },
        hideModal() {
            this.view = 'list';
            this.$router.push({path: '/?date=' + this.date});
        }
    }
};
</script>
<style scoped>
.events__left {
    position: relative;
}
.map {
    position: sticky;
    top: 20px;
    width: 100%;
    height: 500px;
}
.vue-map {
    border-radius: 10px;
}
.modal__container {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
}
.modal-bg {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.7);
}
.modal-content {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    max-width: 768px;
    transform: translateY(-50%);
    margin: 0 auto;
}
</style>
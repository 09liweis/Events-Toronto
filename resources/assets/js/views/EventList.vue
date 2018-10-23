<template>
    <section id="events">
        <transition name="slide">
            <h1 class="events__title">Total {{events.length}} Events on <flatPickr class="form-control" v-model="date" @on-change="changeDate" :config="config"></flatPickr></h1>
        </transition>
        <gmap-map ref="listMap" class="map" :center="center" :zoom="10" :options="mapOptions" v-if="fullmap">
            <GmapMarker
                :key="e.id"
                v-for="(e, index) in events"
                :position="getPostion(e)"
                :clickable="true"
                @click="viewEvent(e.id)"
            />
        </gmap-map>
        <div class="row justify-content-center" v-if="!fullmap">
            <div class="events__left col-md-4">
                <transition-group name="slide">
                    <div key="toggle" class="fullmap" v-on:click="toggleMap()">Full Map</div>
                    <gmap-map key="map" ref="listMap" class="map" :center="center" :zoom="10" :options="mapOptions">
                        <GmapMarker
                            :key="e.id"
                            v-for="(e, index) in events"
                            :position="getPostion(e)"
                            :clickable="true"
                            @click="viewEvent(e.id)"
                        />
                    </gmap-map>
                </transition-group>
            </div>
            <div class="col-md-8">
                <transition-group name="slide">
                    <Event v-for="event in events" v-bind:event="event" :key="event.id" v-bind:viewEvent="viewEvent" v-bind:class="{ selected: eventId == event.id}" />
                </transition-group>
            </div>
        </div>
        
        <transition name="modal">
        <div class="modal__container" v-if="view == 'detail'">
            <div class="modal-bg" v-on:click="hideModal()"></div>
            <div class="modal-content">
                <EventDetail v-bind:id="eventId" />
            </div>
        </div>
        </transition>
        
    </section>
</template>

<script>
import axios from 'axios';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

import EventDetail from './EventDetail.vue';
import Categories from '../components/Categories.vue';
import Event from '../components/Event.vue';

export default {
    components: {
        flatPickr,
        EventDetail,
        Categories,
        Event,
    },
    data() {
        return {
            fullmap: false,
            view: 'list',
            eventId: '',
            center: { lat: 0, lng: 0 },
            date: '',
            config: {
                disableMobile: true
            },
            mapOptions: {
                disableDefaultUI : true
            }
        };
    },
    computed: {
        events() {
            return this.$store.state.events;
        }
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
        window.addEventListener('keyup', (event) => {
            if (event.key == 'Escape') {
                this.view = 'list';
            }
        });
        
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
                if (typeof google != 'undefined') {
                    this.fitBounds();
                }
            });
        },
        getPostion(event) {
            return {lat: parseFloat(event.lat), lng: parseFloat(event.lng)};
        },
        fitBounds() {
            const events = this.events;
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
            const month = date.getMonth() >= 9 ? date.getMonth() + 1 : '0' + (date.getMonth() + 1);
            const day = date.getDate() > 9 ? date.getDate() : '0' + date.getDate();
            return year + '-' + month + '-' + day;
        },
        viewEvent(id) {
            this.eventId = id;
            this.$router.push({ path: '/?date=' + this.date + '&eventId=' + this.eventId });
            this.view = 'detail';
        },
        toggleMap() {
            this.fullmap = !this.fullmap;
        },
        hideModal() {
            this.view = 'list';
            this.$router.push({path: '/?date=' + this.date});
        }
    }
};
</script>
<style scoped>
.events__title {
    margin-bottom: 30px;
}
.events__left {
    position: relative;
}
.fullmap {
    position: absolute;
    background-color: #007bff;
    padding: 10px;
    color: #ffffff;
    top: 10px;
    right: 20px;
    z-index: 1;
    cursor: pointer;
}
.map {
    position: sticky;
    top: 20px;
    width: 100%;
    height: 500px;
}
.map.full {
    position: fixed;
    width: 100%;
    height: 100%;
    margin-top: 141px;
    z-index: 2;
    left: 0;
    right: 0;
}
.vue-map {
    border-radius: 10px;
}
</style>
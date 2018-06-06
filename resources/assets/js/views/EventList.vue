<template>
    <div id="events">
        <h1>Total: {{list.length}} Events in Toronto</h1>
        
        <div class="row justify-content-center">
            <div class="col-md-4">
                <flatPickr v-model="date" @on-change="changeDate" :config="config"></flatPickr>
                <gmap-map class="map" :center="center"></gmap-map>
            </div>
            <div class="col-md-8">
                <div class="event row" v-for="event in list" v-on:click="viewEvent(event.id)">
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
            <div class="modal-bg"></div>
            <div class="modal-content">
                Hello World
            </div>
        </div>
        
    </div>
</template>

<script>
import axios from 'axios';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
export default {
    components: {
        flatPickr
    },
    data() {
        return {
            view: 'list',
            center: { lat: 45.508, lng: -73.587 },
            list: [],
            date: '',
            config: {
                disableMobile: true
            }
        };
    },
    mounted() {
        this.geolocate();
        
        this.date = this.formatDate(new Date());
        if (typeof this.$route.query.date == 'undefined') {
            // this.$router.push({ path: '/?date=' + this.date });
        } else {
            this.date = this.$route.query.date;
        }
        
        const eventId = this.$route.query.event_id;
        if (typeof eventId != 'undefined') {
            this.view = 'detail';   
        }
        
        this.getEvents();
    },
    methods: {
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
                this.list = res.data;
            });
        },
        changeDate(date) {
            const startDate = date[0];
            const formatDate = this.formatDate(startDate);
            this.date = formatDate;
            this.$router.push({ path: '/?date=' + this.date });
            this.getEvents();
            
        },
        formatDate(date) {
            const year = date.getFullYear();
            const month = date.getMonth() > 9 ? date.getMonth() + 1 : '0' + (date.getMonth() + 1);
            const day = date.getDate() > 9 ? date.getDate() : '0' + date.getDate();
            return year + '-' + month + '-' + day;
        },
        viewEvent(id) {
            
        }
    }
};
</script>
<style scoped>
.map {
    width: 100%;
    height: 100px;
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
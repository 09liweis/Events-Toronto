<template>
    <div id="detail" v-if="event">
        <div class="row" v-if="view == 'detail'">
            <div class="col-md-12">
                <h1 class="event__title" v-html="event.name"></h1>
                <div class="event__address" v-on:click="changeView()"><i class="fas fa-map-marker"></i><span v-html="event.location"></span> {{event.address}}</div>
            </div>
            <div class="col-md-4">
                <img :src="event.image" :alt="event.name" class="img-fluid" />
                <div>{{event.start_date}} {{event.start_date == event.end_date ? '' : ' - ' + event.end_date}}</div>
                <div>Free: {{event.free}}</div>
            </div>
            <div class="col-md-8">
                <p v-html="event.description"></p>
                <div class="event__categories"><span class="event__category" v-for="c in event.categories">{{c.name}}</span></div>
                <a v-if="event.website" target="_blank" :href="event.website"><i class="fas fa-link"></i>{{event.website}}</a>
            </div>
        </div>
        <div class="event__map" v-if="view == 'map'">
            <i class="fas fa-times map__close" v-on:click="changeView()"></i>
            <gmap-map class="map" :center="position" :zoom="15">
                <GmapMarker
                    :position="position"
                    :clickable="true"
                    :draggable="true"
                    @click="center = position"
                />
            </gmap-map>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props: ['id'],
    data() {
        return {
            view: 'detail',
            event: null,
            position: null
        };
    },
    mounted() {
        // const id = this.$route.params.id;
        this.getDetail(this.id);
    },
    methods: {
        getDetail(id) {
            axios.get('/api/event/' + id).then(res => {
                this.event = res.data;
                this.position = { lat: parseFloat(this.event.lat), lng: parseFloat(this.event.lng) };
            });
        },
        changeView() {
            this.view = (this.view == 'detail') ? 'map' : 'detail';
        }
    }
};
</script>
<style scoped>
#detail {
    padding: 30px;
    position: relative;
}
.event__title {
    border-left: 5px solid #007bff;
    padding-left: 10px;
}
.event__address {
    cursor: pointer;
    margin-bottom: 20px;
    transition: all 0.3 ease;
}
.event__address:hover {
    opacity: 0.8;
}
.fa-map-marker {
    color: #007bff;
}
.event__map {
    height: 60vh;
    position: relative;
}
.map__close {
    position: absolute;
    top: -22px;
    right: -24px;
    cursor: pointer;
}
.map {
    height: 100%;
}
.event__category {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 10px;
  background-color: #007bff;
  color: #ffffff;
  margin: 0 10px 10px 0;
}
</style>
<template>
    <div id="detail" v-if="event">
        <div class="row event__info" v-if="view == 'detail'">
            <div class="col-md-12"><h1 v-html="event.name"></h1></div>
            <div class="col-md-4">
                <img :src="event.image" :alt="event.name" class="img-fluid" />
                <div>Free: {{event.free}}</div>
            </div>
            <div class="col-md-8">
                <p v-html="event.description"></p>
                <div>{{event.start_date}} - {{event.end_date}}</div>
                <div class="event__categories"><span class="event__category" v-for="c in event.categories">{{c.name}}</span></div>
                <div class="event__address" v-on:click="changeView()"><i class="fas fa-map-marker"></i> {{event.location}} {{event.address}}</div>
                <a v-if="event.website" target="_blank" :href="event.website"><i class="fas fa-link"></i>{{event.website}}</a>
            </div>
        </div>
        <div class="event__map" v-if="view == 'map'">
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
.event__address {
    cursor: pointer;
}
.event__map {
    width: 100%;
    height: 60vh;
}
.map {
    width: 100%;
    height: 100%;
}
.event__categories {
    margin-top: 20px;
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
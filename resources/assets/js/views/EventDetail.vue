<template>
    <div id="detail" class="row" v-if="event">
        <div class="col-md-4">
            <img :src="event.image" :alt="event.name" class="img-fluid" />
            <div class="event__categories"><span class="event__category" v-for="c in event.categories">{{c.name}}</span></div>
        </div>
        <div class="col-md-8">
            <h1 v-html="event.name"></h1>
            <p v-html="event.description"></p>
            <div>{{event.start_date}} - {{event.end_date}}</div>
            <div>Free: {{event.free}}</div>
            <div>Location: {{event.location}}</div>
            <div>Address: {{event.address}}</div>
            <a target="_blank" :href="event.website">{{event.website}}</a>
        </div>
        <div class="col-md-12">
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
        }
    }
};
</script>
<style type="sass" scoped>
#detail {
    padding: 30px;
}
.map {
    margin-top: 20px;
    width: 100%;
    height: 300px;
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
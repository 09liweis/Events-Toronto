<template>
    <div>
        <h1>Door Open 2018</h1>
        <gmap-map ref="listMap" class="map" :center="center" :zoom="10" >
            <GmapMarker
                :key="d.dot_documentID"
                v-for="(d, index) in doors"
                :position="getPostion(d)"
                :clickable="true"
            />
        </gmap-map>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            doors: [],
            center: { lat: 0, lng: 0 },
        };
    },
    mounted() {
        this.geolocate();
        axios.get('api/dooropen').then(res => {
            this.doors = res.data;
        });
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
        getPostion(door) {
            return {lat: parseFloat(door.dot_Address.dot_Latitude), lng: parseFloat(door.dot_Address.dot_Longitude)};
        },
    }
};
</script>
<style type="text/css" scoped>
.map {
    width: 100%;
    height: 50vh;
}
</style>
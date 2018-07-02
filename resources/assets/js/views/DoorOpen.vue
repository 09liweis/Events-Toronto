<template>
    <div>
        <h1>Door Open 2018</h1>
        <div class="" v-for="d in doors" v-if="view == 'list'">
            <h2>{{d.dot_buildingName}}</h2>
            <p>{{d.dot_ProgramGuideDescription[0]}}</p>
        </div>
        <gmap-map ref="listMap" class="map" :center="center" :zoom="10" v-if="view == 'map'" >
            <GmapMarker
                :key="d.dot_documentID"
                v-for="(d, index) in doors"
                :position="getPostion(d)"
                :clickable="true"
                :title="d.dot_buildingName"
                @click="viewDoor(d)"
            />
        </gmap-map>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            view: 'list',
            doors: [],
            center: { lat: 0, lng: 0 },
        };
    },
    mounted() {
        this.geolocate();
        axios.get('api/dooropen').then(res => {
            this.doors = res.data;
            this.fitBounds();
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
        fitBounds() {
            const doors = this.doors;
            const bounds = new google.maps.LatLngBounds();
            for (let door of doors) {
                bounds.extend(this.getPostion(door));
            }
            this.$refs.listMap.fitBounds(bounds);
        },
        viewDoor(d) {
            console.log(d.dot_buildingName);
        }
    }
};
</script>
<style type="text/css" scoped>
.map {
    width: 100%;
    height: 80vh;
}
</style>
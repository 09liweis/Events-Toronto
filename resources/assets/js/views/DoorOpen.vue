<template>
    <div>
        <h1>Door Open 2018</h1>
        <div class="" v-for="door in doors">
            <h2>{{door.dot_buildingName}}</h2>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            doors: []
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
    }
};
</script>
<template>
    <div>
        <h1 class="doors__title">Door Open 2018 - {{doors.length}} Door Open</h1>
        <div class="lds-ellipsis" v-if="doors.length == 0"><div></div><div></div><div></div><div></div></div>
        <transition-group name="slide" class="row" v-if="view == 'list'">
            <div class="door col-hd-2 col-md-3 col-sm-4" v-for="d in doors" :key="d.dot_documentID">
                <div class="card" v-on:click="viewDoor(d)">
                    <h3 class="door__title">{{d.dot_buildingName}}</h3>
                    <div class="address"><i class="fas fa-map-marker-alt"></i>{{d.dot_Address.dot_buildingAddress}}</div>
                    <p class="door__description">{{d.dot_ProgramGuideDescription[0]}}</p>
                </div>
            </div>
        </transition-group>
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
        <transition name="modal">
            <div class="modal__container" v-if="modal">
                <div class="modal-bg" v-on:click="hideModal()"></div>
                <div class="modal-content">
                    <div class="activedoor">
                        <h2>{{door.dot_buildingName}}</h2>
                        <p>{{door.dot_FullDescription[0]}}</p>
                        <p>{{door.dot_ProgramGuideDescription[0]}}</p>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            view: 'list',
            modal: false,
            doors: [],
            center: { lat: 0, lng: 0 },
            door: null
        };
    },
    mounted() {
        this.geolocate();
        axios.get('api/dooropen').then(res => {
            this.doors = res.data;
            // this.fitBounds();
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
        // fitBounds() {
        //     const doors = this.doors;
        //     const bounds = new google.maps.LatLngBounds();
        //     for (let door of doors) {
        //         bounds.extend(this.getPostion(door));
        //     }
        //     this.$refs.listMap.fitBounds(bounds);
        // },
        viewDoor(d) {
            this.modal = true;
            this.door = d;
            console.log(d);
        },
        hideModal() {
            this.modal = false;
        }
    }
};
</script>
<style type="text/css" scoped>
.doors__title {
    margin-bottom: 30px;
}
.door__description {
    margin: 0;
}
.map {
    width: 100%;
    height: 80vh;
}
.door {
    margin-bottom: 20px;
}
.card {
    border-top: 2px solid #007bff;
    padding: 10px;
    cursor: pointer;
}
.activedoor {
    padding: 20px;
}
</style>
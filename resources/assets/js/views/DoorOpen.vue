<template>
    <div>
        <h1 class="doors__title">Door Open 2018 - {{doors.length}} Door Open</h1>
        <div class="lds-ellipsis" v-if="doors.length == 0"><div></div><div></div><div></div><div></div></div>
        <transition-group name="slide" class="row" v-if="view == 'list'">
            <div class="door col-hd-2 col-md-3 col-sm-4" v-for="d in doors" :key="d.dot_documentID" v-on:click="viewDoor(d)">
                <h3 class="door__title">{{d.dot_buildingName}}</h3>
                <div class="address"><i class="fas fa-map-marker-alt"></i>{{d.dot_Address.dot_buildingAddress}}</div>
                <p>{{d.dot_ProgramGuideDescription[0]}}</p>
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
        },
        hideModal() {
            this.modal = false;
        }
    }
};
</script>
<style type="text/css" scoped>
.lds-ellipsis {
  display: inline-block;
  position: relative;
  width: 64px;
  height: 64px;
}
.lds-ellipsis div {
  position: absolute;
  top: 27px;
  width: 11px;
  height: 11px;
  border-radius: 50%;
  background: #cef;
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
  left: 6px;
  animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
  left: 6px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
  left: 26px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
  left: 45px;
  animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes lds-ellipsis3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes lds-ellipsis2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(19px, 0);
  }
}

.slide-enter-active, .slide-leave-active {
  transition: all 0.3s ease-out;
}

.slide-enter, .slide-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}

.modal-enter-active, .modal-leave-active {
  transition: opacity 0.25s ease-out;
}

.modal-enter, .modal-leave-to {
  opacity: 0;
}

.modal__container {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 2;
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

.doors__title {
    margin-bottom: 30px;
}
.map {
    width: 100%;
    height: 80vh;
}
.door {
    margin-bottom: 20px;
    cursor: pointer;
}
.door__title {
    border-top: 2px solid #007bff;
    padding-top: 10px;
}
</style>
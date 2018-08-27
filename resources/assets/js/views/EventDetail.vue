<template>
    <transition name="fade">
    <div id="detail" v-if="event">
        <div class="row" v-if="view == 'detail'">
            <div class="col-md-12">
                <h1 class="event__title" v-html="event.name"></h1>
                <div><i class="fas fa-money-bill-alt"></i>{{event.free == 'Yes' ? 'Free' : 'Paid'}}</div>
                <div class="event__date"><i class="fas fa-calendar-alt"></i>{{event.start_date}} {{event.start_date == event.end_date ? '' : ' - ' + event.end_date}}</div>
                <a v-if="event.website" target="_blank" :href="event.website"><i class="fas fa-link"></i>{{event.website}}</a>
                <div class="event__address" v-on:click="changeView()"><i class="fas fa-map-marker-alt"></i><b class="event__location" v-html="event.location"></b>, {{event.address}}</div>
            </div>
            <div class="col-md-4">
                <img :src="event.image" :alt="event.name" class="img-fluid" />
            </div>
            <div class="col-md-8">
                <p v-html="event.description"></p>
                <Categories v-bind:categories="event.categories" />
            </div>
        </div>
        <div class="event__map" v-if="view == 'map'">
            <span class="map__close" v-on:click="changeView()">Back to Detail</span>
            <gmap-map class="map" :center="position" :zoom="15">
                <GmapMarker
                    :position="position"
                    :clickable="true"
                    @click="center = position"
                />
            </gmap-map>
        </div>
    </div>
    </transition>
</template>
<script>
import axios from 'axios';
import Categories from '../components/Categories.vue';
export default {
    props: ['id'],
    components: {
        Categories
    },
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
.event__map {
    height: 60vh;
    position: relative;
}
.map__close {
    position: absolute;
    top: -25px;
    right: 0;
    cursor: pointer;
    color: #007bff;
    font-size: 14px;
}
.map {
    height: 100%;
}
</style>
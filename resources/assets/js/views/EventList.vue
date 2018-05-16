<template>
    <div id="events">
        <h1>Total: {{list.length}} Events in Toronto</h1>
        
        <div class="row justify-content-center">
            <datepicker :inline="true" class="col-md-4"></datepicker>
            <div class="col-md-8">
                <div class="event row" v-for="event in list">
                    <figure class="col-md-4">
                        <img class="event__thumbnail" :src="event.thumbnail" :alt="event.name">
                    </figure>
                    <div class="col-md-8">
                        <router-link :to="{ name: 'detail', params: { id: event.id }}"><h5 class="card-title" v-html="event.name"></h5></router-link>
                        <div>{{event.start_date}}</div>
                        <div>{{event.address}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Datepicker from 'vuejs-datepicker';
export default {
    components: {
        Datepicker
    },
    data() {
        return {
            list: []
        };
    },
    mounted() {
        this.getList();
    },
    methods: {
        getList() {
            axios.get('/api/events').then(res => {
                this.list = res.data;
            });
        }
    }
};
</script>
<style scope>
.event {
    margin-bottom: 20px;
}
.event__thumbnail {
    width: 100%;
}
</style>
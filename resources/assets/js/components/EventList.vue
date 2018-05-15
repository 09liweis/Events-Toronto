<template>
    <div id="events">
        <h1>Total: {{list.length}} Events in Toronto</h1>
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" v-for="event in list">
                <div class="card card-default event">
                    <img class="card-img-top" :src="event.thumbnail" :alt="event.name">
                    <div class="card-body">
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
export default {
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
<style>
.event {
    margin-bottom: 20px;
}
</style>
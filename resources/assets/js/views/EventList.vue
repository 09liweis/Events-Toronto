<template>
    <div id="events">
        <h1>Total: {{list.length}} Events in Toronto</h1>
        
        <div class="row justify-content-center">
            <div class="col-md-4">
                <flatPickr v-model="date" @on-change="changeDate" :config="config"></flatPickr>
            </div>
            <div class="col-md-8">
                <div class="event row" v-for="event in list">
                    <figure class="col-md-3 event__figure">
                        <router-link :to="{name: 'detail', params: {id:event.id}}">
                            <img class="event__thumbnail" :src="event.thumbnail" :alt="event.name">
                        </router-link>
                    </figure>
                    <div class="col-md-9 event__info">
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
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
export default {
    components: {
        flatPickr
    },
    data() {
        return {
            list: [],
            date: '',
            config: {
                disableMobile: true
            }
        };
    },
    mounted() {
        this.date = this.formatDate(new Date());
        if (typeof this.$route.query.date == 'undefined') {
            this.$router.push({ path: '/?date=' + this.date });
        } else {
            this.date = this.$route.query.date;
        }
        this.getEvents();
    },
    methods: {
        getEvents() {
            axios.get('/api/events?date=' + this.date).then(res => {
                this.list = res.data;
            });
        },
        changeDate(date) {
            const startDate = date[0];
            const formatDate = this.formatDate(startDate);
            this.date = formatDate;
            this.$router.push({ path: '/?date=' + this.date });
            this.getEvents();
            
        },
        formatDate(date) {
            const year = date.getFullYear();
            const month = date.getMonth() > 9 ? date.getMonth() + 1 : '0' + (date.getMonth() + 1);
            const day = date.getDate() > 9 ? date.getDate() : '0' + date.getDate();
            return year + '-' + month + '-' + day;
        }
        
    }
};
</script>
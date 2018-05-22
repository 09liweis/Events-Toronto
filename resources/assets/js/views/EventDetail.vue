<template>
    <div id="detail" class="row">
        <div class="col-md-4">
            <img :src="event.image" :alt="event.name" class="img-fluid" />
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
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
        return {
            event: {
                
            }
        };
    },
    mounted() {
        const id = this.$route.params.id;
        this.getDetail(id);
    },
    methods: {
        getDetail(id) {
            axios.get('/api/event/' + id).then(res => {
                this.event = res.data;
            });
        }
    }
};
</script>
<style type="sass">

</style>
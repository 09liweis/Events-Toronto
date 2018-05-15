<template>
    <div id="detail">
        <h1>{{event.name}}</h1>
        <p>{{event.description}}</p>
        <a target="_blank" :href="event.website">{{event.website}}</a>
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
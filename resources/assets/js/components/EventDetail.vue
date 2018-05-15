<template>
    <div id="detail">
        <h1>Detail</h1>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    mounted() {
        const id = this.$route.params.id;
        this.getDetail(id);
    },
    methods: {
        getDetail(id) {
            axios.get('/api/event/' + id).then(res => {
                console.log(res.data);
            });
        }
    }
};
</script>
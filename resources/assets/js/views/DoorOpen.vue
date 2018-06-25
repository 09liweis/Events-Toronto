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
        axios.get('api/dooropen').then(res => {
            this.doors = res.data;
        });
    },
    methods: {
        
    }
};
</script>
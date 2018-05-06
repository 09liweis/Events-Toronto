<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" v-for="event in list">
                <div class="card card-default">
                    <div class="card-header">{{event.name}}</div>

                    <div class="card-body">
                        I'm an example component.
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

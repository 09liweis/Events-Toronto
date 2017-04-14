var admin = new Vue({
    el: '#admin',
    data: {
    },
    mounted() {
    },
    methods:  {
        updateEvents() {
            this.$http.get('EventController.php?action=updateEvents').then(function(events) {
                console.log('update success');
            });
        }
    }
});
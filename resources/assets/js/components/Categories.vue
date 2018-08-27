<template>
    <div class="event__categories"><span class="event__category" v-for="c in categories">{{c.name}}</span></div>
</template>
<script>
export default {
    props: ['categories']
};
</script>
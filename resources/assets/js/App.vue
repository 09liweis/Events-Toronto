<template>
    <div class="container">
        <p>
            <router-link :to="{ name: 'home' }">Home</router-link> |
        </p>
        <router-view></router-view>
    </div>
</template>
<script>
export default {};
</script>
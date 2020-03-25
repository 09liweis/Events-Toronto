<template>
	<div class="event row" v-on:click="viewEvent(event.id)">
		<figure class="col-md-3 event__figure">
			<img class="event__thumbnail" :src="event.image" :alt="event.name">
		</figure>
		<div class="col-md-9 event__info">
			<h3 v-html="event.name"></h3>
			<div><i class="fas fa-calendar-alt"></i>{{event.start_date}}</div>
			<div><i class="fas fa-map-marker-alt"></i>{{event.location}}</div>
			<a v-if="event.website" target="_blank" :href="event.website"><i class="fas fa-link"></i>{{event.website}}</a>
			<div>{{event.address}}</div>
			<Categories v-bind:categories="event.categories" />
		</div>
	</div>
</template>
<script>
import Categories from './Categories.vue';
export default {
	components: {
		Categories,
	},
	props: ['event', 'viewEvent']
};
</script>
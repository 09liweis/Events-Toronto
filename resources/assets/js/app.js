
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store.js';

import App from './App.vue';
import EventList from './views/EventList';
import DoorOpen from './views/DoorOpen';
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.use(VueRouter);
Vue.use(VueGoogleMaps, {
	load: {
		key: 'AIzaSyCCUfAQlAr-YR9De_ONa1reKPLA2xWuWm8'
	}
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 
const router = new VueRouter({
	// mode: 'history',
	routes: [
		{
			path: '/',
			name: 'home',
			component: EventList
		},
		{
			path: '/dooropen',
			name: 'dooropen',
			component: DoorOpen
		}
		// {
		//     path: '/event/:id',
		//     name: 'detail',
		//     component: EventList
		// }
	],
});

//Vue.component('event-list', require('./components/EventList.vue'));

const app = new Vue({
	el: '#app',
	components: { App },
	router,
	store
});

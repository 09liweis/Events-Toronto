import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
  events: []
};

const mutations = {
  getEvents(state, events) {
    state.events = events;
  }
};

export default new Vuex.Store({
  state,
  mutations
});
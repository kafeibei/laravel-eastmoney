import Vue from 'vue';
import Vuex from 'vuex';
import global from './global';
import form from './form';
import east from './east';
import search from './search';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    form,
    east,
    search: {
      state: {
        searchInfo: '',
        pageInfo: ''
      },
      ...search
    },
    global: {
      state: {
        isLoading: false
      },
      ...global
    }
  }
});

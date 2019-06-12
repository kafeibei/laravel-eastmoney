import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import store from './store/'; // vuex 数据存储所需对象
import routes from './router/';    // 路由配置文件
const router = new VueRouter({
  mode: 'history',
  routes
})

window.ddVue = new Vue({
  store,
  router,
  el: '#page-view'
});

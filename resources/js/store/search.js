import api from '../api';

export default {
  namespace: true,
  mutations: {
    SET_SEARCH_DB (state, data) {
      state.searchInfo = data
    }
  },
  actions: {
    toSearchDB ({commit}, {params}) {
      return api.searchDB(params)
        .then(res => {
          if (res.code === 200) {
            commit('SET_SEARCH_DB', res.data);
          } else {
            alert(res.message);
          }
        }, err => {
          alert('接口访问错误');
        });
    }
  }
}

import api from '../api';

export default {
  namespace: true,
  mutations: {
    SET_SEARCH_DB (state, data) {
      state.searchInfo = data
    },
    SET_PADE_DB (state, data) {
      state.pageInfo = data
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
    },
    toPageDB ({commit}, {params}) {
      return api.searchDB(params)
        .then(res => {
          if (res.code === 200) {
            commit('SET_PADE_DB', res.data);
          } else {
            alert(res.message);
          }
        }, err => {
          alert('接口访问错误');
        });
    }
  }
}

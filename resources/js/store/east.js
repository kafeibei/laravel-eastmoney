import api from '../api';
export default{
  mutations: {

  },
  actions: {
    toCalcDB ({commit}) {
      return api.calcDB();
    },
    toFilterDB ({commit}, {params}) {
      return api.filterDB(params);
    }
  }
}

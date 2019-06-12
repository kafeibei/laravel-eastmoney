import api from '../api';
export default{
  mutations: {

  },
  actions: {
    toCreateDB ({commit}, {params}) {
      return api.createDB(params);
    },
    toUpdateDB ({commit}, {params}) {
      return api.updateDB(params);
    }
  }
}

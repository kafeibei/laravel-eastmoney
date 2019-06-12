export default {
  namespaced: true,
  mutations: {
    GET_LOADING (state, data) {
      state.isLoading = data
    }
  }
}

import axios from 'axios'

const instance = axios.create({
  timeout: 360000,
  credentials: true
})

const loading = {
  count: 0,
  isLoading: false,
  start () {
    this.count += 1
    this.isLoading = true
    window.ddVue.$store.commit('global/GET_LOADING', true)
  },
  cancel () {
    this.count -= 1
    if (this.count <= 0) {
      this.done()
    }
  },
  done () {
    this.count = 0
    this.isLoading = false
    window.ddVue.$store.commit('global/GET_LOADING', false)
  }
}

instance.interceptors.request.use((config) => {
  if (!config.no_global_loading) {
    loading.start()
  }
  config.params = config.params || {}
  return config
}, error => Promise.reject(error))

instance.interceptors.response.use((res, xhr) => {
  loading.cancel()
  return res.data
}, (error) => {
  loading.cancel()
  return Promise.reject(error)
})

const createAPI = (url, config, method='get') => {
  return instance({
    url,
    method,
    ...config
  })
}

export default {
  // 数据库生成
  createDB: (params) => {
    return createAPI('/api/createDB', {
      params: params
    });
  },
  // 数据库更新
  updateDB: (params) => {
    return createAPI('/api/updateDB', {
      params: params
    });
  },
  // 数据计算
  calcDB: () => {
    return createAPI('/api/calcDB', {}, 'post');
  },
  // 数据过滤
  filterDB: (params) => {
    return createAPI('/api/filterDB', {
      params: params
    }, 'post');
  },
  // 展示数据
  searchDB: (params) => {
    return createAPI('/api/searchDB', {
      params: params
    });
  }
}

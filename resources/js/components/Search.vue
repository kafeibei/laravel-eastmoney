<template>
  <form class="form-search" action="./">
    <h2 class="page-title">东方财富网</h2>
    <div class="form-group">
      <label class="form-title">ID：</label>
      <input type="number" name="thresh" class="form-control" placeholder="查询id" v-model="params.stock_id" />
      <button class="btn btn-primary" @click.stop.prevent="searchDB">数据查询</button>
    </div>
    <div class="form-chart">
      <k-chart v-if="loaded" :data="searchInfo"></k-chart>
    </div>
  </form>
</template>

<script>
import '../../sass/search.scss';
import kChart from './Chart';
export default({
  data () {
    return {
      loaded: false,
      params: {
        stock_id: '000001'
      }
    }
  },
  computed: {
    searchInfo () {
      return this.$store.state.search.searchInfo
    }
  },
  methods: {
    searchDB () {
      this.$store.dispatch({
        type: 'toSearchDB',
        params: this.params
      }).then(() => {
        this.loaded = true
      })
    }
  },
  components: {
    kChart
  }
});
</script>

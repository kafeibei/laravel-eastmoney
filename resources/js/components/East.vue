<template>
  <form class="form-east">
    <h2 class="page-title">某某财经门户网</h2>
    <div class="form-group">
      <label class="form-title">数据处理：</label>
      <input type="number" name="thresh" class="form-control" placeholder="post参数" v-model="params.thresh" />
    </div>
    <div class="form-group">
      <label class="form-title">&nbsp;</label>
      <button class="btn btn-primary" @click.stop.prevent="calcDB">数据计算</button>
      <button class="btn btn-primary" @click.stop.prevent="filterDB">数据过滤</button>
    </div>
  </form>
</template>

<script>
import '../../sass/east.scss';
export default({
  data () {
    return {
      params: {
        thresh: null
      }
    }
  },
  methods: {
    calcDB () {
      this.$store.dispatch({
        type: 'toCalcDB'
      }).then((res) => {
        if (res.code !== 500) {
          this.$message({
            message: '数据计算成功',
            type: 'success'
          });
        } else {
          this.$message({
            message: res.message,
            type: 'error'
          });
        }
      })
    },
    filterDB () {
      if (!this.params.thresh) {
        this.params.thresh = 2
      }
      this.$store.dispatch({
        type: 'toFilterDB',
        params: this.params
      }).then((res) => {
        if (res.code !== 500) {
          this.$message({
            message: '数据过滤成功',
            type: 'success'
          });
        } else {
          this.$message({
            message: res.message,
            type: 'error'
          });
        }
      })
    }
  }
});
</script>

<template>
  <form class="form-east">
    <h2 class="page-title">某某财经门户网</h2>
    <div class="form-group">
      <label class="form-title">数据处理：</label>
      <input type="number" name="thresh" class="form-control" placeholder="post参数" v-model="params.thresh" />
    </div>
    <button class="btn btn-primary" @click.stop.prevent="calcDB">数据计算</button>
    <button class="btn btn-primary" @click.stop.prevent="filterDB">数据过滤</button>
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
          alert('数据计算成功');
        } else {
          alert(res.message);
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
          alert('数据过滤成功');
        } else {
          alert(res.message);
        }
      })
    }
  }
});
</script>

<template>
  <form class="form-content">
    <h2 class="page-title">某某财经门户网</h2>
    <div class="form-group">
      <label class="form-title">ID范围：</label>
      <input type="number" name="start_id" class="form-control" placeholder="开始ID" v-model="params.start_id" />-
      <input type="number" name="end_id" class="form-control" placeholder="结束ID" v-model="params.end_id" />
    </div>
    <div class="form-group">
      <label class="form-title">时间范围：</label>
      <input type="date" name="start_date" class="form-control" placeholder="开始时间" v-model="params.start_date" />-
      <input type="date" name="end_date" class="form-control" placeholder="结束时间" v-model="params.end_date" />
    </div>
    <button class="btn btn-primary" @click.stop.prevent="createDB">数据库生成</button>
    <button name="method" class="btn btn-primary" @click.stop.prevent="updateDB">数据库更新</button>
  </form>
</template>

<script>
import '../../sass/form.scss';
export default({
  data () {
    return {
      params: {
        start_id: '',
        end_id: '',
        start_date: '',
        end_date: ''
      }
    }
  },
  methods: {
    createDB () {
      this.$store.dispatch({
        type: 'toCreateDB',
        params: this.params
      }).then((res) => {
        if (res.code !== 500) {
          alert('数据库生成成功');
        } else {
          alert(res.message);
        }
      })
    },
    updateDB () {
      this.$store.dispatch({
        type: 'toUpdateDB',
        params: this.params
      }).then((res) => {
        if (res.code !== 500) {
          alert('数据库更新成功');
        } else {
          alert(res.message);
        }
      })
    }
  }
});
</script>

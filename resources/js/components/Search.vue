<template>
  <div class="search-page">
    <form class="form-search">
      <h2 class="page-title">某某财经门户网</h2>
      <div class="form-group">
        <label class="form-title">stockID：</label>
        <input type="number" name="thresh" class="form-control" placeholder="查询id" v-model="params.stock_id" />
      </div>
      <div class="form-group">
        <label class="form-title">展示类型：</label>
        <el-radio-group v-model="type" @change="changeRadio">
          <el-radio-button label="echart">图标</el-radio-button>
          <el-radio-button label="table">列表</el-radio-button>
        </el-radio-group>
      </div>
      <div class="form-group">
        <label class="form-title">&nbsp;</label>
        <button class="btn btn-primary" @click.stop.prevent="searchDB">数据查询</button>
      </div>
    </form>
    <div class="form-result" v-if="loaded">
      <div class="form-chart" v-if="type === 'echart'">
        <h2 class="table-title" v-if="searchInfo.stock">{{searchInfo.stock.company}}:{{params.stock_id}}</h2>
        <k-chart :data="searchInfo.east"></k-chart>
      </div>
      <div class="form-table" v-else-if="type === 'table'">
        <h2 class="table-title" v-if="pageInfo.stock">{{pageInfo.stock.company}}:{{params.stock_id}}</h2>
        <k-table v-if="pageInfo.east" :data="pageInfo.east.data" :sortChange="sortBy"></k-table>
        <k-pagination class="hg-flex hg-justify-right" :handlePageTo="pageTo" :pager="pager"></k-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import '../../sass/search.scss';
import kChart from './Chart';
import kTable from './Table';
import kPagination from './Pagination';
export default({
  data () {
    return {
      loaded: false,
      type: 'echart',
      params: {
        stock_id: null,
        prop: 'date',
        order: 'descending'
      },
      pager: {
        page_cur: 1,
        page_size: 20,
        page_total: 0
      }
    }
  },
  computed: {
    searchInfo () {
      return this.$store.state.search.searchInfo
    },
    pageInfo () {
      return this.$store.state.search.pageInfo
    }
  },
  methods: {
    searchDB () {
      let message = ''
      if (!this.params.stock_id) {
        message = '请输入ID值'
      } else if (this.params.stock_id.length !== 6) {
        message = '请输入有效的ID值'
      }
      if (message) {
        this.$message({
          message,
          type: 'warning'
        });
        return false;
      }
      if (this.type === 'table') {
        return this.pageDB()
      }
      this.params.prop = 'date'
      this.params.order = 'descending'
      this.$store.dispatch({
        type: 'toSearchDB',
        params: this.params
      }).then(() => {
        this.loaded = true
      })
    },
    pageDB () {
      this.$store.dispatch({
        type: 'toPageDB',
        params: {
          count: this.pager.page_size,
          page: this.pager.page_cur,
          ...this.params
        }
      }).then(() => {
        if (this.pageInfo.east && this.pageInfo.east.page) {
          this.pager.page_total = this.pageInfo.east.page.total
          this.pager.page_cur = this.pageInfo.east.page.current_page
        }
        this.loaded = true
      })
    },
    pageTo (page) {
      this.pager.page_cur = page
      this.pageDB()
    },
    changeRadio () {
      this.loaded = false
    },
    sortBy (data) {
      this.params.prop = data.prop
      this.params.order = data.order
      this.pageDB()
    }
  },
  components: {
    kChart,
    kTable,
    kPagination
  }
});
</script>

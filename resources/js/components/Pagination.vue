<template>
  <el-pagination
    @current-change="handlePageTo"
    layout="prev, pager, next, jumper"
    :current-page="pager.page_cur"
    :page-size="pager.page_size"
    :total="pager.page_total">
  </el-pagination>
</template>

<script>
  import '../../sass/pagination.scss';
  export default {
    props: {
      handlePageTo: Function,
      pager: {
        type: Object,
        default: () => ({ page_cur: 1, page_total: 1, page_size: 10 })
      }
    }
  }
</script>

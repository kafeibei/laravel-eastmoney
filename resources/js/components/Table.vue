<template>
  <div class="table-list">
    <el-table :data="data" @sort-change="sortChange">
      <el-table-column prop="date" label="日期" sortable></el-table-column>
      <el-table-column prop="sprice" label="开盘价"></el-table-column>
      <el-table-column prop="eprice" label="收盘价"></el-table-column>
      <el-table-column prop="trade_vol" label="成交量" width="120"></el-table-column>
      <el-table-column prop="turnover" label="成交额" width="150"></el-table-column>
      <el-table-column class-name="primary" prop="tv_ratio" label="tv_ratio" sortable></el-table-column>
      <el-table-column class-name="primary" prop="to_ratio" label="to_ratio" sortable></el-table-column>
      <el-table-column class-name="primary" prop="tt_ratio" label="tt_ratio" sortable></el-table-column>
    </el-table>
  </div>
</template>

<script>
import '../../sass/table.scss';
export default {
  props: {
    data: Array,
    sortChange: Function
  }
}
</script>

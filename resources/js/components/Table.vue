<template>
  <div class="table-list">
    <el-table :data="data" @sort-change="sortChange">
      <el-table-column prop="date" label="日期" sortable></el-table-column>
      <el-table-column prop="eprice" label="收盘价"></el-table-column>
      <el-table-column prop="trade_vol" label="成交量" width="120"></el-table-column>
      <el-table-column prop="turnover" label="成交额" width="150"></el-table-column>
      <el-table-column class-name="gray" prop="tv_ratio" label="tv_ratio" sortable></el-table-column>
      <el-table-column class-name="gray" prop="to_ratio" label="to_ratio" sortable></el-table-column>
      <el-table-column class-name="primary" prop="avg5" label="5日均线" sortable></el-table-column>
      <el-table-column class-name="primary" prop="avg10" label="10日均线" sortable></el-table-column>
      <el-table-column class-name="primary" prop="avg20" label="20日均线" sortable></el-table-column>
      <el-table-column class-name="primary" prop="avg30" label="30日均线" sortable></el-table-column>
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

<template>
  <div class="chart-area">
    <k-chart :options="options" autoresize/>
  </div>
</template>

<script>
import ECharts from 'vue-echarts'
import 'echarts/lib/chart/line'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'
import 'echarts-liquidfill'
import {stockSetting} from '../config/setting'

export default {
  components: {
    'k-chart': ECharts
  },
  props: ['data'],
  data () {
    return {
      view: ['eprice', 'tv_ratio', 'to_ratio', 'tt_ratio', 'avg5', 'avg10', 'avg20', 'avg30'],
      stockSetting: stockSetting,
      params: {}
    }
  },
  computed: {
    options () {
      let tempOptions = {
        title: {
          show: false
        },
        legend: {
          data: [],
          top: 32
        },
        dataZoom: [
          {
            show: true,
            realtime: true,
            start: 65,
            end: 85
          },
          {
            type: 'inside',
            realtime: true,
            start: 65,
            end: 85
          }
        ],
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
            animation: false,
            label: {
              backgroundColor: '#505765'
            }
          }
		    },
        grid: {
		  	  top: '15%',
		      left: '2%',
		      right: '4%',
		      bottom: '12%',
		      containLabel: true,
		    },
        color: ['#0095ff', '#52cc56', '#faa032', '#f5ca31', '#b42cde'],
        xAxis: {
          type: 'category',
          boundaryGap: false,
          axisLine: {
            lineStyle: {
              color: '#f6f6f6'
            },
            onZero: false
          },
          axisLabel: {
            textStyle: {
              color: '#999'
            }
          },
          data: []
        },
        yAxis: [{
          type: 'value',
          axisLine: {
            lineStyle: {
              color: '#fff'
            }
          },
          axisLabel: {
            textStyle: {
              color: '#999'
            }
          },
          splitLine: {
            lineStyle: {
              color: '#f6f6f6'
            }
          }
        }],
        series: []
      }
      return this.handleOpts(tempOptions)
    }
  },
  methods: {
    handleOpts (options) {
      if (this.data) {
        let setStockData = {};
        this.data.forEach(item => {
          options.xAxis.data.push(item.date);
          for (let i = 0,len=this.view.length; i<len; i++) {
            let iview = this.view[i];
            if (!setStockData[iview]) {
              setStockData[iview] = [];
            }
            setStockData[iview].push(item[iview]);
          }
        });
        for (let i = 0,len=this.view.length; i<len; i++) {
          let iview = this.view[i];
          let iname = stockSetting[iview] || iview;
          options.legend.data.push(iname);
          options.series.push({
            name: iname,
            type: 'line',
            data: setStockData[iview]
          })
        }
      }
      return options
    }
  }
}
</script>

<template>
  <div class="container">
    <LineChart
        v-if="loaded"
        :chartData="chartdata" />
  </div>
</template>

<script>
import { LineChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

export default {
  name: 'AdminStatistics',
  components: { LineChart },
  data: () => ({
    loaded: false,
    chartdata: null
  }),
  async mounted () {
    this.loaded = false
    try {
      // const { userlist } = await fetch('/api/userlist')
      this.chartdata = {
        labels: ['Jan', 'Feb', 'Mar'],
        datasets: [{
          data: [1, 2, 3],
          backgroundColor: [
            'red',
            'blue',
            'yellow'
          ]
        }]
      }
      this.loaded = true
    } catch (e) {
      console.error(e)
    }
  }
}
</script>

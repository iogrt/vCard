<template>
  <div v-if="!loadError">
    <div class="container p-5" v-if="loaded">
        <LineChart
            :chartData="totalBalanceByDayChart" class="mb-5"/>
        <LineChart
            :chartData="avgBalanceByDayChart"  class="mb-5"/>
        <LineChart
            :chartData="totalAmountMovedByDayChart"  class="mb-5"/>
        <LineChart
            :chartData="transactionCountByDayChart"  class="mb-5"/>
    </div>
    <div v-else class="d-flex h-100 mx-auto align-items-stretch">
        <div class="center mx-auto">
          <PulseLoader size="50px" />
          Loading... please wait
        </div>
    </div>
  </div>
</template>

<script>
import { LineChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'

Chart.register(...registerables)

export default {
  name: 'UserStatistics',
  components: { LineChart, PulseLoader },
  data: () => ({
    loaded: false,
    chartdata: null,
    statsData: null,
    loadError: false
  }),
  mounted () {
    this.loaded = false
    this.$axios.get('/vcards/statistics').then(r => {
      this.statsData = r.data
      this.loaded = true
    }).catch(() => {
      this.$toast.error('Error loading statistics')
      this.loadError = true
    })
  },
  computed: {
    days () {
      return Object.keys(this.statsData.dayStats)
    },
    totalBalanceByDayChart () {
      return {
        labels: this.days,
        datasets: [
          {
            label: 'Total balance in app',
            data: Object.values(this.statsData.dayStats).map(x => x.totalBalance),
            borderColor: '#34eba8',
            tension: 0.2,
            fill: true,
            backgroundColor: '#34eba8'
          }
        ]
      }
    },
    avgBalanceByDayChart () {
      return {
        labels: this.days,
        datasets: [
          {
            label: 'Average balance per vCard',
            data: Object.values(this.statsData.dayStats).map(x => x.avgBalance),
            borderColor: 'pink',
            tension: 0.2,
            fill: true,
            backgroundColor: 'pink'
          }
        ]
      }
    },
    totalAmountMovedByDayChart () {
      return {
        labels: this.days,
        datasets: [
          {
            label: 'Total amount moved',
            data: Object.values(this.statsData.dayStats).map(x => x.totalAmountMoved),
            borderColor: 'red',
            tension: 0.2,
            fill: true,
            backgroundColor: 'red'
          }
        ]
      }
    },
    transactionCountByDayChart () {
      return {
        labels: this.days,
        datasets: [
          {
            label: 'Transaction Count',
            data: Object.values(this.statsData.dayStats).map(x => x.countTransactions),
            borderColor: 'orange',
            tension: 0.2,
            fill: true,
            backgroundColor: 'orange'
          }
        ]
      }
    },
    paymentMethodAmountByDayChart () {
      return 0
      /* TODO
      const paymentTypeInfo = Object.values(this.statsData.dayStats).reduce((acc, stats) => {
        const labelsToAdd = Object.keys(stats.transactions).filter(x => !acc.labels.includes(x))
        const newLabels = [...acc.labels, ...labelsToAdd]
        return {
        }
      }, { data: [], labels: [] })
      console.log('paymentTypeInfo', paymentTypeInfo)

      return {
        labels: this.days,
        datasets: [
          {
            label: 'Payment method amount',
            data: Object.values(this.statsData.dayStats).map(x => x.transactions),
            borderColor: 'orange',
            tension: 0.2,
            fill: true,
            backgroundColor: 'orange'
          }
        ]
      } */
    }
  }
}
</script>
<style>
.center {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  text-align: center;
}
</style>

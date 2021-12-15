<template>
<div>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Transactions</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ this.$store.getters.totalTransactions }}</h5>
    </div>
  </div>
  <hr>
  <div class="mb-3 d-flex justify-content-between flex-wrap">
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
        for="selectType"
        class="form-label"
      >Filter by Type:</label>
      <select
        class="form-select"
        id="selectType"
        v-model="filterByType"
      >
        <option :value="null"></option>
        <option value="C">Credit</option>
        <option value="D">Debit</option>
      </select>
    </div>
  </div>
   <TransactionTable
    :transactions="filteredTransactions"
    @edit="editTransaction"
  ></TransactionTable>
</div>
</template>

<script>
import TransactionTable from './TransactionTable.vue'

export default {
  name: 'Transaction',
  components: {
    TransactionTable
  },
  computed: {
    filteredTransactions () {
      return this.$store.getters.filteredTransactions(this.filterByType)
    }
  },
  data () {
    return {
      filterByType: 'D'
    }
  },
  methods: {
    editTransaction (transaction) {
      this.$router.push({ name: 'Transaction', params: { id: transaction.id } })
    }
  }
}
</script>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addprj {
  margin-top: 1.85rem;
}
</style>

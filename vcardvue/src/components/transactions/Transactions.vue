<template>
<div>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Transactions</h3>
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
    <div class="mx-2 mt-2">
      <button
        type="button"
        class="btn btn-success px-4 btn-addprj"
        @click="addTransaction"
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Transaction</button>
    </div>
  </div>
   <TransactionTable
    :transactions="filteredTransactions"
    :showId="true"
    :showDates="true"
    @edit="editTransaction"
    @delete="deleteTransaction"
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
  data () {
    return {
      transactions: [],
      filterByType: 'D'
    }
  },
  computed: {
    filteredTransactions () {
      return this.transactions
    }
  },
  methods: {
    editTransaction (transaction) {
    //      this.$router.push({ name: 'Transaction', params: { id: transaction.id } })
    },
    deleteTransaction () {
    }
  },
  created () {
    this.$axios.get('admin/transactions')
      .then(response => {
        console.log(response.data.data)
        this.transactions = response.data.data
      })
      .catch(errorResponse => {
        console.log(errorResponse)
      })
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

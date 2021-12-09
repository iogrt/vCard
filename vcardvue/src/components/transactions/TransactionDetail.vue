<template>
    <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">{{ transactionTitle }}</h3>
    <hr>

    <div class="mb-3">
      <label for="Description" class="form-label">Description:</label>
        <input class="textbox form-control" name="description" type="text">
    </div>
  </form>
</template>

<script>
export default {
  name: 'TransactionDetail',
  components: {},
  props: {
    transaction: {
      type: Object,
      required: true
    },
    operationType: {
      type: String,
      default: 'insert' // insert / update
    }
  },
  data () {
    return {
      editingTransaction: this.transaction
    }
  },
  created () {
    console.log('ttt', this.transaction)
    console.log('details', this.editingTransaction)
  },
  /* watch: {
    task (newTransaction) {
      this.editingTransaction = newTransaction
    }
  }, */
  computed: {
    transactionTitle () {
      if (!this.editingTransaction) {
        return ''
      }
      return this.operationType === 'insert'
        ? 'New Transaction'
        : 'Transaction #' + this.editingTransaction.id
    }
  }
}
</script>

<style scoped>

</style>

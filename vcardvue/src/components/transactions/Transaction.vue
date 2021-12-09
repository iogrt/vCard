<template>
<div>
    <h1>Edit</h1>
    <TransactionDetail v-if="transaction"
      :operationType="operation"
      :transaction="transaction"
      :errors="errors"
    ></TransactionDetail>
</div>
</template>

<script>
import TransactionDetail from './TransactionDetail.vue'

export default {
  name: 'Transaction',
  components: {
    TransactionDetail
  },
  data () {
    return {
      transaction: this.newTransaction(),
      errors: null
    }
  },
  props: {
    id: {
      type: Number,
      default: null
    }
  },
  computed: {
    operation () {
      return !this.id || this.id < 0 ? 'insert' : 'update'
    }
  },
  watch: {
    // beforeRouteUpdate was not fired correctly
    // Used this watcher instead to update the ID
    id: {
      immediate: true,
      handler (newValue) {
        this.loadTransaction(newValue)
      }
    }
  },
  methods: {
    dataAsString () {
      return JSON.stringify(this.task)
    },
    newTransaction () {
      return {
        id: null,
        vcard: this.$store.state.user.id,
        description: ''
      }
    },
    loadTransaction (id) {
      console.log('teste', this.transaction)
      this.errors = null
      if (!id || id < 0) {
        this.transaction = this.newTransaction()
        this.originalValueStr = this.dataAsString()
      } else {
        this.$axios
          .get('admin/transactions/' + id)
          .then((response) => {
            this.transaction = response.data.data
            this.originalValueStr = this.dataAsString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
    }
  }
}
</script>

<style>
</style>

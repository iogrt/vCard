<template>
<div>
    <h1>Edit</h1>
    <TransactionDetail v-if="transaction"
      :operationType="operation"
      :transaction="transaction"
      :errors="errors"
      @save="save"
      @cancel="cancel"
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
      transaction: null,
      errors: null,
      description: null,
      categoryid: null
    }
  },
  props: {
    id: {
      type: Number
    }
  },
  watch: {
    id: {
      immediate: true,
      handler (newValue) {
        this.loadTransaction(newValue)
      }
    }
  },
  methods: {
    dataAsString () {
      return JSON.stringify(this.transaction)
    },
    loadTransaction (id) {
      this.transaction = this.$store.state.transactions.find(x => x.id === id)
    },
    cancel () {
      this.$router.back()
    },
    save (description, categoryid) {
      console.log(description, categoryid)
      this.errors = []

      this.$store.dispatch('updateTransaction', {
        id: this.id,
        description,
        category_id: categoryid
      })
        .then(transaction => {
          this.$toast.success(
            'Transaction #' + transaction.id + ' was updated successfully.'
          )
          this.transaction.description = transaction.description
          this.transaction.category_id = transaction.category_id

          const idCategory = this.$store.state.categories.find(x => x.id === categoryid)
          if (idCategory) {
            this.transaction.category_name = idCategory.name
          } else {
            this.transaction.category_name = null
          }
          this.originalValueStr = this.dataAsString()
          this.$router.back()
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.$toast.error(
              'Transaction #' +
                this.id +
                ' was not updated due to validation errors!'
            )
            this.errors = error.response.data.errors
          } else {
            this.$toast.error(
              'Transaction #' +
                this.id +
                ' was not updated due to unknown server error!'
            )
          }
        })
    },
    leaveConfirmed () {
      if (this.nextCallBack) {
        this.nextCallBack()
      }
    },
    beforeRouteLeave (to, from, next) {
      console.log('entrei')
      this.nextCallBack = null
      const newValueStr = this.dataAsString()
      if (this.originalValueStr !== newValueStr) {
        this.nextCallBack = next
        const dlg = this.$refs.confirmationDialog
        dlg.show()
      } else {
        next()
      }
    }
  }
}
</script>

<style>
</style>

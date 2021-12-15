<template>
<div>
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
    loadTransaction (id) {
      this.errors = null
      if (id === null || id < 0) {
        // this.transaction = this.newTransaction()
        this.originalValueStr = this.dataAsString()
      } else {
        this.$axios
          .get('/transactions/' + id)
          .then((response) => {
            this.transaction = response.data.data
            this.originalValueStr = this.dataAsString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
    },
    cancel () {
      this.$router.back()
    },
    save (description, categoryid) {
      this.errors = null
      if (this.operation === 'update') {
        this.$axios
          .put('/transactions/' + this.id, {
            description, category_id: categoryid
          })
          .then((response) => {
            this.$toast.success(
              'Transaction #' + response.data.data.id + ' was updated successfully.'
            )
            this.transaction.description = response.data.data.description
            this.transaction.category_id = response.data.data.category_id
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
      }
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

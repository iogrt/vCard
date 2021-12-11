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
      errors: null
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
    /* newTransaction () {
      return {
        id: null,
        vcard: this.$store.state.user.id,
        description: '',
        category_id: null
      }
    }, */
    loadTransaction (id) {
      this.errors = null
      if (!id || id < 0) {
        // this.transaction = this.newTransaction()
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
    },
    cancel () {
      // Replace this code to navigate back
      // this.loadTask(this.id)
      this.$router.back()
    },
    save () {
      console.log('save')
    }
  }
}
</script>

<style>
</style>

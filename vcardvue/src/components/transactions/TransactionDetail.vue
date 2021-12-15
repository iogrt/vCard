<template>
    <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">{{ transactionTitle }}</h3>
    <hr>
    <div class="mb-3">
      <h4>About</h4>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.vcard_owner">VCard Owner: {{editingTransaction.vcard_owner}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.payment_type">Payment Type: {{editingTransaction.payment_type}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.type">Type: {{editingTransaction.type}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.value">Value: {{editingTransaction.value}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.payment_reference">Payment Reference: {{editingTransaction.payment_reference}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.old_balance">Old Balance: {{editingTransaction.old_balance}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.new_balance">New Balance: {{editingTransaction.new_balance}}</label>
      <br>
      <label  for="infos" class="form-label" v-if="editingTransaction.category_name" >Current Category: {{editingTransaction.category_name}}</label>
      <label  for="infos" class="form-label" v-else >Current Category: No category or deleted</label>
    </div>
    <div class="mb-3">
      <label
          for="Description"
          class="form-label"
      >Description</label>
      <input
          type="text"
          class="form-control"
          id="inputDescription"
          placeholder="Transaction Description"
          v-model="editingTransaction.description"
      >
    </div>
    <div class="mb-3" v-if="categories">
      <label
          for="inputCategory"
          class="form-label"
      >Change Category:</label>
      <select class="form-select" v-model="selected" >
        <option :value="null"></option>
        <option v-for="category in categories" :key="category.id">{{category.name}}</option>
      </select>
    </div>
    <div class="mb-3 d-flex justify-content-end">
      <button
          type="button"
          class="btn btn-primary px-5"
          @click="save"
      >
        Save
      </button>
      <button
          type="button"
          class="btn btn-light px-5"
          @click="cancel"
      >
        Cancel
      </button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'TransactionDetail',
  components: {},
  emits: ['cancel', 'save'],
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
      editingTransaction: this.transaction,
      selected: this.transaction.category_name,
      categories: null
    }
  },
  created () {
    // console.log('transaction', this.transaction)
    // console.log('details', this.editingTransaction)
  },
  watch: {
    transaction (newTransaction) {
      this.editingTransaction = newTransaction
    }
  },
  computed: {
    transactionTitle () {
      if (!this.editingTransaction) {
        return ''
      }
      return this.operationType === 'insert'
        ? 'New Transaction'
        : 'Transaction #' + this.editingTransaction.id
    }
  },
  mounted () {
    console.log('operation:', this.operationType)
    this.$axios.get('/vcards/categories').then(response => {
      this.categories = response.data.data
      // console.log('categories', this.categories)
    })
      .catch((error) => {
        console.log(error)
      })
  },
  methods: {
    save () {
      this.$emit('save', this.editingTransaction)
    },
    cancel () {
      this.$emit('cancel', this.editingTransaction)
    }
  }
}
</script>

<style scoped>

</style>

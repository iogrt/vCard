<template>
<div>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Vcard</th>
        <th>Date</th>
        <th>Type</th>
        <th>Payment Type</th>
        <th>Value</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="transaction in transactions"
        :key="transaction.id"
      >
        <td>{{ transaction.id }}</td>
        <td v-if="transaction.vcard_owner">{{ transaction.vcard_owner }}</td>
        <td v-else>{{ 'Admin' }}</td>
        <td>{{ transaction.date }}</td>
        <td>{{ transaction.type }}</td>
        <td>{{ transaction.payment_type }}</td>
        <td v-if="transaction.type === 'D'" class="red">{{- transaction.value }}</td>
        <td v-else class="green">{{ transaction.value }}</td>
        <td
          class="text-end"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-light"
              @click="editClick(transaction)"
            ><i class="bi bi-xs bi-pencil"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
</template>

<script>
export default {
  name: 'TransactionTable',
  data () {
    return {
      editingTask: false
    }
  },
  props: {
    transactions: {
      type: Array,
      default: () => []
    }
  },
  emits: [
    'edit'
  ],
  methods: {
    editClick (transaction) {
      this.$emit('edit', transaction)
    }
  },
  created () {
    console.log('transactions', this.transactions)
  }
}
</script>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}
.red {
  color: red;
}
.green {
  color: green;
}
</style>

<template>
<div>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">#</th>
        <th>Vcard</th>
        <th v-if="showDates">Date</th>
        <th v-if="showType">Type</th>
        <th>Payment Type</th>
        <th v-if="showValue">Value</th>
        <th v-if="showEditButton || showDeleteButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="transaction in transactions"
        :key="transaction.id"
      >
        <td v-if="showId">{{ transaction.id }}</td>
        <td v-if="transaction.vcard_owner">{{ transaction.vcard_owner.phone_number }}</td>
        <td v-else>{{ 'Admin' }}</td>
        <td>{{ transaction.date }}</td>
        <td v-if="showType">{{ transaction.type }}</td>
        <td>{{ transaction.payment_type.code }}</td>
        <td v-if="transaction.type === 'Debit' && showValue" class="red">{{- transaction.value }}</td>
        <td v-else class="green">{{ transaction.value }}</td>
        <td
          class="text-end"
          v-if="showEditButton || showDeleteButton"
        >
          <div class="d-flex justify-content-end">
            <button
              class="btn btn-xs btn-light"
              @click="editClick(transaction)"
              v-if="showEditButton"
            ><i class="bi bi-xs bi-pencil"></i>
            </button>

            <button
              class="btn btn-xs btn-light"
              @click="deleteClick(transaction)"
              v-if="showDeleteButton"
            ><i class="bi bi-xs bi-x-square-fill"></i>
            </button>
          </div>
        </td>
      </tr>
      <tr  v-if="editingTask">
        <div>
            <h2>Teste detail</h2>
            <div class="form-group">
                <label for="inputEditiginTask">editigin teste</label>
            </div>
            <div class="form-group">
                <a class="btn btn-danger">Save</a>
                <a class="btn btn-primary">Cancel</a>
            </div>
        </div>
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
    },
    showId: {
      type: Boolean,
      default: true
    },
    showDates: {
      type: Boolean,
      default: true
    },
    showType: {
      type: Boolean,
      default: true
    },
    showValue: {
      type: Boolean,
      default: true
    },
    showEditButton: {
      type: Boolean,
      default: true
    },
    showDeleteButton: {
      type: Boolean,
      default: true
    }
  },
  emits: [
    'edit'
  ],
  methods: {
    editClick () {
      // this.$emit('edit', transaction)
      this.editingTask = true
    },
    deleteClick () {
    }
  },
  created () {
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

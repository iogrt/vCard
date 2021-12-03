<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">#</th>
        <th>Vcard</th>
        <th v-if="showDates">Date</th>
        <th v-if="showType">Type</th>
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
        <td>{{ transaction.vcard }}</td>
        <td>{{ transaction.date }}</td>
        <td v-if="showType">{{ transaction.type }}</td>
        <td v-if="showValue">{{ transaction.value }}</td>
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
    </tbody>
  </table>
</template>

<script>
export default {
  name: 'TransactionTable',
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
      default: false
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
  ],
  methods: {
    editClick () {
    },
    deleteClick () {
    }
  }
}
</script>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>

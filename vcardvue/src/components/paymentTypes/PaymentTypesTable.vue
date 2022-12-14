<template>
<div>
  <confirmation-dialog
      ref="confirmationDialog"
      confirmationBtn="Delete category"
      :msg="`Do you really want to delete this payment type ${ paymentTypeToDeleteDescription }?`"
      @confirmed="deleteConfirmed"
  >
  </confirmation-dialog>

  <table class="table">
    <thead>
    <tr>
      <th>Code</th>
      <th>Name</th>
      <th>Description</th>
      <th v-if="showRules">Rules</th>
      <th v-if="showTimestamps">Deleted at</th>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="pay in paymentTypes"
        :key="pay.code"
      :class="{ completed: pay.deleted_at}"
    >
      <td>{{ pay.code }}</td>
      <td>{{ pay.name}} </td>
      <td>{{ pay.description}} </td>
      <td v-if="showRules">{{ pay.validation_rules}} </td>
      <td v-if="showTimestamps" >{{pay.deleted_at ? new Date(pay.deleted_at) : ""}}</td>
      <td class="text-end">
        <div class="d-flex justify-content-end">
          <button
              v-if="!pay.deleted_at"
              class="btn btn-xs btn-light"
              @click="editClick(pay)"
          ><i class="bi bi-xs bi-pencil"></i>
          </button>

          <button
              v-if="!pay.deleted_at"
              class="btn btn-xs btn-light"
              @click="deleteClick(pay)"
          ><i class="bi bi-xs bi-x-square-fill"></i>
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
  name: 'PaymentTypesTable',
  props: {
    paymentTypes: {
      type: Array,
      default: () => []
    },
    showTimestamps: {
      type: Boolean,
      default: () => false
    }
  },
  emits: [
    'edit',
    'deleted'
  ],
  data () {
    return {
      editingPaymentTypes: this.paymentTypes,
      paymentTypeToDelete: null,
      showRules: false
    }
  },
  computed: {
    paymentTypeToDeleteDescription () {
      return this.paymentTypeToDelete
        ? `#${this.paymentTypeToDelete.code} (${this.paymentTypeToDelete.description})`
        : ''
    }
  },
  watch: {
    /* tasks (newTasks) {
      this.editingTasks = newTasks
    } */
  },
  methods: {
    editClick (pay) {
      this.$router.push({ name: 'EditPaymentType', params: { code: pay.code } })
    },
    deleteConfirmed () {
      console.log(this.paymentTypeToDelete)
      this.$axios.delete('admin/payment_types/' + this.paymentTypeToDelete.code)
        .then((response) => {
          console.log(response.data.data)
          this.$toast.success('Successfully deleted')

          this.$emit('deleted', this.paymentTypeToDelete)
        })
        .catch(error => {
          this.$toast.error(error.message)
        })
    },
    deleteClick (pay) {
      this.paymentTypeToDelete = pay
      const dlg = this.$refs.confirmationDialog
      dlg.show()
    }
  }
}
</script>

<style scoped>
.completed {
  text-decoration: line-through;
}
button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>

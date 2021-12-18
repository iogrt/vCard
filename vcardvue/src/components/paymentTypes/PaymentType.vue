<template>
  <ConfirmationDialog
      ref="confirmationDialog"
      confirmationBtn="Discard changes and leave"
      msg="Do you really want to leave? You have unsaved changes!"
      @confirmed="leaveConfirmed"
  >
  </ConfirmationDialog>

  <form
      class="row g-3 needs-validation"
      novalidate
      @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3">{{ title }}</h3>
    <hr>

    <div v-if="!code" class="mb-3">
      <label
          for="inputCode"
          class="form-label"
      >Code</label>
      <input
          type="text"
          class="form-control"
          id="inputCode"
          placeholder="Payment type code"
          required
          v-model="editingPaymentType.code"
      >
      <FieldErrorMessage
          :errors="errors"
          fieldName="code"
      ></FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label
          for="inputName"
          class="form-label"
      >Name</label>
      <input
          type="text"
          class="form-control"
          id="inputName"
          placeholder="Payment type name"
          required
          v-model="editingPaymentType.name"
      >
      <FieldErrorMessage
          :errors="errors"
          fieldName="name"
      ></FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label
          for="inputDescription"
          class="form-label"
      >Description</label>
      <input
          type="text"
          class="form-control"
          id="inputDescription"
          placeholder="description..."
          required
          v-model="editingPaymentType.description"
      >
      <FieldErrorMessage
          :errors="errors"
          fieldName="description"
      ></FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label
          for="inputValidation"
          class="form-label"
      >Validation Rules (regex)</label>
      <input
          type="text"
          class="form-control"
          id="inputValidation"
          placeholder="regex string"
          v-model="editingPaymentType.validation_rules"
      >
      <FieldErrorMessage
          :errors="errors"
          fieldName="validation_rules"
      ></FieldErrorMessage>
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
import FieldErrorMessage from '../global/FieldErrorMessage'
import ConfirmationDialog from '../global/ConfirmationDialog'

export default {
  name: 'PaymentType',
  components: { ConfirmationDialog, FieldErrorMessage },
  props: {
    code: {
      type: String
    }
  },
  data () {
    return {
      paymentType: null,
      editingPaymentType: null,
      errors: {}
    }
  },
  created () {
    if (this.code != null) {
      this.loadPaymentType()
    } else {
      this.newPaymentType()
    }
  },
  watch: {
    paymentType (newPaymentType) {
      this.editingPaymentType = this.paymentType
    }
  },
  computed: {
    title () {
      return this.code === null
        ? 'New Payment Type'
        : 'Payment Type #' + this.editingPaymentType.code
    }
  },
  methods: {
    newPaymentType () {
      this.paymentType = {
        code: this.code ?? '',
        name: '',
        description: '',
        validation_rules: ''
      }
    },
    save () {
      if (this.code) {
        this.$axios.put(`admin/payment_types/${this.code}`, this.editingPaymentType)
          .then(response => {
            this.paymentType = response.data.data
            this.$toast.success(`Successfully updated payment type ${this.paymentType.code}!`)
            this.$router.back()
          })
          .catch(err => {
            this.$toast.error(err.message)
            this.errors = err.errors
          })

        return
      }

      this.$axios.post('admin/payment_types', this.editingPaymentType)
        .then(response => {
          this.category = response.data.data
          this.$toast.success(`Successfully created payment type ${this.paymentType.name}!`)
          this.$router.back()
        })
        .catch(err => {
          this.$toast.error(err.message)
          this.errors = err.errors
        })
    },
    cancel () {
      this.$router.back()
    },
    loadPaymentType () {
      this.errors = null
      this.$axios.get(`payment_types/${this.code}`)
        .then(response => {
          this.paymentType = response.data.data
          console.log(this.paymentType)
        })
        .catch(response => {
          this.errors = response.data.errors
          this.$toast.error(response.data.message)
        })
    },
    leaveConfirmed () {
      if (this.nextCallBack) {
        this.nextCallBack()
      }
    },
    dataAsString () {
      return JSON.stringify(this.editingPaymentType)
    }
  },
  beforeRouteLeave (to, from, next) {
    this.nextCallBack = null

    if (JSON.stringify(this.paymentType) !== this.dataAsString()) {
      this.nextCallBack = next
      this.$refs.confirmationDialog.show()
    } else {
      next()
    }
  }
}
</script>

<style scoped>
.total_hours {
  width: 26rem;
}
.checkCompleted {
  min-height: 2.375rem;
}
</style>

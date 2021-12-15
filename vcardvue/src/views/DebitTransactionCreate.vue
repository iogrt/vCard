<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="createCard" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Send Money</h3>
    <hr>
    <Vcard :user="user"/>
    <p>Your balance: <span class="fs-5 text-success">{{user.balance}}€</span>, max debit: <span class="fs-5">{{user.max_debit}}€</span></p>

    <div class="mb-3">
      <div class="row gx-0 d-flex">
        <div class=" col col-sm-2">
          <label for="name" class="form-label">Payment ref:</label>
          <select class="form-select" name="payment_type" v-model="formData.payment_type">
            <option v-for="paymentType in paymentTypes" :key="paymentType.code" :value="paymentType.code">
                {{paymentType.name}}
            </option>
          </select>
        </div>
        <div  class="col col-sm-10" v-if="formData.payment_type">
          <label for="name" class="form-label">{{selectedPaymentType.description}}:</label>
          <input class="form-control" name="payment_reference" type="text" placeholder="Payment reference" v-model="formData.payment_reference" data-rule="required">
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label d-block">Value:</label>
        <input class="textbox  form-control w-50 d-inline" name="name" type="number" min="0" :max="user.balance" placeholder="Value" v-model="formData.value" data-rule="required"> €
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Debit category:</label>
      <div class="row gx-0 d-flex">
        <div class=" col col-sm-6">
          <select class="form-select" name="category" v-model="formData.category">
            <option v-for="category in $store.getters.filteredCategories(null,'D')" :key="category.id" :value="category.name">
                {{category.name}}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label d-block">Description:</label>
        <input class="textbox  form-control w-100 d-inline" name="name" type="text" placeholder="Value" v-model="formData.description">
    </div>

    <div>
        <div v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <ul>
            <li v-for="error in errors" :key=error>{{ error }}</li>
          </ul>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-4" @click.prevent="createTransaction($event)"> Send money </button>
      <button type="reset" class="btn btn-danger  px-4" @click="resetValues">Reset</button>
      <button type="button" class="btn btn-light px-4" @click="cancel" > Cancel </button>
    </div>
  </form>
</template>

<script>
import { mapState } from 'vuex'
import Vcard from '@/components/global/Vcard.vue'

export default {
  components: {
    Vcard
  },
  data () {
    return {
      paymentTypes: [],
      formData: {
        payment_type: null,
        payment_reference: null,
        category: null,
        description: null,
        value: null
      },
      errors: {}
    }
  },
  methods: {
    resetValues () {
      this.formData = {
        payment_type: null,
        payment_reference: null,
        category: null,
        description: null,
        value: null
      }
    },
    async createTransaction (event) {
      event.preventDefault()
      const url = '/transactions'

      this.errors = []
      if (this.formData.value <= 0) {
        this.errors.push('Value must be positive')
      }

      if (this.formData.value > this.user.max_debit) {
        this.errors.push(`Value must be less or equal to your max debit (${this.user.max_debit})`)
      }

      if (this.formData.value > this.user.balance) {
        this.errors.push(`Value must be less or equal to your balance (${this.user.balance})`)
      }

      if (this.formData.payment_reference === null) {
        this.errors.push('Payment reference is required')
      }
      // test payment reference with regex
      if (!(new RegExp(this.selectedPaymentType.validation_rules)).test(this.formData.payment_reference)) {
        this.errors.push('Invalid format for payment reference')
      }

      if (this.errors.length !== 0) {
        return
      }

      const formDataA = new FormData()
      Object.entries(this.formData).map(([key, value]) => formDataA.append(key, value))

      this.$axios.post(url, formDataA)
        .then(response => {
          const trans = response.data.data

          console.log(JSON.stringify(trans))
          this.$toast.success(`Successfully transfered ${this.formData.value}€!`)
          if (this.formData.payment_type === 'VCARD') {
            this.$socket.emit('newCreditTransaction', {
              value: trans.value,
              vcard_owner: trans.vcard_owner,
              payment_type: trans.payment_type,
              payment_reference: trans.payment_reference,
              type: 'C'
            })
          }
          this.resetValues()
          this.$store.dispatch('loadLoggedInUser')
        })
        .catch(errorResponse => {
          console.log(errorResponse.response.status)
          if (errorResponse.response.status === 422) {
            // this.msgErrors = errorResponse.response.data.errors
            this.errors = errorResponse.response.data.errors
            this.$toast.error(errorResponse.response.data.message)
          }
        })
    }
  },
  computed: {
    selectedPaymentType () {
      return this.paymentTypes.find(x => x.code === this.formData.payment_type)
    },
    maxValue () {
      return Math.min(this.user.balance, this.user.max_debit)
    },
    canCreateTransaction () {
      return this.formData.payment_type !== null &&
        this.formData.payment_reference !== null &&
        this.formData.value > 0
    },
    ...mapState(['user'])
  },
  mounted () {
    this.$axios.get('/payment_types/').then(r => {
      console.log('payment_types: ', r.data.data)
      this.paymentTypes = r.data.data
      console.log(this.paymentTypes)
    })
  }
}
</script>

<style scoped>

.disable {
  pointer-events: none;
  opacity: 0.5;
}
.createvcardtransaction--form {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 400px;
  padding: 30px;
  transform: translate(-50%, -50%);
  background: #0d6efd;
  box-shadow: 0 15px 25px rgba(0, 0, 0, 0.37);
  border-radius: 10px;
}
.createvcardtransaction--form .form-box label {
  display: flex;
  flex-direction: column;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 16px;
  color: #fff;
  pointer-events: none;
}
.textbox
{
  height:50px;
  width: 340px;
  font-size:12pt;
}
.buttonstyle{
  display: flex;
  justify-content: space-evenly;
  flex-grow: 1;
  flex-wrap: nowrap;
  padding-top: 10px;
  padding-bottom: 10px;
}
</style>

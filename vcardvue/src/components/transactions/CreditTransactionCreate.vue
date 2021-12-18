<template>
  <form v-if="vcardData" class="row g-3 needs-validation" novalidate @submit.prevent="createCard" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Add money to card {{vcard}}</h3>
    <hr>
    <Vcard :user="vcardData"/>
    <p>Card balance: <span class="fs-5 text-success">{{vcardData.balance}}€</span>, max debit: <span class="fs-5">{{vcardData.max_debit}}€</span></p>

    <div class="mb-3">
      <div class="row gx-0 d-flex">
        <div class=" col col-sm-2">
          <label for="name" class="form-label">Payment ref:</label>
          <select class="form-select" name="payment_type" v-model="formData.payment_type">
            <option v-for="paymentType in paymentTypes" :key="paymentType.code" :value="paymentType.code">
                {{paymentType.code}}
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
        <input class="textbox  form-control w-50 d-inline" name="name" type="number" min="0" :max="vcardData.balance" placeholder="Value" v-model="formData.value" data-rule="required"> €
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
      <router-link :to="{ name: 'Users' }" class="btn btn-light px-4" @click="cancel" > Cancel </router-link>
    </div>
  </form>
</template>

<script>
import Vcard from '@/components/global/Vcard.vue'

export default {
  components: {
    Vcard
  },
  props: {
    vcard: Number
  },
  data () {
    return {
      paymentTypes: [],
      categories: [],
      formData: {
        payment_type: null,
        payment_reference: null,
        value: null
      },
      errors: [],
      vcardData: null
    }
  },
  methods: {
    resetValues () {
      this.formData = {
        payment_type: null,
        payment_reference: null,
        value: null
      }
    },
    async createTransaction (event) {
      event.preventDefault()
      const url = '/admin/transactions'

      this.errors = []
      if (this.formData.value <= 0) {
        this.errors.push('Value must be positive')
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
      formDataA.append('vcard', this.vcard)

      this.$axios.post(url, formDataA)
        .then(response => {
          const transaction = response.data.data
          console.log(transaction)

          this.$toast.success(`Successfully transfered ${this.formData.value}€ to this vCard!`)

          this.$socket.emit('newCreditTransaction', {
            value: transaction.value,
            vcard_owner: transaction.vcard_owner,
            payment_type: transaction.payment_type,
            payment_reference: transaction.payment_reference,
            type: 'C'
          })

          this.resetValues()
          this.loadVcard() // reload balance
        })
        .catch(errorResponse => {
          if (errorResponse.response.status === 422) {
            // this.msgErrors = errorResponse.response.data.errors
            this.errors = Object.entries(errorResponse.response.data.errors).map(([a, b]) => a + ': ' + b)
            this.$toast.error(errorResponse.response.data.message)
          }
        })
    },
    loadVcard () {
      this.$axios.get(`admin/vcard/${this.vcard}`).then(r => {
        const photoUrl = r.data.data.photo_url
        this.vcardData = r.data.data
        this.vcardData.photo_url = photoUrl
          ? process.env.VUE_APP_BASE_URL + '/storage/fotos/' + photoUrl
          : 'img/avatar-none.png'
      }).catch(error => {
        if (error.response.status === 404) {
          this.$toast.error('vcard not found')
        } else {
          this.$toast.error('there was a problem getting vcard info')
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
    }
  },
  mounted () {
    this.loadVcard()
    this.$axios.get('/payment_types/').then(r => {
      // can't simulate a vcard transaction, only from external entities
      this.paymentTypes = r.data.data.filter(x => x.code !== 'VCARD')
    }).catch(() => this.$toast.error('problem communicating to server'))
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

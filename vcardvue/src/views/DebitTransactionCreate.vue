<template>
  <div class="about">
    <h3>Make a Debit Transaction to another vCard</h3>
    <form class="createvcardtransaction--form">
      <!--<div>
        <div v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <ul>
            <li v-for="error in errors" :key=error>{{ error }}</li>
          </ul>
        </div>
      </div>-->
      <div class="form-box">
        <div >
          <label for="vcard">your vCard number:</label>
          <input class="textbox" name="phone" type="number" min="900000000" max="999999999" placeholder="999999999" v-model="formData.vcard" data-rule="required|phone">
          <field-error-message
              :errors="errors"
              fieldName="vcard"
          ></field-error-message>
        </div>
        <div>
          <label for="pair_vcard">destination vCard number:</label>
          <input class="textbox" name="otherphone" type="number" placeholder="999999999" v-model="formData.pair_vcard" data-rule="required">
          <field-error-message
              :errors="errors"
              fieldName="pair_vcard"
          ></field-error-message>
        </div>
        <div>
          <label for="value">Amount:</label>
          <input class="textbox" name="amount" type="number" placeholder="0.00€" v-model="formData.value" data-rule="required|min:0">

          <field-error-message
              :errors="errors"
              fieldName="value"
          ></field-error-message>
        </div>
        <div>
          <label for="category">Category (Optional):</label>
          <input class="textbox" name="category" type="text" placeholder="" v-model="formData.category">

          <field-error-message
              :errors="errors"
              fieldName="category"
          ></field-error-message>
        </div>
        <div>
          <label for="description">Description (Optional):</label>
          <input class="textbox" name="" type="text" placeholder="" v-model="formData.description" data-rule="max:8192">

          <field-error-message
              :errors="errors"
              fieldName="description"
          ></field-error-message>
        </div>
      </div>
      <div class="buttonstyle">
        <button type="submit" class="btn btn-success" :class="[{'disable':!canCreateTransaction}]" @click.prevent="createTransaction($event)">Transfer Money</button>
        <button type="reset" class="btn btn-danger" @click="resetValues">Reset</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      formData: {
        vcard: null,
        pair_vcard: null,
        value: 0,
        category: null,
        description: null
      },
      errors: {}
    }
  },
  methods: {
    resetValues () {
      this.formData.vcard = null
      this.formData.pair_vcard = null
      this.formData.value = 0
      this.formData.category = null
      this.formData.description = null
    },
    async createTransaction (event) {
      event.preventDefault()
      const url = '/transactions/vcard'

      this.errors = {}

      const formDataA = new FormData()
      formDataA.append('vcard', this.formData.vcard)
      formDataA.append('pair_vcard', this.formData.pair_vcard)
      formDataA.append('value', this.formData.value)
      formDataA.append('category', this.formData.category)
      formDataA.append('description', this.formData.description)

      if (!this.canCreateTransaction) {
        this.$toast.error('Fill all the camps')
      }

      this.$axios.post(url, formDataA)
        .then(response => {
          console.log(url)
          console.log(response.data)
          this.$toast.success(`Successfully transfered ${this.value}€ to another vCard!`)
          this.resetValues()
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
    canCreateTransaction () {
      return this.formData.vcard && this.formData.pair_vcard && this.formData.value
    }
  },
  mounted () {
    this.resetValues()
  }
}
</script>

<style scoped>

.disable{
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

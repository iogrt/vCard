<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="createCard" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Create Vcard</h3>
    <hr>

    <div class="mb-3">
      <label for="name" class="form-label">Phone Number:</label>
        <input class="textbox form-control" name="phone" type="number" min="900000000" max="999999999" placeholder="999999999" v-model="formData.phone_number" data-rule="required|phone">
    <FieldErrorMessage
        :errors="errors"
        fieldName="phone_number">
    </FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Password:</label>
        <input class="textbox  form-control" name="password" type="password" placeholder="Password" v-model="formData.password" data-rule="required">
    <FieldErrorMessage
      :errors="errors"
      fieldName="password">
    </FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Name:</label>
        <input class="textbox  form-control" name="name" type="text" placeholder="Name" v-model="formData.name" data-rule="required">
    <FieldErrorMessage
      :errors="errors"
      fieldName="name">
    </FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Email:</label>
        <input class="textbox  form-control" name="email" type="email" placeholder="email@email.com" v-model="formData.email" data-rule="required">
    <FieldErrorMessage
      :errors="errors"
      fieldName="email">
    </FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Confirmation Code:</label>
        <input class="textbox  form-control" name="conf_code" type="number" max="9999" placeholder="9999" v-model="formData.confirmation_code" data-rule="required">
    <FieldErrorMessage
      :errors="errors"
      fieldName="confirmation_code">
    </FieldErrorMessage>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Upload photo: </label>
          <input type="file" accept="image/*" name="photo_url" @change="processImg($event)" id="file-input">
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-4" :class="[{'disable':!canCreateCard}]" @click.prevent="createCard($event)"> Creat Card </button>
      <button type="reset" class="btn btn-danger  px-4" @click="resetValues">Reset</button>
      <button type="button" class="btn btn-light px-4" @click="cancel" > Cancel </button>
    </div>
  </form>
</template>

<script>

import FieldErrorMessage from '../global/FieldErrorMessage'
export default {
  components: { FieldErrorMessage },
  data () {
    return {
      formData: {
        phone_number: null,
        password: null,
        name: null,
        email: null,
        confirmation_code: null,
        photo_url: null,
        blocked: 0
      },
      errors: []
    }
  },
  methods: {
    resetValues () {
      this.formData.phone_number = null
      this.formData.password = null
      this.formData.name = null
      this.formData.email = null
      this.formData.confirmation_code = null
      this.formData.photo_url = null
    },
    async createCard (event) {
      event.preventDefault()
      const url = '/vcards'

      this.errors = []

      /* eslint-disable */
      const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
      /* eslint-enable */

      const formDataA = new FormData()
      formDataA.append('phone_number', this.formData.phone_number)
      formDataA.append('password', this.formData.password)
      formDataA.append('name', this.formData.name)
      formDataA.append('email', this.formData.email)
      formDataA.append('confirmation_code', this.formData.confirmation_code)

      if (this.errors.length) {
        return
      }

      if (!this.canCreateCard) {
        this.$toast.error('Fill all the camps')
      }

      if (this.formData.photo_url !== null) {
        formDataA.append('photo_url', this.formData.photo_url)
        console.log('photo uploaded')
      }

      this.$axios.post(url, formDataA)
        .then(response => {
          console.log(url)
          console.log(response)
          this.$toast.success('Successfully created')
          this.resetValues()
        })
        .catch(errorResponse => {
          if (errorResponse.response.status === 422) {
            // console.log(errorResponse.response.data.message)
            this.errors = Object.entries(error.response.data.errors).map(([a, b]) => a + ': ' + b)
            console.log(this.errors)
            this.$toast.error(errorResponse.response.data.message)
          }
          if (errorResponse.response.status === 500) {
            this.$toast.error('Sorry, internal server error')
          }
        })
    },
    processImg (event) {
      console.log(event.target.files[0])
      this.formData.photo_url = event.target.files[0]
    },
    cancel () {
      this.$emit('cancel', this.createCard)
    }
  },
  computed: {
    canCreateCard () {
      return this.formData.phone_number && this.formData.password && this.formData.name && this.formData.email && this.formData.confirmation_code
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
  .createcard--form {
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
  .createcard--form .form-box label {
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

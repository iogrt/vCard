<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="updateCard" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Edit Vcard</h3>
    <hr>

    <p>Phone number: {{this.$store.state.user.phone_number}}</p>
    <div class="mb-3">
      <label for="name" class="form-label">Password:</label>
        <input class="textbox  form-control" name="passw-ord" type="password" placeholder="<unchanged>" v-model="formData.password">
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Name:</label>
        <input class="textbox  form-control" name="name" type="text" placeholder="Name" v-model="formData.name" data-rule="required">
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Email:</label>
        <input class="textbox  form-control" name="email" type="email" placeholder="email@email.com" v-model="formData.email" data-rule="required">
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Confirmation Code:</label>
        <input class="textbox  form-control" name="conf_code" type="number" max="9999" placeholder="<unchanged>" v-model="formData.confirmation_code">
    </div>

    <div class="mb-3" v-if="needPassword">
      <label for="name" class="form-label">Confirm with current password:</label>
        <input class="textbox  form-control" name="conf_code" type="number" max="9999" placeholder="Current password" v-model="currentPassword" data-rule="required">
    </div>

    <div>Current photo: <img class="rounded-circle" width="100" :src="this.$store.state.user.photo_url" /> </div>
    <div class="mb-3">
      <label for="name" class="form-label">Upload photo: </label>
          <input type="file" accept="image/*" name="photo_url" @change="processImg($event)" id="file-input">
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
      <button type="button" class="btn btn-primary px-4 mx-1"  @click.prevent="updateCard($event)"> Save </button>
      <button type="button" class="btn btn-danger  px-4 mx-1" @click="resetValues">Reset</button>
      <button type="button" class="btn btn-light px-4 mx-1" @click="cancel" > Cancel </button>
    </div>
  </form>
</template>

<script>

export default {
  data () {
    return {
      formData: {
        password: null,
        name: this.$store.state.user.name,
        email: this.$store.state.user.email,
        confirmation_code: null,
        photo_url: null,
        blocked: 0
      },
      currentPassword: '',
      errors: [],
      msgErrors: []
    }
  },
  methods: {
    resetValues () {
      this.formData = {
        password: null,
        name: this.$store.state.user.name,
        email: this.$store.state.user.email,
        confirmation_code: null,
        photo_url: null,
        blocked: 0
      }
    },
    async updateCard (event) {
      event.preventDefault()
      const url = '/users/me'

      this.errors = []

      /* eslint-disable */
      const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
      /* eslint-enable */

      console.log(this.$axios.defaults.headers.common.Authorization)
      const formDataA = new FormData()
      formDataA.append('name', this.formData.name)
      formDataA.append('email', this.formData.email)

      if (this.formData.password) {
        if (String(this.formData.password).length < 6) {
          this.errors.push('Password Should have more that 6 caracteres')
          return
        }
        formDataA.append('password', this.formData.password)
      }
      if (String(this.formData.name).length < 3 || !String(this.formData.name).trim()) {
        this.errors.push('Name Should have more that 3 caracteres')
        return
      }
      if (!emailRegex.test(this.formData.email)) {
        this.errors.push('Invalid email')
        return
      }
      if (this.formData.confirmation_code) {
        if (String(this.formData.confirmation_code).length !== 4) {
          this.errors.push('Confirmation code Should have 4 numbers')
          return
        }
        formDataA.append('confirmation_code', this.formData.confirmation_code)
      }
      if (this.formData.photo_url !== null) {
        formDataA.append('photo_url', this.formData.photo_url)
        console.log('photo uploaded')
      }

      if (this.needPassword) {
        formDataA.append('current_password', this.currentPassword)
      }

      // backend doesn't support formdata
      formDataA.append('_method', 'PUT')

      this.$axios.post(url, formDataA)
        .then(response => {
          console.log(url)
          console.log(response)
          this.$toast.success('Successfully edited')
          this.$store.dispatch('loadLoggedInUser')
          this.$router.push({ name: 'Dashboard' })
        })
        .catch(errorResponse => {
          if (errorResponse.response.status === 422) {
            // console.log(errorResponse.response.data.message)
            console.log(errorResponse)
            console.log(errorResponse.message)
            console.log(errorResponse.response.status)
            console.log(errorResponse.response.data.message)
            this.errors.push(errorResponse.response.data.message)
          }
          if (errorResponse.response.status === 500) {
            console.log(errorResponse)
            this.errors.push('Sorry, we cant create')
          }
        })
    },
    processImg (event) {
      console.log(event.target.files[0])
      this.formData.photo_url = event.target.files[0]
    },
    cancel () {
      this.$router.push({ name: 'Dashboard' })
    }
  },
  computed: {
    needPassword () {
      return this.formData.password || this.formData.confirmation_code
    }
  },
  mounted () {
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

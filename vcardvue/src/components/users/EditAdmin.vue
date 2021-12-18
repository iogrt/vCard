<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="editAdmin" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Edit admin</h3>
    <hr>
    <div class="mb-3">
      <label for="name" class="form-label">Name:</label>
      <input class="textbox  form-control" name="name" type="text" placeholder="Name" v-model="formData.name" data-rule="required">
    </div>
    <div class="mb-3">
      <label for="name" class="form-label">Email:</label>
        <input class="textbox  form-control" name="email" type="email" placeholder="email@email.com" v-model="formData.email" data-rule="required">
    </div>
    <div class="mb-3">
      <label for="name" class="form-label">Password:</label>
        <input class="textbox  form-control" name="password" type="password" placeholder="Password" v-model="formData.password" data-rule="required">
    </div>
    <div class="mb-3" v-if="formData.password">
      <label for="name" class="form-label">Confirm with current password:</label>
        <input class="textbox  form-control" name="password" type="password" placeholder="Current Password" v-model="formData.currentPassword" data-rule="required">
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
      <button type="button" class="btn btn-primary px-4" :class="[{'disable':!canSend}]" @click.prevent="editAdmin"> Edit Admin </button>
      <button type="reset" class="btn btn-danger  px-4" @click="resetValues">Reset</button>
      <button type="button" class="btn btn-light px-4" @click="cancel" > Back </button>
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
        currentPassword: null
      },
      errors: [],
      msgErrors: []
    }
  },
  methods: {
    resetValues () {
      this.formData.password = null
      this.formData.name = this.$store.state.user.name
      this.formData.email = this.$store.state.user.email
    },
    async editAdmin (event) {
      event.preventDefault()
      const url = '/users/me'

      this.errors = []

      /* eslint-disable */
      const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
      /* eslint-enable */

      if (!this.canSend) {
        this.errors.push('Fill all the camps')
      }
      if (this.formData.password && !this.formData.currentPassword) {
        this.errors.push('Confirm password change with your current password')
      }
      if (this.formData.password && String(this.formData.password).length < 6) {
        this.errors.push('Password Should have more that 6 caracteres')
        return
      }
      if (String(this.formData.name).length < 3 || !String(this.formData.name).trim()) {
        this.errors.push('Name Should have more that 3 caracteres')
        return
      }
      if (!emailRegex.test(this.formData.email)) {
        this.errors.push('email invalido')
        return
      }

      const formDataA = new FormData()
      formDataA.append('name', this.formData.name)
      formDataA.append('email', this.formData.email)
      if (this.formData.password) {
        formDataA.append('password', this.formData.password)
        formDataA.append('current_password', this.formData.currentPassword)
      }

      // backend doesn't support formdata without this
      formDataA.append('_method', 'PUT')

      this.$axios.post(url, formDataA)
        .then(response => {
          console.log(url)
          console.log(response)
          this.$toast.success('Successfully edit')
          this.$store.dispatch('loadLoggedInUser')
          this.resetValues()
        })
        .catch(errorResponse => {
          if (errorResponse.response.data.message) {
            this.errors.push(errorResponse.response.data.message)
          }
          if (errorResponse.response.data.errors) {
            this.errors = Object.entries(errorResponse.response.data.errors).map(([a, b]) => a + ': ' + b)
          }
        })
    },
    cancel () {
      this.$router.push({ name: 'Users' })
    }
  },
  computed: {
    canSend () {
      return this.formData.name && this.formData.email && (this.formData.password ? this.formData.currentPassword : true)
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

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="login"
  >
    <h3 class="mt-5 mb-3">Login</h3>
    <hr>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputUsername"
          class="form-label"
        >Username</label>
        <input
          type="text"
          class="form-control"
          id="inputUsername"
          required
          v-model="credentials.username"
        >
        <field-error-message
          :errors="errors"
          fieldName="username"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label
          for="inputPassword"
          class="form-label"
        >Password</label>
        <input
          type="password"
          class="form-control"
          id="inputPassword"
          required
          v-model="credentials.password"
        >
        <field-error-message
          :errors="errors"
          fieldName="password"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="login"
      >Login</button>
    </div>
  </form>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      credentials: {
        username: '',
        password: ''
      },
      errors: null
    }
  },
  emits: [
    'login'
  ],
  methods: {
    login () {
      /* const url = '/login'
      this.$store.dispatch(url, this.credentials)
        .then(() => {
          this.$toast.success('User ' + this.$store.state.user.name + ' has entered the application.')
          this.$emit('login')
          this.$router.push({ name: 'Home' })
        })
        .catch(() => {
          this.credentials.password = ''
          this.$toast.error('User credentials are invalid!')
        }) */
      const url = '/login'
      this.$axios.post(url, this.credentials)
        .then((response) => {
          this.$toast.success('User ' + this.credentials.username + ' has entered the application.')
          this.$axios.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token
          this.$emit('login')
          this.$router.back()
        })
        .catch((errorResponse) => {
          console.log(errorResponse)
          delete this.$axios.defaults.headers.common.Authorization
          this.credentials.password = ''
          this.$toast.error('User credentials are invalid!')
        })
    }
  }
}
</script>

<style scoped>
</style>

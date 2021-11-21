<template>
<div class="container">
  <div class="card p-5">
    <h3 class="card-title text-center">Admin Login</h3>
    <form class="card-body thin-form" @submit.prevent="tryLogin">
      <div>
        <input class="form-control mb-2" name="email" type="text" placeholder="E-mail" v-model="email"/>
      </div>
      <div>
        <input class="form-control mb-1" name="password" placeholder="Password" type="password" v-model="password"/>
      </div>
      <div class="alert alert-danger p-2 mt-2" role="alert" v-show="loginError">
        Login failed, please try again.
      </div>
      <div>
        <input class="btn btn-primary w-100 d-block mt-3" type="submit" value="Login"/>
      </div>
    </form>
  </div>
</div>
</template>
<script>
import axios from 'axios'

export default {
  name: 'AdminLogin',
  data () {
    return {
      email: '',
      password: '',
      loginError: false
    }
  },
  methods: {
    tryLogin () {
      axios.post('admin/login', {
        email: this.email,
        password: this.password
      }).then(r => {
        console.log('r', r)
        sessionStorage.setItem('token', r.data.access_token)
        this.$router.push({ name: 'AdminHome' })
      }).catch(error => {
        if (error.response.status === 401) {
          this.loginError = true
        }
      })
    }
  }
}
</script>
<style>
  .thin-form {
  max-width: 300px;
  margin: auto;
  }
</style>

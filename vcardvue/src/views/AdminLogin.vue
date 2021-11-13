<template>
<div class="container">
  <div class="card p-5">
    <h3 class="card-title text-center">Admin Login</h3>
    <form class="card-body thin-form" @submit.prevent="tryLogin">
      <div>
        <input class="form-control" name="email" type="text" placeholder="E-mail"/>
      </div>
      <div>
        <input class="form-control mb-3" name="password" placeholder="Password" type="pasword"/>
      </div>
      <div>
        <input class="btn btn-primary mx-auto d-block" type="submit" value="Login"/>
      </div>
    </form>
  </div>
</div>
</template>
<script>
import axios from 'axios'
export default {
  name: 'AdminLogin',
  methods: {
    tryLogin () {
      axios.get('users').then(r => console.log(r.data))
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

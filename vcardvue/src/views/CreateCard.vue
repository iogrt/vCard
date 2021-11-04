<template>
  <div class="about">
    <h3>Create a vCard</h3>
    <form class="createcard--form">
      <div>
        <div v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <ul>
            <li v-for="error in errors" :key=error>{{ error }}</li>
          </ul>
        </div>
      </div>
      <div class="form-box">
        <div >
        <label for="name">Phone Number:</label>
        <input class="textbox" name="phone" type="tel" placeholder="999999999" v-model="phone" data-rule="required|phone">
      </div>
      <div>
        <label for="name">Password:</label>
        <input class="textbox" name="passw-ord" type="password" placeholder="Password" v-model="password" data-rule="required">
      </div>
      <div>
        <label for="name">Name:</label>
        <input class="textbox" name="name" type="text" placeholder="Name" v-model="name" data-rule="required">
      </div>
      <div>
        <label for="name">Email:</label>
        <input class="textbox" name="email" type="email" placeholder="email@email.com" v-model="email" data-rule="required">
      </div>
      <div>
        <label for="name">Confirmation Code:</label>
        <input class="textbox" name="conf_code" type="password" placeholder="9999" v-model="conf_code" data-rule="required">
      </div>
       <div>
         <label for="name">Upload photo:</label>
        <input class="textbox" name="photo" type="img" placeholder="Upload picture (to do)" v-model="photo">
      </div>
      </div>
      <div class="buttonstyle">
        <button type="submit" class="btn btn-success" :class="[{'disable':!canCreateCard}]" @click.prevent="createCard($event)" >Creat Card</button>
        <button type="reset" class="btn btn-danger" @click="resetValues">Reset</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      phone: null,
      password: null,
      name: null,
      email: null,
      conf_code: null,
      photo: null,
      errors: []
    }
  },
  methods: {
    resetValues () {
      this.phone = null
      this.password = null
      this.name = null
      this.email = null
      this.conf_code = null
      this.photo = null
    },
    async createCard (event) {
      event.preventDefault()
      this.errors = []

      if (this.errors.length) {
        return
      }
      if (!this.canCreateCard) {
        this.errors.push('Fill all the camps')
      }
      alert('CLICK')
    }
  },
  computed: {
    canCreateCard () {
      return this.phone && this.password && this.name && this.email && this.conf_code
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

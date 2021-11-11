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
        <input class="textbox" name="phone" type="number" min="900000000" max="999999999" placeholder="999999999" v-model="formData.phone" data-rule="required|phone">
      </div>
      <div>
        <label for="name">Password:</label>
        <input class="textbox" name="passw-ord" type="password" placeholder="Password" v-model="formData.password" data-rule="required">
      </div>
      <div>
        <label for="name">Name:</label>
        <input class="textbox" name="name" type="text" placeholder="Name" v-model="formData.name" data-rule="required">
      </div>
      <div>
        <label for="name">Email:</label>
        <input class="textbox" name="email" type="email" placeholder="email@email.com" v-model="formData.email" data-rule="required">
      </div>
      <div>
        <label for="name">Confirmation Code:</label>
        <input class="textbox" name="conf_code" type="number" max="9999" placeholder="9999" v-model="formData.conf_code" data-rule="required">
      </div>
      <div>
        <label for="name">Upload photo:</label>
          <input type="file" accept="image/*" @change="uploadImage($event)" id="file-input">
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
import axios from 'axios'
const baseUrl = 'http://vcardlaravel.test/api/'
export default {
  data () {
    return {
      formData: {
        phone: null,
        password: null,
        name: null,
        email: null,
        conf_code: null,
        photo: null
      },
      errors: []
    }
  },
  methods: {
    resetValues () {
      this.formData.phone = null
      this.formData.password = null
      this.formData.name = null
      this.formData.email = null
      this.formData.conf_code = null
      this.formData.photo = null
    },
    async createCard (event) {
      event.preventDefault()
      const url = baseUrl + 'vcards'
      axios.post(url, this.formData)
        .then(response => {
          console.log(url)
          console.log(response)
        })
        .catch(error => {
          console.log(error)
        })

      this.errors = []

      if (this.errors.length) {
        return
      }
      if (!this.canCreateCard) {
        this.errors.push('Fill all the camps')
      }

      if (String(this.formData.phone).length !== 9) {
        this.errors.push('Phone Should have 9 numbers')
        return
      }
      if (String(this.formData.conf_code).length !== 4) {
        this.errors.push('Confirmation code Should have 4 numbers')
        return
      }
      alert('CLICK')
    },
    uploadImage (e) {
      const image = e.target.files[0]
      const reader = new FileReader()
      reader.readAsDataURL(image)
      reader.onload = e => {
        this.previewImage = e.target.result
        // console.log(this.previewImage)
      }
    }
  },
  computed: {
    canCreateCard () {
      return this.formData.phone && this.formData.password && this.formData.name && this.formData.email && this.formData.conf_code
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

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="createCard" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Add admin</h3>
    <hr>

    <div class="mb-3">
      <label for="name" class="form-label">Name:</label>
        <input class="textbox  form-control" name="name" type="text" placeholder="Name" v-model="formData.name" data-rule="required">
    </div>

    <FieldErrorMessage :errors="errors" fieldName="name" />
    <div class="mb-3">
      <label for="name" class="form-label">Email:</label>
        <input class="textbox  form-control" name="email" type="email" placeholder="email@email.com" v-model="formData.email" data-rule="required">
    </div>
    <FieldErrorMessage :errors="errors" fieldName="email" />

    <div class="mb-3">
      <label for="name" class="form-label">Password:</label>
        <input class="textbox  form-control" name="passw-ord" type="password" placeholder="Password" v-model="formData.password" data-rule="required">
    </div>
    <FieldErrorMessage :errors="errors" fieldName="password" />
    <div class="mb-3 d-flex justify-content-end">
      <button type="button" class="btn btn-primary px-4" :class="[{'disable':!canCreateAdmin}]" @click.prevent="createCard($event)"> Create Admin </button>
      <button type="reset" class="btn btn-danger  px-4" @click="resetValues">Reset</button>
      <button type="button" class="btn btn-light px-4" @click="cancel" >Back</button>
    </div>
  </form>
</template>

<script>
import FieldErrorMessage from '@/components/global/FieldErrorMessage'

export default {
  components: {
    FieldErrorMessage
  },
  data () {
    return {
      formData: {
        password: null,
        name: null,
        email: null
      },
      errors: [],
      msgErrors: []
    }
  },
  methods: {
    resetValues () {
      this.formData.password = null
      this.formData.name = null
      this.formData.email = null
    },
    async createCard (event) {
      event.preventDefault()
      const url = '/admin/users'

      this.errors = []

      /* eslint-disable */
      const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
      /* eslint-enable */

      const formDataA = new FormData()
      formDataA.append('password', this.formData.password)
      formDataA.append('name', this.formData.name)
      formDataA.append('email', this.formData.email)

      if (this.errors.length) {
        return
      }
      if (!this.canCreateAdmin) {
        this.$toast.error('Fill all the camps')
        return
      }
      if (String(this.formData.password).length < 6) {
        this.errors.password = ['Password Should have more that 6 caracteres']
        return
      }
      if (String(this.formData.name).length < 3 || !String(this.formData.name).trim()) {
        this.errors.name = ['Name Should have more that 3 caracteres']
        return
      }
      if (!emailRegex.test(this.formData.email)) {
        this.errors.email = ['email invalido']
        return
      }

      this.$axios.post(url, formDataA)
        .then(response => {
          console.log(url)
          console.log(response)
          this.$toast.success('Successfully created')
          this.resetValues()
        })
        .catch(errorResponse => {
          if (errorResponse.response.status === 422 || errorResponse.response.status === 500) {
            this.$toast.error(errorResponse.response.data.message)
          }
        })
    },
    cancel () {
      this.$router.push({ name: 'Users' })
    }
  },
  computed: {
    canCreateAdmin () {
      return this.formData.password && this.formData.name && this.formData.email
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

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="createCard" enctype='multipart/form-data'>
    <h3 class="mt-5 mb-3">Edit debit limit of {{vcardId}}</h3>
    <hr>

    Current value: {{this.maxDebit}}€
    <div class="mb-3">
      <label for="debitlimit" class="form-label">Debit Limit</label>
        <input class="textbox form-control" name="debitlimit" type="number" placeholder="insert a debit limit" v-model="formData.maxdebit">
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
      <button type="button" class="btn btn-primary px-4" @click.prevent="editDebitLimit($event)"> Update Debit Limit</button>
      <button type="reset" class="btn btn-danger  px-4" @click="resetValues">Reset</button>
      <button type="button" class="btn btn-light px-4" @click="cancel" > Back </button>
    </div>
  </form>
</template>

<script>

export default {
  props: {
    vcardId: {
      type: Number,
      required: true
    },
    maxDebit: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      formData: {
        maxdebit: null
      },
      errors: [],
      msgErrors: []
    }
  },
  methods: {
    resetValues () {
      this.formData.maxdebit = this.maxDebit
    },
    async editDebitLimit (event) {
      event.preventDefault()
      const url = `/admin/vcards/${this.vcardId}/maxdebit`

      this.errors = []

      this.$axios.patch(url, this.formData)
        .then(response => {
          console.log(url)
          console.log(response)
          this.$toast.success('Successfully edited')
          this.resetValues()
          this.$router.push({ name: 'Users' })
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
    cancel () {
      this.$router.push({ name: 'Users' })
    }
  },
  computed: {
    canEdit () {
      return this.formData.maxdebit && this.formData.maxDebit > 0
    }
  },
  created () {
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

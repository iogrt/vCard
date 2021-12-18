<template>
<div>
  <ConfirmationDialog
      ref="confirmationDialog"
      confirmationBtn="Discard changes and leave"
      msg="Do you really want to leave? You have unsaved changes!"
      @confirmed="leaveConfirmed"
  >
  </ConfirmationDialog>

  <form
      class="row g-3 needs-validation"
      novalidate
      @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3">{{ title }}</h3>
    <hr>

    <div class="mb-3">
      <label
          for="inputName"
          class="form-label"
      >Name</label>
      <input
          type="text"
          class="form-control"
          id="inputName"
          placeholder="Category Name"
          required
          v-model="editingCategory.name"
      >
      <FieldErrorMessage
          :errors="errors"
          fieldName="name"
      ></FieldErrorMessage>
    </div>
    <div class="mb-3">
      <label
          for="inputType"
          class="form-label"
      >Type</label>
      <select
          class="form-select"
          id="inputType"
          v-model="editingCategory.type"
      >
        <option value="C">
          Credit
        </option>
        <option value="D">
          Debit
        </option>
      </select>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button
          type="button"
          class="btn btn-primary px-5"
          @click="save"
      >
        Save
      </button>
      <button
          type="button"
          class="btn btn-light px-5"
          @click="cancel"
      >
        Cancel
      </button>
    </div>
  </form>
  </div>
</template>

<script>
import FieldErrorMessage from '../global/FieldErrorMessage'
import ConfirmationDialog from '../global/ConfirmationDialog'
export default {
  name: 'Category',
  components: { ConfirmationDialog, FieldErrorMessage },
  props: {
    id: {
      type: Number
    }
  },
  data () {
    return {
      category: null,
      editingCategory: null,
      errors: {}
    }
  },
  created () {
    if (this.id != null && this.id !== -1) {
      this.loadCategory()
    } else {
      this.newCategory()
    }
  },
  watch: {
    category (newCategory) {
      this.editingCategory = { ...newCategory }
    }
  },
  computed: {
    title () {
      return this.id === null
        ? 'New Category'
        : 'Category #' + this.editingCategory.id
    }
  },
  methods: {
    newCategory () {
      this.category = {
        id: null,
        name: '',
        type: ''
      }
    },
    save () {
      if (JSON.stringify(this.editingCategory) === JSON.stringify(this.category)) {
        this.$toast.info('You haven\'t edited anything out')
        return
      }

      if (this.id != null && this.id !== -1) {
        this.$store.dispatch('updateCategory', this.editingCategory)
          .then(category => {
            console.log(category)
            this.category = category
            this.$toast.success(`Successfully updated category ${this.category.id}!`)
            this.$router.back()
          })
          .catch(err => {
            this.$toast.error(err.data.message)
            this.errors = Object.entries(err.response.data.errors).map(([a, b]) => a + ': ' + b)
          })

        return
      }

      this.$store.dispatch('insertCategory', this.editingCategory)
        .then(category => {
          this.category = category
          this.$toast.success(`Successfully created category ${this.category.name}!`)
          this.$router.back()
        })
        .catch(err => {
          this.$toast.error(err.message)
          this.errors = Object.entries(err.response.data.errors).map(([a, b]) => a + ': ' + b)
        })
    },
    cancel () {
      this.$router.back()
    },
    loadCategory () {
      this.errors = null
      this.$axios.get(`vcards/categories/${this.id}`)
        .then(response => {
          this.category = response.data.data
          console.log(this.category)
        })
        .catch(response => {
          this.errors = Object.entries(response.response.data.errors).map(([a, b]) => a + ': ' + b)
          this.$toast.error(response.data.message)
        })
    },
    leaveConfirmed () {
      if (this.nextCallBack) {
        this.nextCallBack()
      }
    },
    dataAsString () {
      return JSON.stringify(this.editingCategory)
    }
  },
  beforeRouteLeave (to, from, next) {
    console.log('JAJAJAJAJAJA')
    this.nextCallBack = null

    if (JSON.stringify(this.category) !== this.dataAsString()) {
      this.nextCallBack = next
      this.$refs.confirmationDialog.show()
    } else {
      next()
    }
  }
}
</script>

<style scoped>
.total_hours {
  width: 26rem;
}
</style>

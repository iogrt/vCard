<template>
<div>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Your Vcard's Categories</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ this.$store.getters.totalCategories }}</h5>
    </div>
  </div>
  <hr>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
          for="selectType"
          class="form-label"
      >Filter by type:</label>
      <select
          class="form-select"
          id="selectType"
          v-model="filterByType"
      >
        <option :value="null"></option>
        <option value="C">Credit</option>
        <option value="D">Debit</option>
      </select>
    </div>
    <div class="mx-2 mt-2 flex-grow-1 filter-div">
      <label
          for="selectName"
          class="form-label"
      >Filter by name:</label>

      <input id="selectName" class="textbox  form-control" type="text" placeholder="Name" v-model="filterByName">
    </div>
    <div class="mx-2 mt-2">
      <button
          type="button"
          class="btn btn-success px-4 btn-addCategory"
          @click="addCategory"
      ><i class="bi bi-xs bi-plus-circle"></i>Add Category</button>
    </div>
  <CategoriesTable
      :categories="$store.getters.filteredCategories(filterByName,filterByType)"
      @edit="editCategory"
      @delete="deleteConfirmed">
  </CategoriesTable>
  </div>
</template>

<script>

import CategoriesTable from './CategoriesTable'
export default {
  name: 'CategoriesManage',
  components: { CategoriesTable },
  data () {
    return {
      filterByType: '',
      filterByName: ''
    }
  },
  computed: {
  },
  methods: {
    addCategory () {
      this.$router.push({ name: 'NewCategory' })
    },
    editCategory (cat) {
      this.$router.push({ name: 'EditCategory', params: { id: cat.id } })
    },
    deleteConfirmed (cat) {
      this.$store.dispatch('deleteCategory', cat)
        .then(category => {
          this.$toast.success('Successfully deleted')

          this.$emit('deleted', category)
        })
        .catch(error => {
          if (error.status === 422) {
            console.log(error.response.data.errors)
            Object.keys(error.response.data.errors).forEach(x => this.$toast.error(error.response.data.errors[x]))
          } else {
            this.$toast.error(error)
          }
        })
    }
  }
}
</script>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addCategory {
  margin-top: 1.85rem;
}
</style>

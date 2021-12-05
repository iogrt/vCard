<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Your Vcard's Categories</h3>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalCategories }}</h5>
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
          id="selectCompleted"
          v-model="filterByType"
      >
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
      ><i class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Category</button>
    </div>
  <CategoriesTable
      :categories="filteredCategories">
  </CategoriesTable>
</template>

<script>

import CategoriesTable from './CategoriesTable'
export default {
  name: 'CategoriesManage',
  components: { CategoriesTable },
  data () {
    return {
      categories: [],
      filterByType: '',
      filterByName: ''
    }
  },
  computed: {
    filteredCategories () {
      return this.categories.filter((cat, index) => {
        const funName = () => this.filterByName ? cat.name === this.filterByName : true
        const funType = () => this.filterByType ? cat.type === this.filterByType : true

        return funName() && funType()
      })
    },
    totalCategories () {
      return this.categories.length
    }
  },
  methods: {
    loadCategories () {
      this.$axios.get('vcards/categories')
        .then(response => {
          this.categories = response.data.data
          console.log(this.categories)
        })
        .catch((error) => {
          console.error(error)
        })
    }
    /* addCategory () {
      this.$router.push({ name: 'NewTask' })
    },
    editCategory (task) {
      this.$router.push({ name: 'Task', params: { id: task.id } })
    },
    deletedCategory (deletedTask) {
      const idx = this.tasks.findIndex((t) => t.id === deletedTask.id)
      if (idx >= 0) {
        this.tasks.splice(idx, 1)
      }
    } */
  },
  created () {
    this.loadCategories()
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

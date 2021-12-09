<!-- <template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Payment Types</h3>
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
    ><i class="bi bi-xs bi-plus-circle"></i>Add Category</button>
  </div>
  <CategoriesTable
      :categories="filteredCategories"
      @edit="editCategory"
      @deleted="deletedCategory">
  </CategoriesTable>
</template>

<script>

import PaymentTypesTable from './PaymentTypesTable'

export default {
  name: 'CategoriesManage',
  // eslint-disable-next-line vue/no-unused-components
  components: { PaymentTypesTable },
  data () {
    return {
      paymentTypes: [],
      filterByCode: '',
      filterByName: '',
      filterByDescription: ''
    }
  },
  computed: {
    filteredTypes () {
      return this.paymentTypes.filter((cat, index) => {
        const funName = () => this.filterByName ? cat.name.toLowerCase().includes(this.filterByName.toLowerCase()) : true
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
    },
    addCategory () {
      this.$router.push({ name: 'NewCategory' })
    },
    editCategory (cat) {
      this.$router.push({ name: 'EditCategory', params: { id: cat.id } })
    },
    deletedCategory (cat) {
      console.log('gegegregerg')
      const idx = this.categories.findIndex(t => t.id === cat.id)
      if (idx >= 0) {
        this.categories.splice(idx, 1)
      }
    }
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
-->

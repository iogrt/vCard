<template>
  <confirmation-dialog
      ref="confirmationDialog"
      confirmationBtn="Delete Default category"
      :msg="`Do you really want to delete the default category ${ categoryToDeleteDescription }?`"
      @confirmed="deleteConfirmed"
  >
  </confirmation-dialog>
  <table class="table">
    <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Type</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="cat in categories"
        :key="cat.id"
    >
      <td>{{ cat.id }}</td>
      <td>{{ cat.name}} </td>
      <td>{{ cat.type}} </td>
      <td class="text-end">
        <div class="d-flex justify-content-end">
          <button
              class="btn btn-xs btn-light"
              @click="editClick(cat)"
          ><i class="bi bi-xs bi-pencil"></i>
          </button>

          <button
              class="btn btn-xs btn-light"
              @click="deleteClick(cat)"
          ><i class="bi bi-xs bi-x-square-fill"></i>
          </button>
        </div>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: 'DefaultCategoryTable',
  props: {
    categories: {
      type: Array,
      default: () => []
    }
  },
  emits: [
    'edit',
    'deleted'
  ],
  data () {
    return {
      editingCategories: this.categories,
      categoryToDelete: null
    }
  },
  computed: {
    categoryToDeleteDescription () {
      return this.categoryToDelete
        ? `#${this.categoryToDelete.id} (${this.categoryToDelete.description})`
        : ''
    }
  },
  watch: {
    categories (newCategories) {
      this.editingCategories = [...newCategories]
    }
  },
  methods: {
    editClick (cat) {
      this.$router.push({ name: 'EditDefaultCategory', params: { id: cat.id } })
    },
    deleteConfirmed () {
      this.$axios.delete('admin/categories/' + this.categoryToDelete.id)
        .then((response) => {
          console.log(response.data.data)
          this.$toast.success('Successfully deleted')

          this.$emit('deleted', this.categoryToDelete)
        })
        .catch(error => {
          this.$toast.error(error.response.data.message)
          console.log(error)
        })
    },
    deleteClick (cat) {
      this.categoryToDelete = cat
      const dlg = this.$refs.confirmationDialog
      dlg.show()
    }
  }
}
</script>

<style scoped>
.completed {
  text-decoration: line-through;
}

button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>

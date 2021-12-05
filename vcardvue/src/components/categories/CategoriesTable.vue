<template>
  <confirmation-dialog
      ref="confirmationDialog"
      confirmationBtn="Delete category"
      :msg="`Do you really want to delete the category ${ categoryToDeleteDescription }?`"
      @confirmed="deleteConfirmed"
  >
  </confirmation-dialog>
  <table class="table">
    <thead>
    <tr>
      <th>#</th>
      <th>Type</th>
      <th>Name</th>
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
  name: 'CategoriesTable',
  props: {
    categories: {
      type: Array,
      default: () => []
    }
  },
  emits: [
    'completeToggled',
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
    /* tasks (newTasks) {
      this.editingTasks = newTasks
    } */
  },
  methods: {
    editClick (cat) {
      this.$emit('edit', cat)
    },
    deleteConfirmed () {
      this.$axios.delete('categories/', {
        data: {
          name: this.categoryToDelete.name,
          type: this.categoryToDelete.type
        }
      })
        .then((response) => {
          const deletedCat = response.data.data
          this.$emit('deleted', deletedCat)
        })
        .catch((error) => {
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

<template>
<div>
  <ConfirmationDialog
      ref="confirmationDialog"
      confirmationBtn="Delete category"
      :msg="`Do you really want to delete the category ${ categoryToDeleteDescription }?`"
      @confirmed="deleteConfirmed"
  >
  </ConfirmationDialog>
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
  </div>
</template>

<script>
import ConfirmationDialog from '../global/ConfirmationDialog'
export default {
  name: 'CategoriesTable',
  components: { ConfirmationDialog },
  props: {
    categories: {
      type: Array,
      default: () => []
    }
  },
  emits: [
    'edit',
    'delete'
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
    deleteClick (cat) {
      this.categoryToDelete = cat
      const dlg = this.$refs.confirmationDialog
      dlg.show()
    },
    deleteConfirmed () {
      this.$emit('delete', this.categoryToDelete)
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

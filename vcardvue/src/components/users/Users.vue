<template>
  <h3 class="mt-5 mb-3">Team Members</h3>
  <hr>
  <user-table
    :users="$store.getters.users"
    :showId="false"
    @edit="editUser"
  ></user-table>
</template>

<script>

export default {
  name: 'Users',
  components: {
    // UserTable
  },
  computed: {
    totalUsers () {
      return this.$store.getters.totalUsers
    }
  },
  methods: {
    editUser (user) {
      this.$router.push({ name: 'User', params: { id: user.id } })
    }
  }
}
</script>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 2.3rem;
}
</style>

<template>
  <h3 class="mt-5 mb-3">Vcard users</h3>
  <hr>

  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <label for="showPhone" class="form-label" >Show phone number on vcards:</label>
    <input id="showPhone" type="checkbox" v-model="showPhoneNumber" />

  </div>
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
    <option value="V">Vcard</option>
    <option value="A">Admin</option>
    <option value="">None</option>
  </select>
  </div>

  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <label
        for="selectName"
        class="form-label"
    >Filter by {{this.showPhoneNumber ? 'phone number' : 'name'}}:</label>

    <input id="selectName" class="textbox  form-control" type="text" placeholder="Name" v-model="filterByName">
  </div>
  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <label
        for="selectEmail"
        class="form-label"
    >Filter by email:</label>

    <input id="selectEmail" class="textbox  form-control" type="email" placeholder="Email" v-model="filterByEmail">
  </div>
  <div class="mx-2 mt-2">
    <button
        type="button"
        class="btn btn-success px-4 btn-addCategory"
        @click="addAdmin"
    ><i class="bi bi-xs bi-plus-circle"></i>Add New Administrator</button>
  </div>
  <UserTable
      :users="filteredUsers"
      :showPhoneNumber="showPhoneNumber"
      @edit="editUser"
  ></UserTable>
</template>

<script>

import UserTable from './UserTable'

export default {
  name: 'Users',
  components: {
    UserTable
  },
  data () {
    return {
      users: [],
      filterByName: '',
      filterByType: '',
      filterByEmail: '',
      showPhoneNumber: true
    }
  },
  created () {
    this.getUsers()
  },
  computed: {
    totalUsers () {
      return this.users.length
    },
    filteredUsers () {
      return this.users.filter(x => {
        const filternameFN = () => this.filterByName ? (this.showPhoneNumber ? x.name.toLowerCase().includes(this.filterByName.toLowerCase()) : x.phone_number.toLowerCase().includes(this.filterByName.toLowerCase())) : true
        const filtertypeFN = () => this.filterByType ? x.user_type === this.filterByType : true
        const filteremailFN = () => this.filterByEmail ? x.email === this.filterByEmail : true

        return filternameFN() && filtertypeFN() && filteremailFN()
      })
    }
  },
  methods: {
    getUsers () {
      this.$axios.get('admin/users')
        .then(response => {
          this.users = response.data.data
          console.log(this.users)
        })
        .catch(err => {
          console.log(err)
        })
    },

    editedUser (user) {
      this.$router.push({ name: 'User', params: { id: user.id } })
    },
    removedUser (user) {
      const idx = this.users.findIndex(x => user.user_type === 'A' ? x.email === user.email : x.phone_number === user.phone_number)

      if (idx >= 0) {
        this.users.slice(idx, 1)
      }
    },
    blockedUser (user) {
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

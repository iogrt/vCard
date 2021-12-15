<template>
  <table class="table">
    <thead>
      <tr>
        <th
          class="align-middle"
        >Name</th>
        <th class="align-middle">Photo</th>
        <th
          class="align-middle"
        >Email</th>
        <th
            class="align-middle"
        >Balance</th>
        <th
            class="align-middle"
        >Max Debit</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="user in users"
        :key="user.phone_number"
      >
        <td
            class="align-middle"
        >
          <img
              :src="photoUserType(user)"
              class="rounded-circle img_photo"
              alt="profile">
          {{this.showPhoneNumber && user.user_type === 'V' ? user.phone_number : user.name}}

        </td>
        <td
          class="align-middle"
        >
          <img
            :src="photoFullUrl(user)"
            class="rounded-circle img_photo"
           alt="profile">
        </td>
        <td class="align-middle">{{ user.email }}</td>
        <td
            class="align-middle"
        >{{ user.user_type === 'V' ? user.balance : ''}}</td>

        <td
            class="align-middle"
        >{{user.user_type === 'V' ? user.max_debit : ''}}</td>
        <td
          class="text-end align-middle"
        >
            <button
              class="btn btn-xs btn-light"
              @click="editClick(user)"
            ><i class="bi bi-xs bi-pencil"></i>
            </button>
            <button :disabled="user.user_type === 'V' && user.balance === 0"
                class="btn btn-xs btn-light"
                @click="removeClick(user)"
            ><i class="bi bi-xs bi-x-square-fill"></i>
            </button>
            <button v-if="user.user_type === 'V'"
                class="btn btn-xs btn-light"
                @click="blockClick(user)"
            ><i :class="{ ['bi-shield' + (user.blocked ? '-fill' : '')]: true }" class="bi bi-xs" ></i>
            </button>
            <router-link v-if="user.user_type === 'V'"
                class="btn btn-xs btn-light"
                :to="{ name: 'CreditTransactionCreate', params: {id: user.phone_number}}"
            ><i class="bi bi-xs bi-cash"></i>
            </router-link>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {

  name: 'UserTable',
  props: {
    users: {
      type: Array,
      default: () => []
    },
    showPhoneNumber: {
      type: Boolean,
      default: () => true
    }
  },
  emits: [
    'edit',
    'remove',
    'block'
  ],
  methods: {
    photoFullUrl (user) {
      return user.photo_url
        ? this.$serverUrl + '/storage/fotos/' + user.photo_url
        : './img/avatar-none.png'
    },
    photoUserType (user) {
      return user.user_type === 'A' ? './img/Admin.png' : './img/VCard_V.png'
    },
    editClick (user) {
      this.$router.push({ name: 'User', params: { id: user.id } })
    },
    removeClick (user) {
      if (user.user_type === 'V' || user.balance !== 0) {
        this.$toast.error('can\'t remove vcard with a positive balance!')
      }

      if (user.user_type === 'V') {
        this.$axios.delete(`admin/vcards/${user.phone_number}`)
          .then(response => {
            this.$toast.success('successfuly deleted vcard ' + user.phone_number)
            this.$emit('remove', user)
          })
          .catch(err => {
            if (err.status === 422) {
              this.$toast.error(err.response.data.message)
            } else {
              this.$toast.error(err.message)
            }
          })
      } else if (user.user_type === 'A') {
        this.$axios.delete(`admin/${user.phone_number}`)
          .then(response => {
            this.$toast.success('successfuly deleted admin ' + user.email)
            this.$emit('remove', user)
          })
          .catch(err => {
            if (err.status === 422) {
              this.$toast.error(err.response.data.message)
            } else {
              this.$toast.error(err.message)
            }
          })
      }
    },
    blockClick (user) {
      if (user.user_type !== 'V') { return }

      this.$axios.patch(`admin/vcard/${user.phone_number}/block`)
        .then(response => {
          console.log('HEHE', response)
          this.$toast.success(`successfully ${response.data.data.blocked === true ? 'blocked' : 'unblocked'} vcard ${user.phone_number}`)
          this.$emit('block', user)
          this.updateList()
        })
        .catch(err => {
          if (err.status === 422) {
            this.$toast.error(err.response.data.message)
          } else {
            this.$toast.error(err.message)
          }
        })
    },
    updateList () {
      this.$emit('refresh')
    }
  }
}
</script>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>

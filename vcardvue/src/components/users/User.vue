<template>
  <confirmation-dialog
    ref="confirmationDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>
  <user-detail
    :user="user"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></user-detail>
</template>

<script>

export default {
  name: 'User',
  components: {
    // UserDetail
  },
  props: {
    id: {
      type: Number,
      default: null
    }
  },
  data () {
    return {
      user: this.newUser(),
      errors: null
    }
  },
  watch: {
    // beforeRouteUpdate was not fired correctly
    // Used this watcher instead to update the ID
    id: {
      immediate: true,
      handler (newValue) {
        this.loadUser(newValue)
      }
    }
  },
  methods: {
    dataAsString () {
      return JSON.stringify(this.user)
    },
    newUser () {
      return {
        id: null,
        name: '',
        email: '',
        gender: 'M',
        photo_url: null
      }
    },
    loadUser (id) {
      this.errors = null
      if (!id || (id < 0)) {
        this.user = this.newUser()
        this.originalValueStr = this.dataAsString()
      } else {
        this.$axios.get('users/' + id)
          .then((response) => {
            this.user = response.data.data
            this.originalValueStr = this.dataAsString()
          })
          .catch((error) => {
            console.log(error)
          })
      }
    },
    save () {
      this.errors = null
      this.$store.dispatch('updateUser', this.user)
        .then((user) => {
          this.$toast.success(`User #${user.id} (${user.name}) was updated successfully.`)
          this.user = user
          this.originalValueStr = this.dataAsString()
          this.$router.back()
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.$toast.error(`User #${this.user.id} (${this.user.name}) was not updated due to validation errors!`)
            this.errors = Object.entries(error.response.data.errors).map(([a, b]) => a + ': ' + b)
          } else {
            this.$toast.error(`User #${this.user.id} (${this.user.name}) was not updated due to unknown server error!`)
          }
        })
    },
    cancel () {
      // Replace this code to navigate back
      // this.loadUser(this.id)
      this.$router.back()
    },
    leaveConfirmed () {
      if (this.nextCallBack) {
        this.nextCallBack()
      }
    }
  },
  sockets: {
    updateUser (user) {
      if (this.id === user.id) {
        this.loadUser(this.id)
      }
    }
  },
  beforeRouteLeave (to, from, next) {
    this.nextCallBack = null
    const newValueStr = this.dataAsString()
    if (this.originalValueStr !== newValueStr) {
      this.nextCallBack = next
      const dlg = this.$refs.confirmationDialog
      dlg.show()
    } else {
      next()
    }
  }
}
</script>

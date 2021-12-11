<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My vCard</h1>
  </div>
  <div>
    <Vcard :user="user" canEdit />
    <p>Your balance: <span class="fs-5 text-success">{{user.balance}}€</span></p>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Vcard from '@/components/global/Vcard.vue'

export default {
  name: 'Dashboard',
  components: {
    Vcard
  },
  computed: mapState(['user'])
}
</script>

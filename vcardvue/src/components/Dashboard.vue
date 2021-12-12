<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>
  <div>
    <h4>Some content</h4>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur, minus. Voluptatum cum iusto, commodi sunt molestias beatae adipisci architecto aspernatur, molestiae dicta placeat earum fugiat consequatur. Cum reiciendis ex amet!</p>
  </div>
  <div>
    <Vcard :user="$store.state.user" canEdit />
    <p>Your balance: <span class="fs-5 text-success">{{this.$store.state.user.balance}}€</span></p>
  </div>
</template>

<script>
export default {
  name: 'Dashboard'
}
</script>

<style scoped lang="scss">
</style>

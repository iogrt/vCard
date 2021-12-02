<template>
  <div class="container">
    <div class="p-5 rounded-3 bg-primary text-light text-right">
      <h1>vCard App</h1>
      <p>Create virtual cards online</p>
      <router-link :to="{name: 'CardCreate'}" class="btn btn-light">Create a card</router-link>

      <br />
      <br />
      <p>Debit money to other vCard</p>
      <router-link :to="{name: 'DebitTransactionCreate'}" class="btn btn-light">Create transaction</router-link>
    </div>

  </div>
</template>
<script>
export default {
  name: 'Home'
}
</script>

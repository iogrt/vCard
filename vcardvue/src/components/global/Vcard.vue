<template>
  <div class="card mb-4 gradient-card border-2 shadow-lg w-100 d-flex px-3 py-2 justify-content-around" style="max-width: 400px; height: 250px; border-radius: 10px;">
    <div><img class="rounded-circle" :src="user.photo_url" width="90"/></div>
    <p class="text-white mb-0 pt-3">
      <strong><i class="bi bi-telephone"></i>{{user.phone_number}}</strong><br>
      <small>{{user.name}}<br>
      <i class="bi bi-envelope"></i>{{user.email}}</small>
    </p>
    <router-link v-show="canEdit" title="Edit Info" :to="{name: 'EditCard'}" class="vcard-editbtn">
      <div class="btn btn-sm rounded-circle border-white text-center" style="border-style: dashed">
        <i class="bi bi-pen text-white fs-5 mx-auto"></i></div>
    </router-link>
    <h3 class="text-end vcard-logo"><i>vCard</i></h3>
  </div>
</template>

<script>
export default {
  name: 'Vcard',
  props: {
    user: Object,
    canEdit: Boolean
  }
}

</script>
<style scoped lang="css">
.gradient-card {
  background-color: #1a8be8; /* For browsers that do not support gradients */
  background-image: linear-gradient(to bottom right, #1a8be8, #41f0a4);
}

.vcard-logo {
  position: absolute;
  bottom: 0;
  right: 0.8rem;
}

.vcard-editbtn {
  position: absolute;
  top: 1rem;
  right: 0.8rem;

}

</style>

<template>
  <h2>Hello administrator!</h2>
</template>
<script>
import axios from 'axios'

export default {
  name: 'AdminHome',
  mounted () {
    axios.get('locked/users').then(r => console.log(r))
  }
}
</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Payment Types</h3>
    </div>
  </div>
  <hr>
  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <input id="chooseTimestamp" v-model="showTimestamps" type="checkbox" />
    &nbsp;
    <label
        for="chooseTimestamp"
        class="form-label"
    >Show TimeStamps</label>
  </div>
  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <label
        for="inputCode"
        class="form-label"
    >Filter by code:</label>
    <input id="inputCode" class="textbox  form-control" type="text" placeholder="Code" v-model="filterByCode">
  </div>
  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <label
        for="inputName"
        class="form-label"
    >Filter by name:</label>

    <input id="inputName" class="textbox  form-control" type="text" placeholder="Name" v-model="filterByName">
  </div>
  <div class="mx-2 mt-2 flex-grow-1 filter-div">
    <label
        for="inputDescription"
        class="form-label"
    >Filter by description:</label>
    <input id="inputDescription" class="textbox  form-control" type="text" placeholder="Description" v-model="filterByDescription">
  </div>
  <div class="mx-2 mt-2">
    <button
        type="button"
        class="btn btn-success px-4 btn-addPaymentType"
        @click="addPaymentType"
    ><i class="bi bi-xs bi-plus-circle"></i>Add Payment Type</button>
  </div>
  <PaymentTypesTable
      :payment-types="filteredTypes"
      :showTimestamps="showTimestamps"
      @edit="editPaymentType"
      @deleted="deletedPaymentType">
  </PaymentTypesTable>
</template>

<script>

import PaymentTypesTable from './PaymentTypesTable'

export default {
  name: 'PaymentTypes',
  // eslint-disable-next-line vue/no-unused-components
  components: { PaymentTypesTable },
  data () {
    return {
      paymentTypes: [],
      filterByCode: '',
      filterByName: '',
      filterByDescription: '',
      showTimestamps: false
    }
  },
  computed: {
    filteredTypes () {
      return this.paymentTypes.filter((cat, index) => {
        const funCode = () => this.filterByCode ? cat.code.toLowerCase().includes(this.filterByCode.toLowerCase()) : true
        const funName = () => this.filterByName ? cat.name.toLowerCase().includes(this.filterByName.toLowerCase()) : true
        const funDescription = () => this.filterByDescription ? cat.description.toLowerCase().includes(this.filterByDescription.toLowerCase()) : true

        return funName() && funCode() && funDescription()
      })
    }
  },
  methods: {
    loadPaymentTypes () {
      this.$axios.get('admin/payment_types')
        .then(response => {
          this.paymentTypes = response.data.data
          console.log(this.paymentTypes)
        })
        .catch((error) => {
          console.error(error)
        })
    },
    addPaymentType () {
      this.$router.push({ name: 'NewPaymentType' })
    },
    editPaymentType (cat) {
      this.$router.push({ name: 'EditPaymentType', params: { id: cat.id } })
    },
    deletedPaymentType (cat) {
      const idx = this.paymentTypes.findIndex(t => t.id === cat.id)

      if (idx >= 0) {
        this.paymentTypes.splice(idx, 1)
      }
    }
  },
  created () {
    this.loadPaymentTypes()
  }
}
</script>

<style scoped>
.filter-div {
  min-width: 12rem;
}
.total-filtro {
  margin-top: 0.35rem;
}
.btn-addPaymentType {
  margin-top: 1.85rem;
}
</style>

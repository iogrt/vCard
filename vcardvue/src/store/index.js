import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'

const emptyUser = {
  photo_url: 'img/avatar-none.png',
  name: '',
  email: ''
}
export default createStore({
  plugins: [createPersistedState({
    storage: window.sessionStorage,
    paths: [
      'user', 'token', 'categories', 'transactions'
    ]
  })],
  state: {
    user: emptyUser,
    categories: [],
    transactions: []
  },
  mutations: {
    resetUser (state) {
      state.user = emptyUser
    },
    resetToken (state) {
      state.token = null
    },
    setUser (state, loggedInUser) {
      const urlPhoto = loggedInUser ? loggedInUser.photo_url : null
      const ret = urlPhoto
        ? process.env.VUE_APP_BASE_URL + '/storage/fotos/' + urlPhoto
        : 'img/avatar-none.png'
      state.user = {
        ...loggedInUser,
        photo_url: ret
      }
    },
    setBalance (state, plus) {
      if (this.state.user.user_type === 'V') {
        state.user.balance += parseInt(plus)
      }
    },
    resetCategories (state) {
      state.categories = []
    },
    setCategories (state, categories) {
      state.categories = categories
    },
    insertCategory (state, category) {
      state.categories.push(category)
    },
    updateCategory (state, newCategory) {
      const idx = state.categories.findIndex(x => x.id === newCategory.id)

      if (idx < 0) { return }

      state.categories[idx] = newCategory
    },
    deleteCategory (state, category) {
      const idx = state.categories.findIndex(x => x.id === category.id)

      if (idx >= 0) { state.categories.splice(idx, 1) }
    },
    resetTransactions (state) {
      state.transactions = []
    },
    setTransactions (state, transactions) {
      state.transactions = transactions
    },
    insertTransaction (state, transaction) {
      state.transactions.push(transaction)
    },
    updateTransaction (state, transaction) {
      const idx = state.transactions.findIndex(x => x.id === transaction.id)

      if (idx < 0) { return }

      state.transactions[idx] = transaction
    },
    deleteTransaction (state, transaction) {
      const idx = state.transactions.findIndex(x => x.id === transaction.id)

      if (idx >= 0) { state.transactions.splice(idx, 1) }
    }
  },
  getters: {
    isLoggedIn (state) {
      return state.user.name !== ''
    },
    categories (state) {
      return state.categories
    },
    filteredCategories (state) {
      return (filterByName, filterByType) => state.categories.filter((cat, index) => {
        const funName = () => filterByName ? cat.name.toLowerCase().includes(filterByName.toLowerCase()) : true
        const funType = () => filterByType ? cat.type === filterByType : true

        return funName() && funType()
      })
    },
    totalCategories (state) {
      return state.categories.length
    },
    transactions (state) {
      return state.transactions
    },
    filteredTransactions (state) {
      return filterByType => state.transactions.filter((t, index) => {
        const funType = () => filterByType ? t.type === filterByType : true

        return funType()
      })
    },
    totalTransactions (state) {
      return state.transactions.length
    }
  },
  actions: {
    async login (context, credentials) {
      try {
        const response = await this.$axios.post('/login', credentials, { 'Content-Type': 'application/json' })
        this.$axios.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token
        sessionStorage.setItem('token', response.data.access_token)

        await context.dispatch('refresh')
      } catch (error) {
        console.log(error)
        delete this.$axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        context.commit('resetUser', null)

        throw error
      }
    },
    async logout (context) {
      try {
        await this.$axios.post('logout')

        this.$socket.emit('logged_out', context.state.user)
      } finally {
        delete this.$axios.defaults.headers.common.Authorization
        context.commit('resetUser')
        context.commit('resetCategories')
        context.commit('resetToken')
      }
    },
    async loadLoggedInUser (context) {
      try {
        const response = await this.$axios.get('users/me')
        console.log(response.data.data)
        context.commit('setUser', response.data.data)

        this.$socket.emit('logged_in', response.data.data)
      } catch (error) {
        delete this.$axios.defaults.headers.common.Authorization
        context.commit('resetUser', null)
        throw error
      }
    },
    async insertCategory (context, cat) {
      const category = (await this.$axios.post('vcards/categories', cat)).data.data
      context.commit('insertCategory', category)
      this.$socket.emit('newCategory', category)

      return category
    },
    async updateCategory (context, cat) {
      const category = (await this.$axios.put(`vcards/categories/${cat.id}`, cat)).data.data
      context.commit('updateCategory', category)
      this.$socket.emit('updateCategory', category)

      return category
    },
    async deleteCategory (context, cat) {
      const category = (await this.$axios.delete('vcards/categories', { data: cat })).data.data
      context.commit('deleteCategory', category)
      this.$socket.emit('deleteCategory', category)

      return category
    },
    async loadCategories (context) {
      if (context.state.user.user_type === 'A') {
        context.commit('setCategories', [])
        return
      }

      const categories = await this.$axios.get('vcards/categories')
      console.log(categories.data.data)
      context.commit('setCategories', categories.data.data)
    },

    async insertTransaction (context, trans) {
      const transaction = (await this.$axios.post('vcards/transactions', trans)).data.data
      context.commit('insertTransaction', transaction)
      this.$socket.emit('newTransaction', transaction)

      return transaction
    },
    // eslint-disable-next-line camelcase
    async updateTransaction (context, { id, description, category_id }) {
      const transaction = (await this.$axios.put(`vcards/transactions/${id}`, { description, category_id })).data.data
      context.commit('updateTransaction', transaction)
      this.$socket.emit('updateTransaction', transaction)

      return transaction
    },
    async loadTransactions (context) {
      if (context.state.user.user_type !== 'V') {
        context.commit('setTransactions', [])
        return
      }

      const response = await this.$axios.get('vcards/transactions')
      context.commit('setTransactions', response.data.data)
    },

    async blockVcard (context, vcard) {
      const response = await this.$axios.patch(`admin/vcard/${vcard}/block`)
      console.log(JSON.stringify(response.data.data))

      this.$socket.emit('blockVcard', response.data.data)
    },
    async refresh (context) {
      await context.dispatch('loadLoggedInUser')
      await context.dispatch('loadCategories')
      await context.dispatch('loadTransactions')
    },
    async SOCKET_newCategory (context, category) {
      console.log('Someone else has inserted a category')
      this.$toast.info(`A new Category was created (#${category.id} : ${category.name}#${category.type})`)
      context.commit('insertCategory', category)
    },
    async SOCKET_updateCategory (context, category) {
      console.log('Someone else has updated a category')
      this.$toast.info(`The category (#${category.id} : ${category.name}) was updated`)
      context.commit('updateCategory', category)
    },
    async SOCKET_deleteCategory (context, category) {
      console.log('Someone else has deleted a category')
      this.$toast.info(`The project (#${category.id} : ${category.name}) was deleted`)
      context.commit('deleteCategory', category)
    },

    async SOCKET_newTransaction (context, transaction) {
      console.log('HUEHUEHEUHUE')

      var opFN = null

      if (transaction.type === 'D') {
        opFN = x => -x
      } else {
        opFN = x => x
      }

      context.commit('setBalance', opFN(transaction.value))

      this.$toast.info(`You just transferred ${transaction.value}€ via ${transaction.payment_type}`)
      context.commit('insertTransaction', transaction)
    },
    async SOCKET_updateTransaction (context, transaction) {
      this.$toast.info(`The transaction (#${transaction.id}) was updated`)
      context.commit('updateTransaction', transaction)
    },

    async SOCKET_blockVcard (context) {
      this.$toast.info('Your vcard has been blocked :)')

      await context.dispatch('logout')
    },
    async SOCKET_newCreditTransaction (context, transaction) {
      console.log(transaction)
      if (context.state.user.user_type !== 'V' || transaction.type !== 'C' || transaction.payment_reference !== context.state.user.phone_number.toString()) { return }

      context.commit('setBalance', transaction.value)

      this.$toast.info(`You just received ${transaction.value}€ via ${transaction.payment_type}`)
    }
  }
})

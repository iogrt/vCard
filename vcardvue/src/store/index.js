import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'

import axios from 'axios'

const emptyUser = {
  photo_url: 'img/avatar-none.png',
  name: '',
  email: ''
}
export default createStore({
  plugins: [createPersistedState({
    storage: window.sessionStorage,
    paths: [
      'user'
    ]
  })],
  state: {
    user: emptyUser
  },
  mutations: {
    resetUser (state) {
      state.user = emptyUser
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
    }
  },
  getters: {
    isLoggedIn (state) {
      return state.user.name !== ''
    }
  },
  actions: {
    async login (context, credentials) {
      try {
        const response = await axios.post('/login', credentials, { 'Content-Type': 'application/json' })
        axios.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token
        sessionStorage.setItem('token', response.data.access_token)
      } catch (error) {
        console.log(error)
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        context.commit('resetUser', null)
        throw error
      }
      await context.dispatch('refresh')
    },
    async logout (context) {
      try {
        await axios.post('logout')
      } finally {
        delete axios.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        context.commit('resetUser', null)

        await context.dispatch('refresh')
      }
    },
    async loadLoggedInUser (context) {
      try {
        const response = await axios.get('users/me')
        console.log(response.data.data)
        context.commit('setUser', response.data.data)

        // this.$socket.emit('logged_in',response.data.data)
      } catch (error) {
        delete axios.defaults.headers.common.Authorization
        context.commit('resetUser', null)
        throw error
      }
    },
    /* async loadTransactions (context) {
       try {
       const response = await axios.get('transactions')
       context.commit('setTransactions', response.data.data)
       return response.data.data
       } catch (error) {
       context.commit('resetTransactions', null)
       throw error
       }
       }, */
    /* transactionsFilter: (state) => (type) => {
       return type.transaction.filter(p =>
       (!type || type === p.type)
       )
       }, */
    async refresh (context) {
      const userPromise = context.dispatch('loadLoggedInUser')
      // const transactionsPromise = context.dispatch('loadTransactions')

      await userPromise
      // await transactionsPromise
    }
  }
})

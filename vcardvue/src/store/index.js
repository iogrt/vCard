import { createStore } from 'vuex'

import axios from 'axios'

export default createStore({
  state: {
    user: null
  },
  mutations: {
    resetUser (state) {
      state.user = null
    },
    setUser (state, loggedInUser) {
      state.user = loggedInUser
    }
  },
  getter: {
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
    async refresh (context) {
      const userPromise = context.dispatch('loadLoggedInUser')

      await userPromise
    }
  }
})

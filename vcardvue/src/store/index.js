import { createStore } from 'vuex'

// import axios from 'axios'

export default createStore({
  state: {
  //  user: null
  },
  mutations: {
  /*  resetUser (state) {
      state.user = null
    },
    setUser (state, loggedInUser) {
      state.user = loggedInUser
    } */
  },
  actions: {
  },
  modules: {
  /*  async login (context, credentials) {
      try {
        const response = await axios.post('login', credentials)
        axios.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token
        sessionStorage.setItem('token', response.data.access_token)
      } catch (error) {
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
    } */
  }
})

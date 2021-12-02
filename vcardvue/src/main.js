import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
// import VueSocketIO from 'vue-3-socket.io'
import Toaster from '@meforma/vue-toaster'
import FieldErrorMessage from './components/global/FieldErrorMessage'
import ConfirmationDialog from './components/global/ConfirmationDialog'

/* const socketIO = new VueSocketIO({
  debug: true,
  connection: process.env.VUE_APP_WS_SERVER,
  vuex: {
    store,
    actionPrefix: 'SOCKET_',
    mutationPrefix: 'SOCKET_'
  }
}) */

const toastOptions = {
  position: 'top',
  timeout: 3000,
  pauseOnHover: true
}

const app = createApp(App).use(router).use(store).use(Toaster, toastOptions)

store.$toast = app.$toast

console.log(process.env)
axios.defaults.baseURL = process.env.VUE_APP_API_URL
app.config.globalProperties.$axios = axios
app.config.globalProperties.$serverUrl = process.env.VUE_APP_BASE_URL

app.component('field-error-message', FieldErrorMessage)
app.component('confirmation-dialog', ConfirmationDialog)

app.mount('#app')

store.$toast = app.$toast
// store.$socket = socketIO.io

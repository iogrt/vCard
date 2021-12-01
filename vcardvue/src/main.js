import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import Toaster from '@meforma/vue-toaster'
import FieldErrorMessage from './components/global/FieldErrorMessage'
import ConfirmationDialog from './components/global/ConfirmationDialog'

const toastOptions = {
  position: 'top',
  timeout: 3000,
  pauseOnHover: true
}

const app = createApp(App).use(store).use(router).use(Toaster, toastOptions)

store.$toast = app.$toast

console.log(process.env)
axios.defaults.baseURL = process.env.VUE_APP_API_URL
app.config.globalProperties.$axios = axios

app.component('field-error-message', FieldErrorMessage)
app.component('confirmation-dialog', ConfirmationDialog)

app.mount('#app')

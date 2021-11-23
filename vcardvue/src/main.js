import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import Toaster from '@meforma/vue-toaster'
import FieldErrorMessage from './global/FieldErrorMessage'

const toastOptions = {
  position: 'top',
  timeout: 3000,
  pauseOnHover: true
}

const app = createApp(App).use(store).use(router).use(Toaster, toastOptions)

axios.defaults.baseURL = process.env.VUE_APP_API_URL
app.config.globalProperties.$axios = axios

axios.interceptors.request.use(config => {
  config.headers = {
    Authorization: `Bearer ${sessionStorage.getItem('token')}`,
    Accept: 'application/json'
  }
  return config
})

axios.interceptors.response.use(res => res, err => {
  if (err.response.status === 401) {
    sessionStorage.setItem('token', '')
  }
  return Promise.reject(err)
})

app.component('field-error-message', FieldErrorMessage)

createApp(App).use(router).mount('#app')

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:80/api'
axios.interceptors.request.use(config => {
  config.headers = {
    Authorization: `Bearer ${sessionStorage.getItem('token')}`,
    Accept: 'application/json'
  }
  return config
})

// clean token on failed request
axios.interceptors.response.use(res => res, err => {
  if (err.response.status === 401) {
    sessionStorage.setItem('token', '')
  }
  return Promise.reject(err)
})

createApp(App).use(router).mount('#app')

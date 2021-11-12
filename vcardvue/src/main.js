import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'

const app = createApp(App).use(store).use(router)

console.log(process.env)
axios.defaults.baseURL = process.env.VUE_APP_API_URL
app.config.globalProperties.$axios = axios

app.mount('#app')

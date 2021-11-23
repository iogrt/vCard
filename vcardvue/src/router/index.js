import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import DebitTransactionCreate from '../views/DebitTransactionCreate.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/card/create',
    name: 'CardCreate',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/CreateCard.vue')
  },

  {
    path: '/card/transaction/debit',
    name: 'DebitTransactionCreate',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => DebitTransactionCreate
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

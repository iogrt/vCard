import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import DebitTransactionCreate from '../views/DebitTransactionCreate'
import AdminHome from '../views/AdminHome'
import About from '../views/About.vue'

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
    component: () => About
  },
  {
    path: '/card/create',
    name: 'CardCreate',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import('../views/CreateCard.vue')
  },
  {
    path: '/admin/login',
    name: 'AdminLogin',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "adminLogin" */ '../views/AdminLogin.vue')
  },
  {
    path: '/card/transaction/debit',
    name: 'DebitTransactionCreate',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => DebitTransactionCreate
  },
  {
    path: '/admin/',
    name: 'AdminHome',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => AdminHome,
    meta: {
      adminOnly: true
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

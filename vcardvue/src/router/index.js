import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'

// ver depois
import Dashboard from '../components/Dashboard.vue'
import Login from '../components/auth/Login.vue'
import ChangePassword from '../components/auth/ChangePassword.vue'
import Users from '../components/users/Users.vue'
import User from '../components/users/User.vue'
import Report from '../components/Report.vue'

import Card from '../components/cards/Card.vue'

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
    component: () => import(/* webpackChunkName: 'about' */ '../views/About.vue')
  },
  {
    path: '/cards',
    name: 'CardCreate',
    component: Card
  },

  // ver depois
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/password',
    name: 'ChangePassword',
    component: ChangePassword
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/reports',
    name: 'Reports',
    component: Report
  },
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/users/:id',
    name: 'User',
    component: User,
    // props: true
    // Replaced with the following line to ensure that id is a number
    props: route => ({ id: parseInt(route.params.id) })
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

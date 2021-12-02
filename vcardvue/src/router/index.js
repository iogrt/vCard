import { createRouter, createWebHashHistory } from 'vue-router'
import Home from '../views/Home.vue'
import DebitTransactionCreate from '../views/DebitTransactionCreate'
import About from '../views/About.vue'

// ver depois
import Dashboard from '../components/Dashboard.vue'
import Login from '../components/auth/Login.vue'
import ChangePassword from '../components/auth/ChangePassword.vue'
import Users from '../components/users/Users.vue'
import User from '../components/users/User.vue'

// import Report from '../components/Report.vue'

import Card from '../components/cards/Card.vue'

import store from '../store'

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
  /*  {
    path: '/reports',
    name: 'Reports',
    component: Report
  },
*/
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
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if ((to.name === 'Login') || (to.name === 'Home') || (to.name === 'CardCreate')) {
    next()
    return
  }
  if (!store.state.user) {
    next({ name: 'Login' })
    return
  }
  if (to.name === 'Reports') {
    if (store.state.user.user_type !== 'A') {
      next(false)
      return
    }
  }
  if (to.name === 'User') {
    if ((store.state.user.user_type === 'A') || (store.state.user.id === to.params.id)) {
      next()
      return
    }
    next(false)
    return
  }
  next()
})

export default router

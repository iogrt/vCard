import { createRouter, createWebHashHistory } from 'vue-router'
// import Home from '../views/Home.vue'
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
import Transactions from '../components/transactions/Transactions.vue'
// import Transaction from '../components/transactions/Transaction.vue'

import store from '../store'
import CategoriesManage from '../components/categories/CategoriesManage'

const routes = [
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
  {
    path: '/login',
    name: 'Login',
    component: Login
  },

  // ver depois
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
    path: '/transactions',
    name: 'Transactions',
    component: Transactions
  },
  /*  {
    path: '/transactions/:id',
    name: 'Transaction',
    component: Transaction,
    props: route => ({ id: parseInt(route.params.id) })
  }, */
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
    path: '/categories',
    name: 'CategoriesManage',
    component: CategoriesManage
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
  if ((to.name === 'Login') || (to.name === 'Dashboard') || (to.name === 'CardCreate')) {
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

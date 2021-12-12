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

import CreateCard from '../components/cards/CreateCard.vue'
import EditCard from '../components/cards/EditCard.vue'
import Transactions from '../components/transactions/Transactions.vue'
import Transaction from '../components/transactions/Transaction.vue'

import store from '../store'
import CategoriesManage from '../components/categories/CategoriesManage'
import Category from '../components/categories/Category'

import PaymentTypes from '../components/paymentTypes/PaymentTypes'
import PaymentType from '../components/paymentTypes/PaymentType'
import DefaultCategories from '../components/categories/DefaultCategories'
import DefaultCategory from '../components/categories/DefaultCategory'
import AdminStatistics from '../components/statistics/AdminStatistics'

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
    component: CreateCard
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
    path: '/card/edit',
    name: 'EditCard',
    component: EditCard
  },
  {
    path: '/transactions',
    name: 'Transactions',
    component: Transactions
  },
  {
    path: '/transactions/:id',
    name: 'Transaction',
    component: Transaction,
    props: route => ({ id: parseInt(route.params.id) })
  },
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/default_categories',
    name: 'DefaultCategories',
    component: DefaultCategories
  },
  {
    path: '/default_categories/:id',
    name: 'EditDefaultCategory',
    component: DefaultCategory,
    props: route => ({ id: parseInt(route.params.id) })
  },
  {
    path: '/default_categories/new',
    name: 'NewDefaultCategory',
    component: DefaultCategory,
    props: route => ({ id: null })
  },
  {
    path: '/categories',
    name: 'CategoriesManage',
    component: CategoriesManage
  },
  {
    path: '/categories/:id',
    name: 'EditCategory',
    component: Category,
    props: route => ({ id: parseInt(route.params.id) })
  },
  {
    path: '/categories/new',
    name: 'NewCategory',
    component: Category,
    props: route => ({ id: null })
  },
  {
    path: '/paymentTypes/:code',
    name: 'EditPaymentType',
    component: PaymentType,
    props: route => ({ code: route.params.code })
  },
  {
    path: '/paymentTypes/new',
    name: 'NewPaymentType',
    component: PaymentType,
    props: route => ({ code: null })
  },
  {
    path: '/paymentTypes',
    name: 'PaymentTypes',
    component: PaymentTypes
  },
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/admin/statistics',
    name: 'AdminStatistics',
    component: AdminStatistics
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

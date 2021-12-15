import { createRouter, createWebHashHistory } from 'vue-router'
// import Home from '../views/Home.vue'
import DebitTransactionCreate from '../components/transactions/DebitTransactionCreate'
import CreditTransactionCreate from '../components/transactions/CreditTransactionCreate'
import About from '../views/About.vue'

import Dashboard from '../components/Dashboard.vue'
import Login from '../components/auth/Login.vue'
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

const addAuthLevel = (authLevel) => route => ({
  ...route,
  meta: {
    ...route.meta,
    authLevel
  }
})

const anonymousRoutes = [
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => About
  },
  {
    path: '/card/new',
    name: 'CardCreate',
    label: 'Create Vcard',
    icon: 'bi-list-check',
    component: CreateCard
  },
  {
    path: '/login',
    name: 'Login',
    icon: 'bi-box-arrow-in-right',
    label: 'Login',
    component: Login
  }
]

const userRoutes = [
  {
    path: '/card',
    name: 'Dashboard',
    label: 'My vCard',
    icon: 'bi-house',
    component: Dashboard
  },
  {
    path: '/card/edit',
    name: 'EditCard',
    component: EditCard
  },
  {
    path: '/card/transaction/debit',
    name: 'DebitTransactionCreate',
    label: 'Send Money',
    icon: 'bi-send',
    component: () => DebitTransactionCreate
  },
  {
    path: '/categories',
    name: 'CategoriesManage',
    label: 'Categories',
    icon: 'bi-pentagon',
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
  }
]

const adminRoutes = [
  {
    path: '/users',
    name: 'Users',
    label: 'Users',
    icon: 'bi-people',
    component: Users
  },
  {
    path: '/administration',
    name: 'Administration',
    icon: 'bi-people',
    label: 'Administration',
    component: null
  },
  {
    path: '/transactions',
    name: 'Transactions',
    label: 'Transactions',
    icon: 'bi-list-stars',
    component: Transactions
  },
  {
    path: '/transactions/:id',
    name: 'Transaction',
    component: Transaction,
    props: route => ({ id: parseInt(route.params.id) })
  },
  {
    path: '/vcards/:id/addcredit',
    name: 'CreditTransactionCreate',
    component: () => CreditTransactionCreate,
    props: route => ({ vcard: parseInt(route.params.id) })
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
    path: '/default_categories',
    name: 'DefaultCategories',
    icon: 'bi-hexagon',
    label: 'Default Categories',
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
    icon: 'bi-pentagon',
    label: 'Payment Types',
    component: PaymentTypes
  },
  {
    path: '/admin/statistics',
    name: 'AdminStatistics',
    icon: 'bi-bar-chart',
    label: 'Admin Statistics',
    component: AdminStatistics
  },
  {
    path: '/users/:id',
    name: 'ManageUser',
    component: User,
    // props: true
    // Replaced with the following line to ensure that id is a number
    props: route => ({ id: parseInt(route.params.id) })
  }
]

const routes = [
  ...anonymousRoutes.map(addAuthLevel('anon')),
  ...userRoutes.map(addAuthLevel('user')),
  ...adminRoutes.map(addAuthLevel('admin'))
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.authLevel === 'anon') {
    next()
    return
  }

  if (!store.getters.isLoggedIn) {
    next({ name: 'Login' })
    return
  }
  if (to.meta.authLevel === 'admin' && store.state.user.user_type === 'A') {
    next()
    return
  }

  if (to.meta.authLevel === 'user' && store.state.user.user_type === 'V') {
    next()
    return
  }
  next(false)
})

export { router, routes }

import { createRouter, createWebHashHistory } from 'vue-router'
// import Home from '../views/Home.vue'
import DebitTransactionCreate from '../components/transactions/DebitTransactionCreate'
import CreditTransactionCreate from '../components/transactions/CreditTransactionCreate'
import About from '../views/About.vue'

import Dashboard from '../components/Dashboard.vue'
import Login from '../components/auth/Login.vue'
// import ChangePassword from '../components/auth/ChangePassword.vue'
import Users from '../components/users/Users.vue'
import User from '../components/users/User.vue'

// import Report from '../components/Report.vue'

import CreateCard from '../components/cards/CreateCard.vue'
import EditCard from '../components/cards/EditCard.vue'
import Transactions from '../components/transactions/Transactions.vue'
import Transaction from '../components/transactions/Transaction.vue'

import store from '../store'
import CategoriesManage from '../components/categories/CategoriesManage'
import Category from '../components/categories/Category'

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
    component: CreateCard
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  }
]

const userRoutes = [
  {
    path: '/card',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/card/edit',
    name: 'EditCard',
    component: EditCard
  },
  {
    path: '/card/transfer',
    name: 'DebitTransactionCreate',
    component: () => DebitTransactionCreate
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
  }
]

const adminRoutes = [
  {
    path: '/users',
    name: 'Users',
    component: Users
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
    name: 'ManageUsers',
    component: Users,
    // props: true
    // Replaced with the following line to ensure that id is a number
    props: route => ({ id: parseInt(route.params.id) })
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
  if (to.meta.authLevel === 'admin' && store.state.user.user_type !== 'A') {
    next(false)
  }
  next()
})

export default router

<template>
<div>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <div class="container-fluid">
      <a
        class="navbar-brand col-md-3 col-lg-2 me-0 px-3"
        href="#"
      ><img
          src="./assets/logo.png"
          alt=""
          width="30"
          height="24"
          class="d-inline-block align-text-top"
        >
        </a>
      <button
        id="buttonSidebarExpandId"
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu"
        aria-controls="sidebarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a
              class="nav-link"
              href="#"
            ><i class="bi bi-person-check-fill"></i>
              Register
            </a>
          </li>
          <li class="nav-item">
            <router-link
              class="nav-link"
              :class="{active: $route.name === 'Login'}"
              :to="{ name: 'Login'}"
            ><i class="bi bi-box-arrow-in-right"></i>
              Login
            </router-link>
          </li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdownMenuLink"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                :src="user.photo_url"
                class="rounded-circle z-depth-0 avatar-img"
                alt="avatar image"
              >
              <span class="avatar-text">{{ userName }}</span>
            </a>
            <ul
              class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <li><a
                  class="dropdown-item"
                  href="#"
                ><i class="bi bi-person-square"></i>Profile</a></li>
              <li><a
                  class="dropdown-item"
                  href="#"
                ><i class="bi bi-key-fill"></i>Change password</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a
                  class="dropdown-item"
                  @click.prevent="logout"
                ><i class="bi bi-arrow-right"></i>Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav
        id="sidebarMenu"
        class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
      >
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">

            <li class="nav-item" v-for="sidebarLink in sidebarLinks" :key="sidebarLink.routeName">
              <router-link :to="{name: sidebarLink.routeName}"
                           class="nav-link"
                           :class="{active: $route.name === sidebarLink.routeName}">
                <i class="bi" :class="{[sidebarLink.icon]: true}"></i>
                {{sidebarLink.label}}
              </router-link>
            </li>
          </ul>

          <div class="d-block d-md-none">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>User</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a
                  class="nav-link"
                  href="#"
                ><i class="bi bi-person-check-fill"></i>
                  Register
                </a>
              </li>
              <li class="nav-item">
                <router-link
                  class="nav-link"
                  :class="{active: $route.name === 'Login'}"
                  :to="{ name: 'Login'}"
                >
                  <i class="bi bi-box-arrow-in-right"></i>
                  Login
                </router-link>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdownMenuLink2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    :src="user.photo_url"
                    class="rounded-circle z-depth-0 avatar-img"
                    alt="avatar image"
                  >
                  <span class="avatar-text">{{ userName }}</span>
                </a>
                <ul
                  class="dropdown-menu"
                  aria-labelledby="navbarDropdownMenuLink2"
                >
                  <li><a
                      class="dropdown-item"
                      href="#"
                    ><i class="bi bi-person-square"></i>Profile</a></li>
                  <li><a
                      class="dropdown-item"
                      href="#"
                    ><i class="bi bi-key-fill"></i>Change password</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a
                      class="dropdown-item"
                      @click.prevent="logout"
                    ><i class="bi bi-arrow-right"></i>Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>

        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>
    </div>
  </div>
</div>
</template>

<script>

const anonSidebarLinks = [
  {
    routeName: 'CardCreate',
    label: 'Create Vcard',
    icon: 'bi-list-check'
  }
]

const vcardSidebarLinks = [
  {
    routeName: 'Dashboard',
    label: 'My vCard',
    icon: 'bi-house'
  },
  {
    routeName: 'DebitTransactionCreate',
    label: 'Send Money',
    icon: 'bi-send'
  },
  {
    routeName: 'CategoriesManage',
    label: 'Categories',
    icon: 'bi-pentagon'
  }
]

const adminSidebarLinks = [
  {
    routeName: 'Transactions',
    label: 'Transactions',
    icon: 'bi-list-stars'
  },
  {
    routeName: 'ManageUsers',
    label: 'Manage Users',
    icon: 'bi-people'
  }
]

export default {
  name: 'RootComponent',
  methods: {
    refresh () {
      this.$store.dispatch('refresh')
    },
    logout () {
      this.$store.dispatch('logout')
      // this.$axios.post('logout')
        .then(() => {
          this.$toast.success('User has logged out of the application.')
          this.$router.push({ name: 'Dashboard' })
          delete this.$axios.defaults.headers.common.Authorization
        })
        .catch(() => {
          this.$toast.error('There was a problem logging out of the application!')
        })
    }
  },
  computed: {
    user () {
      return this.$store.state.user
    },
    userId () {
      return this.$store.state.user ? this.$store.state.user.id : -1
    },
    userName () {
      return this.$store.state.user ? this.$store.state.user.name : ''
    },
    sidebarLinks () {
      if (!this.$store.getters.isLoggedIn) {
        return anonSidebarLinks
      }
      if (this.user.user_type === 'A') {
        return adminSidebarLinks
      }
      return vcardSidebarLinks
    }
  },
  created () {
    console.log('created', this.$store.state)
  }
}
</script>

<style lang="css">
@import "./assets/css/dashboard.css";

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}
.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}

#sidebarMenu {
  overflow-y: auto;
}
</style>

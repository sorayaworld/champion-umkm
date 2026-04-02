import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import BusinessView from '../views/BusinessView.vue'
import ApplicationListView from '../views/ApplicationListView.vue'
import ApplicationDetailView from '../views/ApplicationDetailView.vue'
import LoanCreateView from '../views/LoanCreateView.vue'
import LandingView from '../views/LandingView.vue'
import MainLayout from '../components/MainLayout.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { 
      path: '/', 
      name: 'landing', 
      component: LandingView 
    },
    { 
      path: '/login', 
      name: 'login', 
      component: LoginView 
    },
    { 
      path: '/register', 
      name: 'register', 
      component: RegisterView 
    },
    {
      path: '/_app',
      component: MainLayout,
      meta: { requiresAuth: true },
      children: [
        {
          path: '/dashboard',
          name: 'dashboard',
          component: DashboardView,
        },
        {
          path: '/business',
          name: 'business',
          component: BusinessView,
        },
        {
          path: '/applications',
          name: 'applications',
          component: ApplicationListView,
        },
        {
          path: '/applications/create',
          name: 'application-create',
          component: LoanCreateView,
        },
        {
          path: '/applications/:id',
          name: 'application-detail',
          component: ApplicationDetailView,
        }
      ]
    }
  ],
})

router.beforeEach((to, from) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return '/login'
  }

  if (to.name === 'login' && authStore.isAuthenticated) {
    return '/dashboard'
  }
})

export default router

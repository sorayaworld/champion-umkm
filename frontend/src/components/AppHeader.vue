<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { Bell, LogOut } from 'lucide-vue-next'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="flex flex-1 flex-col overflow-hidden bg-white">
    <header class="h-16 flex items-center justify-between px-8 border-b border-gray-200 bg-white sticky top-0 z-30">
      
      <div class="flex items-center text-sm text-gray-600 font-medium hidden sm:block">
         Aplikasi Pembiayaan / Dashboard
      </div>

      <div class="flex items-center space-x-6">
        <div class="h-4 w-px bg-gray-200"></div>
        <button @click="handleLogout" class="flex items-center gap-2 text-sm font-bold text-red-600 hover:text-red-700 transition-colors">
          Keluar
        </button>
      </div>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
      <div class="p-8 max-w-7xl mx-auto">
        <router-view />
      </div>
    </main>
  </div>
</template>

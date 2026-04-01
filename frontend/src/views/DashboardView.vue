<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="">


    <div class="flex w-full">
      <div class="bg-white p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Haloooo {{ authStore.userName }}!
        </h1>
        <p class="text-lg text-gray-600 mb-8">
          Anda masuk sebagai <span class="font-semibold text-blue-600">{{ authStore.userRole }}</span>.
        </p>
<button 
              @click="handleLogout"
              class="bg-red-100 text-red-600 px-4 py-2 "
            >
              Keluar
            </button>
      </div>
    </div>
  </div>
</template>

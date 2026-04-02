<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const name = ref('')
const email = ref('')
const nik = ref('')
const phone = ref('')
const role = ref('borrower')
const password = ref('')
const password_confirmation = ref('')

const handleRegister = async () => {
  if (password.value !== password_confirmation.value) {
    authStore.error = "Konfirmasi password tidak cocok!"
    return
  }

  const success = await authStore.register({
    name: name.value,
    email: email.value,
    nik: nik.value,
    phone: phone.value,
    role: role.value,
    password: password.value,
    password_confirmation: password_confirmation.value
  })
  
  if (success) {
    router.push('/dashboard')
  }
}






</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-[#F8F8F8] py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="bg-white p-10 rounded-[10px] border border-gray-100 w-full max-w-[500px]">
      
      <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-2.5 mb-6">
          <span class="text-2xl font-bold text-[#0B132A]">PT FA UMKM</span>
        </div>
        <h2 class="text-[28px] font-medium text-[#0B132A] mb-2">Daftar Akun Baru</h2>
        <p class="text-[#4F5665]">Lengkapi data di sinii untuk bergabung dengan kami.</p>
      </div>

      <div v-if="authStore.error" class="mb-6 p-4 bg-[#FFECEC] text-[#F53838] 0 rounded-md text-sm text-center">
        {{ authStore.error }}
      </div>

      <form @submit.prevent="handleRegister">
        
        <div class="mb-5">
          <label class="block text-[#0B132A] text-sm font-medium mb-2">Nama Lengkap</label>
          <input 
            v-model="name" 
            type="text" 
            required 
            class="w-full px-4 py-3 border border-gray-200 rounded-md text-[#4F5665] placeholder-gray-400 focus:outline-none focus:border-[#F53838] focus:ring-1 focus:ring-[#F53838] transition-all"
            placeholder="Masukkan nama lengkap"
          >
        </div>

        <div class="mb-5">
          <label class="block text-[#0B132A] text-sm font-medium mb-2">Email</label>
          <input 
            v-model="email" 
            type="email" 
            required 
            class="w-full px-4 py-3 border border-gray-200 rounded-md text-[#4F5665] placeholder-gray-400 focus:outline-none focus:border-[#F53838] focus:ring-1 focus:ring-[#F53838] transition-all"
            placeholder="Masukkan email"
          >
        </div>

        <div class="mb-5">
          <label class="block text-[#0B132A] text-sm font-medium mb-2">
          NIK
          </label>
          <input type="text" v-model="nik" required maxlength="16 "
           class="w-full px-4 py-3 border border-gray-200 rounded-md text-[#4F5665] placeholder-gray-400 focus:outline-none focus:border-[#F53838] focus:ring-1 focus:ring-[#F53838] transition-all"
              placeholder="masukkan NIK">
        </div>

         <div class="mb-5">
          <label class="block text-[#0B132A] text-sm font-medium mb-2">
          No Telp
          </label>
          <input type="text" v-model="phone" required maxlength="16 "
           class="w-full px-4 py-3 border border-gray-200 rounded-md text-[#4F5665] placeholder-gray-400 focus:outline-none focus:border-[#F53838] focus:ring-1 focus:ring-[#F53838] transition-all"
              placeholder="masukkan nomer telp">
        </div>

      


        <div class="mb-5">
          <label class="block text-[#0B132A] text-sm font-medium mb-2">Password</label>
          <input 
            v-model="password" 
            type="password" 
            required 
            minlength="8"
            class="w-full px-4 py-3 border border-gray-200 rounded-md text-[#4F5665] placeholder-gray-400 focus:outline-none focus:border-[#F53838] focus:ring-1 focus:ring-[#F53838] transition-all"
            placeholder="Buat password (min 8 karakter)"
          >
        </div>

        <div class="mb-8">
          <label class="block text-[#0B132A] text-sm font-medium mb-2">Konfirmasi Password</label>
          <input 
            v-model="password_confirmation" 
            type="password" 
            required 
            minlength="8"
            class="w-full px-4 py-3 border border-gray-200 rounded-md text-[#4F5665] placeholder-gray-400 focus:outline-none focus:border-[#F53838] focus:ring-1 focus:ring-[#F53838] transition-all"
            placeholder="Ulangi password"
          >
        </div>

        <button 
          type="submit" 
          :disabled="authStore.loading"
          class="w-full bg-[#F53838] text-white font-bold py-3.5 px-4 rounded-md hover:bg-[#D93030] disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          <span v-if="authStore.loading">Sedang mendaftar...</span>
          <span v-else>Daftar Sekarang</span>
        </button>

        <div class="mt-6 text-center text-sm text-[#4F5665]">
          Sudah punya akun? 
          <button type="button" @click="router.push('/login')" class="text-[#F53838] font-medium hover:underline">
            Login di sini
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

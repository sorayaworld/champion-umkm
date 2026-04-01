<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const logout = () => {
  authStore.logout();
  router.push({ name: 'landing' });
};
</script>

<template>
  <nav class="w-full bg-white fixed top-0 z-[100] border-b border-gray-50 min-w-[1200px]">
    <div class="w-[1200px] px-16 mx-auto h-24 flex items-center justify-between">
      <router-link :to="{ name: 'landing' }" class="flex items-center gap-2.5 cursor-pointer">
        <span class="text-xl font-bold text-[#0B132A]">PT FA UMKM</span>
      </router-link>
      
      <div class="flex space-x-10 items-center mt-1">
        <router-link :to="{ name: 'landing' }" class="text-[#4F5665] hover:text-[#F53838] transition-colors pb-2">Home</router-link>
        <a href="#benefit" class="text-[#4F5665] hover:text-[#F53838] transition-colors pb-2">Benefit</a>
      </div>

      <div class="flex items-center gap-8">
        <template v-if="!authStore.isAuthenticated">
          <router-link :to="{ name: 'login' }" class="text-[#0B132A] font-medium hover:text-[#F53838] transition-colors">Masuk</router-link>
          <router-link :to="{ name: 'register' }" class="border border-[#F53838] text-[#F53838] px-8 py-2 rounded-full font-medium hover:bg-[#F53838] hover:text-white transition-all">
            Daftar
          </router-link>
        </template>
        <template v-else>
          <router-link :to="{ name: 'dashboard' }" class="text-[#0B132A] font-medium hover:text-[#F53838] transition-colors">
            Dashboard
          </router-link>
          <button @click="logout" class="border border-[#F53838] text-[#F53838] px-8 py-2 rounded-full font-medium hover:bg-[#F53838] hover:text-white transition-all">
            Keluar
          </button>
        </template>
      </div>
    </div>
  </nav>
</template>

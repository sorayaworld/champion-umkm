<script setup>
import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRoute } from 'vue-router'
import { LayoutDashboard, FileText, Briefcase, CheckSquare } from 'lucide-vue-next'

const authStore = useAuthStore()
const route = useRoute()

const roleLinks = computed(() => {
  const role = authStore.userRole
  const links = [
    { name: 'Beranda', path: '/dashboard', icon: LayoutDashboard }
  ]

  if (role === 'borrower') {
    links.push({ name: 'Pengajuan Pinjaman', path: '/applications', icon: FileText })
    links.push({ name: 'Profil Bisnis', path: '/business', icon: Briefcase })
  } else if (role === 'officer' || role === 'manager' || role === 'admin') {
    if (role === 'officer' || role === 'manager') {
       links.push({ name: 'Data Pinjaman', path: '/applications', icon: CheckSquare })
    } else {
       links.push({ name: 'Data Pinjaman', path: '/applications', icon: FileText })
    }
  }

  return links
})
</script>

<template>
  <aside class="w-64 bg-[#F53838] flex flex-col h-screen shrink-0 relative z-20 text-white">
    
    <div class="h-20 flex items-center px-8 border-b border-white/10">
      <span class="font-bold text-lg text-white">
        PT FA UMKM
      </span>
    </div>

    <div class="px-6 py-8">
      <p class="text-[10px] font-bold text-red-100 uppercase mb-6 px-2 tracking-widest opacity-80">Menu Utama</p>
      <nav class="space-y-1.5">
        <router-link
          v-for="link in roleLinks"
          :key="link.path"
          :to="link.path"
          class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition-all text-sm group"
          :class="[
            route.path.startsWith(link.path)
              ? 'bg-white text-[#F53838]'
              : 'text-white hover:bg-white/10'
          ]"
        >
          <component 
             :is="link.icon" 
             class="w-5 h-5 transition-colors" 
             :class="route.path.startsWith(link.path) ? 'text-[#F53838]' : 'text-red-100 group-hover:text-white'"
          />
          {{ link.name }}
        </router-link>
      </nav>
    </div>

    <div class="mt-auto p-6 border-t border-white/10 bg-black/5">
      <div class="flex items-center gap-3 px-2">
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-white truncate">{{ authStore.userName || 'Pengguna' }}</p>
          <p class="text-[10px] text-red-100 font-bold uppercase tracking-wider opacity-70">{{ authStore.userRole }}</p>
        </div>
      </div>
    </div>
  </aside>
</template>

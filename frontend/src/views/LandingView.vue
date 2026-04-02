<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const router = useRouter()
const omzet = ref(10000000)
const amount = ref(5000000)
const tenor = ref(12)

const interestRate = computed(() => {
  if (omzet.value < 50000000) return 12
  if (omzet.value <= 200000000) return 10
  return 8
})

const calculation = computed(() => {
  const p = amount.value
  const n = tenor.value
  const r = (interestRate.value / 100) / 12
  
  let m = 0
  if (r > 0) {
    m = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1)
  } else {
    m = p / n
  }

  return {
    monthly: Math.round(m),
    total: Math.round(m * n),
    interest: Math.round((m * n) - p)
  }
})

const formatIDR = (val) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(val)
}
</script>

<template>
  <div class="min-h-screen bg-white text-[#4F5665] min-w-[1200px] no-scrollbar overflow-x-hidden">
    
    <Navbar />

    <header class="pt-48 pb-20">
      <div class="w-[1200px] px-16 mx-auto grid grid-cols-2 gap-10 items-center">
        <div class="space-y-6">
          <h1 class="text-[50px] font-bold leading-[1.2] text-[#0B132A]">
            Solusi Pinjaman untuk Kembangkan Bisnis Anda.
          </h1>
          <p class="text-base text-[#4F5665] leading-loose max-w-md">
            Dapatkan akses pendanaan cepat, aman, dan tanpa ribet untuk memperbesar bisnis UMKM Anda bersama kami
          </p>
          <div class="pt-6">
            <router-link :to="{ name: 'login' }" class="bg-[#F53838] text-white px-14 py-4 rounded-md font-bold hover:bg-[#D93030] transition-all">
              Pinjam Sekarang
            </router-link>
          </div>
        </div>
        <div>
          <img src="/logohomepagee.jpg" class="w-full h-auto" alt="Hero Image" />
        </div>
      </div>
    </header>

    <section class="py-10">
      <div class="w-[1200px] px-16 mx-auto">
        <div class="rounded-xl w-full grid grid-cols-3 py-12 border border-gray-100 bg-white relative z-10 shadow-sm">
          <div class="flex items-center justify-center border-r border-gray-200 py-0">
             <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-full bg-[#FFECEC] text-[#F53838] flex items-center justify-center">
                  <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-[#0B132A]">10K+</p>
                  <p class="text-lg text-[#4F5665]">UMKM Terbantu</p>
                </div>
             </div>
          </div>
          <div class="flex items-center justify-center border-r border-gray-200 py-0">
             <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-full bg-[#FFECEC] text-[#F53838] flex items-center justify-center">
                  <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-[#0B132A]">50+</p>
                  <p class="text-lg text-[#4F5665]">Kota Meminjam</p>
                </div>
             </div>
          </div>
          <div class="flex items-center justify-center py-0">
             <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-full bg-[#FFECEC] text-[#F53838] flex items-center justify-center">
                  <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                </div>
                <div>
                  <p class="text-2xl font-bold text-[#0B132A]">Rp 100M+</p>
                  <p class="text-lg text-[#4F5665]">Dana Dicairkan</p>
                </div>
             </div>
          </div>
        </div>
      </div>
    </section>

    <section id="benefit" class="py-16 bg-white">
      <div class="w-[1200px] px-16 mx-auto grid grid-cols-2 gap-20 items-center">
        <div>
           <img src="/logo_benefit.svg" class="w-full h-auto" alt="Benefit" />
        </div>
        <div class="space-y-6 pl-10">
           <h2 class="text-4xl font-medium leading-[1.3] text-[#0B132A]">
             Keunggulan Pinjaman <br /> di PT FA UMKM
           </h2>
           <p class="text-base leading-loose text-[#4F5665]">
              Kami memberikan berbagai kemudahan untuk memastikan bisnis Anda dapat terus berkembang tanpa hambatan modal.
           </p>
           <ul class="space-y-5 pt-2">
              <li class="flex items-center gap-3 text-sm text-[#4F5665]">
                 <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#2FAB73] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                 </div>
                 Bunga rendah dan kompetitif.
              </li>
              <li class="flex items-center gap-3 text-sm text-[#4F5665]">
                 <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#2FAB73] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                 </div>
                 Proses pengajuan 100% online.
              </li>
              <li class="flex items-center gap-3 text-sm text-[#4F5665]">
                 <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#2FAB73] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                 </div>
                 Pencairan dana super cepat.
              </li>
              <li class="flex items-center gap-3 text-sm text-[#4F5665]">
                 <div class="flex-shrink-0 w-6 h-6 rounded-full bg-[#2FAB73] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                 </div>
                 Tanpa agunan untuk limit tertentu.
              </li>
           </ul>
        </div>
      </div>
    </section>

    <section class="py-24 ">
      <div class="w-[1200px] px-16 mx-auto">
        <div class="text-center mb-16 space-y-4">
          <h2 class="text-3xl md:text-4xl font-bold text-[#0B132A]">Simulasi Pinjaman UMKM</h2>
          <p class="max-w-2xl mx-auto text-gray-500">Hitung estimasi cicilan per bulan Anda secara transparan dan cepat.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
          <div class="bg-white border border-gray-200 p-8 rounded-xl space-y-6">
            <div class="space-y-2 text-left">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimasi Omzet / Bulan</label>
              <div class="relative">
                <span class="absolute left-4 top-3.5 text-gray-400 font-bold">Rp</span>
                <input v-model="omzet" type="number" class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] transition-all outline-none font-bold text-[#0B132A]" />
              </div>
            </div>

            <div class="space-y-2 text-left">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Pinjaman</label>
              <div class="relative">
                <span class="absolute left-4 top-3.5 text-gray-400 font-bold">Rp</span>
                <input v-model="amount" type="number" class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] transition-all outline-none font-bold text-[#0B132A]" />
              </div>
            </div>

            <div class="space-y-2 text-left">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tenor (Bulan)</label>
              <select v-model="tenor" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] transition-all outline-none bg-white font-bold text-[#0B132A]">
                <option v-for="t in [3, 6, 12, 18, 24]" :key="t" :value="t">{{ t }} Bulan</option>
              </select>
            </div>
          </div>

          <div class="bg-[#F53838] p-8 md:p-10 rounded-xl text-white space-y-8 text-left">
            <div class="border-b border-white/20 pb-6 text-left">
              <p class="text-sm font-bold uppercase tracking-widest opacity-80 mb-2">Estimasi Cicilan</p>
              <h3 class="text-4xl md:text-5xl font-bold">{{ formatIDR(calculation.monthly) }}<span class="text-lg opacity-60">/bln</span></h3>
            </div>

            <div class="grid grid-cols-2 gap-8 text-left">
              <div>
                <p class="text-xs font-bold uppercase tracking-widest opacity-70 mb-1">Bunga ({{ interestRate }}%)</p>
                <p class="text-xl font-bold">{{ formatIDR(calculation.interest) }}</p>
              </div>
              <div>
                <p class="text-xs font-bold uppercase tracking-widest opacity-70 mb-1">Total Bayar</p>
                <p class="text-xl font-bold">{{ formatIDR(calculation.total) }}</p>
              </div>
            </div>

            <div class="pt-4">
              <router-link :to="{ name: 'register' }" class="block w-full bg-white text-[#F53838] text-center py-4 rounded-lg font-bold hover:bg-gray-50 transition-all uppercase tracking-wide">Ajukan Sekarang</router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="w-[1200px] px-16 mx-auto relative z-10 -mb-[90px]">
      <div class="bg-white py-14 px-16 rounded-[10px] border border-gray-100 flex flex-row justify-between items-center gap-8 shadow-md">
         <div class="text-left space-y-2">
            <h2 class="text-[32px] font-medium text-[#0B132A] leading-snug">Mulai Kembangkan Bisnis <br /> Anda Hari Ini!</h2>
            <p class="text-[#4F5665]">Ajukan pinjaman sekarang dan rasakan kemudahannya.</p>
         </div>
         <router-link :to="{ name: 'register' }" class="bg-[#F53838] text-white px-16 py-4 rounded-md font-bold hover:bg-[#D93030] transition-colors whitespace-nowrap ">Mulai Pinjaman</router-link>
      </div>
    </div>

    <Footer />
  </div>
</template>

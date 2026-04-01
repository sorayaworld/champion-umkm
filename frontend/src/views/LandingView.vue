<script setup>
import { ref } from 'vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
</script>

<template>
  <div class="min-h-screen bg-white  text-[#4F5665] min-w-[1200px] no-scrollbar overflow-x-hidden   ">
    
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
            <router-link :to="{ name: 'login' }" class="bg-[#F53838] text-white px-14 py-4 rounded-md font-bold hover:bg-[#D93030] ">
            Pinjam Sekarang
          </router-link>
           
          </div>
        </div>
        <div>
          <img src="/logohomepagee.jpg" class="w-full h-auto" />
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
           <img src="/logo_benefit.svg" class="w-full h-auto" />
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

    <div class="w-[1200px] px-16 mx-auto relative z-10 -mb-24 mt-16">
      <div class="bg-white py-14 px-16 rounded-[10px] border border-gray-100 flex flex-row justify-between items-center gap-8 shadow-md">
         <div class="text-left space-y-2">
            <h2 class="text-[32px] font-medium text-[#0B132A] leading-snug">Mulai Kembangkan Bisnis <br /> Anda Hari Ini!</h2>
            <p class="text-[#4F5665]">Ajukan pinjaman sekarang dan rasakan kemudahannya.</p>
         </div>
         <button @click="router.push('/register')" class="bg-[#F53838] text-white px-16 py-4 rounded-md font-bold hover:bg-[#D93030] transition-colors whitespace-nowrap shadow-lg shadow-[#F53838]/20">Mulai Pinjaman</button>
      </div>
    </div>

    <Footer />
  </div>
</template>


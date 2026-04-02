<script setup>
import { FileSearch } from 'lucide-vue-next'

defineProps({
  title: {
    type: String,
    default: 'Tidak ada data'
  },
  description: {
    type: String,
    default: 'Data yang Anda cari tidak ditemukan.'
  }
})
</script>

<template>
  <div class="p-12 flex flex-col items-center justify-center text-[#6c757d]">
    <div class="w-16 h-16 bg-[#f8f9fa] border border-[#e9ecef] rounded-full flex items-center justify-center mb-4">
      <FileSearch class="w-7 h-7 text-[#adb5bd]" />
    </div>
    <p class="text-[15px] font-semibold text-[#212529]">{{ title }}</p>
    <p class="text-[13px] mt-1 text-center max-w-sm">{{ description }}</p>
  </div>
</template>

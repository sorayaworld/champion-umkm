<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'
import LoadingButton from '@/components/LoadingButton.vue'
import { notifySuccess, notifyError } from '@/utils/dialog'
import { ChevronLeft } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const loadingProfile = ref(true)
const isSubmitting = ref(false)
const profile = ref(null)

const form = ref({
  amount: 10000000,
  tenor: 12,
  purpose: '',
  description: ''
})

const fetchProfile = async () => {
  try {
    loadingProfile.value = true
    const res = await api.get('/business')
    if (res.data?.success) {
      profile.value = res.data.data
    }
  } catch (error) {
    notifyError('Mohon isi Profil Bisnis terlebih dulu.')
    router.push('/business')
  } finally {
    loadingProfile.value = false
  }
}

const formatCurrency = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val)
}

const submitApplication = async () => {
  if (isSubmitting.value) return
  try {
    isSubmitting.value = true
    await api.post('/applications', form.value)
    notifySuccess('Berhasil', 'Pengajuan pinjaman Anda telah dikirim.')
    router.push('/applications')
  } catch (error) {
    notifyError(error.response?.data?.message || 'Gagal mengirim pengajuan.')
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  if (authStore.userRole !== 'borrower') {
    router.push('/applications')
  } else {
    fetchProfile()
  }
})
</script>

<template>
  <div class="max-w-2xl mx-auto space-y-6 pb-20">
    
    <div class="flex items-center gap-4">
      <button @click="router.back()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-500">
        <ChevronLeft class="w-6 h-6" />
      </button>
      <div>
        <h1 class="text-2xl font-bold text-[#0B132A]">Buat Pengajuan Baru</h1>
        <p class="text-sm text-gray-500">Silakan isi data pengajuan pinjaman UMKM Anda.</p>
      </div>
    </div>

    <div v-if="loadingProfile" class="bg-white p-12 border border-gray-200 rounded-lg animate-pulse h-96"></div>
    
    <div v-else class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
        <h2 class="text-sm font-bold text-[#0B132A] uppercase">Formulir Pengajuan</h2>
      </div>
      
      <form @submit.prevent="submitApplication" class="p-8 space-y-6">
        
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
          <p class="text-xs font-bold text-gray-500 uppercase mb-1">Limit Pinjaman Anda</p>
          <p class="text-lg font-bold text-[#0B132A]">{{ formatCurrency(profile.omzet * 6) }}</p>
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[#0B132A]">Nominal Pinjaman (Rp) *</label>
          <input 
            v-model="form.amount" 
            type="number" 
            required 
            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] transition-all outline-none" 
          />
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[#0B132A]">Jangka Waktu (Bulan) *</label>
          <select v-model="form.tenor" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] outline-none bg-white font-medium">
            <option :value="3">3 Bulan</option>
            <option :value="6">6 Bulan</option>
            <option :value="12">12 Bulan</option>
            <option :value="16">16 Bulan</option>
          </select>
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[#0B132A]">Tujuan Peminjaman *</label>
          <input v-model="form.purpose" type="text" required class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] outline-none" />
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-[#0B132A]">Deskripsi Detail</label>
          <textarea v-model="form.description" rows="5" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#F53838] outline-none resize-none"></textarea>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
          <LoadingButton 
            type="submit" 
            :loading="isSubmitting" 
            text="Simpan" 
            class="bg-[#F53838] hover:bg-[#D93030] text-white font-bold py-3 px-10 rounded-lg transition-all"
          />
        </div>

      </form>
    </div>

  </div>
</template>

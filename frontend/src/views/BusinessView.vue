<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'
import LoadingButton from '@/components/LoadingButton.vue'
import { notifySuccess, notifyError } from '@/utils/dialog'

const authStore = useAuthStore()

const loading = ref(true)
const isSubmitting = ref(false)
const hasProfile = ref(false)

const form = ref({
  business_name: '',
  category: '',
  years_running: '',
  employee_count: 0,
  address: '',
  city: '',
  province: '',
  omzet: 0
})

const fetchProfile = async () => {
  try {
    loading.value = true
    const res = await api.get('/business')
    if (res.data?.success && res.data.data) {
      hasProfile.value = true
      Object.assign(form.value, res.data.data)
    }
  } catch (error) {
    if (error.response?.status === 404) {
      hasProfile.value = false
    } else {
      console.error(error)
    }
  } finally {
    loading.value = false
  }
}

const saveProfile = async () => {
  if (isSubmitting.value) return
  try {
    isSubmitting.value = true
    if (hasProfile.value) {
      await api.put('/business', form.value)
    } else {
      await api.post('/business', form.value)
      hasProfile.value = true
    }

    notifySuccess('Berhasil', 'Profil bisnis Anda telah disimpan.')
  } catch (error) {
    notifyError(error.response?.data?.message || 'Gagal menyimpan profil bisnis.')
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  if (authStore.userRole === 'borrower') {
    fetchProfile()
  } else {
    loading.value = false
  }
})
</script>

<template>
  <div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-[#0B132A]">Profil Bisnis</h1>
    </div>

    <div v-if="authStore.userRole !== 'borrower'" class="bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-lg">
      <p class="text-sm font-bold">Hanya role Borrower yang dapat mengatur Profil Bisnis.</p>
    </div>

    <div v-else>
      <div v-if="loading" class="bg-white p-8 border border-gray-200 rounded-lg animate-pulse h-96"></div>
      
      <div v-else class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
          <h2 class="text-sm font-bold text-[#0B132A] uppercase">Informasi Utama Bisnis</h2>
          <p class="text-xs text-gray-500 mt-1">Lengkapi data usaha Anda untuk proses verifikasi sistem.</p>
        </div>

        <form @submit.prevent="saveProfile" class="p-8 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase">Nama Bisnis *</label>
              <input v-model="form.business_name" type="text" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all" />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase">Kategori Bisnis *</label>
              <select v-model="form.category" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all bg-white font-medium">
                <option value="" disabled>Pilih Kategori</option>
                <option value="Kuliner">Makanan</option>
                <option value="Jasa">Jasa</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase">Lama Beroperasi *</label>
              <input v-model="form.years_running" type="text" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all" />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase">Jumlah Karyawan *</label>
              <input v-model="form.employee_count" type="number" required min="0" class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all" />
            </div>

            <div class="space-y-1.5 md:col-span-2">
              <label class="text-xs font-bold text-gray-700 uppercase">Alamat Operasional *</label>
              <textarea v-model="form.address" required rows="3" placeholder="" class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all resize-none"></textarea>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase">Kota / Kabupaten *</label>
              <input v-model="form.city" type="text" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all" />
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase">Provinsi *</label>
              <input v-model="form.province" type="text" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all" />
            </div>

            <div class="space-y-1.5 md:col-span-2 mt-4 pt-6 border-t border-gray-100">
              <label class="text-xs font-bold text-gray-700 uppercase">Rata-rata Omzet Bulanan (Rp) *</label>
              <div class="relative mt-2">
                <span class="absolute left-4 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                <input v-model="form.omzet" type="number" required min="0" class="w-full text-sm pl-12 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#F53838] transition-all font-bold" />
              </div>
            </div>

          </div>

          <div class="flex justify-end pt-6 mt-8 border-t border-gray-100 ">
            <LoadingButton 
              type="submit" 
              :loading="isSubmitting"
              :text="hasProfile ? 'Perbarui Data Bisnis' : 'Simpan Data Bisnis'"
              loadingText="Menyimpan..."
              class="px-10 bg-[#F53838]"
            />
          </div>
        </form>

      </div>
    </div>

  </div>
</template>

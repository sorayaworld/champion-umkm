<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'
import StatusBadge from '@/components/StatusBadge.vue'
import LoadingButton from '@/components/LoadingButton.vue'
import { notifySuccess, notifyError, confirmDialog } from '@/utils/dialog'
import { ChevronLeft } from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const loading = ref(true)
const application = ref(null)
const isActionLoading = ref(false)

const fetchDetail = async () => {
  try {
    loading.value = true
    const { data } = await api.get(`/applications/${route.params.id}`)
    if (data.success) {
      application.value = data.data
    }
  } catch (error) {
    notifyError('Gagal memuat detail pengajuan.')
  } finally {
    loading.value = false
  }
}

const handleAction = async (action) => {
  const isReject = action === 'reject'
  const actionName = action.toUpperCase()

  const result = await confirmDialog(
    isReject ? 'Tolak pengajuan ini?' : `Proses ${actionName} pengajuan ini?`,
    isReject ? 'Tolak Sekarang' : 'Konfirmasi'
  )
  if (!result.isConfirmed) return

  try {
    isActionLoading.value = true
    await api.put(`/applications/${route.params.id}/${action}`)
    notifySuccess('Sukses', `Data telah di-${action}.`)
    fetchDetail()
  } catch (error) {
    notifyError(error.response?.data?.message || 'Gagal memproses.')
  } finally {
    isActionLoading.value = false
  }
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount || 0)
}

const getLogActivity = (action) => {
  const a = (action || '').toLowerCase()
  if (a === 'submit' || a === 'submitted') return 'Mengajukan pinjaman (menunggu review officer)'
  if (a === 'review' || a === 'reviewed') return 'Diteruskan ke manager untuk persetujuan'
  if (a === 'approve' || a === 'approved') return 'Pinjaman resmi disetujui'
  if (a === 'reject' || a === 'rejected') return 'Pengajuan pinjaman ditolak'
  return a
}

onMounted(fetchDetail)
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6 pb-20">
    
    <div class="flex items-center gap-4">
      <button @click="router.back()" class="p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-500">
        <ChevronLeft class="w-6 h-6" />
      </button>
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-[#0B132A]">Rincian Pengajuan</h1>
        <p class="text-xs text-gray-400 font-bold uppercase mt-1">ID: #{{ route.params.id.substring(0, 8) }}</p>
      </div>
      <StatusBadge v-if="application" :status="application.status" />
    </div>

    <div v-if="loading" class="bg-white p-20 border border-gray-200 rounded-lg animate-pulse h-96"></div>

    <div v-else-if="application" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
      
      <div class="md:col-span-2 space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-xs font-bold text-[#0B132A] uppercase">Informasi Pembiayaan</h2>
          </div>
          <div class="p-6 grid grid-cols-2 gap-8">
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase mb-1">Jumlah Pinjaman</p>
              <p class="text-lg font-bold text-[#0B132A]">{{ formatCurrency(application.amount) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase mb-1">Tenor Pinjaman</p>
              <p class="text-lg font-bold text-[#0B132A]">{{ application.tenor }} Bulan</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase mb-1">Suku Bunga</p>
              <p class="text-lg font-bold text-[#F53838]">{{ application.interest_rate }}%</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase mb-1">Angsuran Bulanan</p>
              <p class="text-lg font-bold text-gray-800">{{ formatCurrency(application.monthly_installment) }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-xs font-bold text-[#0B132A] uppercase">Tabel Amortisasi</h2>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse whitespace-nowrap">
              <thead>
                <tr class="bg-[#F53838] border-b border-red-600 text-xs font-bold text-white uppercase">
                  <th class="px-6 py-4">#</th>
                  <th class="px-6 py-4 text-left">Pokok</th>
                  <th class="px-6 py-4 text-left">Bunga</th>
                  <th class="px-6 py-4 text-left">Total</th>
                  <th class="px-6 py-4 text-left">Sisa Saldo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="row in application.amortization_schedules" :key="row.installment_number" class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-3 text-center font-bold text-gray-400">{{ row.installment_number }}</td>
                  <td class="px-6 py-3 font-medium">{{ formatCurrency(row.principal_payment) }}</td>
                  <td class="px-6 py-3 font-medium">{{ formatCurrency(row.interest_payment) }}</td>
                  <td class="px-6 py-3 font-bold text-[#0B132A]">{{ formatCurrency(row.total_payment) }}</td>
                  <td class="px-6 py-3 text-gray-400 font-bold">{{ formatCurrency(row.remaining_balance) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-xs font-bold text-[#0B132A] uppercase">Profil Bisnis</h2>
          </div>
          <div class="p-6 space-y-4">
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase mb-1">Nama UMKM</p>
              <p class="text-sm font-bold text-[#0B132A] uppercase">{{ application.business_name || '-' }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase mb-1">Tujuan Penggunaan</p>
              <p class="text-sm text-gray-600 leading-relaxed">{{ application.purpose }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-xs font-bold text-[#0B132A] uppercase">Riwayat Aktivitas</h2>
          </div>
          <div class="p-6">
            <div v-if="!application.approval_logs?.length" class="text-xs text-gray-400 text-center py-4 font-bold uppercase italic">Log Kosong.</div>
            <div v-else class="space-y-6 border-l-2 border-gray-100 pl-6">
              <div v-for="log in application.approval_logs" :key="log.id" class="relative">
                <div class="absolute -left-[31px] top-1 w-3.5 h-3.5 rounded-full border border-gray-200" :class="{
                  'bg-blue-500': log.action === 'review',
                  'bg-[#198754]': log.action === 'approve',
                  'bg-[#F53838]': log.action === 'reject'
                }"></div>
                <p class="text-xs font-bold text-[#0B132A] uppercase leading-snug">{{ getLogActivity(log.action) }}</p>
                <p class="text-xs text-gray-400 mt-1 uppercase font-bold">
                  {{ new Date(log.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div v-if="(authStore.userRole === 'officer' && (application.status?.toLowerCase() === 'pending' || application.status?.toLowerCase() === 'submitted')) || (authStore.userRole === 'manager' && application.status?.toLowerCase() === 'reviewed')" class="bg-gray-50 border border-gray-200 p-6 rounded-lg space-y-4">
           <h3 class="text-xs font-bold text-gray-600 uppercase">Kontrol Keputusan</h3>
           
           <div v-if="authStore.userRole === 'officer'" class="w-full">
              <LoadingButton 
                @click="handleAction('review')" 
                :loading="isActionLoading" 
                text="Konfirmasi Review" 
                class="w-full bg-[#198754] hover:bg-green-700 text-white font-bold py-3 rounded-lg"
              />
           </div>

           <div v-else-if="authStore.userRole === 'manager'" class="grid grid-cols-1 gap-3">
              <LoadingButton 
                @click="handleAction('approve')" 
                :loading="isActionLoading" 
                text="SETUJUI DATA" 
                class="w-full bg-[#198754] hover:bg-green-700 text-white font-bold py-3 rounded-lg"
              />
              <LoadingButton 
                @click="handleAction('reject')" 
                :loading="isActionLoading" 
                text="TOLAK" 
                variant="danger"
                class="w-full bg-white border border-red-500 text-red-500 font-bold py-3 rounded-lg hover:bg-red-50"
              />
           </div>
        </div>

      </div>

    </div>
  </div>
</template>

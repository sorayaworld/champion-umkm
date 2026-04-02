<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'
import { Plus, Search, Filter, ChevronLeft, ChevronRight, Eye } from 'lucide-vue-next'
import StatusBadge from '@/components/StatusBadge.vue'
import EmptyState from '@/components/EmptyState.vue'
import { notifyError } from '@/utils/dialog'

const authStore = useAuthStore()
const router = useRouter()
const loading = ref(true)
const applications = ref([])

const filters = ref({
  search: '',
  status: '',
  date: '',
  min_amount: '',
  max_amount: '',
  page: 1,
  limit: 10
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0
})

const fetchApplications = async () => {
  try {
    loading.value = true
    const { data } = await api.get('/applications', { params: filters.value })
    if (data.success) {
      const res = data.data
      if (res && res.meta) {
        applications.value = res.data
        pagination.value = {
          current_page: res.meta.current_page,
          last_page: res.meta.last_page,
          total: res.meta.total
        }
      } else if (res && res.data) {
        applications.value = res.data
        pagination.value = {
          current_page: res.current_page || 1,
          last_page: res.last_page || 1,
          total: res.total || res.data.length
        }
      } else {
        applications.value = res || []
      }
    }
  } catch (error) {
    console.error('Fetch error:', error)
    notifyError('Gagal memuat data pengajuan.')
  } finally {
    loading.value = false
  }
}

watch([() => filters.value.status, () => filters.value.date], () => {
  filters.value.page = 1
  fetchApplications()
})

const handleSearch = () => {
  filters.value.page = 1
  fetchApplications()
}

const changePage = (p) => {
  if (p < 1 || p > pagination.value.last_page) return
  filters.value.page = p
  fetchApplications()
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount || 0)
}

const getWorkflowMessage = (status) => {
  const s = (status || '').toLowerCase()
  if (s === 'submitted' || s === 'pending') return 'Mengajukan pinjaman baru (menunggu review officer)'
  if (s === 'reviewed') return 'Sedang diproses manager'
  if (s === 'approved') return 'Pinjaman disetujui'
  if (s === 'rejected') return 'Pinjaman ditolak'
  return '-'
}

onMounted(fetchApplications)
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-[#0B132A]">Manajemen Pengajuan</h1>
        <p class="text-sm text-gray-500">Daftar seluruh riwayat pembiayaan UMKM.</p>
      </div>

      <button 
        v-if="authStore.userRole === 'borrower'"
        @click="router.push('/applications/create')"
        class="bg-[#F53838] hover:bg-[#D93030] text-white text-sm font-bold py-2.5 px-6 rounded-lg flex items-center gap-2 transition-colors"
      >
        <Plus class="w-5 h-5" />
        Ajukan Pinjaman
      </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col md:flex-row gap-4 items-end">
      <div class="flex-grow space-y-1.5 w-full">
        <label class="text-xs font-bold text-[#0B132A]">CARI BISNIS</label>
        <div class="relative">
          <Search class="absolute left-3 top-3 w-4 h-4 text-gray-400" />
          <input 
            v-model="filters.search" 
            @keyup.enter="handleSearch"
            type="text" 
            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:border-gray-400" 
          />
        </div>
      </div>
      
      <div class="w-full md:w-56 space-y-1.5">
        <label class="text-xs font-bold text-[#0B132A]">STATUS</label>
        <select v-model="filters.status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-white outline-none focus:border-gray-400">
          <option value="">SEMUA DATA</option>
          <option value="submitted">SUBMITTED</option>
          <option value="reviewed">REVIEWED</option>
          <option value="approved">APPROVED</option>
          <option value="rejected">REJECTED</option>
        </select>
      </div>

      <div class="w-full md:w-56 space-y-1.5">
        <label class="text-xs font-bold text-[#0B132A]">TANGGAL</label>
        <input type="date" v-model="filters.date" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:border-gray-400" />
      </div>

      <button @click="handleSearch" class="bg-gray-100 p-2.5 rounded-lg hover:bg-gray-200 text-gray-600 transition-all">
        <Filter class="w-5 h-5" />
      </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      <div v-if="loading" class="p-12 text-center text-gray-400 font-bold uppercase animate-pulse">
        Sedang memuat data...
      </div>
      
      <EmptyState 
        v-else-if="applications.length === 0" 
        title="Data Tidak Ditemukan" 
        description="Hasil pencarian atau daftar pengajuan saat ini belum tersedia." 
      />

      <div v-else>
        <table class="w-full text-left">
          <thead>
            <tr class="bg-[#F53838] text-white text-xs font-bold uppercase border-b border-red-600">
              <th class="px-6 py-4">Nama Bisnis</th>
              <th class="px-6 py-4 text-right">Nominal Dana</th>
              <th class="px-6 py-4">Aktivitas</th>
              <th class="px-6 py-4 text-right">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="app in applications" 
              :key="app.id"
              class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
            >
              <td class="px-6 py-5">
                <p class="text-sm font-bold text-[#0B132A]">{{ app.business_name || '-' }}</p>
                <p class="text-xs text-gray-400 mt-0.5 uppercase">
                  {{ new Date(app.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                </p>
              </td>
              <td class="px-6 py-5 text-right">
                <p class="text-sm font-bold text-[#0B132A]">{{ formatCurrency(app.amount) }}</p>
                <p class="text-xs text-gray-400 mt-1 font-bold">{{ app.tenor }} BLN</p>
              </td>
              <td class="px-6 py-5">
                <p class="text-xs font-bold text-gray-500 uppercase">{{ getWorkflowMessage(app.status) }}</p>
              </td>
              <td class="px-6 py-5">
                <StatusBadge :status="app.status" />
              </td>
              <td class="px-6 py-5 text-right">
                <router-link 
                  v-if="authStore.userRole === 'officer' && (app.status === 'submitted' || app.status === 'pending')"
                  :to="`/applications/${app.id}`"
                  class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded uppercase transition-all inline-block"
                >
                  Review
                </router-link>
                <router-link 
                  v-else-if="authStore.userRole === 'manager' && app.status === 'reviewed'"
                  :to="`/applications/${app.id}`"
                  class="bg-[#198754] hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded uppercase transition-all inline-block"
                >
                  Proses
                </router-link>
                <router-link 
                  v-else
                  :to="`/applications/${app.id}`"
                  class="bg-white border border-gray-300 hover:bg-gray-50 p-2 rounded-lg transition-all inline-flex items-center"
                >
                  <Eye class="w-4 h-4 text-gray-600" />
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200 bg-gray-50">
           <p class="text-xs text-gray-500 font-bold uppercase">Total {{ pagination.total }} Data</p>
           <div class="flex items-center gap-2">
              <button 
                @click="changePage(pagination.current_page - 1)" 
                :disabled="pagination.current_page === 1"
                class="px-2 py-1.5 border border-gray-300 rounded bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                <ChevronLeft class="w-4 h-4" />
              </button>
              <div class="px-4 text-xs font-bold text-[#0B132A] uppercase">
                {{ pagination.current_page }} / {{ pagination.last_page }}
              </div>
              <button 
                @click="changePage(pagination.current_page + 1)" 
                :disabled="pagination.current_page === pagination.last_page"
                class="px-2 py-1.5 border border-gray-300 rounded bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                <ChevronRight class="w-4 h-4" />
              </button>
           </div>
        </div>
      </div>
    </div>
  </div>
</template>

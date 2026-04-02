<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import { useAuthStore } from '@/stores/auth'
import StatusBadge from '@/components/StatusBadge.vue'
import EmptyState from '@/components/EmptyState.vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const router = useRouter()
const authStore = useAuthStore()
const loading = ref(true)

const stats = ref({
  total_submission: 0,
  total_reviewed: 0,
  total_approved: 0,
  total_rejected: 0,
  approval_ratio: 0,
  average_loan_amount: 0
})

const recentActivities = ref([])

const chartData = computed(() => ({
  labels: ['SUBMITTED', 'REVIEWED', 'APPROVED', 'REJECTED'],
  datasets: [
    {
      label: 'Jumlah Pengajuan',
      backgroundColor: ['#e9ecef', '#0d6efd', '#198754', '#F53838'],
      data: [
        stats.value.total_submission - stats.value.total_reviewed - stats.value.total_approved - stats.value.total_rejected,
        stats.value.total_reviewed,
        stats.value.total_approved,
        stats.value.total_rejected
      ],
      borderRadius: 4
    }
  ]
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false }
  },
  scales: {
    y: { beginAtZero: true, grid: { borderDash: [2] } },
    x: { grid: { display: false } }
  }
}

const loadData = async () => {
  try {
    const statsRes = await api.get('/dashboard/stats')
    if (statsRes.data?.success) {
      stats.value = statsRes.data.data
    }

    const appsRes = await api.get('/applications')
    if (appsRes.data?.success) {
      const res = appsRes.data.data
      let allApps = []
      if (res && res.data && Array.isArray(res.data)) {
        allApps = res.data
      } else if (Array.isArray(res)) {
        allApps = res
      }
      recentActivities.value = allApps.slice(0, 5)
    }
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
  }
}

const getWorkflowMessage = (status) => {
  const s = (status || '').toLowerCase()
  if (s === 'submitted' || s === 'pending') return 'Mengajukan pinjaman (review officer)'
  if (s === 'reviewed') return 'Diteruskan ke manager'
  if (s === 'approved') return 'Pinjaman disetujui'
  if (s === 'rejected') return 'Pinjaman ditolak'
  return '-'
}

onMounted(loadData)

const formatRupiah = (number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(number || 0)
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-[#0B132A]">Dashboard Utama</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="item in [
        { label: 'Total Pengajuan', val: stats.total_submission, color: 'text-[#0B132A]' },
        { label: 'Proses Review', val: stats.total_reviewed, color: 'text-blue-600' },
        { label: 'Disetujui', val: stats.total_approved, color: 'text-green-600' },
        { label: 'Ditolak', val: stats.total_rejected, color: 'text-[#F53838]' },
        { label: 'Approval Ratio', val: stats.approval_ratio + '%', color: 'text-indigo-600' },
        { label: 'Rata-rata Pinjaman', val: formatRupiah(stats.average_loan_amount), color: 'text-gray-700' }
      ]" :key="item.label" class="bg-white border border-gray-200 p-6 rounded-lg">
        <p class="text-xs font-bold text-gray-500 uppercase mb-1">{{ item.label }}</p>
        <p class="text-2xl font-bold" :class="item.color">{{ item.val }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
          <h2 class="text-xs font-bold text-[#0B132A] uppercase">Statistik Workflow</h2>
        </div>
        <div class="p-6 h-72">
          <Bar :data="chartData" :options="chartOptions" />
        </div>
      </div>

     
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-xs font-bold text-[#0B132A] uppercase">Aktivitas Terbaru</h2>
      </div>
      <EmptyState v-if="!loading && recentActivities.length === 0" title="Data Kosong" description="Daftar pengajuan terbaru akan muncul di sini." />
      <table v-else class="w-full text-left">
        <thead class="bg-[#F53838] border-b border-red-600 text-xs font-bold text-white uppercase">
          <tr>
            <th class="px-6 py-3 uppercase">Nama UMKM</th>
            <th class="px-6 py-3 uppercase">Nominal Dana</th>
            <th class="px-6 py-3 uppercase">Aktivitas</th>
            <th class="px-6 py-3 uppercase text-right">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in recentActivities" :key="item.id" class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
              <p class="text-sm font-bold text-[#0B132A] uppercase">{{ item.business_name || '-' }}</p>
            </td>
            <td class="px-6 py-4">
              <p class="text-sm text-gray-600 font-bold">{{ formatRupiah(item.amount) }}</p>
            </td>
            <td class="px-6 py-4">
              <p class="text-xs font-bold text-gray-400 uppercase">{{ getWorkflowMessage(item.status) }}</p>
            </td>
            <td class="px-6 py-4 text-right">
              <router-link 
                v-if="authStore.userRole === 'officer' && (item.status === 'submitted' || item.status === 'pending')"
                :to="`/applications/${item.id}`"
                class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-3 py-1.5 rounded uppercase transition-all inline-block"
              >
                Review
              </router-link>
              <router-link 
                v-else-if="authStore.userRole === 'manager' && item.status === 'reviewed'"
                :to="`/applications/${item.id}`"
                class="bg-[#198754] hover:bg-green-700 text-white text-[10px] font-bold px-3 py-1.5 rounded uppercase transition-all inline-block"
              >
                Proses
              </router-link>
              <router-link 
                v-else
                :to="`/applications/${item.id}`" 
                class="text-xs font-bold text-[#F53838] hover:underline uppercase"
              >
                Detail
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

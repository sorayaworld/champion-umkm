<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true,
    default: ''
  }
})

const badgeClass = computed(() => {
  const s = (props.status || '').toLowerCase()
  if (['submitted', 'pending'].includes(s)) return 'bg-yellow-50 text-yellow-700 border-yellow-100'
  if (['reviewed'].includes(s)) return 'bg-blue-50 text-blue-700 border-blue-100'
  if (['approved'].includes(s)) return 'bg-green-50 text-green-700 border-green-100'
  if (['rejected'].includes(s)) return 'bg-red-50 text-red-700 border-red-100'
  return 'bg-gray-50 text-gray-600 border-gray-100'
})

const badgeText = computed(() => {
  const s = (props.status || '').toLowerCase()
  if (s === 'submitted' || s === 'pending') return 'SUBMITTED'
  if (s === 'reviewed') return 'REVIEWED'
  if (s === 'approved') return 'APPROVED'
  if (s === 'rejected') return 'REJECTED'
  return props.status?.toUpperCase() || 'UNKNOWN'
})
</script>

<template>
  <span class="px-3 py-1 rounded border text-xs font-bold uppercase inline-block text-center min-w-[100px]" :class="badgeClass">
    {{ badgeText }}
  </span>
</template>

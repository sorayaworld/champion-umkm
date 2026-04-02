<script setup>
import { Loader2 } from 'lucide-vue-next'

const props = defineProps({
  loading: {
    type: Boolean,
    default: false
  },
  text: {
    type: String,
    required: true
  },
  loadingText: {
    type: String,
    default: 'Memproses...'
  },
  type: {
    type: String,
    default: 'submit'
  },
  variant: {
    type: String,
    default: 'primary'
  }
})

const emit = defineEmits(['click'])

const handleClick = (e) => {
  if (!props.loading) {
    emit('click', e)
  }
}
</script>

<template>
  <button 
    :type="type" 
    :disabled="loading"
    @click="handleClick"
    class="flex items-center justify-center gap-2 text-[13px] font-semibold py-2 px-5 rounded transition-colors disabled:opacity-60"
    :class="{
      'bg-[#212529] hover:bg-[#343a40] text-white': variant === 'primary',
      'bg-white border border-[#ced4da] hover:bg-[#f8f9fa] text-[#495057]': variant === 'secondary',
      'bg-[#dc3545] hover:bg-[#b02a37] text-white': variant === 'danger',
      'cursor-not-allowed': loading
    }"
  >
    <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
    <slot name="icon" v-else-if="$slots.icon"></slot>
    {{ loading ? loadingText : text }}
  </button>
</template>

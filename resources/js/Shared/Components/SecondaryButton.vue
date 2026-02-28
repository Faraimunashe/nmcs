<template>
  <button
    :type="type"
    :disabled="disabled || processing"
    :class="[
      'inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-[15px] font-semibold transition',
      'focus:outline-none focus:ring-2 focus:ring-offset-2',
      disabled || processing
        ? 'cursor-not-allowed opacity-50'
        : 'hover:bg-slate-50 active:scale-[0.98]',
      'bg-white text-slate-700 ring-1 ring-slate-300',
      'hover:ring-slate-400 focus:ring-slate-400/40',
      size === 'sm' && 'px-3 py-2 text-sm',
      size === 'lg' && 'px-6 py-4 text-base'
    ]"
  >
    <i v-if="icon && !processing" :class="icon"></i>
    <i v-if="processing" class="fas fa-spinner fa-spin"></i>
    <slot />
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'button'
  },
  disabled: Boolean,
  processing: Boolean,
  icon: String,
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
});
</script>

<template>
  <span
    :class="[
      'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold',
      variantClasses[variant] || variantClasses.default
    ]"
  >
    <i v-if="icon" :class="icon"></i>
    <slot />
  </span>
</template>

<script setup>
const variantClasses = {
  success: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  error: 'bg-red-50 text-red-700 ring-1 ring-red-200',
  warning: 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  info: 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
  default: 'bg-slate-50 text-slate-700 ring-1 ring-slate-200'
};

defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['success', 'error', 'warning', 'info', 'default'].includes(value)
  },
  icon: String
});
</script>

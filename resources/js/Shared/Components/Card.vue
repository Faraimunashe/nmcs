<template>
  <div
    :class="[
      'rounded-3xl bg-white ring-1 ring-slate-200 shadow-xl shadow-emerald-900/5',
      padding && 'p-6 sm:p-8'
    ]"
  >
    <div v-if="$slots.header || title" class="mb-4">
      <slot name="header">
        <h3 v-if="title" class="text-lg font-semibold text-slate-900">{{ title }}</h3>
      </slot>
    </div>
    <slot />
  </div>
</template>

<script setup>
defineProps({
  title: String,
  padding: {
    type: Boolean,
    default: true
  }
});
</script>

<template>
  <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
    <div
      v-if="icon"
      class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100"
    >
      <i :class="[icon, 'text-2xl text-slate-400']"></i>
    </div>
    <h3 v-if="title" class="text-lg font-semibold text-slate-900">{{ title }}</h3>
    <p v-if="description" class="mt-2 max-w-sm text-sm text-slate-500">{{ description }}</p>
    <slot name="action" />
  </div>
</template>

<script setup>
defineProps({
  icon: String,
  title: String,
  description: String
});
</script>

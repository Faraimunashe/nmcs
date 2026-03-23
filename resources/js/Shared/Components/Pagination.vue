<template>
  <nav v-if="links && links.length" aria-label="Page navigation" class="flex justify-center mt-6">
    <ul class="inline-flex items-center space-x-1">
      <li
        v-for="(link, index) in links"
        :key="index"
        :class="{
          // Disabled
          'bg-slate-100 text-slate-400 cursor-not-allowed': !link.url,
          // Active
          'bg-emerald-600 text-white': link.active,
          // Hover for clickable pages
          'hover:bg-emerald-500 hover:text-white': link.url && !link.active,
          // Default clickable
          'text-emerald-700': !link.active && link.url,
          // Default border for clickable
          'border-emerald-200': !link.active && link.url
        }"
        class="px-4 py-2 border rounded-lg shadow-sm transition duration-150 ease-in-out"
      >
        <component
          :is="link.url ? Link : 'span'"
          class="page-link text-center"
          :href="link.url"
          v-html="link.label"
        />
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  links: Array
});
</script>

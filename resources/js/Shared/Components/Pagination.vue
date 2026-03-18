<template>
  <nav v-if="links && links.length" aria-label="Page navigation" class="flex justify-center mt-6">
    <ul class="inline-flex items-center space-x-1">
      <li
        v-for="(link, index) in links"
        :key="index"
        :class="{
          'bg-gray-300 text-gray-500 cursor-not-allowed': !link.url,
          'bg-blue-500 text-white': link.active,
          'hover:bg-blue-500 hover:text-white': link.url && !link.active,
          'text-blue-700': !link.active && link.url,
          'border-gray-300': !link.active && link.url
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

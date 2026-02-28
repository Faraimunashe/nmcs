<template>
    <footer class="border-t border-slate-200 bg-white/95 backdrop-blur">
      <div class="mx-auto max-w-7xl px-4 py-6">
        <div class="flex flex-col items-center justify-center gap-2 text-center">
          <p class="text-sm text-slate-600">
            Made with <i class="fa-solid fa-heart text-red-500"></i> by <span class="font-semibold text-emerald-700">Faraimunashe</span>
          </p>
          <p class="text-xs text-slate-500">
            NMCS Zimbabwe Easter Conference Registration System
          </p>
          <p class="text-xs text-slate-400">
            © {{ currentYear }} All rights reserved
          </p>
        </div>
      </div>
    </footer>
</template>

<script setup>
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>

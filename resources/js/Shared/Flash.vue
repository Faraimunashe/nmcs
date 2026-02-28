<template>
  <div
    v-if="visible && message"
    class="fixed right-4 top-4 z-50 max-w-sm w-full"
  >
    <div
      class="pointer-events-auto rounded-xl border shadow-lg px-4 py-3 flex items-start gap-3 transition
             bg-white/95 backdrop-blur"
      :class="isSuccess ? 'border-emerald-200 shadow-emerald-100' : 'border-rose-200 shadow-rose-100'"
    >
      <div
        class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full"
        :class="isSuccess ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
      >
        <i :class="isSuccess ? 'fas fa-check' : 'fas fa-exclamation-circle'"></i>
      </div>
      <div class="flex-1">
        <p
          class="text-sm font-semibold"
          :class="isSuccess ? 'text-emerald-800' : 'text-rose-800'"
        >
          {{ isSuccess ? 'Success' : 'Error' }}
        </p>
        <p class="mt-0.5 text-xs text-slate-600">
          {{ message }}
        </p>
      </div>
      <button
        type="button"
        class="ml-2 text-slate-400 hover:text-slate-600 transition"
        @click="close"
        aria-label="Close notification"
      >
        <i class="fas fa-times text-xs"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const type = ref(null);
const text = ref('');
const visible = ref(false);
let timer = null;

const AUTO_HIDE_MS = 10000;

const message = computed(() => text.value);
const isSuccess = computed(() => type.value === 'success');

const show = (newType, newText) => {
  if (!newText) return;

  type.value = newType;
  text.value = newText;
  visible.value = true;

  if (timer) {
    clearTimeout(timer);
  }
  timer = setTimeout(() => {
    visible.value = false;
  }, AUTO_HIDE_MS);
};

const close = () => {
  visible.value = false;
  if (timer) {
    clearTimeout(timer);
    timer = null;
  }
};

watch(
  () => page.props.flash?.success,
  (value) => {
    if (value) {
      show('success', value);
    }
  }
);

watch(
  () => page.props.flash?.error,
  (value) => {
    if (value) {
      show('error', value);
    }
  }
);

onMounted(() => {
  if (page.props.flash?.success) {
    show('success', page.props.flash.success);
  } else if (page.props.flash?.error) {
    show('error', page.props.flash.error);
  }
});

onBeforeUnmount(() => {
  if (timer) {
    clearTimeout(timer);
  }
});
</script>

<template>
  <div
    v-if="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    @click.self="$emit('cancel')"
  >
    <div class="w-full max-w-sm mx-4 rounded-2xl bg-white p-5 shadow-xl ring-1 ring-slate-200">
      <div class="space-y-4">
        <div>
          <h3 class="text-base font-semibold text-slate-900">{{ title }}</h3>
          <p v-if="message" class="mt-1 text-sm text-slate-500">
            {{ message }}
          </p>
        </div>
        <div class="flex items-center justify-end gap-3">
          <button
            type="button"
            class="text-sm font-semibold text-slate-600 hover:text-slate-800"
            @click="$emit('cancel')"
          >
            {{ cancelText || 'Cancel' }}
          </button>
          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500"
            @click="$emit('confirm')"
          >
            <i class="fa-solid fa-check"></i>
            {{ confirmText || 'Confirm' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    required: true,
  },
  message: {
    type: String,
    default: '',
  },
  confirmText: {
    type: String,
    default: 'Confirm',
  },
  cancelText: {
    type: String,
    default: 'Cancel',
  },
});

defineEmits(['confirm', 'cancel']);
</script>


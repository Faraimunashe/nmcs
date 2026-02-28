<template>
  <div class="flex items-start gap-3">
    <input
      :id="id"
      :type="type"
      :checked="modelValue"
      :required="required"
      :disabled="disabled"
      :class="[
        'mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40',
        error && 'border-red-300',
        disabled && 'opacity-50 cursor-not-allowed'
      ]"
      @change="$emit('update:modelValue', $event.target.checked)"
    />
    <label v-if="label" :for="id" class="text-sm text-slate-600">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
  </div>
  <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    default: () => `checkbox-${Math.random().toString(36).substr(2, 9)}`
  },
  label: String,
  type: {
    type: String,
    default: 'checkbox'
  },
  modelValue: Boolean,
  required: Boolean,
  disabled: Boolean,
  error: String
});

defineEmits(['update:modelValue']);
</script>

<template>
  <div>
    <label v-if="label" :for="id" class="text-sm font-medium text-slate-700">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <select
      :id="id"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :class="[
        'mt-2 w-full rounded-2xl bg-white px-4 py-3 text-[15px] text-slate-900 ring-1',
        error
          ? 'ring-red-300 focus:ring-2 focus:ring-red-400/40 focus:border-red-500'
          : 'ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500',
        disabled && 'opacity-50 cursor-not-allowed'
      ]"
      @change="$emit('update:modelValue', $event.target.value)"
      @blur="$emit('blur')"
    >
      <option v-if="placeholder" value="">{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
    <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    <p v-else-if="hint" class="mt-1 text-xs text-slate-500">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  id: {
    type: String,
    default: () => `select-${Math.random().toString(36).substr(2, 9)}`
  },
  label: String,
  modelValue: [String, Number],
  options: {
    type: Array,
    required: true,
    default: () => []
  },
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String,
  hint: String
});

defineEmits(['update:modelValue', 'blur']);
</script>

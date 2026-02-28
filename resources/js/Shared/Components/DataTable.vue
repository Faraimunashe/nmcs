<template>
  <div class="overflow-hidden rounded-3xl bg-white ring-1 ring-slate-200 shadow-xl shadow-emerald-900/5">
    <div v-if="searchable || $slots.header" class="border-b border-slate-200 px-6 py-4">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <slot name="header">
          <h3 v-if="title" class="text-lg font-semibold text-slate-900">{{ title }}</h3>
        </slot>
        <div v-if="searchable" class="w-full sm:w-auto">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              :placeholder="searchPlaceholder"
              class="w-full rounded-2xl border-slate-300 bg-white px-4 py-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-500 sm:w-64"
              @input="$emit('search', $event.target.value)"
            />
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr>
            <th
              v-for="(column, index) in columns"
              :key="index"
              :class="[
                'px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700',
                column.align === 'right' && 'text-right',
                column.align === 'center' && 'text-center'
              ]"
            >
              {{ column.label }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 bg-white">
          <tr v-if="loading" v-for="n in 5" :key="n" class="animate-pulse">
            <td
              v-for="(column, index) in columns"
              :key="index"
              class="px-6 py-4 whitespace-nowrap"
            >
              <div class="h-4 bg-slate-200 rounded w-3/4"></div>
            </td>
          </tr>
          <slot v-else-if="!loading" />
          <tr v-if="!loading && empty">
            <td :colspan="columns.length" class="px-6 py-12">
              <slot name="empty">
                <EmptyState
                  :icon="emptyIcon"
                  :title="emptyTitle"
                  :description="emptyDescription"
                />
              </slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="pagination && pagination.links && pagination.links.length > 3" class="border-t border-slate-200">
      <Pagination
        :links="pagination.links"
        :from="pagination.from"
        :to="pagination.to"
        :total="pagination.total"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import EmptyState from './EmptyState.vue';
import Pagination from './Pagination.vue';

const searchQuery = ref('');

defineProps({
  columns: {
    type: Array,
    required: true
  },
  title: String,
  searchable: Boolean,
  searchPlaceholder: {
    type: String,
    default: 'Search...'
  },
  loading: Boolean,
  empty: Boolean,
  emptyIcon: {
    type: String,
    default: 'fas fa-inbox'
  },
  emptyTitle: {
    type: String,
    default: 'No data found'
  },
  emptyDescription: {
    type: String,
    default: 'There are no items to display at this time.'
  },
  pagination: Object
});

defineEmits(['search']);
</script>

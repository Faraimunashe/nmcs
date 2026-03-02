<template>
  <Head :title="title" />
  
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">{{ title }}</h1>
        <p class="mt-1 text-sm text-slate-500">List of students {{ type === 'fully-paid' ? 'who have fully paid' : type === 'partial' ? 'with partial payments' : 'who have not made any payments' }}</p>
      </div>
      <Link href="/admin/dashboard" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Dashboard
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div class="relative w-full sm:max-w-md">
            <input
              v-model="filters.search"
              type="text"
              placeholder="Search by name or email..."
              @input="applyFilters"
              class="w-full rounded-2xl bg-white px-4 py-2 pl-11 text-sm text-slate-900 placeholder:text-slate-400 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
            />
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          </div>
          <div class="flex items-center gap-2">
            <a
              :href="exportUrl('excel')"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500"
            >
              <i class="fa-solid fa-file-excel"></i>
              Export Excel
            </a>
            <a
              :href="exportUrl('pdf')"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
            >
              <i class="fa-solid fa-file-pdf"></i>
              Export PDF
            </a>
          </div>
        </div>

        <div v-if="students?.data && students.data.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Institution</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Total Paid</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Balance</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="student in students.data" :key="student.id" class="hover:bg-slate-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-slate-900">{{ student.firstnames }} {{ student.surname }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ student.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ student.institution }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-emerald-700">${{ student.total_paid }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold" :class="parseFloat(student.balance) > 0 ? 'text-red-700' : 'text-emerald-700'">
                    ${{ student.balance }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link
                    :href="`/admin/attendants/${student.id}/transactions`"
                    class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-900"
                  >
                    <i class="fa-solid fa-receipt"></i>
                    View Transactions
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <EmptyState
          v-else
          icon="fas fa-users"
          title="No students found"
          description="No students match your search criteria."
        />

        <div v-if="students && students.total > students.per_page" class="flex items-center justify-between border-t border-slate-200 pt-4">
          <div class="text-sm text-slate-600">
            Showing {{ ((students.current_page - 1) * students.per_page) + 1 }} to {{ Math.min(students.current_page * students.per_page, students.total) }} of {{ students.total }} results
          </div>
          <div class="flex gap-2">
            <button
              v-if="students.current_page > 1"
              @click="goToPage(students.current_page - 1)"
              class="rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:ring-emerald-200"
            >
              Previous
            </button>
            <button
              v-if="students.current_page < students.last_page"
              @click="goToPage(students.current_page + 1)"
              class="rounded-2xl bg-white px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:ring-emerald-200"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import EmptyState from '../../../Shared/Components/EmptyState.vue';
import { ref } from 'vue';

const props = defineProps({
  title: String,
  type: String,
  students: Object,
  filters: Object,
});

const filters = ref({
  search: props.filters?.search || '',
});

const applyFilters = () => {
  const routeMap = {
    'fully-paid': '/admin/attendants/payments/fully-paid',
    'partial': '/admin/attendants/payments/partial',
    'none': '/admin/attendants/payments/none',
  };
  
  router.get(routeMap[props.type] || '/admin/attendants', filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const goToPage = (page) => {
  const routeMap = {
    'fully-paid': '/admin/attendants/payments/fully-paid',
    'partial': '/admin/attendants/payments/partial',
    'none': '/admin/attendants/payments/none',
  };
  
  router.get(routeMap[props.type] || '/admin/attendants', {
    ...filters.value,
    page: page
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const exportUrl = (format) => {
  const params = new URLSearchParams({ type: props.type, format });
  if (filters.value.search) params.set('search', filters.value.search);
  return `/admin/attendants/export?${params.toString()}`;
};
</script>

<script>
export default {
  layout: Layout
};
</script>

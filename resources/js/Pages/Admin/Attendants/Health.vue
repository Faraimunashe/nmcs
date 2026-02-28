<template>
  <Head :title="title" />
  
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">{{ title }}</h1>
        <p class="mt-1 text-sm text-slate-500">List of students with {{ type === 'dietary' ? 'dietary requirements' : type === 'chronic' ? 'chronic conditions' : 'disabilities' }}</p>
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
        </div>

        <div v-if="students?.data && students.data.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Institution</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Region</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">{{ type === 'dietary' ? 'Dietary Requirements' : type === 'chronic' ? 'Chronic Conditions' : 'Disabilities' }}</th>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ student.region }}
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-wrap gap-2">
                    <span
                      v-for="condition in student.conditions"
                      :key="condition"
                      :class="[
                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold ring-1',
                        type === 'dietary' ? 'bg-blue-50 text-blue-700 ring-blue-200' :
                        type === 'chronic' ? 'bg-orange-50 text-orange-700 ring-orange-200' :
                        'bg-purple-50 text-purple-700 ring-purple-200'
                      ]"
                    >
                      {{ condition }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link
                    :href="`/admin/attendants/${student.id}`"
                    class="text-emerald-600 hover:text-emerald-900"
                  >
                    <i class="fa-solid fa-eye mr-1"></i>
                    View
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

        <Pagination
          v-if="students.links && students.links.length > 3"
          :links="students.links"
          :from="students.from"
          :to="students.to"
          :total="students.total"
        />
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import EmptyState from '../../../Shared/Components/EmptyState.vue';
import Pagination from '../../../Shared/Components/Pagination.vue';
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
    dietary: '/admin/attendants/health/dietary',
    chronic: '/admin/attendants/health/chronic',
    disabilities: '/admin/attendants/health/disabilities',
  };
  
  router.get(routeMap[props.type] || '/admin/attendants', filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout
};
</script>

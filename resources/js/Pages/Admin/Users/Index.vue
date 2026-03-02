<template>
  <Head title="Admin - Users" />

  <div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Users</h1>
        <p class="mt-1 text-sm text-slate-500">Manage system users and roles</p>
      </div>
      <Link
        href="/admin/users/create"
        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
      >
        <i class="fa-solid fa-user-plus"></i>
        New User
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

          <div class="flex flex-wrap gap-2">
            <SelectInput
              v-model="filters.role"
              :options="roleOptions"
              placeholder="All Roles"
              @change="applyFilters"
            />
          </div>
        </div>

        <div v-if="users?.data && users.data.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Name</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Roles</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Created</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-slate-900">{{ user.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ user.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  <div class="flex flex-wrap gap-2">
                    <span
                      v-for="role in user.roles"
                      :key="role"
                      class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200"
                    >
                      {{ role }}
                    </span>
                    <span v-if="!user.roles || user.roles.length === 0" class="text-xs text-slate-400">No role</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ user.created_at || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-3">
                    <Link
                      :href="`/admin/users/${user.id}/edit`"
                      class="text-emerald-600 hover:text-emerald-900"
                    >
                      <i class="fa-solid fa-pen-to-square mr-1"></i>
                      Edit
                    </Link>
                    <button
                      type="button"
                      class="text-red-600 hover:text-red-900"
                      @click="confirmDelete(user)"
                    >
                      <i class="fa-solid fa-trash-can mr-1"></i>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <EmptyState
          v-else
          icon="fas fa-user"
          title="No users found"
          description="No users match your search criteria."
        />

        <Pagination
          v-if="users.links && users.links.length > 3"
          :links="users.links"
          :from="users.from"
          :to="users.to"
          :total="users.total"
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
import SelectInput from '../../../Shared/Components/SelectInput.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
});

const filters = ref({
  search: props.filters?.search || '',
  role: props.filters?.role || '',
});

const roleOptions = computed(() => {
  const base = [{ value: '', label: 'All Roles' }];

  if (!props.roles || props.roles.length === 0) {
    return base;
  }

  return base.concat(
    props.roles.map((role) => ({
      value: role.name,
      label: role.display_name || role.name,
    })),
  );
});

const applyFilters = () => {
  router.get('/admin/users', filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const confirmDelete = (user) => {
  if (!confirm(`Are you sure you want to delete ${user.name}?`)) {
    return;
  }

  router.delete(`/admin/users/${user.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


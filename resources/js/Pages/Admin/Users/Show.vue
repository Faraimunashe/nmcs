<template>
  <Head :title="`Admin - ${user.name}`" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">User Details</h1>
        <p class="mt-1 text-sm text-slate-500">View user information and roles</p>
      </div>
      <div class="flex items-center gap-3">
        <Link href="/admin/users" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
          <i class="fa-solid fa-arrow-left mr-2"></i>
          Back to Users
        </Link>
        <Link
          :href="`/admin/users/${user.id}/edit`"
          class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-500"
        >
          <i class="fa-solid fa-pen-to-square"></i>
          Edit
        </Link>
      </div>
    </div>

    <Card>
      <div class="space-y-4">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Name:</span>
              <span class="text-sm font-semibold text-slate-900">{{ user.name }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Email:</span>
              <span class="text-sm font-semibold text-slate-900">{{ user.email }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Created At:</span>
              <span class="text-sm font-semibold text-slate-900">{{ user.created_at || '-' }}</span>
            </div>
          </div>

          <div class="space-y-3">
            <p class="text-sm font-semibold text-slate-700">Roles</p>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="role in user.roles"
                :key="role"
                class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200"
              >
                {{ role }}
              </span>
              <span v-if="!user.roles || user.roles.length === 0" class="text-xs text-slate-400">No role assigned</span>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';

defineProps({
  user: Object,
});
</script>

<script>
export default {
  layout: Layout,
};
</script>


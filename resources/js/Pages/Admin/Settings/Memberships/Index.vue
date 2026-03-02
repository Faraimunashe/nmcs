<template>
  <Head title="Admin - Memberships" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Memberships</h1>
        <p class="mt-1 text-sm text-slate-500">Membership statuses available to students.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-3" @submit.prevent="submitMembership">
          <TextInput
            v-model="membershipForm.status"
            label="Status"
            placeholder="e.g. Member, Non-member"
            :error="membershipForm.errors.status"
            required
          />
          <TextInput
            v-model="membershipForm.description"
            label="Description"
            placeholder="Optional description"
            :error="membershipForm.errors.description"
          />
          <div class="flex items-end">
            <PrimaryButton type="submit" :processing="membershipForm.processing" size="sm">
              Add Membership
            </PrimaryButton>
          </div>
        </form>

        <div v-if="memberships && memberships.length > 0" class="space-y-2">
          <div
            v-for="membership in memberships"
            :key="membership.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900">{{ membership.status }}</p>
              <p class="text-xs text-slate-500">{{ membership.description || 'No description' }}</p>
            </div>
            <button
              type="button"
              class="text-xs font-semibold text-red-600 hover:text-red-900"
              @click="deleteMembership(membership)"
            >
              <i class="fa-solid fa-trash-can mr-1"></i>
              Delete
            </button>
          </div>
        </div>
        <EmptyState
          v-else
          icon="fas fa-id-card"
          title="No memberships"
          description="Define membership statuses used when registering students."
        />
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import Layout from '../../../../Shared/Layout.vue';
import Card from '../../../../Shared/Components/Card.vue';
import TextInput from '../../../../Shared/Components/TextInput.vue';
import PrimaryButton from '../../../../Shared/Components/PrimaryButton.vue';
import EmptyState from '../../../../Shared/Components/EmptyState.vue';

const props = defineProps({
  memberships: Array,
});

const membershipForm = useForm({
  status: '',
  description: '',
});

const submitMembership = () => {
  membershipForm.post('/admin/settings/memberships', {
    preserveScroll: true,
    onSuccess: () => membershipForm.reset('status', 'description'),
  });
};

const deleteMembership = (membership) => {
  if (!confirm(`Delete membership "${membership.status}"?`)) {
    return;
  }
  router.delete(`/admin/settings/memberships/${membership.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


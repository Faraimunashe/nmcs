<template>
  <Head title="Admin - Conference Fees" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Conference Fees</h1>
        <p class="mt-1 text-sm text-slate-500">Set and manage conference fees. Only one fee is active at a time.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-4" @submit.prevent="submitConferenceFee">
          <TextInput
            v-model="conferenceFeeForm.name"
            label="Name"
            placeholder="Easter Conference 2026"
            :error="conferenceFeeForm.errors.name"
            required
          />
          <TextInput
            v-model="conferenceFeeForm.amount"
            type="number"
            label="Amount (USD)"
            placeholder="25.00"
            :error="conferenceFeeForm.errors.amount"
            required
          />
          <TextInput
            v-model="conferenceFeeForm.description"
            label="Description"
            placeholder="Optional description"
            :error="conferenceFeeForm.errors.description"
          />
          <div class="flex items-end gap-3">
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
              <input
                v-model="conferenceFeeForm.is_active"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
              />
              <span>Set as active</span>
            </label>
          </div>
          <div class="md:col-span-4 flex justify-end">
            <PrimaryButton type="submit" :processing="conferenceFeeForm.processing" icon="fa-solid fa-save">
              Save Fee
            </PrimaryButton>
          </div>
        </form>

        <div v-if="conferenceFees && conferenceFees.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Name</th>
                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Amount</th>
                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Active</th>
                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="fee in conferenceFees" :key="fee.id">
                <td class="px-4 py-2 text-sm text-slate-900">{{ fee.name }}</td>
                <td class="px-4 py-2 text-sm text-slate-900">${{ Number(fee.amount).toFixed(2) }}</td>
                <td class="px-4 py-2 text-sm">
                  <span
                    v-if="fee.is_active"
                    class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200"
                  >
                    Active
                  </span>
                  <span v-else class="text-xs text-slate-400">Inactive</span>
                </td>
                <td class="px-4 py-2 text-sm">
                  <button
                    type="button"
                    class="text-red-600 hover:text-red-900"
                    @click="deleteConferenceFee(fee)"
                  >
                    <i class="fa-solid fa-trash-can mr-1"></i>
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <EmptyState
          v-else
          icon="fas fa-receipt"
          title="No conference fees"
          description="Add at least one conference fee to use in balances and payments."
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
  conferenceFees: Array,
});

const conferenceFeeForm = useForm({
  name: '',
  description: '',
  amount: '',
  is_active: true,
});

const submitConferenceFee = () => {
  conferenceFeeForm.post('/admin/settings/conference-fees', {
    preserveScroll: true,
    onSuccess: () => conferenceFeeForm.reset('name', 'description', 'amount'),
  });
};

const deleteConferenceFee = (fee) => {
  if (!confirm(`Delete conference fee "${fee.name}"?`)) {
    return;
  }
  router.delete(`/admin/settings/conference-fees/${fee.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


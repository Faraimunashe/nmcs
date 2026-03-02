<template>
  <Head title="Admin - Payment Recipients" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Payment Recipients</h1>
        <p class="mt-1 text-sm text-slate-500">Define who payments can be made to.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-3" @submit.prevent="submitPaymentRecipient">
          <TextInput
            v-model="paymentRecipientForm.name"
            label="Name"
            placeholder="NMCS Zimbabwe"
            :error="paymentRecipientForm.errors.name"
            required
          />
          <TextInput
            v-model="paymentRecipientForm.details"
            label="Details"
            placeholder="Account number or description"
            :error="paymentRecipientForm.errors.details"
          />
          <div class="flex items-end justify-between gap-3">
            <label class="inline-flex items-center gap-2 text-xs text-slate-700">
              <input
                v-model="paymentRecipientForm.is_active"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
              />
              <span>Active</span>
            </label>
            <PrimaryButton type="submit" :processing="paymentRecipientForm.processing" size="sm">
              Add Recipient
            </PrimaryButton>
          </div>
        </form>

        <div v-if="paymentRecipients && paymentRecipients.length > 0" class="space-y-2">
          <div
            v-for="recipient in paymentRecipients"
            :key="recipient.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900">{{ recipient.name }}</p>
              <p class="text-xs text-slate-500">{{ recipient.details || 'No additional details' }}</p>
              <p class="text-xs text-slate-500">
                {{ recipient.is_active ? 'Active' : 'Inactive' }}
              </p>
            </div>
            <button
              type="button"
              class="text-xs font-semibold text-red-600 hover:text-red-900"
              @click="deletePaymentRecipient(recipient)"
            >
              <i class="fa-solid fa-trash-can mr-1"></i>
              Delete
            </button>
          </div>
        </div>
        <EmptyState
          v-else
          icon="fas fa-user-tag"
          title="No payment recipients"
          description="Add named recipients to help track where payments are going."
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
  paymentRecipients: Array,
});

const paymentRecipientForm = useForm({
  name: '',
  details: '',
  is_active: true,
});

const submitPaymentRecipient = () => {
  paymentRecipientForm.post('/admin/settings/payment-recipients', {
    preserveScroll: true,
    onSuccess: () => paymentRecipientForm.reset('name', 'details'),
  });
};

const deletePaymentRecipient = (recipient) => {
  if (!confirm(`Delete payment recipient "${recipient.name}"?`)) {
    return;
  }
  router.delete(`/admin/settings/payment-recipients/${recipient.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


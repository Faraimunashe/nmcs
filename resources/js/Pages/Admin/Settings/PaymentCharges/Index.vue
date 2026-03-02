<template>
  <Head title="Admin - Payment Charges" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Payment Charges</h1>
        <p class="mt-1 text-sm text-slate-500">Service charges applied to payment methods.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-3" @submit.prevent="submitPaymentCharge">
          <TextInput
            v-model="paymentChargeForm.narration"
            label="Narration"
            placeholder="EcoCash Charge"
            :error="paymentChargeForm.errors.narration"
            required
          />
          <TextInput
            v-model="paymentChargeForm.amount"
            type="number"
            label="Amount"
            placeholder="0.50"
            :error="paymentChargeForm.errors.amount"
            required
          />
          <div class="flex items-end">
            <PrimaryButton type="submit" :processing="paymentChargeForm.processing" size="sm">
              Add Charge
            </PrimaryButton>
          </div>
        </form>

        <div v-if="paymentCharges && paymentCharges.length > 0" class="space-y-2">
          <div
            v-for="charge in paymentCharges"
            :key="charge.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900">{{ charge.narration }}</p>
              <p class="text-xs text-slate-500">${{ Number(charge.amount).toFixed(2) }}</p>
            </div>
            <button
              type="button"
              class="text-xs font-semibold text-red-600 hover:text-red-900"
              @click="deletePaymentCharge(charge)"
            >
              <i class="fa-solid fa-trash-can mr-1"></i>
              Delete
            </button>
          </div>
        </div>
        <EmptyState
          v-else
          icon="fas fa-percent"
          title="No payment charges"
          description="Optional service charges can be added here."
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
  paymentCharges: Array,
});

const paymentChargeForm = useForm({
  narration: '',
  amount: '',
});

const submitPaymentCharge = () => {
  paymentChargeForm.post('/admin/settings/payment-charges', {
    preserveScroll: true,
    onSuccess: () => paymentChargeForm.reset('narration', 'amount'),
  });
};

const deletePaymentCharge = (charge) => {
  if (!confirm(`Delete charge "${charge.narration}"?`)) {
    return;
  }
  router.delete(`/admin/settings/payment-charges/${charge.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


<template>
  <Head title="Payment Details" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Payment Details</h1>
        <p class="mt-1 text-sm text-slate-500">View full information about this payment</p>
      </div>
      <button
        type="button"
        class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
        @click="$inertia.visit('/payments')"
      >
        <i class="fas fa-arrow-left"></i>
        Back to Payments
      </button>
    </div>

    <Card>
      <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <p class="text-xs font-semibold text-slate-600">Amount</p>
            <p class="mt-1 text-3xl font-semibold text-slate-900">
              ${{ payment.amount }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
              Credited to conference account:
              <span class="font-semibold">${{ payment.final_amount }}</span>
            </p>
          </div>
          <div class="space-y-3 text-sm">
            <div class="flex items-center gap-3">
              <span class="text-xs font-semibold text-slate-600">Status</span>
              <Badge :variant="getStatusVariant(payment.status)">
                {{ payment.status }}
              </Badge>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xs font-semibold text-slate-600">Purpose</span>
              <span class="text-sm text-slate-800">
                {{ formatPurpose(payment.purpose) }}
              </span>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xs font-semibold text-slate-600">Date</span>
              <span class="text-sm text-slate-800">
                {{ payment.payment_date }}
              </span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div class="space-y-3">
            <h2 class="text-sm font-semibold text-slate-700">Payment Details</h2>
            <dl class="space-y-2 text-sm">
              <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Method</dt>
                <dd class="font-medium text-slate-900">
                  {{ payment.payment_method?.name || 'N/A' }}
                </dd>
              </div>
              <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Recipient</dt>
                <dd class="font-medium text-slate-900">
                  {{ payment.payment_recipient?.name || 'N/A' }}
                </dd>
              </div>
              <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Reference</dt>
                <dd class="font-mono text-xs text-slate-900">
                  {{ payment.reference || '-' }}
                </dd>
              </div>
              <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Created At</dt>
                <dd class="text-slate-900">
                  {{ payment.created_at }}
                </dd>
              </div>
            </dl>
          </div>

          <div class="space-y-3">
            <h2 class="text-sm font-semibold text-slate-700">Student</h2>
            <dl class="space-y-2 text-sm">
              <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Name</dt>
                <dd class="font-medium text-slate-900">
                  {{ studentName }}
                </dd>
              </div>
            </dl>

            <div v-if="payment.status === 'REJECTED' && payment.rejection_reason" class="mt-4 rounded-2xl bg-red-50 p-3 text-xs text-red-700 ring-1 ring-red-200">
              <div class="mb-1 font-semibold">
                <i class="fas fa-circle-exclamation mr-1"></i>
                Rejection Reason
              </div>
              <p>{{ payment.rejection_reason }}</p>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import Layout from '../../Shared/Layout.vue';
import Card from '../../Shared/Components/Card.vue';
import Badge from '../../Shared/Components/Badge.vue';

const props = defineProps({
  payment: Object,
});

const studentName = computed(() => {
  if (!props.payment?.student) return 'N/A';
  return `${props.payment.student.firstnames} ${props.payment.student.surname}`.trim();
});

const formatPurpose = (purpose) => {
  const map = {
    DEPOSIT: 'Deposit',
    FULL_PAYMENT: 'Full Payment',
    BALANCE: 'Balance',
  };
  return map[purpose] || purpose;
};

const getStatusVariant = (status) => {
  const map = {
    PENDING: 'warning',
    APPROVED: 'success',
    REJECTED: 'error',
  };
  return map[status] || 'default';
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


<template>
  <Head :title="`Transactions - ${student.name}`" />
  
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Payment Transactions</h1>
        <p class="mt-1 text-sm text-slate-500">{{ student.name }} - Payment history and balance</p>
      </div>
      <Link href="/admin/attendants" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Attendants
      </Link>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Conference Fee</p>
          <p class="text-2xl font-semibold text-slate-900">${{ balance.conference_fee }}</p>
        </div>
      </Card>
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Total Paid</p>
          <p class="text-2xl font-semibold text-emerald-700">${{ balance.total_paid }}</p>
        </div>
      </Card>
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Pending</p>
          <p class="text-2xl font-semibold text-amber-700">${{ balance.total_pending }}</p>
        </div>
      </Card>
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Balance</p>
          <p class="text-2xl font-semibold" :class="parseFloat(balance.balance) > 0 ? 'text-red-700' : 'text-emerald-700'">
            ${{ balance.balance }}
          </p>
        </div>
      </Card>
    </div>

    <Card>
      <div class="space-y-4">
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Payment History</h2>
          <p class="text-xs text-slate-500">All payment transactions for this student</p>
        </div>

        <div v-if="payments && payments.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Purpose</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Method</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Details</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="payment in payments" :key="payment.id" class="hover:bg-slate-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                  {{ payment.payment_date }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-slate-900">${{ payment.amount }}</div>
                  <div class="text-xs text-slate-500">Final: ${{ payment.final_amount }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ formatPurpose(payment.purpose) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ payment.payment_method }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ payment.reference || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <Badge :variant="getStatusVariant(payment.status)">
                    {{ payment.status }}
                  </Badge>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                  <div v-if="payment.status === 'APPROVED' && payment.approved_by" class="text-xs">
                    Approved by {{ payment.approved_by }}<br>
                    {{ payment.approved_at }}
                  </div>
                  <div v-else-if="payment.status === 'REJECTED' && payment.rejected_by" class="text-xs">
                    Rejected by {{ payment.rejected_by }}<br>
                    {{ payment.rejected_at }}<br>
                    <span class="text-red-600">{{ payment.rejection_reason }}</span>
                  </div>
                  <div v-else class="text-xs text-slate-400">
                    -
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <EmptyState
          v-else
          icon="fas fa-receipt"
          title="No payments found"
          description="This student has not made any payments yet."
        />
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import Badge from '../../../Shared/Components/Badge.vue';
import EmptyState from '../../../Shared/Components/EmptyState.vue';

const props = defineProps({
  student: Object,
  balance: Object,
  payments: Array,
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
  layout: Layout
};
</script>

<template>
  <Head title="My Payments" />
  
  <div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">My Payments</h1>
        <p class="mt-1 text-sm text-slate-500">View and manage all your payment records</p>
      </div>
      <PrimaryButton @click="$inertia.visit('/payments/create')">
        <i class="fas fa-plus mr-2"></i>
        Record Payment
      </PrimaryButton>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Total Paid</p>
          <p class="text-2xl font-semibold text-emerald-700">${{ balanceSummary?.total_paid || '0.00' }}</p>
          <p class="text-xs text-slate-500">Approved payments</p>
        </div>
      </Card>
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Pending</p>
          <p class="text-2xl font-semibold text-slate-900">${{ balanceSummary?.total_pending || '0.00' }}</p>
          <p class="text-xs text-slate-500">Awaiting approval</p>
        </div>
      </Card>
      <Card>
        <div class="space-y-2">
          <p class="text-xs font-semibold text-slate-600">Final Balance</p>
          <p class="text-2xl font-semibold" :class="parseFloat(balanceSummary?.balance || 0) >= 0 ? 'text-emerald-700' : 'text-red-700'">
            ${{ balanceSummary?.balance || '0.00' }}
          </p>
          <p class="text-xs text-slate-500">{{ parseFloat(balanceSummary?.balance || 0) >= 0 ? 'Remaining' : 'Overpaid' }}</p>
        </div>
      </Card>
    </div>

    <Card>
      <div class="space-y-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div class="flex flex-wrap gap-2">
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="rounded-2xl border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
            >
              <option value="">All Status</option>
              <option value="PENDING">Pending</option>
              <option value="APPROVED">Approved</option>
              <option value="REJECTED">Rejected</option>
            </select>
            <select
              v-model="filters.purpose"
              @change="applyFilters"
              class="rounded-2xl border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"
            >
              <option value="">All Purposes</option>
              <option value="DEPOSIT">Deposit</option>
              <option value="FULL_PAYMENT">Full Payment</option>
              <option value="BALANCE">Balance</option>
            </select>
          </div>
        </div>

        <!-- Desktop table -->
        <div v-if="payments?.data && payments.data.length > 0" class="hidden md:block overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Purpose</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Method</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Reference</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="payment in payments.data" :key="payment.id" class="hover:bg-slate-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                  {{ payment.payment_date }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-slate-900">${{ payment.amount }}</div>
                  <div class="text-xs text-slate-500">Credited: ${{ payment.final_amount }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ formatPurpose(payment.purpose) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ payment.payment_method.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <Badge :variant="getStatusVariant(payment.status)">
                    {{ payment.status }}
                  </Badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  <div>{{ payment.reference || '-' }}</div>
                  <div v-if="payment.status === 'REJECTED' && payment.rejection_reason" class="mt-1 text-xs text-red-600">
                    Reason: {{ payment.rejection_reason }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button
                    v-if="payment.status === 'PENDING'"
                    @click="$inertia.visit(`/payments/${payment.id}/edit`)"
                    class="text-emerald-600 hover:text-emerald-900"
                  >
                    <i class="fas fa-edit mr-1"></i>
                    Edit
                  </button>
                  <span v-else class="text-slate-400">-</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile cards -->
        <div v-if="payments?.data && payments.data.length > 0" class="space-y-3 md:hidden">
          <div
            v-for="payment in payments.data"
            :key="payment.id"
            class="rounded-2xl bg-white p-4 ring-1 ring-slate-200 space-y-2"
          >
            <div class="flex items-center justify-between gap-3">
              <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">{{ formatPurpose(payment.purpose) }}</div>
                <div class="text-xs text-slate-500">
                  {{ payment.payment_method.name }} • {{ payment.payment_date }}
                </div>
              </div>
              <Badge :variant="getStatusVariant(payment.status)">
                {{ payment.status }}
              </Badge>
            </div>
            <div class="flex items-center justify-between text-sm text-slate-700">
              <span class="font-semibold">${{ payment.amount }}</span>
              <span class="text-xs text-slate-500">Credited: ${{ payment.final_amount }}</span>
            </div>
            <div class="text-xs text-slate-600">
              Reference: <span class="font-mono">{{ payment.reference || '-' }}</span>
            </div>
            <div
              v-if="payment.status === 'REJECTED' && payment.rejection_reason"
              class="text-xs text-red-600"
            >
              Reason: {{ payment.rejection_reason }}
            </div>
            <div class="pt-2 border-t border-slate-200 text-right">
              <button
                v-if="payment.status === 'PENDING'"
                @click="$inertia.visit(`/payments/${payment.id}/edit`)"
                class="inline-flex items-center gap-1 rounded-2xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500"
              >
                <i class="fas fa-edit"></i>
                Edit Payment
              </button>
              <span v-else class="text-xs text-slate-400">No actions available</span>
            </div>
          </div>
        </div>

        <EmptyState
          v-else
          icon="fas fa-receipt"
          title="No payments found"
          description="You haven't recorded any payments yet."
        />

        <Pagination
          v-if="payments.links && payments.links.length > 3"
          :links="payments.links"
          :from="payments.from"
          :to="payments.to"
          :total="payments.total"
        />
      </div>
    </Card>
  </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import Layout from '../../Shared/Layout.vue';
import Card from '../../Shared/Components/Card.vue';
import Badge from '../../Shared/Components/Badge.vue';
import PrimaryButton from '../../Shared/Components/PrimaryButton.vue';
import EmptyState from '../../Shared/Components/EmptyState.vue';
import Pagination from '../../Shared/Components/Pagination.vue';
import { ref, watch } from 'vue';

const props = defineProps({
  payments: Object,
  paymentMethods: Array,
  filters: Object,
  balanceSummary: Object,
});

const filters = ref({
  status: props.filters?.status || '',
  purpose: props.filters?.purpose || '',
  method: props.filters?.method || '',
  date_from: props.filters?.date_from || '',
  date_to: props.filters?.date_to || '',
});

const applyFilters = () => {
  router.get('/payments', filters.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

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

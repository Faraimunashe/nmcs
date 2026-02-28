<template>
  <Head title="Admin - Payments" />
  
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900">Payments Management</h1>
      <p class="mt-1 text-sm text-slate-500">View and manage all payments</p>
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

        <div v-if="payments?.data && payments.data.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Student</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Purpose</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Method</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Date</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
              <tr v-for="payment in payments.data" :key="payment.id" class="hover:bg-slate-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-slate-900">{{ payment.student_name }}</div>
                  <div class="text-xs text-slate-500">{{ payment.student_email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-slate-900">${{ payment.amount }}</div>
                  <div class="text-xs text-slate-500">Final: ${{ payment.final_amount }}</div>
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
                  {{ payment.payment_date }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div v-if="payment.status === 'PENDING'" class="flex items-center justify-end gap-2">
                    <button
                      @click="showRejectModal(payment)"
                      class="text-red-600 hover:text-red-900"
                    >
                      <i class="fa-solid fa-times-circle"></i>
                    </button>
                    <button
                      @click="approvePayment(payment.id)"
                      class="text-emerald-600 hover:text-emerald-900"
                    >
                      <i class="fa-solid fa-check-circle"></i>
                    </button>
                  </div>
                  <span v-else-if="payment.status === 'APPROVED'" class="text-xs text-slate-500">
                    Approved by {{ payment.approved_by }}<br>
                    {{ payment.approved_at }}
                  </span>
                  <span v-else-if="payment.status === 'REJECTED'" class="text-xs text-slate-500">
                    Rejected by {{ payment.rejected_by }}<br>
                    {{ payment.rejected_at }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <EmptyState
          v-else
          icon="fas fa-receipt"
          title="No payments found"
          description="No payments match your filters."
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

    <div
      v-if="showRejectDialog"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click.self="closeRejectModal"
    >
      <Card class="w-full max-w-md mx-4">
        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold text-slate-900">Reject Payment</h3>
            <p class="text-sm text-slate-500">Please provide a reason for rejection</p>
          </div>
          <form @submit.prevent="rejectPayment">
            <div class="space-y-4">
              <TextInput
                v-model="rejectForm.rejection_reason"
                label="Rejection Reason"
                placeholder="Enter reason for rejection"
                :error="rejectForm.errors.rejection_reason"
                required
              />
              <div class="flex items-center justify-end gap-3">
                <button
                  type="button"
                  @click="closeRejectModal"
                  class="text-sm font-semibold text-slate-600 hover:text-slate-800"
                >
                  Cancel
                </button>
                <DangerButton type="submit" :processing="rejectForm.processing">
                  Reject Payment
                </DangerButton>
              </div>
            </div>
          </form>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import Badge from '../../../Shared/Components/Badge.vue';
import TextInput from '../../../Shared/Components/TextInput.vue';
import PrimaryButton from '../../../Shared/Components/PrimaryButton.vue';
import DangerButton from '../../../Shared/Components/DangerButton.vue';
import EmptyState from '../../../Shared/Components/EmptyState.vue';
import Pagination from '../../../Shared/Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
  payments: Object,
  filters: Object,
});

const filters = ref({
  status: props.filters?.status || '',
  purpose: props.filters?.purpose || '',
  method: props.filters?.method || '',
  date_from: props.filters?.date_from || '',
  date_to: props.filters?.date_to || '',
});

const showRejectDialog = ref(false);
const selectedPayment = ref(null);

const rejectForm = useForm({
  rejection_reason: '',
});

const applyFilters = () => {
  router.get('/admin/payments', filters.value, {
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

const approvePayment = (paymentId) => {
  if (confirm('Are you sure you want to approve this payment?')) {
    router.post(`/admin/payments/${paymentId}/approve`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        router.reload({ only: ['payments'] });
      },
    });
  }
};

const showRejectModal = (payment) => {
  selectedPayment.value = payment;
  showRejectDialog.value = true;
};

const closeRejectModal = () => {
  showRejectDialog.value = false;
  selectedPayment.value = null;
  rejectForm.reset();
};

const rejectPayment = () => {
  if (!selectedPayment.value) return;
  
  rejectForm.post(`/admin/payments/${selectedPayment.value.id}/reject`, {
    preserveScroll: true,
    onSuccess: () => {
      closeRejectModal();
      router.reload({ only: ['payments'] });
    },
  });
};
</script>

<script>
export default {
  layout: Layout
};
</script>

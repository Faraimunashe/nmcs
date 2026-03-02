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

        <!-- Desktop table -->
        <div v-if="payments?.data && payments.data.length > 0" class="hidden md:block overflow-x-auto">
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
                  <div v-if="payment.status === 'PENDING'" class="flex items-center justify-end gap-3">
                    <button
                      @click="showRejectModal(payment)"
                      class="inline-flex items-center gap-1 rounded-2xl bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100 hover:text-red-800"
                    >
                      <i class="fa-solid fa-times-circle"></i>
                      Reject
                    </button>
                    <button
                      @click="requestApprovePayment(payment.id)"
                      class="inline-flex items-center gap-1 rounded-2xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500"
                    >
                      <i class="fa-solid fa-check-circle"></i>
                      Approve
                    </button>
                  </div>
                  <span v-else-if="payment.status === 'APPROVED'" class="text-xs text-slate-500">
                    Approved by {{ payment.approved_by }}<br>
                    {{ payment.approved_at }}
                  </span>
                  <span v-else-if="payment.status === 'REJECTED'" class="text-xs text-slate-500">
                    Rejected by {{ payment.rejected_by }}<br>
                    {{ payment.rejected_at }}<br>
                    <span v-if="payment.rejection_reason" class="text-red-600">
                      Reason: {{ payment.rejection_reason }}
                    </span>
                  </span>
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
              <div>
                <div class="text-sm font-semibold text-slate-900">{{ payment.student_name }}</div>
                <div class="text-xs text-slate-500">{{ payment.student_email }}</div>
              </div>
              <Badge :variant="getStatusVariant(payment.status)">
                {{ payment.status }}
              </Badge>
            </div>
            <div class="flex items-center justify-between text-sm text-slate-700">
              <span class="font-semibold">${{ payment.amount }}</span>
              <span class="text-xs text-slate-500">Final: ${{ payment.final_amount }}</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-600">
              <span>{{ formatPurpose(payment.purpose) }}</span>
              <span>{{ payment.payment_method.name }}</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500">
              <span>{{ payment.payment_date }}</span>
            </div>
            <div class="pt-2 border-t border-slate-200 text-right">
              <div v-if="payment.status === 'PENDING'" class="flex items-center justify-end gap-3">
                <button
                  @click="showRejectModal(payment)"
                  class="inline-flex items-center gap-1 rounded-2xl bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 ring-1 ring-red-200 hover:bg-red-100 hover:text-red-800"
                >
                  <i class="fa-solid fa-times-circle"></i>
                  Reject
                </button>
                <button
                  @click="requestApprovePayment(payment.id)"
                  class="inline-flex items-center gap-1 rounded-2xl bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500"
                >
                  <i class="fa-solid fa-check-circle"></i>
                  Approve
                </button>
              </div>
              <div v-else-if="payment.status === 'APPROVED'" class="text-xs text-slate-500 text-left">
                Approved by {{ payment.approved_by }}<br>
                {{ payment.approved_at }}
              </div>
              <div v-else-if="payment.status === 'REJECTED'" class="text-xs text-slate-500 text-left">
                Rejected by {{ payment.rejected_by }}<br>
                {{ payment.rejected_at }}<br>
                <span v-if="payment.rejection_reason" class="text-red-600">
                  Reason: {{ payment.rejection_reason }}
                </span>
              </div>
            </div>
          </div>
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

  <ConfirmDialog
    :show="showApproveDialog"
    title="Approve payment?"
    message="This will mark the payment as approved and update the student's balance."
    confirm-text="Approve Payment"
    cancel-text="Cancel"
    @confirm="approvePayment"
    @cancel="() => { showApproveDialog = false; approvePaymentId = null; }"
  />
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
import ConfirmDialog from '../../../Shared/Components/ConfirmDialog.vue';
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

const showApproveDialog = ref(false);
const approvePaymentId = ref(null);

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

const requestApprovePayment = (paymentId) => {
  approvePaymentId.value = paymentId;
  showApproveDialog.value = true;
};

const approvePayment = () => {
  if (!approvePaymentId.value) return;

  router.post(`/admin/payments/${approvePaymentId.value}/approve`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      router.reload({ only: ['payments'] });
      showApproveDialog.value = false;
      approvePaymentId.value = null;
    },
  });
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

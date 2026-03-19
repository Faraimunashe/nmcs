<template>
  <Head title="Admin - Payment Details" />

  <div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Payment Details</h1>
        <p class="mt-1 text-sm text-slate-500">Review full payment information and update status</p>
      </div>

      <button
        type="button"
        class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200"
        @click="$inertia.visit('/admin/payments')"
      >
        <i class="fa-solid fa-arrow-left"></i>
        Back to Payments
      </button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <Card class="lg:col-span-2">
        <div class="space-y-6">
          <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
              <p class="text-xs font-semibold text-slate-600">Amount</p>
              <p class="mt-1 text-3xl font-semibold text-slate-900">${{ payment.amount }}</p>
              <p class="mt-1 text-xs text-slate-500">
                Credited to conference account:
                <span class="font-semibold">${{ payment.final_amount }}</span>
              </p>
            </div>

            <div class="space-y-3 text-sm w-full sm:w-auto">
              <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-slate-600">Status</span>
                <Badge :variant="getStatusVariant(payment.status)">
                  {{ payment.status }}
                </Badge>
              </div>

              <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                <div class="flex items-center gap-2">
                  <span class="text-xs font-semibold text-slate-600">Purpose</span>
                  <span class="text-sm text-slate-800">{{ formatPurpose(payment.purpose) }}</span>
                </div>

                <div class="flex items-center gap-2">
                  <span class="text-xs font-semibold text-slate-600">Payment Date</span>
                  <span class="text-sm text-slate-800">{{ payment.payment_date }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-3">
              <h2 class="text-sm font-semibold text-slate-700">Student</h2>

              <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Name</dt>
                  <dd class="font-medium text-slate-900">{{ payment.student?.name || 'N/A' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Email</dt>
                  <dd class="font-mono text-xs text-slate-900">{{ payment.student?.email || 'N/A' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Institution</dt>
                  <dd class="font-medium text-slate-900">{{ payment.student?.institution?.name || 'N/A' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Region</dt>
                  <dd class="font-medium text-slate-900">{{ payment.student?.region?.name || 'N/A' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Membership</dt>
                  <dd class="font-medium text-slate-900">
                    <span>
                      {{ payment.student?.membership?.status || 'N/A' }}
                    </span>
                    <span v-if="payment.student?.membership?.description" class="block text-xs text-slate-500 mt-1">
                      {{ payment.student.membership.description }}
                    </span>
                  </dd>
                </div>
              </dl>
            </div>

            <div class="space-y-3">
              <h2 class="text-sm font-semibold text-slate-700">Payment Details</h2>

              <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Method</dt>
                  <dd class="font-medium text-slate-900">{{ payment.payment_method?.name || 'N/A' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Recipient</dt>
                  <dd class="font-medium text-slate-900">{{ payment.payment_recipient?.name || 'N/A' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Reference</dt>
                  <dd class="font-mono text-xs text-slate-900">{{ payment.reference || '-' }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                  <dt class="text-slate-500">Description</dt>
                  <dd class="font-mono text-xs text-slate-900">
                    {{ payment.description || '-' }}
                  </dd>
                </div>
              </dl>
            </div>
          </div>

          <div class="space-y-3">
            <h2 class="text-sm font-semibold text-slate-700">Audit</h2>

            <dl class="grid grid-cols-1 gap-3 md:grid-cols-2 text-sm">
              <div class="flex justify-between gap-4">
                <dt class="text-slate-500">Created At</dt>
                <dd class="text-slate-900">{{ payment.created_at }}</dd>
              </div>

              <div v-if="payment.approved_by" class="flex justify-between gap-4">
                <dt class="text-slate-500">Approved By</dt>
                <dd class="text-slate-900">
                  {{ payment.approved_by }}
                  <span class="text-xs text-slate-500">({{ payment.approved_at }})</span>
                </dd>
              </div>

              <div v-if="payment.rejected_by" class="flex justify-between gap-4">
                <dt class="text-slate-500">Rejected By</dt>
                <dd class="text-slate-900">
                  {{ payment.rejected_by }}
                  <span class="text-xs text-slate-500">({{ payment.rejected_at }})</span>
                </dd>
              </div>
            </dl>

            <div
              v-if="payment.status === 'REJECTED' && payment.rejection_reason"
              class="mt-4 rounded-2xl bg-red-50 p-3 text-xs text-red-700 ring-1 ring-red-200"
            >
              <div class="mb-1 font-semibold">
                <i class="fa-solid fa-circle-exclamation mr-1"></i>
                Rejection Reason
              </div>
              <p>{{ payment.rejection_reason }}</p>
            </div>
          </div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <h2 class="text-sm font-semibold text-slate-700">Approval</h2>
          <p class="text-xs text-slate-500">
            Approve or reject this payment request. Actions are available only for pending payments.
          </p>

          <div v-if="payment.status === 'PENDING'" class="space-y-3">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
              <PrimaryButton
                class="w-full sm:w-auto"
                @click="showApproveDialog = true"
              >
                <i class="fa-solid fa-check mr-2"></i>
                Approve
              </PrimaryButton>

              <DangerButton
                class="w-full sm:w-auto"
                @click="openRejectModal"
              >
                <i class="fa-solid fa-xmark mr-2"></i>
                Reject
              </DangerButton>
            </div>
          </div>

          <div v-else class="rounded-2xl bg-slate-50 p-4 text-sm ring-1 ring-slate-200">
            <p class="font-semibold text-slate-900">No action available</p>
            <p class="mt-1 text-xs text-slate-600">
              This payment is currently marked as {{ payment.status }}.
            </p>
          </div>
        </div>
      </Card>
    </div>

    <ConfirmDialog
      :show="showApproveDialog"
      title="Approve this payment?"
      message="This will mark the payment as approved and update the student's balance."
      confirm-text="Approve Payment"
      cancel-text="Cancel"
      @confirm="approvePayment"
      @cancel="() => { showApproveDialog = false; }"
    />

    <div
      v-if="showRejectDialog"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click.self="closeRejectModal"
    >
      <Card class="w-full max-w-md mx-4">
        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold text-slate-900">Reject Payment</h3>
            <p class="text-sm text-slate-500">
              Please provide a reason for rejection. This reason will be saved with the payment.
            </p>
          </div>

          <form @submit.prevent="rejectPayment">
            <div class="space-y-4">
              <TextInput
                v-model="rejectForm.rejection_reason"
                label="Rejection Reason"
                placeholder="Enter rejection reason"
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
                  <i class="fa-solid fa-xmark mr-2"></i>
                  Confirm Reject
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
import { ref } from 'vue';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import Badge from '../../../Shared/Components/Badge.vue';
import ConfirmDialog from '../../../Shared/Components/ConfirmDialog.vue';
import TextInput from '../../../Shared/Components/TextInput.vue';
import DangerButton from '../../../Shared/Components/DangerButton.vue';
import PrimaryButton from '../../../Shared/Components/PrimaryButton.vue';

const props = defineProps({
  payment: Object,
});

const showApproveDialog = ref(false);
const showRejectDialog = ref(false);

const rejectForm = useForm({
  rejection_reason: '',
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

const approvePayment = () => {
  showApproveDialog.value = false;

  router.post(`/admin/payments/${props.payment.id}/approve`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      router.reload({ only: ['payment'] });
    },
  });
};

const openRejectModal = () => {
  rejectForm.reset();
  showRejectDialog.value = true;
};

const closeRejectModal = () => {
  showRejectDialog.value = false;
  rejectForm.reset();
};

const rejectPayment = () => {
  rejectForm.post(`/admin/payments/${props.payment.id}/reject`, {
    preserveScroll: true,
    onSuccess: () => {
      closeRejectModal();
      router.reload({ only: ['payment'] });
    },
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


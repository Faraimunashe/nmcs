<template>
  <Head title="Edit Payment" />
  
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900">Edit Payment</h1>
      <p class="mt-1 text-sm text-slate-500">Update payment details (only pending payments can be edited)</p>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <Card>
        <div class="space-y-5">
          <SelectInput
            v-model="form.student_id"
            label="Student"
            :options="studentOptions"
            required
            :error="form.errors.student_id"
            :disabled="true"
          />

          <SelectInput
            v-model="form.payment_method_id"
            label="Payment Method"
            :options="paymentMethodOptions"
            required
            :error="form.errors.payment_method_id"
            @update:modelValue="updatePaymentMethod"
          />

          <TextInput
            v-model="form.payment_recipient_name"
            label="Payment Recipient (Optional)"
            placeholder="Enter recipient name (e.g. John Moyo)"
            :error="form.errors.payment_recipient_name"
            hint="Enter the name of the person receiving the payment"
          />

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <SelectInput
              v-model="form.purpose"
              label="Payment Purpose"
              :options="[
                { value: 'DEPOSIT', label: 'Deposit' },
                { value: 'FULL_PAYMENT', label: 'Full Payment' },
                { value: 'BALANCE', label: 'Balance' }
              ]"
              required
              :error="form.errors.purpose"
            />
            <TextInput
              v-model="form.amount"
              label="Amount"
              type="number"
              step="0.01"
              min="0.01"
              placeholder="0.00"
              required
              :error="form.errors.amount"
            />
          </div>

          <div v-if="selectedPaymentMethod" class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Payment Amount:</span>
              <span class="text-sm font-semibold text-slate-900">${{ form.amount || '0.00' }}</span>
            </div>
            <div v-if="selectedPaymentMethod.charge > 0" class="mt-2 flex items-center justify-between">
              <span class="text-sm text-slate-600">Charge ({{ selectedPaymentMethod.charge_narration }}):</span>
              <span class="text-sm font-semibold text-red-600">-${{ selectedPaymentMethod.charge.toFixed(2) }}</span>
            </div>
            <div class="mt-2 flex items-center justify-between border-t border-slate-200 pt-2">
              <span class="text-sm font-semibold text-slate-900">Amount Credited:</span>
              <span class="text-lg font-semibold text-emerald-700">${{ finalAmount.toFixed(2) }}</span>
            </div>
          </div>

          <div v-if="requiresReference" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <TextInput
              v-model="form.reference"
              label="Payment Reference"
              placeholder="e.g. ECA-123456"
              required
              :error="form.errors.reference"
              hint="Transaction reference number"
            />
            <TextInput
              v-model="form.payment_date"
              label="Payment Date"
              type="date"
              required
              :error="form.errors.payment_date"
            />
          </div>
          <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <TextInput
              v-model="form.reference"
              label="Payment Reference (Optional)"
              placeholder="e.g. Receipt number"
              :error="form.errors.reference"
            />
            <TextInput
              v-model="form.payment_date"
              label="Payment Date"
              type="date"
              required
              :error="form.errors.payment_date"
            />
          </div>

          <TextInput
            v-model="form.description"
            label="Description (Optional)"
            placeholder="Additional notes about this payment"
            :error="form.errors.description"
          />
        </div>
      </Card>

      <div class="flex items-center justify-end gap-3">
        <a href="/payments" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
          Cancel
        </a>
        <PrimaryButton type="submit" :processing="form.processing" icon="fas fa-save">
          Update Payment
        </PrimaryButton>
      </div>
    </form>
  </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Layout from '../../Shared/Layout.vue';
import Card from '../../Shared/Components/Card.vue';
import TextInput from '../../Shared/Components/TextInput.vue';
import SelectInput from '../../Shared/Components/SelectInput.vue';
import PrimaryButton from '../../Shared/Components/PrimaryButton.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  payment: Object,
  students: Array,
  paymentMethods: Array,
});

const studentOptions = computed(() => {
  return props.students?.map(student => ({
    value: student.id,
    label: `${student.firstnames} ${student.surname}`
  })) || [];
});

const paymentMethodOptions = computed(() => {
  return props.paymentMethods?.map(method => ({
    value: method.id,
    label: `${method.name}${method.payment_charge?.amount > 0 ? ` (+$${method.payment_charge.amount})` : ''}`
  })) || [];
});

const form = useForm({
  student_id: props.payment?.student_id || '',
  payment_method_id: props.payment?.payment_method_id || '',
  payment_recipient_name: props.payment?.payment_recipient_name || '',
  amount: props.payment?.amount || '',
  purpose: props.payment?.purpose || '',
  reference: props.payment?.reference || '',
  description: props.payment?.description || '',
  payment_date: props.payment?.payment_date || new Date().toISOString().split('T')[0],
});

const selectedPaymentMethod = computed(() => {
  if (!form.payment_method_id) return null;
  const method = props.paymentMethods?.find(m => m.id == form.payment_method_id);
  if (!method) return null;
  return {
    charge: parseFloat(method.payment_charge?.amount || 0),
    charge_narration: method.payment_charge?.narration || '',
    requires_reference: method.requires_reference || false,
  };
});

const requiresReference = computed(() => {
  return selectedPaymentMethod.value?.requires_reference || false;
});

const finalAmount = computed(() => {
  const amount = parseFloat(form.amount) || 0;
  const charge = selectedPaymentMethod.value?.charge || 0;
  return Math.max(0, amount - charge);
});

const updatePaymentMethod = () => {
  if (!requiresReference.value) {
    form.reference = '';
  }
};

const submit = () => {
  form.put(`/payments/${props.payment.id}`);
};
</script>

<script>
export default {
  layout: Layout
};
</script>

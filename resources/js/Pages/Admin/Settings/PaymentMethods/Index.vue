<template>
  <Head title="Admin - Payment Methods" />

  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Payment Methods</h1>
        <p class="mt-1 text-sm text-slate-500">Channels students can use when recording payments.</p>
      </div>
      <Link href="/admin/settings" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Settings
      </Link>
    </div>

    <Card>
      <div class="space-y-4">
        <form class="grid grid-cols-1 gap-4 md:grid-cols-3" @submit.prevent="submitPaymentMethod">
          <TextInput
            v-model="paymentMethodForm.name"
            label="Name"
            placeholder="EcoCash, Bank Transfer"
            :error="paymentMethodForm.errors.name"
            required
          />
          <SelectInput
            v-model="paymentMethodForm.payment_charge_id"
            label="Charge"
            :options="paymentChargeOptions"
            placeholder="No charge"
            :error="paymentMethodForm.errors.payment_charge_id"
          />
          <div class="space-y-2">
            <label class="inline-flex items-center gap-2 text-xs text-slate-700">
              <input
                v-model="paymentMethodForm.requires_reference"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
              />
              <span>Requires reference</span>
            </label>
            <label class="inline-flex items-center gap-2 text-xs text-slate-700">
              <input
                v-model="paymentMethodForm.is_active"
                type="checkbox"
                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-400/40"
              />
              <span>Active</span>
            </label>
            <div class="flex justify-end pt-1">
              <PrimaryButton type="submit" :processing="paymentMethodForm.processing" size="sm">
                Add Method
              </PrimaryButton>
            </div>
          </div>
        </form>

        <div v-if="paymentMethods && paymentMethods.length > 0" class="space-y-2">
          <div
            v-for="method in paymentMethods"
            :key="method.id"
            class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-slate-200"
          >
            <div>
              <p class="text-sm font-semibold text-slate-900">{{ method.name }}</p>
              <p class="text-xs text-slate-500">
                <span v-if="method.payment_charge">
                  Charge: ${{ Number(method.payment_charge.amount).toFixed(2) }} ({{ method.payment_charge.narration }})
                </span>
                <span v-else>No charge</span>
              </p>
              <p class="text-xs text-slate-500">
                {{ method.requires_reference ? 'Requires reference' : 'No reference required' }} •
                {{ method.is_active ? 'Active' : 'Inactive' }}
              </p>
            </div>
            <button
              type="button"
              class="text-xs font-semibold text-red-600 hover:text-red-900"
              @click="deletePaymentMethod(method)"
            >
              <i class="fa-solid fa-trash-can mr-1"></i>
              Delete
            </button>
          </div>
        </div>
        <EmptyState
          v-else
          icon="fas fa-money-bill-wave"
          title="No payment methods"
          description="Add payment methods students can use when recording payments."
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
import SelectInput from '../../../../Shared/Components/SelectInput.vue';
import PrimaryButton from '../../../../Shared/Components/PrimaryButton.vue';
import EmptyState from '../../../../Shared/Components/EmptyState.vue';
import { computed } from 'vue';

const props = defineProps({
  paymentMethods: Array,
  paymentCharges: Array,
});

const paymentMethodForm = useForm({
  name: '',
  payment_charge_id: '',
  requires_reference: false,
  is_active: true,
});

const paymentChargeOptions = computed(() => {
  if (!props.paymentCharges || props.paymentCharges.length === 0) {
    return [];
  }

  return props.paymentCharges.map((charge) => ({
    value: String(charge.id),
    label: `${charge.narration} ($${Number(charge.amount).toFixed(2)})`,
  }));
});

const submitPaymentMethod = () => {
  const payload = {
    ...paymentMethodForm.data(),
    payment_charge_id: paymentMethodForm.payment_charge_id || null,
  };

  paymentMethodForm.post('/admin/settings/payment-methods', {
    preserveScroll: true,
    data: payload,
    onSuccess: () => {
      paymentMethodForm.reset('name', 'payment_charge_id', 'requires_reference', 'is_active');
      paymentMethodForm.is_active = true;
    },
  });
};

const deletePaymentMethod = (method) => {
  if (!confirm(`Delete payment method "${method.name}"?`)) {
    return;
  }
  router.delete(`/admin/settings/payment-methods/${method.id}`, {
    preserveScroll: true,
  });
};
</script>

<script>
export default {
  layout: Layout,
};
</script>


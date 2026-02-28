<template>
  <Head title="Admin - Attendant Details" />
  
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-semibold text-slate-900">Attendant Details</h1>
        <p class="mt-1 text-sm text-slate-500">Complete profile information</p>
      </div>
      <Link href="/admin/attendants" class="text-sm font-semibold text-slate-600 hover:text-slate-800">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to List
      </Link>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Personal Details</h2>
            <p class="text-xs text-slate-500">Basic information</p>
          </div>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">First Names:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student?.firstnames }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Surname:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student?.surname }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Gender:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student?.gender }}</span>
            </div>
            <div v-if="student?.nationalid" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">National ID:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student.nationalid }}</span>
            </div>
            <div v-if="student?.email" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Email:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student.email }}</span>
            </div>
            <div v-if="student?.address" class="flex items-start justify-between">
              <span class="text-sm text-slate-600">Address:</span>
              <span class="text-sm font-semibold text-slate-900 text-right">{{ student.address }}</span>
            </div>
          </div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Contact Information</h2>
            <p class="text-xs text-slate-500">Phone numbers</p>
          </div>
          <div v-if="student?.phones && student.phones.length > 0" class="space-y-2">
            <div v-for="(phone, index) in student.phones" :key="index" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Phone {{ index + 1 }}:</span>
              <span class="text-sm font-semibold text-slate-900">{{ phone }}</span>
            </div>
          </div>
          <div v-else class="text-sm text-slate-500">No phone numbers recorded</div>
        </div>
      </Card>

      <Card>
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Institution & Membership</h2>
            <p class="text-xs text-slate-500">Academic and membership details</p>
          </div>
          <div class="space-y-3">
            <div v-if="student?.institution" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Institution:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student.institution.name }}</span>
            </div>
            <div v-if="student?.institution?.code" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Institution Code:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student.institution.code }}</span>
            </div>
            <div v-if="student?.institution?.region" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Region:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student.institution.region }}</span>
            </div>
            <div v-if="student?.membership" class="flex items-center justify-between">
              <span class="text-sm text-slate-600">Membership:</span>
              <span class="text-sm font-semibold text-slate-900">{{ student.membership.status }}</span>
            </div>
            <div v-if="student?.membership?.description" class="flex items-start justify-between">
              <span class="text-sm text-slate-600">Description:</span>
              <span class="text-sm font-semibold text-slate-900 text-right">{{ student.membership.description }}</span>
            </div>
          </div>
        </div>
      </Card>

      <Card v-if="student?.next_of_kins && student.next_of_kins.length > 0">
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Next of Kin</h2>
            <p class="text-xs text-slate-500">Emergency contacts</p>
          </div>
          <div class="space-y-4">
            <div v-for="(nok, index) in student.next_of_kins" :key="index" class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
              <div class="space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-slate-600">Relationship:</span>
                  <span class="text-sm font-semibold text-slate-900">{{ nok.relationship }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-slate-600">Full Name:</span>
                  <span class="text-sm font-semibold text-slate-900">{{ nok.fullname }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-slate-600">Phone:</span>
                  <span class="text-sm font-semibold text-slate-900">{{ nok.phone }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <Card v-if="(student?.disabilities && student.disabilities.length > 0) || (student?.dietaries && student.dietaries.length > 0) || (student?.chronic_conditions && student.chronic_conditions.length > 0)">
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Health & Dietary Information</h2>
            <p class="text-xs text-slate-500">Special requirements</p>
          </div>
          <div class="space-y-3">
            <div v-if="student?.disabilities && student.disabilities.length > 0">
              <p class="text-sm font-semibold text-slate-700 mb-2">Disabilities:</p>
              <div class="flex flex-wrap gap-2">
                <span v-for="disability in student.disabilities" :key="disability" class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                  {{ disability }}
                </span>
              </div>
            </div>
            <div v-if="student?.dietaries && student.dietaries.length > 0">
              <p class="text-sm font-semibold text-slate-700 mb-2">Dietary Requirements:</p>
              <div class="flex flex-wrap gap-2">
                <span v-for="dietary in student.dietaries" :key="dietary" class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">
                  {{ dietary }}
                </span>
              </div>
            </div>
            <div v-if="student?.chronic_conditions && student.chronic_conditions.length > 0">
              <p class="text-sm font-semibold text-slate-700 mb-2">Chronic Conditions:</p>
              <div class="flex flex-wrap gap-2">
                <span v-for="condition in student.chronic_conditions" :key="condition" class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                  {{ condition }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <Card v-if="student?.payments && student.payments.length > 0" class="lg:col-span-2">
        <div class="space-y-4">
          <div>
            <h2 class="text-lg font-semibold text-slate-900">Payment History</h2>
            <p class="text-xs text-slate-500">All payments made by this attendant</p>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Purpose</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Method</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200 bg-white">
                <tr v-for="payment in student.payments" :key="payment.id" class="hover:bg-slate-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ payment.payment_date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">${{ payment.amount }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ payment.purpose }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ payment.payment_method }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <Badge :variant="getStatusVariant(payment.status)">
                      {{ payment.status }}
                    </Badge>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../../../Shared/Layout.vue';
import Card from '../../../Shared/Components/Card.vue';
import Badge from '../../../Shared/Components/Badge.vue';

defineProps({
  student: Object
});

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

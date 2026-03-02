<template>
  <Head title="Dashboard" />

  <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">
        Welcome back, <span class="text-emerald-700">{{ user?.name || 'User' }}</span>
      </h1>
      <p class="mt-1 text-sm text-slate-600">
        View your basic information, payment balance, and important notices.
      </p>
    </div>
  </section>

  <section class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <Card>
      <div class="space-y-4">
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Basic Information</h2>
          <p class="text-xs text-slate-500">Your profile summary</p>
        </div>
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-sm text-slate-600">Name:</span>
            <span class="text-sm font-semibold text-slate-900">{{ student?.firstnames }} {{ student?.surname }}</span>
          </div>
          <div v-if="student?.phones && student.phones.length > 0" class="flex items-center justify-between">
            <span class="text-sm text-slate-600">Phone:</span>
            <span class="text-sm font-semibold text-slate-900">{{ student.phones[0] }}</span>
          </div>
          <div v-if="student?.institution" class="flex items-center justify-between gap-3">
            <span class="text-sm text-slate-600">Institution:</span>
            <div class="flex items-center gap-2">
              <span class="text-sm font-semibold text-slate-900">{{ student.institution.name }}</span>
              <img
                v-if="student.institution.logo"
                :src="`/storage/${student.institution.logo}`"
                alt="Institution Logo"
                class="h-7 w-7 rounded-full bg-white ring-1 ring-slate-200 object-contain"
              />
            </div>
          </div>
          <div v-if="student?.membership" class="flex items-center justify-between">
            <span class="text-sm text-slate-600">Membership:</span>
            <span class="text-sm font-semibold text-slate-900">{{ student.membership.status }}</span>
          </div>
        </div>
        <div class="pt-3 border-t border-slate-200 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <Link
            :href="`/attendants/${student?.id}`"
            class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800"
          >
            <i class="fa-solid fa-user-circle"></i>
            View Full Personal Info
          </Link>
          <a
            :href="`/attendants/${student?.id}/card`"
            class="inline-flex items-center gap-2 rounded-2xl bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500"
          >
            <i class="fa-solid fa-file-pdf"></i>
            Download Attendant PDF
          </a>
        </div>
      </div>
    </Card>

    <Card>
      <div class="space-y-4">
        <div>
          <h2 class="text-lg font-semibold text-slate-900">Payment Balance</h2>
          <p class="text-xs text-slate-500">Your payment summary</p>
        </div>
        <div class="space-y-3">
          <div class="rounded-2xl bg-emerald-50 p-4 ring-1 ring-emerald-200">
            <p class="text-xs font-semibold text-emerald-700">Total Paid</p>
            <p class="mt-1 text-2xl font-semibold text-emerald-700">${{ paymentBalance?.total_paid || '0.00' }}</p>
          </div>
          <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200">
            <p class="text-xs font-semibold text-slate-700">Pending Approval</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900">${{ paymentBalance?.total_pending || '0.00' }}</p>
          </div>
          <div class="rounded-2xl bg-blue-50 p-4 ring-1 ring-blue-200">
            <p class="text-xs font-semibold text-blue-700">Final Balance</p>
            <p class="mt-1 text-2xl font-semibold" :class="parseFloat(balanceSummary?.balance || 0) >= 0 ? 'text-blue-700' : 'text-red-700'">
              ${{ balanceSummary?.balance || '0.00' }}
            </p>
            <p class="mt-1 text-xs text-blue-600">{{ parseFloat(balanceSummary?.balance || 0) >= 0 ? 'Remaining' : 'Overpaid' }}</p>
          </div>
        </div>
        <div class="pt-3 border-t border-slate-200">
          <Link
            :href="`/payments/create?student_id=${student?.id}`"
            class="inline-flex items-center justify-center gap-2 w-full rounded-2xl bg-emerald-600 px-4 py-3 text-white font-semibold shadow-sm shadow-emerald-600/20 hover:bg-emerald-500 transition"
          >
            <i class="fa-solid fa-receipt"></i>
            Record Payment
          </Link>
        </div>
      </div>
    </Card>
  </section>

  <section class="mt-6">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-semibold text-slate-900">Notices</h2>
        <p class="text-xs text-slate-500">Important announcements and updates</p>
      </div>
    </div>

    <Card>
      <div class="space-y-4">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                <i class="fa-solid fa-bullhorn"></i>
                Important
              </span>
              <span class="text-xs text-slate-500">{{ notice.date }}</span>
            </div>
            <h3 class="text-base font-semibold text-slate-900 mb-2">{{ notice.title }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ notice.content }}</p>
            <div v-if="notice.details" class="mt-3 space-y-2">
              <p v-for="(detail, index) in notice.details" :key="index" class="text-sm text-slate-600 flex items-start gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-1 text-xs"></i>
                <span>{{ detail }}</span>
              </p>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
          <span class="text-xs font-semibold text-slate-600">Share this notice:</span>
          <button
            @click="shareOnWhatsApp"
            class="inline-flex items-center gap-2 rounded-xl bg-green-50 px-3 py-2 text-sm font-semibold text-green-700 ring-1 ring-green-200 hover:bg-green-100 transition"
          >
            <i class="fa-brands fa-whatsapp"></i>
            WhatsApp
          </button>
          <button
            @click="shareOnFacebook"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 ring-1 ring-blue-200 hover:bg-blue-100 transition"
          >
            <i class="fa-brands fa-facebook"></i>
            Facebook
          </button>
        </div>
      </div>
    </Card>
  </section>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '../../Shared/Layout.vue';
import Card from '../../Shared/Components/Card.vue';
import { computed } from 'vue';

const props = defineProps({
  user: Object,
  student: Object,
  paymentBalance: {
    type: Object,
    default: () => ({
      total_paid: '0.00',
      total_pending: '0.00'
    })
  },
  balanceSummary: {
    type: Object,
    default: () => ({
      balance: '0.00'
    })
  }
});

const notice = {
  date: 'February 22, 2026',
  title: 'Easter Conference 2026 Registration Now Open!',
  content: 'We are excited to announce that registration for the NMCS Zimbabwe Easter Conference 2026 is now open. Join us for an inspiring conference filled with worship, teaching, and fellowship.',
  details: [
    'Conference Fee: $25 USD per person',
    'Early registration deadline: March 15, 2026',
    'Full payment required before conference date',
    'Payment methods: EcoCash & Cash'
  ]
};

const shareText = computed(() => {
  return `${notice.title}\n\n${notice.content}\n\n${notice.details.join('\n')}\n\nRegister now at: ${window.location.origin}`;
});

const shareOnWhatsApp = () => {
  const url = `https://wa.me/?text=${encodeURIComponent(shareText.value)}`;
  window.open(url, '_blank');
};

const shareOnFacebook = () => {
  const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.origin)}&quote=${encodeURIComponent(shareText.value)}`;
  window.open(url, '_blank');
};
</script>

<script>
export default {
  layout: Layout
};
</script>
